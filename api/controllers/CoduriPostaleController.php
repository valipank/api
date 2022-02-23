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

    public function getList($judet, $localitate, $strada)
    {
        $query = "SELECT 
                      * 
                    FROM 
                      v_coduripostale 
                    WHERE 
                      judet_cod = :judet ";
        if (! empty($localitate)) {
            $query .= "AND localitate LIKE :localitate ";
        }
        if (! empty($strada)) {
            $query .= "AND denumire_artera LIKE :strada";
        }
        $query .= ";";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':judet', $judet);
        if (! empty($localitate)) {
            $stmt->bindParam(':localitate', $localitate);
        }
        if (! empty($strada)) {
            $stmt->bindParam(':strada', $strada);
        }

        $stmt->execute();

        return $stmt;
    }

}

?>