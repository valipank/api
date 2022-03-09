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
                      date_format(data,'%Y-%m-%d') AS date, 
                      cazuri, 
                      incidenta,
                      ROUND((
                            SELECT sum(b.cazuri) / COUNT(b.cazuri)
                            FROM full_corona_ro_cities b 
                            WHERE ( judet_cod = :judet AND localitate = :localitate) 
                              AND DATEDIFF (a.data , b.data) BETWEEN 0 AND 13
                          ), 2) AS avg
                        FROM 
                          full_corona_ro_cities a 
                        WHERE 
                          judet_cod = :judet 
                          AND localitate = :localitate
                        ORDER BY 
                          a.DATA;";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':judet', $judet);
        $stmt->bindParam(':localitate', $localitate);

        $stmt->execute();

        return $stmt;
    }
}

?>