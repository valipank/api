<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once dirname(__DIR__, 4) . '/api/config/DatabaseCorona.php';
include_once dirname(__DIR__, 4) . '/api/controllers/CountryDailyController.php';
include_once dirname(__DIR__, 4) . '/api/controllers/CountryInfoController.php';

$database = new DatabaseCorona();

$db = $database->getConnection();
$avg = 14;

$items = new CountryDailyController($db);

$country = $_GET['country'];

$stmt = $items->read($country, $avg);
$itemCount = $stmt->rowCount();

if ($itemCount > 0) :
    http_response_code(200);

    $arr = array();
    $arr['response'] = array();
    $arr['count'] = $itemCount;
    $arr['avg'] = $avg;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
        $elem = $row;
        array_push($arr['response'], $elem);
    endwhile
    ;

    // call to CountryInfoController
    $info = new CountryInfoController($db);
    $stmtinfo = $info->read($country);
    $resultinfo = $stmtinfo->fetch(PDO::FETCH_ASSOC);

    $arr['country_info'] = $resultinfo;

    echo json_encode($arr);

else :
    http_response_code(404);

    echo json_encode(array(
        "type" => "danger",
        "title" => "failed",
        "message" => "No records found"
    ));
endif;

?>