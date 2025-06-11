/**
 * Cambia entre un formulario y el otro de la página de login añadiendo y removiendo la clase active
 *
 * @param string formId Id del formulario a mostrar
 */
function showForm(formId) {
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}

// Añade escuchas de eventos a los botones de login y register para validar los campos
document.querySelector("#registerButton").addEventListener("click", validateRegisterForm);
document.querySelector("#loginButton").addEventListener("click", validateLoginForm);

/**
 * Si los inputs no son válidos evita el envío del formulario
 *
 * @param event e Elemento sobre el que actúa el evento
 */
function validateRegisterForm(e) {
    if ( !validRegisterInputs() ) {
        e.preventDefault();
    }
}

/**
 * Si los inputs no son válidos evita el envío del formulario
 *
 * @param event e Elemento sobre el que actúa el evento
 */
function validateLoginForm(e) {
    if ( !validLoginInputs() ) {
        e.preventDefault();
    }
}

/**
 * Comprueba los campos y devuelve true o false dependiendo de si hay alguno inválido
 * Añade mensajes de error a los campos que tienen errores
 *
 * @returns boolean - true si los campos son válidos, false al contrario
 */
function validRegisterInputs() {

    // Recupera los datos de los campos
    let name = document.querySelector("#registerName");
    let username = document.querySelector("#registerUsername");
    let email = document.querySelector("#registerEmail");
    let password = document.querySelector("#registerPassword");

    // Limpia los errores anteriores cada vez que se comprueba para no mantener errores que no corresponden
    clearErrors([
        "registerName",
        "registerUsername",
        "registerEmail",
        "registerPassword"
    ]);

    // Variable valid que almacena el estado de la validación
    let valid = true;

    // Se valida el nombre
    if (name.value.trim() === "") {
        valid = false;
        error("registerName", "El nombre no puede estar vacío.");
    } else if (!/^[a-zA-Z\.\-]+$/.test(name.value.trim())) {
        valid = false;
        error("registerName", "Caracteres válidos: letras, puntos, -.");
    }

    // Se valida el nombre de usuario
    if (username.value.trim() === "") {
        valid = false;
        error("registerUsername", "El nombre de usuario no puede estar vacío.");
    } else if (!/^[a-zA-Z0-9_]+$/.test(username.value.trim())) {
        valid = false;
        error("registerUsername", "Caracteres válidos: letras, números, _.");
    }

    // Se valida el e-mail
    if(email.value.trim() === "") {
        valid = false;
        error("registerEmail", "El E-Mail no puede estar vacío.");
    } else if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email.value.trim())) {
        valid = false;
        error("registerEmail", "E-Mail inválido");
    }

    // Se valida la contraseña
    if(!/^.{8,}$/.test(password.value)) {
        valid = false;
        error("registerPassword", "La contraseña debe contener al menos 8 caracteres.")
    } else if (!/(?=.*\d)/.test(password.value)) {
        valid = false;
        error("registerPassword", "La contraseña debe contener al menos un número.")
    } else if (!/(?=.*[A-Za-z])/.test(password.value)) {
        valid = false;
        error("registerPassword", "La contraseña debe contener al menos una letra.")
    }

    // Se devuelve el estado de la validación
    return valid;
}

/**
 * Comprueba los campos y devuelve true o false dependiendo de si hay alguno inválido
 * Añade mensajes de error a los campos que tienen errores
 *
 * @returns boolean - true si los campos son válidos, false al contrario
 */
function validLoginInputs() {

    // Recupera los datos de los campos
    let email = document.querySelector("#loginEmail");
    let password = document.querySelector("#loginPassword");

    // Limpia los errores anteriores cada vez que se comprueba para no mantener errores que no corresponden
    clearErrors([
        "loginEmail",
        "loginPassword"
    ]);

    // Variable valid que almacena el estado de la validación
    let valid = true;

    // Se valida el E-Mail
    if(email.value.trim() === "") {
        valid = false;
        error("loginEmail", "El E-Mail no puede estar vacío.");
    }

    // Se valida la contraseña
    if(password.value.trim() === "") {
        valid = false;
        error("loginPassword", "La contraseña no puede estar vacía.");
    }

    // Se devuelve el estado de la validación
    return valid;
}

/**
 * Añade un mensaje de error al elemento pasado por parámetro
 *
 * @param string element Elemento que tiene error
 * @param string message Mensaje de error
 */
function error(element, message) {
    let span = document.getElementById(element + "-error");
    span.innerHTML = message;
}

/**
 * Limpia los errores de los elementos pasados por parámetro en forma de array
 *
 * @param array elements 
 */
function clearErrors(elements) {
    elements.forEach(element => {
        let span = document.getElementById(element + "-error");
        span.innerHTML = "";
    });
}