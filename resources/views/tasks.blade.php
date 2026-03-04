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
            padding: 10px;
            border-bottom: 1px solid #ccc;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .task-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        img {
            width: 80px;
            height: auto;
            margin-top: 5px;
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

        .delete-btn {
            background: red;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Task List</h1>

        <ul>
            @foreach ($tasks as $task)
                <li>
                    <div class="task-row">
                        <div>
                            <input type="checkbox"
                                   onchange="completeTask({{ $task->id }}, this)"
                                   {{ $task->is_completed ? 'checked disabled' : '' }}>

                            {{ $task->title }} - {{ $task->description }} -
                            <span class="status">
                                {{ $task->is_completed ? 'Completed' : 'Pending' }}
                            </span>
                        </div>

                        <button class="delete-btn"
                                onclick="deleteTask({{ $task->id }})">
                            Delete
                        </button>
                    </div>

                    @if($task->image)
                        <img src="{{ asset('storage/' . $task->image) }}">
                    @endif
                </li>
            @endforeach
        </ul>

        <!-- FORM WITH IMAGE -->
        <form id="taskForm" enctype="multipart/form-data">
            <input type="text" id="title" placeholder="Title" required>
            <input type="text" id="description" placeholder="Description" required>
            <input type="file" id="image">
            <button type="submit">Add Task</button>
        </form>
    </div>

    <script>
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        // ADD TASK WITH IMAGE
        document.getElementById('taskForm').onsubmit = async (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('title', document.getElementById('title').value);
            formData.append('description', document.getElementById('description').value);

            const imageFile = document.getElementById('image').files[0];
            if (imageFile) {
                formData.append('image', imageFile);
            }

            await fetch('api/tasks', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            location.reload();
        };

        // COMPLETE TASK
        async function completeTask(id, checkbox) {
            const response = await fetch(`api/tasks/${id}/complete`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                alert('Error completing task');
                checkbox.checked = false;
                return;
            }

            checkbox.disabled = true;
            checkbox.parentElement.querySelector('.status').innerText = 'Completed';
        }

        // DELETE TASK
        async function deleteTask(id) {
            if (!confirm('Are you sure you want to delete this task?')) return;

            await fetch(`api/tasks/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            location.reload();
        }
    </script>
</body>

</html>