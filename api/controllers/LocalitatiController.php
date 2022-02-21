<?php

class LocalitatiController
{

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read($judet)
    {
        $query = "SELECT 
                      DISTINCT(localitate) 
                    FROM 
                      v_coduripostale 
                    WHERE 
                      judet_cod = :judet
                    ORDER BY 
                      localitate;
                    ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':judet', $judet);

        $stmt->execute();

        return $stmt;
    }
}

?>