<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json; charset=UTF-8');

include_once dirname(__DIR__, 3) . '/api/config/DatabaseMisc.php';
include_once dirname(__DIR__, 3) . '/api/controllers/CoduriPostaleController.php';
include_once dirname(__DIR__, 3) . '/api/helpers/JsonPrintHelper.php';

$database = new DatabaseMisc();

$db = $database->getConnection();

$items = new CoduriPostaleController($db);

$helper = new JsonPrintHelper();

$data = json_decode(file_get_contents("php://input"));

// $cod = $data->cod;
// $judet = $data->judet;
// $localitate = $data->localitate;
// $strada = $dat->strada;

// echo $cod;

if (isset($data->cod)) {

    $cod = $data->cod;

    if (strlen($cod) < 4) {
        http_response_code(400);

        echo json_encode(array(
            "err" => "zip_too_short",
            "type" => "danger",
            "title" => "failed",
            "message" => "ar fi bine ca codul postal sa fie de minim 4 caractere !"
        ));
    } else {

        $cod = $cod . "%";
        $stmt = $items->getListByCodPostal($cod);
        $itemCount = $stmt->rowCount();

        if ($itemCount > 0) :

            http_response_code(200);

            $arr = $helper->printCodPostalItem($stmt);

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
} else if (isset($data->judet)) {
    $judet = $data->judet;
    $localitate = null;
    $strada = null;

    if (isset($data->localitate)) {
        $localitate = $data->localitate . '%';
    }

    if (isset($data->strada)) {
        $strada = '%' . $data->strada . '%';
    }

    $stmt = $items->getList($judet, $localitate, $strada);
    $itemCount = $stmt->rowCount();

    if ($itemCount > 0) :

        http_response_code(200);

        $arr = $helper->printCodPostalItem($stmt);

        echo json_encode($arr);

    else :
        http_response_code(404);

        echo json_encode(array(
            "err" => "no_zip_codes",
            "type" => "danger",
            "title" => "failed",
            "message" => "Nu cod pentru combinatia selectata"
        ));
    endif;

    // check by judet + localitate + strada
}

?>