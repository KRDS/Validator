<?php

/**
 * Validation of Start date 
 * 
 * Class take 3 parameters
 * @param int $start_date 
 * @param int $end_date
 * @param string $depend_on
 * 
 * validation is done only if $depend_on field value is set and check following
 * date should be less than current time
 * and date is within the $start_date and $end_date
 */
class Validation_Custom_AdStartDateValidation extends Validation_Abstract
{
	protected $_start_date;
	protected $_end_date;
	protected $_depend_on;
	
	public function __construct($start_date, $end_date, $depend_on)
	{
		$this->_start_date	=	$start_date;		
		$this->_end_date	=	$end_date;		
		$this->_depend_on	=	$depend_on;		
	}

	public function check($value, $values)
	{			
		if(array_key_exists($this->_depend_on, $values) && empty($values[$this->_depend_on]))
		{
			$is_empty	=	false;
			
			try
			{
				$date_obj	=	new Validation_DateHour();
				$date_obj->check($value);
			}
			catch(Exception $e)
			{
				$is_empty	=	true;
			}
			
			$date	=	strtotime($value);
			
			if($is_empty)
				throw new Exception(Lib::i18n()->error_validation_date);
			
			if($date < $_SERVER['REQUEST_TIME'])
				throw new Exception(Lib::i18n()->error_validation_lower_than_current_time);
			
			if($date < $this->_start_date || $date > $this->_end_date)
				throw new Exception(Lib::i18n()->error_validation_date_not_within_campaign_date);
		}		
	}
}