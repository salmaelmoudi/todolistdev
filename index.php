<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>🌟 To-Do Aesthetic</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(to right, #fbd3e9, #bbd2c5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            padding: 40px 30px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 480px;
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        h1 {
            text-align: center;
            font-size: 2.8em;
            margin-bottom: 20px;
            color: #7f5af0;
        }

        #todo-form {
            display: flex;
            margin-bottom: 20px;
        }

        #todo-input {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 12px 0 0 12px;
            font-size: 1em;
            background: #f0f0f0;
        }

        #add-btn {
            background: #7f5af0;
            color: white;
            padding: 12px 18px;
            border: none;
            border-radius: 0 12px 12px 0;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        #add-btn:hover {
            background: #5c3dc0;
        }

        ul {
            list-style: none;
            margin-top: 10px;
        }

        li {
            background: #fff8f0;
            padding: 14px 16px;
            margin-bottom: 12px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .delete-btn {
            background: #ff6b81;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85em;
            transition: background 0.3s;
        }

        .delete-btn:hover {
            background: #e84362;
        }

        #reset-btn {
            margin-top: 20px;
            background: #f5a623;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 12px;
            font-weight: bold;
            cursor: pointer;
        }

        #reset-btn:hover {
            background: #d48806;
        }

        .empty {
            text-align: center;
            color: #999;
            font-style: italic;
            margin-top: 12px;
        }

        #task-count {
            margin-top: 10px;
            text-align: center;
            font-weight: 600;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.85em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div>
            <h1>🌸 Ma To-Do List</h1>
            <form id="todo-form" autocomplete="off" onsubmit="return false;">
                <input type="text" id="todo-input" placeholder="Ajouter une tâche..." />
                <button id="add-btn">+</button>
            </form>
            <ul id="todo-list"></ul>
            <div id="empty-msg" class="empty">Aucune tâche pour le moment.</div>
        </div>

        <div>
            <button id="reset-btn">🗑️ Tout effacer</button>
            <div id="task-count">Nombre de tâches : 0</div>
        </div>
    </div>
    <footer>
        ✨ Réalisé avec ❤️ par Salma, Zineb & Yassine — projet devops <?php echo date('Y'); ?>
    </footer>

    <script>
        const todoInput = document.getElementById('todo-input');
        const addBtn = document.getElementById('add-btn');
        const todoList = document.getElementById('todo-list');
        const emptyMsg = document.getElementById('empty-msg');
        const resetBtn = document.getElementById('reset-btn');
        const countDiv = document.getElementById('task-count');

        const staticTasks = [
            "📚 Réviser pour le contrôle de maths",
            "💧 Boire 2 litres d'eau",
            "🧘 Faire 15 minutes de méditation",
            "📸 Poster une photo sur Insta",
            "🎧 Écouter un podcast inspirant"
        ];

        function updateEmptyMsg() {
            emptyMsg.style.display = todoList.children.length === 0 ? 'block' : 'none';
        }

        function updateCount() {
            countDiv.textContent = "Nombre de tâches : " + todoList.children.length;
        }

        function renderTodo(id, text) {
            const li = document.createElement('li');
            li.textContent = text;
            li.dataset.id = id;

            const delBtn = document.createElement('button');
            delBtn.textContent = '❌';
            delBtn.className = 'delete-btn';
            delBtn.onclick = () => {
                todoList.removeChild(li);
                updateEmptyMsg();
                updateCount();
            };

            li.appendChild(delBtn);
            todoList.appendChild(li);
        }

        function addTask(text) {
            const id = Date.now(); // simulate unique ID
            renderTodo(id, text);
            updateEmptyMsg();
            updateCount();
        }

        addBtn.onclick = () => {
            const text = todoInput.value;
            if (!text.trim()) return;
            addTask(text);
            todoInput.value = '';
            todoInput.focus();
        };

        todoInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                addBtn.click();
            }
        });

        resetBtn.onclick = () => {
            todoList.innerHTML = '';
            updateEmptyMsg();
            updateCount();
        };

        // Charger des tâches statiques au démarrage
        window.onload = () => {
            staticTasks.forEach(task => addTask(task));
        };
    </script>
</body>
</html>
