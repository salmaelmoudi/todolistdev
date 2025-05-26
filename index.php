<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma To-Do List</title>
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: #fff;
            padding: 40px 60px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            text-align: center;
            min-width: 350px;
        }
        h1 {
            color: #3498db;
            margin-bottom: 20px;
            font-size: 2.2em;
            letter-spacing: 2px;
        }
        #todo-form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        #todo-input {
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 6px 0 0 6px;
            outline: none;
            width: 70%;
        }
        #add-btn {
            padding: 10px 18px;
            font-size: 1em;
            border: none;
            background: #3498db;
            color: #fff;
            border-radius: 0 6px 6px 0;
            cursor: pointer;
            transition: background 0.2s;
        }
        #add-btn:hover {
            background: #217dbb;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background: #f9f9f9;
            margin-bottom: 10px;
            padding: 12px 16px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1em;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        }
        .delete-btn {
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 0.95em;
            transition: background 0.2s;
        }
        .delete-btn:hover {
            background: #c0392b;
        }
        .empty {
            color: #aaa;
            font-style: italic;
            margin-top: 20px;
        }
        .info-section {
            margin-top: 30px;
            padding: 18px 24px;
            background: #eaf6fb;
            border-radius: 8px;
            color: #217dbb;
            font-size: 1.05em;
        }
        footer {
            margin-top: 40px;
            color: #888;
            font-size: 0.95em;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>salma to-do list</h1>
        <form id="todo-form" autocomplete="off" onsubmit="return false;">
            <input type="text" id="todo-input" placeholder="Ajouter une tâche..." />
            <button id="add-btn">Ajouter</button>
        </form>
        <ul id="todo-list"></ul>
        <div id="empty-msg" class="empty">Aucune tâche pour le moment.</div>
        <div class="info-section">
            <strong>Astuce :</strong> Click "DELETE" to delete a task.<br>
            Appuyez sur <kbd>Entrée</kbd> pour ajouter rapidement une tâche.<br>
            <br>
            Cette application ne sauvegarde pas les tâches après le rechargement de la page.<br>
            Pour une version persistante, bla bla bla .
        </div>
    </div>
    <footer>
        Réalisé avec  en PHP, HTML, CSS,et validation &amp; Js.<br>
        &copy; <?php echo date('Y'); ?> Chafiq Hamza et youssef khalid
    </footer>
    <script>
        const todoInput = document.getElementById('todo-input');
        const addBtn = document.getElementById('add-btn');
        const todoList = document.getElementById('todo-list');
        const emptyMsg = document.getElementById('empty-msg');

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

        function resetTodos() {
            // Supprime toutes les tâches côté client et côté serveur
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

        // Ajout d'un bouton pour réinitialiser la liste
        const resetBtn = document.createElement('button');
        resetBtn.textContent = 'Tout effacer';
        resetBtn.style.marginTop = '18px';
        resetBtn.style.background = '#f39c12';
        resetBtn.style.color = '#fff';
        resetBtn.style.border = 'none';
        resetBtn.style.borderRadius = '6px';
        resetBtn.style.padding = '8px 18px';
        resetBtn.style.cursor = 'pointer';
        resetBtn.onclick = () => {
            resetTodos();
        };
        document.querySelector('.container').appendChild(resetBtn);

        // Affichage dynamique du nombre de tâches
        const countDiv = document.createElement('div');
        countDiv.style.marginTop = '12px';
        countDiv.style.color = '#555';
        countDiv.style.fontSize = '1em';
        document.querySelector('.container').appendChild(countDiv);

        function updateCount() {
            countDiv.textContent = "Nombre de tâches : " + getTodoCount();
        }

        // Charger les tâches depuis la base de données au chargement de la page
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
        updateEmptyMsg();
        updateCount();
    </script>
</body>
</html>
