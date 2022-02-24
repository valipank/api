<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once dirname(__DIR__, 4) . '/api/config/DatabaseCorona.php';
include_once dirname(__DIR__, 4) . '/api/controllers/CountryEvolutionController.php';

$database = new DatabaseCorona();

$db = $database->getConnection();

$items = new CountryEvolutionController($db);

$country = $_GET['country'];

$stmt = $items->read($country);
$itemCount = $stmt->rowCount();

if ($itemCount > 0) :
    http_response_code(200);

    $arr = array();
    $arr['response'] = array();
    $arr['count'] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
        $elem = $row;
        array_push($arr['response'], $elem);
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
    $uri .= "/api/json/countries.json";

    echo json_encode(array(
        "invalid_country_iso" => "please provide a valid country iso code: " . $uri,
        "type" => "danger",
        "title" => "failed",
        "message" => "No records found"
    ));
endif;

?>