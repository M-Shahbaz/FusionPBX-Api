<?php

header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';

$dialplan_uuid = (isset($_GET['dialplan_uuid']) ? $_GET['dialplan_uuid'] : null);
$message = array(['message' => 'Missing info!']);


/*Show dialplan details for bridges a specific domain*/
if ($dialplan_uuid !== null) {
    
    $sql = "SELECT * FROM v_dialplan_details  ";
    $sql .= "WHERE dialplan_uuid = '$dialplan_uuid' AND dialplan_detail_type = 'bridge' ";

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
