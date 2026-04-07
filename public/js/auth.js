document.addEventListener("DOMContentLoaded", function () {
    var formLogin = document.getElementById("formLogin");
    var btnLogout = document.getElementById("btnLogout");

    if (formLogin) {
        formLogin.addEventListener("submit", async function (e) {
            e.preventDefault();

            var username = document.getElementById("username").value.trim();
            var password = document.getElementById("password").value.trim();

            if (username === "" || password === "") {
                alert("Debe completar todos los campos");
                return;
            }

            try {
                var body = new URLSearchParams();
                body.append("option", "login");
                body.append("username", username);
                body.append("password", password);

                var response = await fetch("index.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    credentials: "same-origin",
                    body: body.toString()
                });

                var text = await response.text();
                var data;

                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error("Respuesta no JSON:", text);
                    alert("Error en la respuesta del servidor");
                    return;
                }

                if (data.response === "00") {
                    window.location.href = "index.php?page=talleres";
                } else {
                    alert(data.message || "No se pudo iniciar sesión");
                }
            } catch (error) {
                console.error("Error login:", error);
                alert("Error en login");
            }
        });
    }

    if (btnLogout) {
        btnLogout.addEventListener("click", async function () {
            try {
                var body = new URLSearchParams();
                body.append("option", "logout");

                var response = await fetch("index.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    credentials: "same-origin",
                    body: body.toString()
                });

                var text = await response.text();
                var data;

                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error("Respuesta no JSON:", text);
                    alert("Error en la respuesta del servidor");
                    return;
                }

                if (data.response === "00") {
                    window.location.href = "index.php?page=login";
                } else {
                    alert(data.message || "No se pudo cerrar sesión");
                }
            } catch (error) {
                console.error("Error logout:", error);
                alert("Error al cerrar sesión");
            }
        });
    }
});