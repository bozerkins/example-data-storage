<?php

namespace ServiceDimensions;

use DataStorageComponent\Model;

class CityModel extends Model
{
	protected $primaryKey = array(
		'city_title', 'country_title'
	);
}
