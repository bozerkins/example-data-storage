<?php

use DataStorageComponent\WebApi\Server\Server;

require_once "../vendor/autoload.php";

$server = new Server();
$server->configure(array(
	'action_key' => 'action',
	'params_key' => 'params'
));


$server->register('isDimension', function($params) {
	return $params;
});

$server->register('getDimension', function($params) {
	return $params;
});

$server->register('createDimension', function($params) {
	return $params;
});
$response = $server->execute();
echo json_encode($response);
// echo json_encode($response ? '1' : '0');
