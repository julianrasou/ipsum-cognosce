let categories = [];
let tasks = [];
let selectedCategory = "Sin categoría";

document.addEventListener("DOMContentLoaded", () => {
    loadPage();
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
            document.querySelector("#taskTitle").value = "";
            document.querySelector("#taskDescription").value = "";
        });
});

async function loadPage() {
    await loadCategories();
    await loadTasks();
    updateView();
}

function loadCategories() {
    return fetch("app/api/categories.php", {
        method: "GET",
        credentials: "include",
    })
        .then((response) => response.json())
        .then((data) => {
            categories = data;
        });
}

function loadTasks() {
    return fetch("app/api/tasks.php", {
        method: "GET",
        credentials: "include",
    })
        .then((response) => response.json())
        .then((data) => {
            tasks = data;
        });
}

function updateView() {
    console.log(categories);
    console.log(tasks);

    updateCategoriesView();
    updateTasksView();
}

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

function updateSelectedCategory(category) {
    selectedCategory = category;
    updateView();
}

function updateTasksView() {
    let filteredTasks = [];
    let filteredTasksCompleted = [];
    let tasksDiv = document.querySelector("#tasks");
    tasksDiv.innerHTML = "";

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

function toggleCompleted(task) {
    let status = task["status"];
    if (status) {
        apiAccess(
            "app/api/toggleCompleted.php",
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
            "app/api/toggleCompleted.php",
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

function addCategory() {
    let categoryName = prompt("Indica el nombre de la nueva categoría:");

    apiAccess(
        "app/api/addCategory.php",
        {
            name: categoryName,
        },
        "Error: No se pudo realizar la operación.",
        () => {
            loadPage();
        }
    );
}

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
    if (confirmar) {
        apiAccess(
            "app/api/removeCategory.php",
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

function showAddTask() {
    let dialog = document.getElementById("addTaskDialog");
    dialog.showModal();
}

function addTask() {
    let title = document.querySelector("#taskTitle").value;
    document.querySelector("#taskTitle").value = "";
    let description = document.querySelector("#taskDescription").value;
    document.querySelector("#taskDescription").value = "";
    let taskCategory = 0;
    for (let i = 0; i < categories.length; i++) {
        if (selectedCategory === categories[i]["name"]) {
            taskCategory = categories[i]["id"];
            break;
        }
    }

    if (title.trim() === "") {
        alert("El título es obligatorio.");
    } else {
        apiAccess(
            "app/api/addTask.php",
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

function removeTask(taskId) {
    let confirmar = confirm("¿Desea eliminar la tarea definitivamente?");

    if (!confirmar) {
        return;
    }

    apiAccess(
        "app/api/removeTask.php",
        {
            taskId: taskId,
        },
        "Error: No se pudo realizar la operación.",
        () => {
            loadPage();
        }
    );
}
