<main>
    <div class="container">
        <?php
        if( $error !== '' ) {
            echo '<div class="login-error">' . $error . '</div>';
        }
        ?>
        <div class="form-box active" id="login-form">
            <form action="?c=login&a=loginUser" method="post">
                <h2>Iniciar sesión</h2>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="login">Login</button>
                <p>No tienes una cuenta? <a href="#" onclick="showForm('register-form')">Registrarse</a></p>
            </form>
        </div>

        <div class="form-box" id="register-form">
            <form action="?c=login&a=saveUser" method="post">
                <h2>Registrarse</h2>
                <input type="text" name="name" placeholder="Nombre" required>
                <input type="text" name="username" placeholder="Nombre de usuario" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="register">Register</button>
                <p>Ya tienes una cuenta? <a href="#" onclick="showForm('login-form')">Iniciar sesión</a></p>
            </form>
        </div>
    </div>

    <script src="public/javascript/login.js"></script>
</main>