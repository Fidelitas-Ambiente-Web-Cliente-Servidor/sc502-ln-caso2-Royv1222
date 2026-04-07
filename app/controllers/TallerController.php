<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Taller.php';
require_once __DIR__ . '/../models/Solicitud.php';

class TallerController
{
    private $tallerModel;
    private $solicitudModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->tallerModel = new Taller($db);
        $this->solicitudModel = new Solicitud($db);
    }

    private function redirigirConMensaje($tipo, $texto)
    {
        $_SESSION['mensaje_taller'] = [
            'tipo' => $tipo,
            'texto' => $texto
        ];

        header('Location: index.php?page=talleres');
        exit;
    }

    public function index()
    {
        if (!isset($_SESSION['id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $talleres = $this->tallerModel->getAll();

        $mensaje = $_SESSION['mensaje_taller'] ?? null;
        unset($_SESSION['mensaje_taller']);

        require __DIR__ . '/../views/taller/listado.php';
    }

    public function getTalleresJson()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id'])) {
            echo json_encode([]);
            return;
        }

        $talleres = $this->tallerModel->getAll();
        echo json_encode($talleres);
    }

    public function solicitar()
    {
        if (!isset($_SESSION['id'])) {
            $this->redirigirConMensaje('danger', 'Debes iniciar sesión');
        }

        $tallerId = isset($_POST['taller_id']) ? (int) $_POST['taller_id'] : 0;
        $usuarioId = (int) $_SESSION['id'];

        if ($tallerId <= 0) {
            $this->redirigirConMensaje('danger', 'Taller inválido');
        }

        $taller = $this->tallerModel->getById($tallerId);

        if (!$taller) {
            $this->redirigirConMensaje('danger', 'Taller no encontrado');
        }

        if ((int)$taller['cupos_disponibles'] <= 0) {
            $this->redirigirConMensaje('danger', 'No hay cupos disponibles');
        }

        if ($this->solicitudModel->existeSolicitudActivaOAprobada($usuarioId, $tallerId)) {
            $this->redirigirConMensaje('danger', 'Ya tienes una solicitud pendiente o aprobada para este taller');
        }

        if ($this->solicitudModel->crear($usuarioId, $tallerId)) {
            $this->redirigirConMensaje('success', 'Solicitud enviada correctamente');
        } else {
            $this->redirigirConMensaje('danger', 'No se pudo registrar la solicitud');
        }
    }

    public function guardar()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            $this->redirigirConMensaje('danger', 'No autorizado');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $cupos = (int)($_POST['cupos'] ?? 0);

        if ($nombre === '' || $descripcion === '' || $cupos <= 0) {
            $this->redirigirConMensaje('danger', 'Debe completar todos los campos correctamente');
        }

        if ($this->tallerModel->crear($nombre, $descripcion, $cupos)) {
            $this->redirigirConMensaje('success', 'Taller guardado correctamente');
        } else {
            $this->redirigirConMensaje('danger', 'No se pudo guardar el taller');
        }
    }
}