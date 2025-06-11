// Se inicializa el array que contendrá las categorías que se muestran
let categories = [];
// Se inicializa el array que contendrá las tareas que se muestran
let tasks = [];
// Se inicializa la categoría seleccionada
let selectedCategory = "Sin categoría";

// Se añade una escucha de evento a la página de forma que cuanto cargue el DOM se ejecuten las siguientes instrucciones
document.addEventListener("DOMContentLoaded", () => {
    // Se carga la página
    loadPage();

    // Se añaden las escuchas de eventos a los diferentes boñtones de la página para
    // que cumplan sus funciones correspondientes
    document.getElementById("closeDialogBtn").addEventListener("click", () => {
        document.getElementById("taskDetailsDialog").close();
    });

    document
        .getElementById("addCategory")
        .addEventListener("click", addCategory);

    document
        .getElementById("removeCategory")
        .addEventListener("click", removeCategory);

    document.getElementById("addTask").addEventListener("click", showAddTask);

    document
        .getElementById("addTaskDialogButton")
        .addEventListener("click", addTask);

    document
        .getElementById("closeTaskDialogBtn")
        .addEventListener("click", () => {
            document.getElementById("addTaskDialog").close();
            // Se vacían los inputs si se le da a cancelar para que no se mantengan
            document.querySelector("#taskTitle").value = "";
            document.querySelector("#taskDescription").value = "";
        });
});

/**
 * Función que carga la página
 * Carga las categorías
 * Carga las tareas
 * Actualiza la vista
 */
async function loadPage() {
    await loadCategories();
    await loadTasks();
    updateView();
}

/**
 * Función loadCategories
 * Recupera las categorías del usuario activo llamando al API endpint personalizado en php
 * @returns promise
 */
function loadCategories() {
    return fetch("app/api/tasks/categories.php", {
        method: "GET",
        credentials: "include",
    })
        .then((response) => response.json())
        .then((data) => {
            categories = data;
        });
}

/**
 * Función loadTasks
 * Recupera las tareas del usuario activo llamando al API endpint personalizado en php
 * @returns promise
 */
function loadTasks() {
    return fetch("app/api/tasks/tasks.php", {
        method: "GET",
        credentials: "include",
    })
        .then((response) => response.json())
        .then((data) => {
            tasks = data;
        });
}

/**
 * Actualiza la vista de las categorías y de las tareas
 */
function updateView() {
    // console.log(categories);
    // console.log(tasks);

    updateCategoriesView();
    updateTasksView();
}

/**
 * Actualiza la vista de las categorías
 * Añade las categorías del usuario en el área de las categorías
 * Crea los elementos de forma dinámica y añade la clase selected-category a la categoría seleccionada
 * Añade escuchas de eventos para que al pulsat una categoría se actualice la categoría seleccionada
 */
function updateCategoriesView() {
    let categoriesDiv = document.querySelector("#categories");
    categoriesDiv.innerHTML = "";

    let categoryUl = document.createElement("ul");
    let noCategoryLi = document.createElement("li");
    noCategoryLi.appendChild(document.createTextNode("Sin categoría"));
    noCategoryLi.addEventListener("click", () => {
        updateSelectedCategory("Sin categoría");
    });
    if ("Sin categoría" === selectedCategory) {
        noCategoryLi.classList.add("selected-category");
    } else {
        noCategoryLi.classList.add("unselected-category");
    }
    categoryUl.appendChild(noCategoryLi);
    categories.forEach((category) => {
        let categoryLi = document.createElement("li");
        categoryLi.appendChild(document.createTextNode(category["name"]));
        categoryLi.addEventListener("click", () => {
            updateSelectedCategory(category["name"]);
        });
        if (category["name"] === selectedCategory) {
            categoryLi.classList.add("selected-category");
        } else {
            categoryLi.classList.add("unselected-category");
        }
        categoryUl.appendChild(categoryLi);
    });
    categoriesDiv.appendChild(categoryUl);
}

/**
 * Actualiza la categoría seleccionada y actualiza la vista sin acceder de nuevo a la base de datos
 * @param string category
 */
function updateSelectedCategory(category) {
    selectedCategory = category;
    updateView();
}

/**
 * Actualiza la vista de las tareas
 * Filtra las tareas de forma que solo se muestren las de la categoría seleccionada
 * También separa entre completadas y no completadas
 */
function updateTasksView() {
    let filteredTasks = [];
    let filteredTasksCompleted = [];
    let tasksDiv = document.querySelector("#tasks");
    tasksDiv.innerHTML = "";

    // Se filtran las tareas
    tasks.forEach((task) => {
        let taskCategory;
        for (let i = 0; i < categories.length; i++) {
            if (task["category_id"] === categories[i]["id"]) {
                taskCategory = categories[i]["name"];
                break;
            }
        }
        if (
            selectedCategory === "Sin categoría" &&
            task["category_id"] == null
        ) {
            filteredTasks.push(task);
        } else if (taskCategory === selectedCategory) {
            if (task["status"] == true) {
                filteredTasksCompleted.push(task);
            } else {
                filteredTasks.push(task);
            }
        }
    });

    // Se añaden las tareas al área de tareas
    filteredTasks.forEach((task) => {
        let singleTaskDiv = document.createElement("div");
        let taskCheckbox = document.createElement("input");
        taskCheckbox.setAttribute("type", "checkbox");
        if (task["status"] == true) {
            taskCheckbox.checked = true;
            singleTaskDiv.classList.add("completed");
        }
        taskCheckbox.addEventListener("change", () => {
            toggleCompleted(task);
        });
        singleTaskDiv.appendChild(taskCheckbox);
        let taskSpan = document.createElement("span");
        taskSpan.appendChild(document.createTextNode(task["title"]));
        taskSpan.addEventListener("click", () => {
            showTaskDetails(task);
        });
        singleTaskDiv.appendChild(taskSpan);
        let removeButton = document.createElement("button");
        removeButton.textContent = "x";
        removeButton.classList.add("removeButton");
        removeButton.addEventListener("click", () => {
            removeTask(task["id"]);
        });
        singleTaskDiv.appendChild(removeButton);
        tasksDiv.appendChild(singleTaskDiv);
    });

    // Si hay tareas completadas se muestran en un subapartado al final de las completadas y con una clase personalizada
    if (filteredTasksCompleted.length != 0) {
        let completedTittle = document.createElement("h2");
        completedTittle.appendChild(document.createTextNode("Completadas:"));
        completedTittle.classList.add("completedTitle");
        tasksDiv.appendChild(completedTittle);

        filteredTasksCompleted.forEach((task) => {
            let singleTaskDiv = document.createElement("div");
            let taskCheckbox = document.createElement("input");
            taskCheckbox.setAttribute("type", "checkbox");
            if (task["status"] == true) {
                taskCheckbox.checked = true;
                singleTaskDiv.classList.add("completed");
            }
            taskCheckbox.addEventListener("change", () => {
                toggleCompleted(task);
            });
            singleTaskDiv.appendChild(taskCheckbox);
            let taskSpan = document.createElement("span");
            taskSpan.appendChild(document.createTextNode(task["title"]));
            taskSpan.addEventListener("click", () => {
                showTaskDetails(task);
            });
            singleTaskDiv.appendChild(taskSpan);
            let removeButton = document.createElement("button");
            removeButton.textContent = "x";
            removeButton.classList.add("removeButton");
            removeButton.addEventListener("click", () => {
                removeTask(task["id"]);
            });
            singleTaskDiv.appendChild(removeButton);
            tasksDiv.appendChild(singleTaskDiv);
        });
    }
}

/**
 * Funcción de llamada a las AIs
 * Se utilizan las APIs en varias situaciones por lo que para evitar repetir código se crea esta función
 * Se pasan por parámetro los datos necesarios para realizar la llamada
 *
 * @param String apiURL URL de la API
 * @param Array config Parámetros que se comparten con la API
 * @param String errorMessage Mensaje de error personalizado si algo falla
 * @param function success Función flecha personalizada que se ejecuta si todo va bien
 */
function apiAccess(apiURL, config, errorMessage, success) {
    fetch(apiURL, {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(config),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                success();
            } else {
                alert(errorMessage);
            }
        });
}

/**
 * Función que cambia el estado de la tarea seleccionada
 * Llama a la API correspondiente
 * @param Array task
 */
function toggleCompleted(task) {
    let status = task["status"];
    if (status) {
        apiAccess(
            "app/api/tasks/toggleCompleted.php",
            {
                id: task["id"],
                newStatus: 0,
            },
            "Error: No se pudo realizar la operación.",
            () => {
                loadPage();
            }
        );
    } else {
        apiAccess(
            "app/api/tasks/toggleCompleted.php",
            {
                id: task["id"],
                newStatus: 1,
            },
            "Error: No se pudo realizar la operación.",
            () => {
                loadPage();
            }
        );
    }
}

/**
 * Muestra un diálogo con las características de la tarea pasada por parámetro
 * @param Array task
 */
function showTaskDetails(task) {
    let dialog = document.getElementById("taskDetailsDialog");

    document.getElementById("dialogTitle").textContent = task["title"];

    document.getElementById("dialogDescription").textContent =
        task["description"] || "No description";

    document.getElementById("dialogCategory").textContent = selectedCategory;

    document.getElementById("dialogStatus").textContent = task["status"]
        ? "Completada"
        : "Pendiente";

    document.getElementById("dialogDate").textContent = task["created_at"];

    dialog.showModal();
}


/**
 * Añade una categoría a la lista de categorías
 * Se añade a la base de datos mediante uso de una API personalizada
 */
function addCategory() {
    let categoryName = prompt("Indica el nombre de la nueva categoría:");
    if (categoryName.trim() === "") {
        alert("El nombre es obligatorio.");
    } else {
        apiAccess(
            "app/api/tasks/addCategory.php",
            {
                name: categoryName,
            },
            "Error: No se pudo realizar la operación.",
            () => {
                loadPage();
            }
        );
    }
}

/**
 * Elimina la categoría activa de la base de datos
 */
function removeCategory() {
    if (selectedCategory === "Sin categoría") {
        alert("Seleccione una categoría");
        return;
    }
    let confirmar = confirm(
        "Desea eliminar la categoría " +
            selectedCategory +
            " y todas las tareas que contiene?"
    );

    // Si no se confirma no elimina la categoría
    if (confirmar) {
        apiAccess(
            "app/api/tasks/removeCategory.php",
            {
                name: selectedCategory,
            },
            "Error: No se pudo realizar la operación.",
            () => {
                selectedCategory = "Sin categoría";
                loadPage();
            }
        );
    }
}

/**
 * Muestra el diálogo para añadir una tarea
 */
function showAddTask() {
    let dialog = document.getElementById("addTaskDialog");
    dialog.showModal();
}

/**
 * Añade una tarea a la base de datos según los datos de los campos rellenos
 */
function addTask() {
    let title = document.querySelector("#taskTitle").value;
    document.querySelector("#taskTitle").value = "";

    let description = document.querySelector("#taskDescription").value;
    document.querySelector("#taskDescription").value = "";

    let taskCategory = 0;

    // Recupera el id de la categoría seleccionada
    for (let i = 0; i < categories.length; i++) {
        if (selectedCategory === categories[i]["name"]) {
            taskCategory = categories[i]["id"];
            break;
        }
    }

    // Si el título no es válido no continúa, si lo es añade la tarea mediante el uso de una API personalizada
    if (title.trim() === "") {
        alert("El título es obligatorio.");
    } else {
        apiAccess(
            "app/api/tasks/addTask.php",
            {
                title: title,
                description: description,
                category: taskCategory,
            },
            "Error: No se pudo realizar la operación.",
            () => {
                document.getElementById("addTaskDialog").close();
                loadPage();
            }
        );
    }
}


/**
 * Elimina la tarea seleccionada de la base de datos mediante el uso de una API personalizada
 * @param Integer taskId 
 */
function removeTask(taskId) {
    let confirmar = confirm("¿Desea eliminar la tarea definitivamente?");

    // Si no se confirma no continúa
    if (!confirmar) {
        return;
    }

    apiAccess(
        "app/api/tasks/removeTask.php",
        {
            taskId: taskId,
        },
        "Error: No se pudo realizar la operación.",
        () => {
            loadPage();
        }
    );
}
