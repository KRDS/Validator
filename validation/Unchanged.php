<?php

/**
 * Validates that the value has not been changed.
 *
 * @param mixed $reference Reference value to be checked against
 */

namespace Validation;

class Unchanged extends \Validator\Validation_Abstract
{
	protected $_reference;

	public function __construct($reference)
	{
		$this->_reference	=	$reference;
	}

	public function check($value)
	{
		if($value != $this->_reference)
			throw new \Exception(\Validator\i18n::get('error_validation_unchanged'));
	}
}