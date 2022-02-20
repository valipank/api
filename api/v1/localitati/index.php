<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once $_SERVER['DOCUMENT_ROOT'] . '/api/config/databaseMisc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/api/controllers/localitaticontroller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/api/controllers/judetecontroller.php';

$database = new Database();

$db = $database->getConnection();

$items = new LocalitatiController($db);

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

    $judetinfo = new JudeteController($db);
    $stmtjudet = $judetinfo->read($judet);
    $judetrow = $stmtjudet->fetch(PDO::FETCH_ASSOC);
    $arr['judet'] = $judetrow;

    echo json_encode($arr);

else :
    http_response_code(404);

    echo json_encode(array(
        "judet_invalid" => "vezi aici o lista a judetelor valide: " . stripos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === 0 ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . "/api/v1/judete",
        "type" => "danger",
        "title" => "failed",
        "message" => "No records found"
    ));
endif;

?>