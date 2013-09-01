<?php

/**
 * Validates an alphanumeric string
 */

namespace Validation;

class Alphanumeric extends \Validator\Validation_Abstract
{
	public function check($value)
	{
		if( ! is_int($value) && ! ctype_alnum($value))
			throw new \Exception(\Validator\i18n::get('error_validation_alphanumeric'));
	}
}