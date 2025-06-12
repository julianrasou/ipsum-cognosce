// Se inicializa el array que contendrá las categorías que se muestran
let categories = [];
// Se inicializa el array que contendrá las notas que se muestran
let notes = [];
// Se inicializa la categoría seleccionada
let selectedCategory = "Sin categoría";
// Se inicializa la nota seleccionada
let selectedNote = "0"

// Se añade una escucha de evento a la página de forma que cuanto cargue el DOM se ejecuten las siguientes instrucciones
document.addEventListener("DOMContentLoaded", () => {
    // Se carga la página
    loadPage();

    // Se añaden las escuchas de eventos a los diferentes boñtones de la página para
    // que cumplan sus funciones correspondientes
    document.getElementById("closeDialogBtn").addEventListener("click", () => {
        document.getElementById("noteDetailsDialog").close();
    });

    document.getElementById("editNoteBtn").addEventListener("click", () => {
        window.location = '/ipsum-cognosce?c=notes&a=edit&id=' + selectedNote;
    });

    document.getElementById("addNote").addEventListener("click", addNote);

    document
        .getElementById("addCategory")
        .addEventListener("click", addCategory);

    document
        .getElementById("removeCategory")
        .addEventListener("click", removeCategory);

});


/**
 * Función addNote
 * Redirige a la página de editar notas
 */
function addNote() {
    if(selectedCategory === "Sin categoría") {
        window.location = "/ipsum-cognosce?c=notes&a=edit";
    } else {
        let noteCategory;
        for (let i = 0; i < categories.length; i++) {
            if (selectedCategory === categories[i]["name"]) {
                noteCategory = categories[i]["id"];
                break;
            }
        }
        window.location = "/ipsum-cognosce?c=notes&a=edit&cat=" + noteCategory;
    }
}

/**
 * Función que carga la página
 * Carga las categorías
 * Carga las notas
 * Actualiza la vista
 */
async function loadPage() {
    await loadCategories();
    await loadNotes();
    updateView();
}

/**
 * Función loadCategories
 * Recupera las categorías del usuario activo llamando al API endpint personalizado en php
 * @returns promise
 */
function loadCategories() {
    return fetch("app/api/notes/categories.php", {
        method: "GET",
        credentials: "include",
    })
        .then((response) => response.json())
        .then((data) => {
            categories = data;
        });
}

/**
 * Función loadNotes
 * Recupera las notas del usuario activo llamando al API endpint personalizado en php
 * @returns promise
 */
function loadNotes() {
    return fetch("app/api/notes/notes.php", {
        method: "GET",
        credentials: "include",
    })
        .then((response) => response.json())
        .then((data) => {
            notes = data;
        });
}

/**
 * Actualiza la vista de las categorías y de las notas
 */
function updateView() {
    // console.log(categories);
    // console.log(notes);

    updateCategoriesView();
    updateNotesView();
}

/**
 * Actualiza la vista de las categorías
 * Añade las categorías del usuario en el área de las categorías
 * Crea los elementos de forma dinámica y añade la clase selected-category a la categoría seleccionada
 * Añade escuchas de eventos para que al pulsar una categoría se actualice la categoría seleccionada
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
 * Actualiza la vista de las notas
 * Filtra las tareas de forma que solo se muestren las de la categoría seleccionada
 */
function updateNotesView() {
    let filteredNotes = [];
    let notesDiv = document.querySelector("#notes");
    notesDiv.innerHTML = "";

    // Se filtran las tareas
    notes.forEach((note) => {
        let noteCategory;
        for (let i = 0; i < categories.length; i++) {
            if (note["category_id"] === categories[i]["id"]) {
                noteCategory = categories[i]["name"];
                break;
            }
        }
        if (
            selectedCategory === "Sin categoría" &&
            note["category_id"] == null
        ) {
            filteredNotes.push(note);
        } else if (noteCategory === selectedCategory) {
            filteredNotes.push(note);
        }
    });

    // Se añaden las tareas al área de tareas
    filteredNotes.forEach((note) => {
        let singleNoteDiv = document.createElement("div");
        let icon = document.createElement("img");
        icon.setAttribute("src", "public/images/writing.png");
        icon.setAttribute("class", "noteLogo");
        singleNoteDiv.appendChild(icon);
        let noteSpan = document.createElement("span");
        noteSpan.appendChild(document.createTextNode(note["title"]));
        noteSpan.addEventListener("click", () => {
            showNoteDetails(note);
        });
        noteSpan.addEventListener("click", () => {
            showNoteDetails(note);
        });
        singleNoteDiv.appendChild(noteSpan);
        let removeButton = document.createElement("button");
        removeButton.textContent = "x";
        removeButton.classList.add("removeButton");
        removeButton.addEventListener("click", () => {
            removeNote(note["id"]);
        });
        singleNoteDiv.appendChild(removeButton);
        notesDiv.appendChild(singleNoteDiv);
    });
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
 * Muestra un diálogo con las características de la nota pasada por parámetro
 * @param Array task
 */
function showNoteDetails(note) {
    let dialog = document.getElementById("noteDetailsDialog");

    selectedNote = note["id"];

    document.getElementById("dialogTitle").textContent = note["title"];

    document.getElementById("dialogContent").textContent =
        note["content"] || "";

    document.getElementById("dialogCategory").textContent = selectedCategory;

    document.getElementById("dialogDate").textContent = note["created_at"];

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
            "app/api/notes/addCategory.php",
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
            " y todas las notas que contiene?"
    );

    // Si no se confirma no elimina la categoría
    if (confirmar) {
        apiAccess(
            "app/api/notes/removeCategory.php",
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
 * Elimina la tarea seleccionada de la base de datos mediante el uso de una API personalizada
 * @param Integer taskId 
 */
function removeNote(noteId) {
    let confirmar = confirm("¿Desea eliminar la nota definitivamente?");

    // Si no se confirma no continúa
    if (!confirmar) {
        return;
    }

    apiAccess(
        "app/api/notes/removeNote.php",
        {
            noteId: noteId,
        },
        "Error: No se pudo realizar la operación.",
        () => {
            loadPage();
        }
    );
}