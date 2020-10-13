<?php

header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';

$call_uuid = (isset($_GET['call_uuid']) ? $_GET['call_uuid'] : null);
$message = array(['message' => 'Missing info!']);


/*Show an single call under a specific domain*/
if ($call_uuid !== null) {
    $sql = "select * from v_xml_cdr ";
    $sql .= "where xml_cdr_uuid = '$call_uuid'";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $call_cdr = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    $message = $call_cdr;

    echo (json_encode($message));
} else {
    http_response_code(403);
    echo (json_encode($message));
}