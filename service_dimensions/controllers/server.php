<?php

use DataStorageComponent\WebApi\Server\Server;
use DataStorageComponent\Database\DatabaseProvider;
use ServiceDimensions;

require_once "../vendor/autoload.php";
require_once "../../local.config.php";

DatabaseProvider::initialize($config['host'], $config['dbname'], $config['user'], $config['password']);

$server = new Server();
$server->configure(array(
	'action_key' => 'action',
	'params_key' => 'params'
));


$server->register('isDimension', function($params) {
	$model = DimensionManager::makeByDimensionCode($params['dimension_code']);
	return $model->exists($params['dimension_identifier']) ? 1 : 0;
});

$server->register('getDimension', function($params) {
	$model = DimensionManager::makeByDimensionCode($params['dimension_code']);
	return $model->readOne($params['dimension_identifier']);
});

$server->register('createDimension', function($params) {
	$model = DimensionManager::makeByDimensionCode($params['dimension_code']);
	$model->createOne($params['dimension_identifier']);
	return 1;
});
$response = $server->execute();
echo json_encode($response);
// echo json_encode($response ? '1' : '0');
