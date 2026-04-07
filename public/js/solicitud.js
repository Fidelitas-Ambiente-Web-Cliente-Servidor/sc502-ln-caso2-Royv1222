document.addEventListener("DOMContentLoaded", function () {
    var cuerpo = document.getElementById("solicitudes-body");
    var mensaje = document.getElementById("mensaje");

    if (cuerpo) {
        cargarSolicitudes();
    }

    async function cargarSolicitudes() {
        try {
            var response = await fetch("index.php?option=solicitudes_json", {
                method: "GET",
                credentials: "same-origin"
            });

            var text = await response.text();
            var solicitudes;

            try {
                solicitudes = JSON.parse(text);
            } catch (e) {
                console.error("Respuesta no JSON:", text);
                cuerpo.innerHTML = '<tr><td colspan="5">Error al cargar solicitudes.</td></tr>';
                return;
            }

            if (!Array.isArray(solicitudes)) {
                if (solicitudes && solicitudes.error) {
                    cuerpo.innerHTML = '<tr><td colspan="5">' + solicitudes.error + '</td></tr>';
                } else {
                    cuerpo.innerHTML = '<tr><td colspan="5">No hay solicitudes pendientes.</td></tr>';
                }
                return;
            }

            var html = "";

            if (solicitudes.length === 0) {
                html = '<tr><td colspan="5">No hay solicitudes pendientes.</td></tr>';
            } else {
                for (var i = 0; i < solicitudes.length; i++) {
                    var s = solicitudes[i];

                    html += '<tr>';
                    html += '<td>' + s.id + '</td>';
                    html += '<td>' + s.taller_nombre + '</td>';
                    html += '<td>' + s.usuario_nombre + '</td>';
                    html += '<td>' + s.fecha_solicitud + '</td>';
                    html += '<td>';
                    html += '<button type="button" class="btn btn-success btnAprobar" data-id="' + s.id + '">Aprobar</button> ';
                    html += '<button type="button" class="btn btn-danger btnRechazar" data-id="' + s.id + '">Rechazar</button>';
                    html += '</td>';
                    html += '</tr>';
                }
            }

            cuerpo.innerHTML = html;
        } catch (error) {
            console.error("Error cargar solicitudes:", error);
            cuerpo.innerHTML = '<tr><td colspan="5">Error al cargar solicitudes.</td></tr>';
        }
    }

    document.addEventListener("click", async function (e) {
        if (e.target.classList.contains("btnAprobar")) {
            var id = e.target.getAttribute("data-id");

            try {
                var body = new URLSearchParams();
                body.append("option", "aprobar");
                body.append("id_solicitud", id);

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
                    mensaje.innerHTML = '<div class="alert alert-danger">Error en la respuesta del servidor.</div>';
                    return;
                }

                if (data.success) {
                    mensaje.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    cargarSolicitudes();
                } else {
                    mensaje.innerHTML = '<div class="alert alert-danger">' + (data.error || 'No se pudo aprobar') + '</div>';
                }
            } catch (error) {
                console.error("Error aprobar:", error);
                mensaje.innerHTML = '<div class="alert alert-danger">Error al aprobar.</div>';
            }
        }

        if (e.target.classList.contains("btnRechazar")) {
            var idr = e.target.getAttribute("data-id");

            try {
                var body = new URLSearchParams();
                body.append("option", "rechazar");
                body.append("id_solicitud", idr);

                var response2 = await fetch("index.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    credentials: "same-origin",
                    body: body.toString()
                });

                var text2 = await response2.text();
                var data2;

                try {
                    data2 = JSON.parse(text2);
                } catch (e) {
                    console.error("Respuesta no JSON:", text2);
                    mensaje.innerHTML = '<div class="alert alert-danger">Error en la respuesta del servidor.</div>';
                    return;
                }

                if (data2.success) {
                    mensaje.innerHTML = '<div class="alert alert-success">' + data2.message + '</div>';
                    cargarSolicitudes();
                } else {
                    mensaje.innerHTML = '<div class="alert alert-danger">' + (data2.error || 'No se pudo rechazar') + '</div>';
                }
            } catch (error) {
                console.error("Error rechazar:", error);
                mensaje.innerHTML = '<div class="alert alert-danger">Error al rechazar.</div>';
            }
        }
    });
});