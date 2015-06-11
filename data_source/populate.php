<?php

use DataStorageComponent\WebApi\Client\Client;

require_once "vendor/autoload.php";
require_once "static_data.php";

$requests = array();
$amount = 1;
for($i = 1; $i <= $amount; $i++) {
	$record = array();
	$record['req_id'] = str_replace('.', '', microtime(true));
	$record['emp_id'] = array_rand($employes);
	$record['emp_record'] = $employes[$record['emp_id']];
	$record['cust_id'] = array_rand($customers);
	$record['cust_record'] = $customers[$record['cust_id']];
	$record['created_dt'] = date('Y-m-d H:i:s', time() + rand(10000, 50000) + rand(100000, 500000));
	$record['sale_list'] = array();
	if (rand(0,30) % 5) {
		$sales = rand(1,5);
		for($i=1; $i < $sales; $i++) {
			$record2 = array();
			$record2['sale_id'] = str_replace('.', '', microtime(true));
			$record2['req_id'] = $record['req_id'];
			$record2['proc_id'] = array_rand($processes);
			$record2['proc_record'] = $processes[$record2['proc_id']];
			$record2['prod_code'] = array_rand($products);
			$record2['prod_record'] = $products[$record2['prod_code']];
			$record2['emp_id'] = rand(0,2) ? array_rand($employes) : $record['emp_id'];
			$record2['emp_record'] = $employes[$record2['emp_id']];
			$rate = 1.13;
			$record2['discount_usd'] = rand(0,1) ? 100 : 0;
			$record2['discount_eur'] = round($record2['discount_usd'] / $rate, 2);
			$record2['created_dt'] = date('Y-m-d H:i:s', strtotime($record['created_dt']) + rand(10000, 50000) + rand(100000, 500000));
			$record['sale_list'][] = $record2;
		}
	}

	$requests[] = $record;
}

$client = new Client();
$client->configure(array(
	'url' => 'http://' . $_SERVER['HTTP_HOST'] . str_replace('data_source/populate.php', 'service_etl/controllers/server.php', $_SERVER['SCRIPT_NAME']),
));

foreach($requests as $request) {
	$response = $client->execute('pushRawData', array(
		'id' => $request['req_id'],
		'data' => $request,
	));
	echo '<pre>';
	print_r(json_decode($response, true));exit;
}
