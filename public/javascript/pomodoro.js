// Se inicializan las variables que contienen los diferentes elementos del contador
// así como un contador para los ciclos de pomodoro completados
let pomodoro = document.getElementById("pomodoro-timer");
let short = document.getElementById("short-timer");
let timers = document.querySelectorAll(".timer-display");
let session = document.getElementById("pomodoro-session");
let shortBreak = document.getElementById("short-break");
let startBtn = document.getElementById("pomodoro-start");
let stopBtn = document.getElementById("pomodoro-stop");
let resetBtn = document.getElementById("pomodoro-reset");
let button = document.querySelector(".pomodoro-button");
let cicles = 0;

updateCicles();

// Se almacena el contador activo así como el intervalo
let currentTimer = pomodoro;
let myInterval = null;

// Muestra contador predeterminado al cargar la página
function showDefaultTimer() {
    pomodoro.style.display = "block";
    short.style.display = "none";
}
showDefaultTimer();

/**
 * Función que oculta todos los contadores
 */
function hideAll() {
    timers.forEach((timer) => (timer.style.display = "none"));
}

session.addEventListener("click", startPomodoro);

/**
 * Muestra el contador del pomodoro y reinicia el del descanso
 */
function startPomodoro() {
    hideAll();

    if (currentTimer) {
        clearInterval(myInterval);
    }

    short.textContent = "5:00";

    pomodoro.style.display = "block";

    session.classList.add("active");
    shortBreak.classList.remove("active");

    currentTimer = pomodoro;
}

shortBreak.addEventListener("click", startBreak);

/**
 * Muestra el contador del descanso y reinicia el del pomodoro
 */
function startBreak() {
    hideAll();

    if (currentTimer) {
        clearInterval(myInterval);
    }

    pomodoro.textContent = "25:00";

    short.style.display = "block";

    session.classList.remove("active");
    shortBreak.classList.add("active");

    currentTimer = short;
}

/**
 * Comienza la cuentra atrás almacenada en la variable
 * @param element timerDisplay la cuenta atrás almacenada
 */
function startTimer(timerDisplay) {
    // limpia el intervalo si existe
    if (myInterval) {
        clearInterval(myInterval);
    }

    // recupera el tiempo del contador seleccionado
    timerDuration = timerDisplay.getAttribute("data-duration").split(":")[0];

    // calcula el tiempo de duración y cuándo termina
    let durationinmiliseconds = timerDuration * 60 * 1000;
    let endTimestamp = Date.now() + durationinmiliseconds;

    // establece el intervalo
    myInterval = setInterval(function () {
        const timeRemaining = new Date(endTimestamp - Date.now());

        if (timeRemaining <= 0) {
            clearInterval(myInterval);

            // salta una alarma al terminar un pomodoro o descanso
            const alarm = new Audio("public/audio/bell.mp3");
            alarm.play();

            // inicia el siguiente ciclo / descanso 
            if (timerDisplay === pomodoro) {
                startBreak();
                start();
            } else if ((timerDisplay = short)) {
                startPomodoro();
                start();
                cicles += 1;
                updateCicles();
            }
        } else {
            // calcula minnutos y segundos
            const minutes = Math.floor(timeRemaining / 60000);
            const seconds = ((timeRemaining % 60000) / 1000).toFixed(0);
            const formattedTime = `${minutes}:${seconds
                .toString()
                .padStart(2, "0")}`;
            timerDisplay.textContent = formattedTime;
        }
    }, 1000);
}

startBtn.addEventListener("click", start);

/**
 * Comienza el contador activo
 */
function start() {
    if (currentTimer) {
        startTimer(currentTimer);
    }
}

// Detiene el contador activo
stopBtn.addEventListener("click", () => {
    if (currentTimer) {
        clearInterval(myInterval);
    }
});

// Resetea el contador de ciclos
resetBtn.addEventListener("click", () => {
    cicles = 0;
    updateCicles();
});

/**
 * Actualiza el mensaje que muestra los ciclos completados
 */
function updateCicles() {
    document.getElementById("cicles").innerHTML = cicles;
}
