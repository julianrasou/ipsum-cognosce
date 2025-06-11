<main>
    <div class="layout">
        <section class="sidebar">
            <h2>Categorías</h2>
            <div id="categories">

            </div>
            <div class="sidebarButtons">
                <button id="addCategory">+</button>
                <button id="removeCategory">-</button>
            </div>
        </section>

        <section class="list">
            <button id="addTask" class="add">+ Añadir tarea</button><br>
            <div id="tasks">

            </div>
        </section>
    </div>
    <dialog id="taskDetailsDialog" class="dialog details">
        <h2 id="dialogTitle"></h2>
        <textarea id="dialogDescription" disabled></textarea>
        <h3>Categoría:</h3>
        <p><span id="dialogCategory"></span></p>
        <h3>Estado:</h3>
        <p><span id="dialogStatus"></span></p>
        <h3>Fecha de creación:</h3>
        <p><span id="dialogDate"></span></p>
        <button id="closeDialogBtn">Cerrar</button>
    </dialog>
    <dialog id="addTaskDialog" class="dialog">
        <h2>Añadir tarea:</h2>
        <h3>Título:</h3>
        <input type="text" id="taskTitle" placeholder="Título de la tarea">
        <h3>Descripción:</h3>
        <textarea id="taskDescription" placeholder="Descripción de la tarea"></textarea><br>
        <button id="addTaskDialogButton">Añadir</button>
        <button id="closeTaskDialogBtn">Cancelar</button>
    </dialog>
    <script src="public/javascript/tasks.js"></script>
</main>