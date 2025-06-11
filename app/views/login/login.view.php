<main>
    <div class="flex-container">
        
        <div class="form-box active" id="login-form">
            <form action="?c=login&a=loginUser" method="post">
                <h2>Iniciar sesión</h2>
                <div id="errors">
                    <?php
                    if ($error !== '') {
                        echo '<div class="login-error">' . $error . '</div>';
                    }
                    ?>
                </div>
                <div class="inputDiv">
                    <input type="text" name="email" placeholder="Nombre de usuario o email"
                        value="<?php isset($_COOKIE["lastUsedEmail"]) ? print $_COOKIE["lastUsedEmail"] : "" ?>"
                        id="loginEmail"
                    >
                    <span class="input-error" id="loginEmail-error"></span>
                </div>
                <div class="inputDiv">
                    <input type="password" name="password" placeholder="Contraseña" id="loginPassword">
                    <span class="input-error" id="loginPassword-error"></span>
                </div>
                <button type="submit" name="login" id="loginButton">Login</button>
                <p>No tienes una cuenta? <a href="#" onclick="showForm('register-form')">Registrarse</a></p>
            </form>
        </div>

        <div class="form-box" id="register-form">
            <form action="?c=login&a=saveUser" method="post">
                <h2>Registrarse</h2>
                <?php
                if ($error !== '') {
                    echo '<div class="login-error">' . $error . '</div>';
                }
                ?>
                <div class="inputDiv">
                    <input type="text" name="name" placeholder="Nombre" id="registerName">
                    <span id="registerName-error" class="input-error"></span>
                </div>
                <div class="inputDiv">
                    <input type="text" name="username" placeholder="Nombre de usuario" id="registerUsername">
                    <span id="registerUsername-error" class="input-error"></span>
                </div>
                <div class="inputDiv">
                    <input type="email" name="email" placeholder="Email" id="registerEmail">
                    <span id="registerEmail-error" class="input-error"></span>
                </div>
                <div class="inputDiv">
                    <input type="password" name="password" placeholder="Contraseña" id="registerPassword">
                    <span id="registerPassword-error" class="input-error"></span>
                </div>
                <button type="submit" name="register" id="registerButton">Register</button>
                <p>Ya tienes una cuenta? <a href="#" onclick="showForm('login-form')">Iniciar sesión</a></p>
            </form>
        </div>
    </div>

    <script src="public/javascript/login.js"></script>
</main>