<?php

/**
 * Validates an alphanumeric string
 */

class Validation_Alphanumeric extends Validation_Abstract
{
	public function check($value)
	{
		if( ! is_int($value) && ! ctype_alnum($value))
			throw new Exception(\Validator\i18n::get('error_validation_alphanumeric'));
	}
}