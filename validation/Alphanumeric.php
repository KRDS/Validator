<?php

/**
 * Validates an alphanumeric string
 */

class Validation_Alphanumeric extends Validation_Abstract
{
	public function check($value)
	{
		if( ! is_int($value) && ! ctype_alnum($value))
			throw new Exception(Lib::i18n()->error_validation_alphanumeric);
	}
}