<?php

/**
 * Validates a length lower than a given length.
 * 
 * @param int $length Length for which the field must be lower than
 */

namespace Validation;

class LengthLowerThan  extends \Validator\Validation_Abstract
{
	protected $_length;
	
	public function __construct($length)
	{
		$this->_length	=	$length;		
	}

	public function check($value)
	{
		if(mb_strlen($value, 'UTF-8') >= $this->_length)
		{
			throw new \Exception(\Validator\i18n::get('error_length_lower_than', [
				'max' => $this->_length,
			]));
		}
	}
}