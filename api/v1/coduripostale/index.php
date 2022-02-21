<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once dirname(__DIR__, 3) . '/api/config/DatabaseMisc.php';
include_once dirname(__DIR__, 3) . '/api/controllers/CoduriPostaleController.php';

$database = new DatabaseMisc();

$db = $database->getConnection();

$items = new CoduriPostaleController($db);

if (isset($_GET['cod'])) {

    $cod = $_GET['cod'];

    if (strlen($cod) < 4) {
        http_response_code(400);

        echo json_encode(array(
            "err" => "zip_too_short",
            "type" => "danger",
            "title" => "failed",
            "message" => "ar fi bine ca codul postal sa fie de minim 4 caractere !"
        ));
    } else {

        $cod = $_GET['cod'] . "%";
        $stmt = $items->getListByCodPostal($cod);
        $itemCount = $stmt->rowCount();

        if ($itemCount > 0) :

            http_response_code(200);

            $arr = array();
            $arr['result'] = array();
            $arr['count'] = $itemCount;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                $elem = $row;
                array_push($arr['result'], array(
                    "judet_cod" => $elem['judet_cod'],
                    "judet_nume" => $elem['judet_nume'],
                    "localitate" => $elem['localitate'],
                    "artera" => $elem['denumire_artera'],
                    "numar" => $elem['numar'],
                    "cod_postal" => $elem['cod_postal']
                ));
            endwhile
            ;

            echo json_encode($arr);

        else :
            http_response_code(404);

            echo json_encode(array(
                "err" => "no_zip_code",
                "type" => "danger",
                "title" => "failed",
                "message" => "Nu exista codul postal"
            ));
        endif;
    }
} else {
    // check by judet + localitate + strada
}

?>