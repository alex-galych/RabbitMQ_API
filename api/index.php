<?php
/**
 * Created by PhpStorm.
 * User: olg
 * Date: 24.12.14
 * Time: 11:36
 */
require_once 'core/RabbitmqApi.php';

try {
    $rabbitMqApi = new RabbitmqApi($_REQUEST['method']);
    echo $rabbitMqApi->processAPI();
} catch (Exception $e) {
    echo json_encode(array('error' => $e->getMessage()));
}