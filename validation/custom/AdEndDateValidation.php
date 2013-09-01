<?php


/**
 * Validation of end date 
 * 
 * Class take 3 parameters
 * @param int $start_date  
 * @param string $depend_on
 * 
 * validation is done only if $depend_on field value is set and check following
 * and date is greater than $start_date
 */
class Validation_Custom_AdEndDateValidation extends Validation_Abstract
{
	protected $_start_date;
	protected $_end_date;
	protected $_depend_on;
	
	public function __construct($start_date, $depend_on)
	{
		$this->_start_date	=	$start_date;				
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
			
			if( ! $is_empty)
			{			
				if($this->_start_date > strtotime($value))
					throw new Exception(Lib::i18n()->error_validation_lower_than_date(Library_Utils::formatDate($this->_start_date)));
			}
		}		
	}
}