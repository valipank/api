<?php

class EvolutieLocalitatiController
{

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read($judet, $localitate)
    {
        $query = "SELECT 
                      DATA, 
                      cazuri, 
                      incidenta 
                    FROM 
                      full_corona_ro_cities 
                    WHERE 
                      judet_cod = :judet 
                      AND localitate = :localitate 
                    ORDER BY 
                      DATA;";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':judet', $judet);
        $stmt->bindParam(':localitate', $localitate);

        $stmt->execute();

        return $stmt;
    }
}

?>