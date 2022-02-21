<?php

class JudeteController
{

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read($judet)
    {
        $query = "SELECT judet_cod, judet_nume, judet_resedinta, judet_populatie FROM judete_ro WHERE judet_cod = :judet;";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':judet', $judet);

        $stmt->execute();

        return $stmt;
    }
}

?>