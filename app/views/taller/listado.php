<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talleres</title>
    <link rel="stylesheet" href="public/css/style.css?v=10">
</head>
<body>

    <nav>
        <div>
            <a href="index.php?page=talleres">Talleres</a>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="index.php?page=admin">Gestionar Solicitudes</a>
            <?php endif; ?>
        </div>
        <div>
            <span><?= htmlspecialchars($_SESSION['user'] ?? 'Usuario') ?></span>
            <button type="button" id="btnLogout" class="btn btn-danger">Cerrar sesión</button>
        </div>
    </nav>

    <main>
        <h2>Talleres disponibles</h2>

        <div id="mensaje">
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?= $mensaje['tipo'] === 'success' ? 'success' : 'danger' ?>">
                    <?= htmlspecialchars($mensaje['texto']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div id="listadoTalleres" class="card-listado">
            <?php if (!empty($talleres)): ?>
                <?php foreach ($talleres as $taller): ?>
                    <div class="card-taller">
                        <h4><?= htmlspecialchars($taller['nombre']) ?></h4>
                        <p>
                            <strong>Descripción:</strong>
                            <?= htmlspecialchars($taller['descripcion'] ?? '') ?>
                        </p>
                        <p>
                            <strong>Cupo máximo:</strong>
                            <?= htmlspecialchars($taller['cupo_maximo']) ?>
                        </p>
                        <p>
                            <strong>Cupos disponibles:</strong>
                            <?= htmlspecialchars($taller['cupos_disponibles']) ?>
                        </p>

                        <?php if ((int)$taller['cupos_disponibles'] > 0): ?>
                            <form method="POST" action="index.php?page=talleres">
                                <input type="hidden" name="option" value="solicitar">
                                <input type="hidden" name="taller_id" value="<?= (int)$taller['id'] ?>">
                                <button type="submit" class="btn btn-success">Solicitar inscripción</button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary" disabled>Sin cupos</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay talleres registrados.</p>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <div class="seccion-formulario">
                <h3>Agregar nuevo taller</h3>

                <form id="formTaller" method="POST" action="index.php?page=talleres">
                    <input type="hidden" name="option" value="guardar_taller">

                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre">

                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion"></textarea>

                    <label for="cupos">Cupos disponibles</label>
                    <input type="number" id="cupos" name="cupos" min="1">

                    <button type="submit">Guardar taller</button>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <script src="public/js/auth.js?v=10"></script>
</body>
</html>