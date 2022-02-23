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

            
            if (! is_null($elem['denumire_artera']) && ! is_null($elem['tip_artera'])) {
                //$strada = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $strada);
                //iconv_get_encoding($strada);
                switch ($elem['tip_artera']) {
                    //case 'Stradă':
                    //    $strada = 'Strada';
                    //    break;
                    case 'Șosea':
                        $strada = 'Sosea';
                        break;
                    case 'Bulevard':
                        $strada = 'Bulevardul';
                        break;
                    case 'Alee':
                        $strada = 'Aleea';
                        break;
                    default:
                        $strada = $elem['tip_artera'];
                }

                $strada .= " " . $elem['denumire_artera'];
            }

            array_push($arr['result'], array(
                "judet_cod" => $elem['judet_cod'],
                "judet_nume" => $elem['judet_nume'],
                "localitate" => $elem['localitate'],
                //"enc"=>iconv_get_encoding($strada),
                // "tip_artera" => $elem['tip_artera'],
                // "artera" => $elem['denumire_artera'],
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