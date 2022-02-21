<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once dirname(__DIR__, 4) . '/api/config/DatabaseCorona.php';
include_once dirname(__DIR__, 4) . '/api/controllers/CountryInfoController.php';

$database = new DatabaseCorona();

$db = $database->getConnection();

$items = new CountryInfoController($db);

$country = $_GET['country'];

$stmt = $items->read($country);
$itemCount = $stmt->rowCount();

if ($itemCount > 0) :
    http_response_code(200);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);

else :
    http_response_code(404);

    echo json_encode(array(
        "type" => "danger",
        "title" => "failed",
        "message" => "No records found"
    ));
endif;

?>