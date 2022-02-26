<?php

class JsonPrintHelper
{

    public function printCodPostalItem($stmt)
    {
        $arr = array();
        $arr['result'] = array();
        $arr['count'] = $stmt->rowCount();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
            $elem = $row;

            $strada = null;

            if (! is_null($elem['denumire_artera'])) {
                $strada = $elem['tip_artera_articulat'] . " " . $elem['denumire_artera'];
            }

            array_push($arr['result'], array(
                "judet_cod" => $elem['judet_cod'],
                "judet_nume" => $elem['judet_nume'],
                "localitate" => $elem['localitate'],
                "strada" => $strada ?: "",
                "numar" => $elem['numar'] ?: "",
                "cod_postal" => $elem['cod_postal']
            ));
        endwhile
        ;

        return $arr;
    }

    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }
}

?>