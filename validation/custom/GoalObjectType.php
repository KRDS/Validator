<?php

/**
 * Validate Object type for goal
 *
 */
class Validation_Custom_GoalObjectType extends Validation_Abstract
{
	protected $_object_types;
	protected $_is_fb_ad;
	protected $_value;
			
	public function __construct($object_types, $is_fb_ad, $object_type)
	{
		$this->_object_types	=	$object_types;		
		$this->_is_fb_ad		=	$is_fb_ad;		
		$this->_value			=	$object_type;		
	}
	
	public function check($value)
	{	
		if( ! in_array($value, $this->_object_types))
			throw new Exception(Lib::i18n()->error_validation_object_type);		
		
		//Special Case validation of not allowing object type to change if FB Ad is created
		//------------------------------------------------>
		if($this->_is_fb_ad && $this->_value != $value)
			throw new Exception_WrongMethod;
	}
}