<?php

/**
 * Validates a length greater than a given length.
 * 
 * @param int $length Length for which the field must be greater than
 */

namespace Validation;

class LengthGreaterThan  extends \Validator\Validation_Abstract
{
	protected $_length;
	
	public function __construct($length)
	{
		$this->_length	=	$length;				
	}

	public function check($value)
	{		
		if(mb_strlen($value, 'UTF-8') <= $this->_length)
		{
			throw new \Exception(\Validator\i18n::get('error_length_greater_than', [
				'min' => $this->_length,
			]));
		}
	}
}