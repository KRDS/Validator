<?php

/**
 * Validates that the value has not been changed.
 *
 * @param mixed $reference Reference value to be checked against
 */

class Validation_Unchanged extends Validation_Abstract
{
	protected $_reference;

	public function __construct($reference)
	{
		$this->_reference	=	$reference;
	}

	public function check($value)
	{
		if($value != $this->_reference)
			throw new Exception(Lib::i18n()->error_validation_unchanged);
	}
}