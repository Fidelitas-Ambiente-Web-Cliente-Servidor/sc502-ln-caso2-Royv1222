<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="public/js/auth.js"></script>
</head>
<body>

    <main class="container">
        <h2>Login</h2>

        <form id="formLogin">
            <input type="text" name="username" id="username" placeholder="Usuario">
            <input type="password" name="password" id="password" placeholder="Contraseña">

            <button type="submit">Ingresar</button>
            <a href="index.php?page=registro" class="btn btn-secondary">Registrarse</a>
        </form>
    </main>

</body>
</html>