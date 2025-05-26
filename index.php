<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma To-Do List</title>
    <style>
        /* Fonts & Base Reset */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .container {
            background: #ffffffcc;
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 420px;
            text-align: center;
        }

        h1 {
            font-size: 2.5em;
            color: #6c63ff;
            margin-bottom: 25px;
        }

        #todo-form {
            display: flex;
            gap: 0;
            margin-bottom: 20px;
        }

        #todo-input {
            flex: 1;
            padding: 12px 16px;
            font-size: 1em;
            border: none;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            outline: none;
            background: #f0f0f0;
        }

        #add-btn {
            padding: 12px 20px;
            border: none;
            background: #6c63ff;
            color: white;
            font-weight: 600;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }

        #add-btn:hover {
            background: #574fd6;
        }

        ul {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }

        li {
            background: #f7f7ff;
            margin-bottom: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .delete-btn {
            background: #ff6b6b;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background 0.3s;
        }

        .delete-btn:hover {
            background: #d94343;
        }

        .empty {
            font-style: italic;
            color: #999;
            margin-top: 15px;
        }

        .info-section {
            background: #fff7e6;
            color: #d9822b;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            font-size: 0.95em;
        }

        footer {
            margin-top: 30px;
            font-size: 0.85em;
            color: #555;
        }

        #reset-btn {
            margin-top: 20px;
            background: #ffa502;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        #reset-btn:hover {
            background: #e08900;
        }

        #task-count {
            margin-top: 12px;
            font-weight: 500;
            color: #444;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌸 Ma To-Do List</h1>
        <form id="todo-form" autocomplete="off" onsubmit="return false;">
            <input type="text" id="todo-input" placeholder="Ajouter une tâche..." />
            <button id="add-btn">Ajouter</button>
        </form>
        <ul id="todo-list"></ul>
        <div id="empty-msg" class="empty">Aucune tâche pour le moment.</div>
        <div class="info-section">
            <strong>Astuce :</strong> Cliquez sur "Supprimer" pour effacer une tâche.<br>
            Appuyez sur <kbd>Entrée</kbd> pour ajouter rapidement une tâche.<br><br>
            Cette version ne sauvegarde pas les tâches après rechargement.<br>
            (Version persistante disponible avec une base de données)
        </div>
        <button id="reset-btn">Tout effacer</button>
        <div id="task-count">Nombre de tâches : 0</div>
    </div>
    <footer>
        Réalisé avec ❤️ en PHP, HTML, CSS, et JavaScript.<br>
        &copy; <?php echo date('Y'); ?> Salma El Moudi & Zineb Chadery & Yassine Alahy
    </footer>

    <script>
        const todoInput = document.getElementById('todo-input');
        const addBtn = document.getElementById('add-btn');
        const todoList = document.getElementById('todo-list');
        const emptyMsg = document.getElementById('empty-msg');
        const resetBtn = document.getElementById('reset-btn');
        const countDiv = document.getElementById('task-count');

        function updateEmptyMsg() {
            emptyMsg.style.display = todoList.children.length === 0 ? 'block' : 'none';
        }

        function renderTodo(id, text) {
            const li = document.createElement('li');
            li.textContent = text;
            li.dataset.id = id;
            const delBtn = document.createElement('button');
            delBtn.textContent = 'Supprimer';
            delBtn.className = 'delete-btn';
            delBtn.onclick = () => {
                fetch('todo_api.php?action=delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        todoList.removeChild(li);
                        updateEmptyMsg();
                        updateCount();
                    }
                });
            };
            li.appendChild(delBtn);
            todoList.appendChild(li);
        }

        function getTodoCount() {
            return todoList.children.length;
        }

        function updateCount() {
            countDiv.textContent = "Nombre de tâches : " + getTodoCount();
        }

        function resetTodos() {
            const items = Array.from(todoList.children);
            items.forEach(li => {
                const id = li.dataset.id;
                fetch('todo_api.php?action=delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                todoList.removeChild(li);
            });
            updateEmptyMsg();
            updateCount();
        }

        addBtn.onclick = () => {
            const text = todoInput.value;
            if (!text.trim()) return;
            fetch('todo_api.php?action=add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ text })
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    renderTodo(data.id, text);
                    updateEmptyMsg();
                    updateCount();
                }
            });
            todoInput.value = '';
            todoInput.focus();
        };

        todoInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                addBtn.click();
            }
        });

        resetBtn.onclick = resetTodos;

        function loadTodos() {
            fetch('todo_api.php?action=list')
                .then(res => res.json())
                .then(todos => {
                    todoList.innerHTML = '';
                    todos.forEach(todo => renderTodo(todo.id, todo.text));
                    updateEmptyMsg();
                    updateCount();
                });
        }

        loadTodos();
    </script>
</body>
</html>
