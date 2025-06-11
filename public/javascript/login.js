function showForm(formId) {
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}

document.querySelector("#registerButton").addEventListener("click", validateRegisterForm);
document.querySelector("#loginButton").addEventListener("click", validateLoginForm);

function validateRegisterForm(e) {
    if ( !validRegisterInputs() ) {
        e.preventDefault();
    }
}

function validateLoginForm(e) {
    if ( !validLoginInputs() ) {
        e.preventDefault();
    }
}

function validRegisterInputs() {
    let name = document.querySelector("#registerName");
    let username = document.querySelector("#registerUsername");
    let email = document.querySelector("#registerEmail");
    let password = document.querySelector("#registerPassword");
    clearErrors([
        "registerName",
        "registerUsername",
        "registerEmail",
        "registerPassword"
    ]);

    let valid = true;
    if (name.value.trim() === "") {
        valid = false;
        error("registerName", "El nombre no puede estar vacío.");
    } else if (!/^[a-zA-Z\.\-]+$/.test(name.value.trim())) {
        valid = false;
        error("registerName", "Caracteres válidos: letras, puntos, -.");
    }

    if (username.value.trim() === "") {
        valid = false;
        error("registerUsername", "El nombre no puede estar vacío.");
    } else if (!/^[a-zA-Z0-9_]+$/.test(username.value.trim())) {
        valid = false;
        error("registerUsername", "Caracteres válidos: letras, números, _.");
    }

    if(email.value.trim() === "") {
        valid = false;
        error("registerEmail", "El email no puede estar vacío.");
    } else if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email.value.trim())) {
        valid = false;
        error("registerEmail", "Correo electrónico inválido");
    }

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

    return valid;
}

function validLoginInputs() {
    let email = document.querySelector("#loginEmail");
    let password = document.querySelector("#loginPassword");
    clearErrors([
        "loginEmail",
        "loginPassword"
    ]);

    let valid = true;
    if(email.value.trim() === "") {
        valid = false;
        error("loginEmail", "El email no puede estar vacío.");
    }

    if(password.value.trim() === "") {
        valid = false;
        error("loginPassword", "La contraseña no puede estar vacía.");
    }

    return valid;
}

function error(element, message) {
    let span = document.getElementById(element + "-error");
    span.innerHTML = message;
}

function clearErrors(elements) {
    elements.forEach(element => {
        let span = document.getElementById(element + "-error");
        span.innerHTML = "";
    });
}