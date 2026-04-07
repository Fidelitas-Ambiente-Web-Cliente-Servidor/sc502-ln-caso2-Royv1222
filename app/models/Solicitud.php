<?php

class Solicitud
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function crear($usuarioId, $tallerId)
    {
        $query = "INSERT INTO solicitudes (usuario_id, taller_id, estado, fecha_solicitud)
                  VALUES (?, ?, 'pendiente', NOW())";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ii", $usuarioId, $tallerId);

        if (!$stmt->execute()) {
            return false;
        }

        return $stmt->affected_rows > 0;
    }

    public function existeSolicitudActivaOAprobada($usuarioId, $tallerId)
    {
        $query = "SELECT id
                  FROM solicitudes
                  WHERE usuario_id = ?
                  AND taller_id = ?
                  AND estado IN ('pendiente', 'aprobada')
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ii", $usuarioId, $tallerId);

        if (!$stmt->execute()) {
            return false;
        }

        $result = $stmt->get_result();
        if (!$result) {
            return false;
        }

        return $result->num_rows > 0;
    }

    public function getPendientes()
    {
        $query = "SELECT s.id,
                         s.fecha_solicitud,
                         t.nombre AS taller_nombre,
                         u.username AS usuario_nombre
                  FROM solicitudes s
                  INNER JOIN talleres t ON s.taller_id = t.id
                  INNER JOIN usuarios u ON s.usuario_id = u.id
                  WHERE s.estado = 'pendiente'
                  ORDER BY s.fecha_solicitud ASC";

        $result = $this->conn->query($query);
        if (!$result) {
            return [];
        }

        $solicitudes = [];

        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }

        return $solicitudes;
    }

    public function getById($id)
    {
        $query = "SELECT *
                  FROM solicitudes
                  WHERE id = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            return null;
        }

        $result = $stmt->get_result();
        if (!$result) {
            return null;
        }

        return $result->fetch_assoc();
    }

    public function aprobar($id)
    {
        $query = "UPDATE solicitudes
                  SET estado = 'aprobada'
                  WHERE id = ? AND estado = 'pendiente'";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            return false;
        }

        return $stmt->affected_rows > 0;
    }

    public function rechazar($id)
    {
        $query = "UPDATE solicitudes
                  SET estado = 'rechazada'
                  WHERE id = ? AND estado = 'pendiente'";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            return false;
        }

        return $stmt->affected_rows > 0;
    }
}