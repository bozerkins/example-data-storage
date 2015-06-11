<?php

use DataStorageComponent\WebApi\Server\Server;

require_once "../vendor/autoload.php";

$server = new Server();
$server->configure(array(
	'action_key' => 'action',
	'params_key' => 'params'
));


$server->register('pushRawData', function($params) {
	if (!$params['id']) {
		return false;
	}
	$reqData = $params['data'];

	// parse request
	$request = array();
	$request['req_id'] = $reqData['req_id'];
	$request['emp_id'] = $reqData['emp_id'];
	$request['created_dt'] = $reqData['created_dt'];
	$request['cust_id'] = $reqData['cust_id'];
	$request['cust_name'] = $reqData['cust_record']['name'] . '/' . $reqData['cust_record']['surname'];
	$request['cust_is_premium'] = $reqData['cust_record']['settings']['is_premium'];
	$request['cust_created_dt'] = $reqData['cust_record']['created_dt'];
	$request['cust_last_contacted_dt'] = $reqData['cust_record']['last_contacted_dt'] ?: null;
	if ($reqData['cust_record']['last_contacted_dt']) {
		$date1 = new \DateTime($reqData['cust_record']['created_dt']);
		$date2 = new \DateTime($reqData['cust_record']['last_contacted_dt']);
	}
	$request['cust_days_created_after_contact'] =  $date2 ? $date2->diff($date1)->format("%a") : null;

	// parse employes
	$employes = array();
	$employes[$reqData['emp_id']] = $reqData['emp_record'];
	foreach($reqData['sale_list'] as $sale) {
		$employes[$sale['emp_id']] = $sale['emp_record'];
	}

	// parse sales
	$sales = array();
	foreach($reqData['sale_list'] as $saleData) {
		$sale = array();
		$sale['sale_id'] = $saleData['sale_id'];
		$sale['emp_id'] = $saleData['emp_id'];
		$sale['req_id'] = $saleData['req_id'];
		$sale['prod_code'] = $saleData['prod_code'];
		$sale['prod_amount'] = 1;
		$sale['prod_id'] = 1;
		$sale['created_dt'] = $saleData['created_dt'];
		$sale['prod_price_usd'] = $saleData['prod_record']['price_usd'];
		$sale['prod_price_eur'] = $saleData['prod_record']['price_eur'];
		$sale['discount_usd'] = $saleData['discount_usd'];
		$sale['discount_eur'] = $saleData['discount_eur'];
	}

	return array($sale, $params);
});
$response = $server->execute();
echo json_encode($response);
// echo json_encode($response ? '1' : '0');
