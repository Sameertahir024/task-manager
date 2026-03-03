<!DOCTYPE html>
<html>

<head>
    <title>Tasks Full-Stack</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        

        .container {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 600px;
            margin: auto;
            padding: 10px;
            justify-content: center;
            align-items: center;
        }

        h1 {
            color: coral;
        }

        ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        li {
            padding: 5px;
            border-bottom: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .task-info {
            flex: 1;
            margin-left: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
            margin-top: 20px;
        }

        input, button {
            padding: 8px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Task List</h1>

        <ul>
            @foreach ($tasks as $task)
                <li>
                    <input type="checkbox" 
                           onchange="completeTask({{ $task->id }}, this)" 
                           {{ $task->is_completed ? 'checked disabled' : '' }}>
                    <span class="task-info">
                        {{ $task->title }} - {{ $task->description }} -
                        <span class="status">{{ $task->is_completed ? 'Completed' : 'Pending' }}</span>
                    </span>
                </li>
            @endforeach
        </ul>

        <form id="taskForm">
            <input type="text" id="title" placeholder="Title" required>
            <input type="text" id="description" placeholder="Description" required>
            <button type="submit">Add Task</button>
        </form>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Handle adding a task
        document.getElementById('taskForm').onsubmit = async (e) => {
            e.preventDefault();
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;

            await fetch('api/tasks', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ title, description })
            });

            location.reload();
        };

        // Handle completing a task
        async function completeTask(id, checkbox) {
            try {
                const response = await fetch(`api/tasks/${id}/complete`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    alert('Error completing task');
                    checkbox.checked = false; 
                    return;
                }

                checkbox.disabled = true;
                const statusSpan = checkbox.parentElement.querySelector('.status');
                statusSpan.innerText = 'Completed';

            } catch (error) {
                console.error('Error:', error);
                checkbox.checked = false;
            }
        }
    </script>
</body>

</html>