<?php

$sampleTime = strtotime('2015-06-11 22:11:13');

$roles = array();
for($i=1; $i < 50; $i++) {
	$record = array();
	$record['role_id'] = $i;
	$record['role_title'] = 'sample role ' . chr(64+($i % 15));
	$record['restriction_code'] = round($i * 100 / 22);
	$roles[] = $record;
}
$departments = array();
for($i=1; $i < 50; $i++) {
	$record = array();
	$record['dep_id'] = $i;
	$record['address'] = 'sample address ' . chr(64+($i));
	$record['city'] = 'sample city ' . chr(64+($i % 15));
	$record['country'] = 'sample country ' . chr(64+($i % 5));
	$record['created_dt'] = date('Y-m-d H:i:s', $sampleTime - (round($i * 100 / 22) % 500 * 100000) - 1000000);
	$departments[] = $record;
}

$employes = array();
for($i=1; $i < 500; $i++) {
	$record = array();
	$record['emp_id'] = $i;
	$record['dep_id'] = ($i * 1522) % 49;
	$record['dep_record'] = $departments[$record['dep_id']];
	$record['role_id'] = ($i * 512211) % 49;
	$record['role_record'] = $roles[$record['role_id']];
	$record['created_dt'] = date('Y-m-d H:i:s', $sampleTime - (round($i * 100 / 11) % 200 * 100000) - 1000000);
	$employes[] = $record;
}

$processes = array();
$procCounter = 0;
for($i=0; $i < 2; $i++) {
	for($j=0; $j < 2; $j++) {
		for($z=0; $z < 2; $z++) {
			for($f=0; $f<2; $f++) {
				$processes[$procCounter] = array(
					'proc_id' => $procCounter,
					'is_contacted' => $f,
					'is_viewed' => $z,
					'is_approved' => $j,
					'is_purchased' => $i
				);
				$procCounter++;
			}
		}
	}
}

$products = array();
$faker = Faker\Factory::create();
for($i=1; $i < 1000; $i++) {
	$name = explode(' ', str_replace('. ', '.', $faker->name));
	$record = array();
	$rate = 1.13;
	$record['prod_code'] = ($i * 2314) % 6002 . '551' . ($i % 10);
	$record['price_usd'] = round($i * 1123621 % 10000 , 2) + 250;
	$record['price_eur'] = round($record['price_usd'] / $rate, 2);
	$record['created_dt'] = date('Y-m-d H:i:s', $sampleTime - (round($i * 1123655 / 11) % 200 * 100000) - 1000000);
	$products[$record['prod_code']] = $record;
}
$customers = array();
$faker = Faker\Factory::create('da_DK');
for($i=1; $i < 1000; $i++) {
	$name = explode(' ', str_replace('. ', '.', $faker->name));
	$record = array();
	$record['cust_id'] = str_replace('.', '', microtime(true));
	$record['name'] = trim($name[0], '.');
	$record['login'] = str_replace(array(' ', '..'), '.', strtolower($faker->name));
	$record['password'] = md5($record['login']);
	$record['surname'] = $name[1];
	$record['pers_code'] = $faker->cpr;
	$record['settings'] = array(
		'cust_id' => $record['cust_id'],
		'is_premium' => rand(0,1)
	);
	$record['created_dt'] = date('Y-m-d H:i:s', $sampleTime - (round($i * 51221 / 11) % 200 * 100000) - 1000000);
	$record['last_contacted_dt'] = rand(0,1) ? date('Y-m-d H:i:s', strtotime($record['created_dt']) + (round($i * 412421 / 11) % 200 * 10000) - 100000) : null;
	$customers[$record['cust_id']] = $record;
}
