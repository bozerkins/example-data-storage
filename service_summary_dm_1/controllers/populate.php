<?php

use DataStorageComponent\Database\DatabaseProvider;
use ServiceSummaryDataMart\RequestSummaryModel;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../../local.config.php";

DatabaseProvider::initialize($config['host'], $config['dbname'], $config['user'], $config['password']);

$model = new RequestSummaryModel();

echo '<pre>';
$dates = DatabaseProvider::make()->query("SELECT date FROM service_dimensions_date WHERE date > '2008-01-01' and date < '2016-01-01'")->fetchAll();
for($i=0; $i < 100; $i++) {
	foreach($dates as $date) {
		$record = array();
		$record['dim_creation_date'] = $date['date'];
		$record['dim_days_since_request'] = rand(400, 500);
		$record['msr_sales'] = rand(0,1) ? 0 : rand(0, 10);
		$record['msr_requests'] = rand($record['msr_sales'], 100);
		$record['msr_sold_requests'] = $record['msr_sales'] ? rand(1, $record['msr_sales']) : 0;
		$record['msr_premium_sales'] = rand(0,2) ? 0 : ($record['msr_sales'] - rand(0, $record['msr_sales']));
		$record['msr_profit'] = round($record['msr_sales'] ? rand(10000, 100000) / 33 : 0, 2);

		$model->createQuery($record, true);
	}
}
