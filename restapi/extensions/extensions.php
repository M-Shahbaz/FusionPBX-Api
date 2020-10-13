<?php

header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';

$domain_uuid = (isset($_GET['domain_uuid']) ? $_GET['domain_uuid'] : null);
$extension = (isset($_GET['extension']) ? $_GET['extension'] : null);
$message = array(['message' => 'Missing info!']);


/*Show an single extension under a specific domain*/
if ($extension !== null and $domain_uuid !== null) {
    $sql = "select * from v_extensions ";
    $sql .= "where domain_uuid = '$domain_uuid' and extension = '$extension'";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $extension = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    $message = $extension;

    echo (json_encode($message));
} else {
    http_response_code(403);
    echo (json_encode($message));
}
