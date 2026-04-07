<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes pendientes</title>
    <link rel="stylesheet" href="public/css/style.css?v=10">
</head>
<body>

    <nav>
        <div>
            <a href="index.php?page=talleres">Talleres</a>
            <a href="index.php?page=admin">Gestionar Solicitudes</a>
        </div>
        <div>
            <span>Admin: <?= htmlspecialchars($_SESSION['user'] ?? 'admin') ?></span>
            <button type="button" id="btnLogout" class="btn btn-danger">Cerrar sesión</button>
        </div>
    </nav>

    <main>
        <h2>Solicitudes pendientes de aprobación</h2>

        <div id="mensaje">
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?= $mensaje['tipo'] === 'success' ? 'success' : 'danger' ?>">
                    <?= htmlspecialchars($mensaje['texto']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Taller</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($solicitudes)): ?>
                        <?php foreach ($solicitudes as $s): ?>
                            <tr>
                                <td><?= htmlspecialchars($s['id']) ?></td>
                                <td><?= htmlspecialchars($s['taller_nombre']) ?></td>
                                <td><?= htmlspecialchars($s['usuario_nombre']) ?></td>
                                <td><?= htmlspecialchars($s['fecha_solicitud']) ?></td>
                                <td>
                                    <form method="POST" action="index.php?page=admin" style="display:inline-block; margin-right:8px;">
                                        <input type="hidden" name="option" value="aprobar">
                                        <input type="hidden" name="id_solicitud" value="<?= (int)$s['id'] ?>">
                                        <button type="submit" class="btn btn-success">Aprobar</button>
                                    </form>

                                    <form method="POST" action="index.php?page=admin" style="display:inline-block;">
                                        <input type="hidden" name="option" value="rechazar">
                                        <input type="hidden" name="id_solicitud" value="<?= (int)$s['id'] ?>">
                                        <button type="submit" class="btn btn-danger">Rechazar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No hay solicitudes pendientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="public/js/auth.js?v=10"></script>
</body>
</html>