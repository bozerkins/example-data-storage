<?php

use DataStorageComponent\Database\DatabaseProvider;
use ServiceSummaryDataMart\RequestSummaryModel;

require_once "../vendor/autoload.php";
require_once "../../local.config.php";

DatabaseProvider::initialize($config['host'], $config['dbname'], $config['user'], $config['password']);

$dateFrom = array_key_exists('date_from', $_REQUEST) ? $_REQUEST['date_from'] : null;
$dateTo = array_key_exists('date_to', $_REQUEST) ? $_REQUEST['date_to'] : null;
$sort = array_key_exists('sort', $_REQUEST) ? $_REQUEST['sort'] : null;

if (!$dateFrom || !$dateTo || !preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/", $dateFrom) || !preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/", $dateFrom)) {
	echo 'Invalid dates passed';
	return;
}
$sortSql = $sort ? " ORDER BY `{$sort}` DESC" : '';

$time = microtime(true);

$model = new RequestSummaryModel();
$data = $model->getConnection()->query("
	SELECT SQL_NO_CACHE
		date_table.month as `Request Creation Date`,
		delay_table.months as `Sale Creation Delay Months`,
		SUM(msr_requests) as `Requests`,
		SUM(msr_sold_requests) as `Sold Requests`,
		ROUND(SUM(msr_sold_requests) / SUM(msr_requests) *100, 2) as `Sold Requests (%)`,
		ROUND(SUM(msr_profit) / SUM(msr_requests), 2) as `Profit / Requests ($)`,
		ROUND(SUM(msr_profit) / SUM(msr_sold_requests), 2) as `Profit / Sold Requests ($)`,
		ROUND(SUM(msr_profit) / SUM(msr_requests), 2) as `Premium Profit / Requests ($)`,
		ROUND(SUM(msr_profit) / SUM(msr_sold_requests), 2) as `Premium Profit / Sold Requests ($)`
	FROM {$model->getTable()} as main_table
	JOIN service_symmary_db_1_date as date_table
		ON main_table.dim_creation_date = date_table.date
	JOIN service_symmary_db_1_days_counter as delay_table
		ON main_table.dim_days_since_request = delay_table.days
	WHERE dim_creation_date BETWEEN '{$dateFrom}' AND '{$dateTo}'
	GROUP BY date_table.month, delay_table.months
	{$sortSql}
")->fetchAll();

$heads = $data ? array_keys($data[0]) : array();

?>
<center>
<h1>Overall processing performance</h1>
Dates: <i><?=$dateFrom; ?> - <?=$dateTo; ?></i>
</center><br>
<style>
	/*
	Generic Styling, for Desktops/Laptops
	*/
	table.cont-table {
		border-collapse: collapse;
		font: 12px/1.4 Georgia, Serif;
		table-layout: fixed;
		margin: 0 auto;
	}
	/* Zebra striping */
	table.cont-table tr:nth-of-type(odd) {
		background: #eee;
	}
	table.cont-table tr {
		font-size: 11px;
	}
	table.cont-table th {
		background: #333;
		color: white;
		font-weight: bold;
	}
	table.cont-table td, table.cont-table th {
		padding: 6px;
		border: 1px solid #ccc;
		text-align: left;
		vertical-align: top;
	}
</style>
<table class="cont-table">
	<thead>
		<tr>
			<?php foreach($heads?:array() as $header) : ?>
				<td><a href="<?=$_SERVER['REQUEST_URI'];?>&sort=<?=$header;?>"><?=$header; ?></a></td>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data?:array() as $row) : ?>
		<tr>
			<?php foreach($row as $item) : ?>
			<td><?=preg_match("/^[0-9]+$/", $item) ? number_format($item) : $item; ?></td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<br>
<center><?=round(microtime(true) - $time, 3) . ' sec'; ?></center>
