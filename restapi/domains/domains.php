<?php

/**
 * Created by PhpStorm.
 * User: aspurlock
 * Date: 2/26/2019
 * Time: 1:29 PM
 */

header('Content-Type: application/json');

include '/var/www/fusionpbx/root.php';
require_once '/var/www/fusionpbx/resources/check_auth.php';

$domain_uuid = (isset($_GET['domain_uuid']) ? $_GET['domain_uuid'] : null);
$message = array(['message' => 'Missing info!']);

/*Show an single extension under a specific domain*/
if ($domain_uuid !== null) {
    $sql = "select * from v_domains ";
    $sql .= "where domain_uuid = '$domain_uuid'";
    $prep_statement = $db->prepare(check_sql($sql));
    $prep_statement->execute();
    $domain = $prep_statement->fetchAll(PDO::FETCH_NAMED);
    unset($sql);
    $message = $domain;
    echo (json_encode($message));
} else {
    http_response_code(403);
    echo (json_encode($message));
}