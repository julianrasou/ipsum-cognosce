<main>
    <div class="tasks-layout">
        <section class="tasks-sidebar">
            <h2>Categorías</h2>
            <div id="categories">

            </div>
            <button id="addCategory">+</button>
            <button id="removeCategory">-</button>
        </section>

        <section class="tasks-list">
            <button id="addTask" class="add-task">+ Añadir tarea</button><br>
            <div id="tasks">

            </div>
        </section>
    </div>
    <dialog id="taskDetailsDialog">
        <h3 id="dialogTitle"></h3>
        <p id="dialogDescription"></p>
        <p>
            <strong>Categoría:</strong> <span id="dialogCategory"></span>
        </p>
        <p>
            <strong>Estado:</strong> <span id="dialogStatus"></span>
        </p>
        <p>
            <strong>Fecha:</strong> <span id="dialogDate"></span>
        </p>
        <button id="closeDialogBtn">Cerrar</button>
    </dialog>
    <dialog id="addTaskDialog">
        <h3 id="dialogTitle">Añadir tarea:</h3>
        <p>Título:</p>
        <input type="text" id="taskTitle">
        <p>Descripción:</p>
        <textarea id="taskDescription"></textarea><br>
        <button id="addTaskDialogButton">Añadir</button>
        <button id="closeTaskDialogBtn">Cancelar</button>
    </dialog>
    <script src="public/javascript/tasks.js"></script>
</main>