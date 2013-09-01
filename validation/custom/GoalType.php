<?php

/**
 * Validate Goal type
 *
 */
class Validation_Custom_GoalType extends Validation_Abstract
{
	protected $_goal_types;	
			
	public function __construct($goal_types)
	{
		$this->_goal_types		=	$goal_types;				
	}
	
	public function check($value)
	{	
		if( ! in_array($value, $this->_goal_types))
			throw new Exception_UnknownIdentifier;
	}
}