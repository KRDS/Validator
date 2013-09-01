<?php

/**
 * Validate placement is available in the list
 *
 */
class Validation_Custom_Placement extends Validation_Abstract
{
	protected $_placements;
			
	public function __construct($placements)
	{
		$this->_placements			=	$placements;		
	}
	
	public function check($value)
	{	
		if( ! in_array($value, $this->_placements))
			throw new Exception(Lib::i18n()->error_validation_placement);
		
	}
}