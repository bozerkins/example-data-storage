<?php

namespace ServiceDimensions;

class ModelManager
{
	public static function makeByDimensionCode($dimensionCode)
	{
		if ($dimensionCode === 'city') {
			return new CityModel;
		}
		if ($dimensionCode === 'date') {
			return new DateModel;
		}
		if ($dimensionCode === 'days_counter') {
			return new DaysCounterModel;
		}
		if ($dimensionCode === 'role') {
			return new RoleModel;
		}
		if ($dimensionCode === 'process') {
			return new ProcessModel;
		}
		throw new \ErrorException('Invalid dimension code receved received');
	}
}
