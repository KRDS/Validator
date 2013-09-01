<?php

/**
 * Validation for campaign budget 
 * 
 * Class take 2 parameters
 * @param int $daily_budget_field 
 * @param int $end_date_field
 * 
 * If the lifetime budget field is empty, we need to check daily budget should be set + 
 * end date should be empty
 * 
 */
class Validation_Custom_CampaignBudget extends Validation_Abstract
{
	protected $_daily_budget_field;
	protected $_end_date_field;	
	
	public $accept_missing_value	=	true;
	
	public function __construct($daily_budget_field, $end_date_field)
	{
		$this->_daily_budget_field	=	$daily_budget_field;		
		$this->_end_date_field		=	$end_date_field;					
	}

	public function check($value, $values)
	{		
		if(empty($value))
		{			
			if( ! array_key_exists($this->_daily_budget_field, $values) || empty($values[$this->_daily_budget_field]))
				throw new Exception(Lib::i18n()->error_validation_budget);
		}		
	}
}