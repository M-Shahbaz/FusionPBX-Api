<?php

header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';

$call_uuid = (isset($_GET['call_uuid']) ? $_GET['call_uuid'] : null);
$domain_uuid = (isset($_GET['domain_uuid']) ? $_GET['domain_uuid'] : null);
$gateway_uuid = (isset($_GET['gateway_uuid']) ? $_GET['gateway_uuid'] : null);
$start = (isset($_GET['start']) ? $_GET['start'] : null);
$end = (isset($_GET['end']) ? $_GET['end'] : null);
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
} else if ($domain_uuid !== null && $gateway_uuid !== null && $start !== null && $end !== null) {
    
    $sql = "SELECT SUM(billmsec) as msec, SUM(billsec) as sec, SUM(billsec)/60 as min FROM v_xml_cdr ";
    $sql .= "WHERE domain_uuid = '$domain_uuid' AND direction = 'outbound' ";

    $sql .= "AND json->'app_log'->>'application' LIKE '%sofia/gateway/{$gateway_uuid}%' ";
    $sql .= "AND start_stamp BETWEEN '{$start}' AND '{$end}' ";

    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $bill_duration = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    $message = $bill_duration;

    echo (json_encode($message));

} else {
    http_response_code(403);
    echo (json_encode($message));
}
