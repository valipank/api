<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once dirname(__DIR__, 4) . '/api/config/DatabaseCorona.php';
include_once dirname(__DIR__, 4) . '/api/controllers/corona/ListaLocalitatiController.php';

$database = new DatabaseCorona();

$db = $database->getConnection();

$items = new ListaLocalitatiController($db);

$judet = $_GET['judet'];

$stmt = $items->read($judet);
$itemCount = $stmt->rowCount();

if ($itemCount > 0) :

    http_response_code(200);

    $arr = array();
    $arr['localitati'] = array();
    $arr['count'] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
        $elem = $row;
        array_push($arr['localitati'], $elem['localitate']);
    endwhile
    ;

    echo json_encode($arr);

else :
    http_response_code(404);

    if (! empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
        $uri = 'https://';
    } else {
        $uri = 'http://';
    }
    $uri .= $_SERVER['HTTP_HOST'];
    $uri .= "/api/v1/judete";

    echo json_encode(array(
        "judet_invalid" => "vezi aici o lista a judetelor valide: " . $uri,
        "type" => "danger",
        "title" => "failed",
        "message" => "No records found"
    ));
endif;

?>