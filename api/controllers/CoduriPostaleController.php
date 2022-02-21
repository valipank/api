<?php

class CoduriPostaleController
{

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getListByCodPostal($cod)
    {
        $query = "SELECT * FROM v_coduripostale WHERE cod_postal LIKE :cod;";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':cod', $cod);

        $stmt->execute();

        return $stmt;
    }
}

?>