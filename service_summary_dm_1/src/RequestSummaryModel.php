<?php

namespace ServiceSummaryDataMart;

use DataStorageComponent\Model;

class RequestSummaryModel extends Model
{
	protected $table = 'service_summary_db_1_requests';
	protected $primaryKey = array(
		'dim_creation_date', 'dim_days_since_request'
	);
}
