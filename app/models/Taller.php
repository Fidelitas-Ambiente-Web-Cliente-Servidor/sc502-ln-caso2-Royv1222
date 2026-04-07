<?php
class Taller
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT 
                    id,
                    nombre,
                    descripcion,
                    cupo_maximo,
                    cupo_disponible AS cupos_disponibles
                  FROM talleres
                  ORDER BY nombre";

        $result = $this->conn->query($query);
        $talleres = [];

        while ($row = $result->fetch_assoc()) {
            $talleres[] = $row;
        }

        return $talleres;
    }

    public function getAllDisponibles()
    {
        $query = "SELECT 
                    id,
                    nombre,
                    descripcion,
                    cupo_maximo,
                    cupo_disponible AS cupos_disponibles
                  FROM talleres
                  WHERE cupo_disponible > 0
                  ORDER BY nombre";

        $result = $this->conn->query($query);
        $talleres = [];

        while ($row = $result->fetch_assoc()) {
            $talleres[] = $row;
        }

        return $talleres;
    }

    public function getById($id)
    {
        $query = "SELECT 
                    id,
                    nombre,
                    descripcion,
                    cupo_maximo,
                    cupo_disponible AS cupos_disponibles
                  FROM talleres
                  WHERE id = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function descontarCupo($tallerId)
    {
        $query = "UPDATE talleres
                  SET cupo_disponible = cupo_disponible - 1
                  WHERE id = ? AND cupo_disponible > 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $tallerId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function sumarCupo($tallerId)
    {
        $query = "UPDATE talleres
                  SET cupo_disponible = cupo_disponible + 1
                  WHERE id = ? AND cupo_disponible < cupo_maximo";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $tallerId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function crear($nombre, $descripcion, $cupos)
    {
        $query = "INSERT INTO talleres (nombre, descripcion, cupo_maximo, cupo_disponible)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssii", $nombre, $descripcion, $cupos, $cupos);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}