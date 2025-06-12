<!--
    Vista principal de la página principal
-->

<main>

    <div class="pomodoro-container">
        <div class="timer">
            <h1>Pomodoro timer</h1>
            <p class="cicles">Pomodoros completados: <strong><span id="cicles"></span></strong></p>
            <div class="button-container">
                <button class="pomodoro-button" id="pomodoro-session">Pomodoro</button>
                <button class="pomodoro-button" id="short-break">Descanso</button>
            </div>

            <section class="pomodoro">
                <div id="pomodoro-timer" class="timer-display" data-duration="25.00">
                    <h1 class="time">25:00</h1>
                </div>
                <div id="short-timer" class="timer-display" data-duration="5.00">
                    <h1 class="time">5:00</h1>
                </div>
            </section>

            <div class="buttons">
                <button class="control-button" id="pomodoro-start">Comenzar</button>
                <button class="control-button" id="pomodoro-reset">Reiniciar contador</button>
                <button class="control-button" id="pomodoro-stop">Parar</button>
            </div>
        </div>
    </div>

    <!-- Script JavaScript -->
     <script src="public/javascript/pomodoro.js"></script>
</main>
