<?php
class Usuario {
    private $conn;
    private $table_usuario = "usuario";
    private $table_cliente = "cliente";

    public $id_usuario;
    public $correo;
    public $password;
    public $nombre;
    public $direccion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function actualizarPerfil() {
        try {
            $this->conn->beginTransaction();

            if (!empty($this->correo)) {
                $sql = "UPDATE {$this->table_usuario} SET correo_usuario = :correo WHERE id_usuario = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':correo', $this->correo);
                $stmt->bindParam(':id', $this->id_usuario);
                $stmt->execute();
            }

            if (!empty($this->password)) {
                $hash = password_hash($this->password, PASSWORD_DEFAULT);
                $sql = "UPDATE {$this->table_usuario} SET contraseña = :pass WHERE id_usuario = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':pass', $hash);
                $stmt->bindParam(':id', $this->id_usuario);
                $stmt->execute();
            }

            $sql = "UPDATE {$this->table_cliente} 
                    SET nombre = :nombre, direccion = :direccion 
                    WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':direccion', $this->direccion);
            $stmt->bindParam(':id', $this->id_usuario);
            $stmt->execute();

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>