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
        $query = "SELECT 
                      c.*, 
                      a.tip_artera_articulat 
                    FROM 
                      v_coduripostale c 
                      LEFT JOIN artere a ON c.tip_artera = a.tip_artera 
                      AND a.tip_artera_articulat IS NOT null 
                    WHERE 
                      cod_postal LIKE :cod 
                    ORDER by 
                      cod_postal;";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':cod', $cod);

        $stmt->execute();

        return $stmt;
    }

    public function getList($judet, $localitate, $strada)
    {
        $query = "SELECT 
                      c.*, 
                      a.tip_artera_articulat 
                    FROM 
                      v_coduripostale c 
                      LEFT JOIN artere a ON c.tip_artera = a.tip_artera 
                      AND a.tip_artera_articulat IS NOT null 
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