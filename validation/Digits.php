<?php

/**
 * Validates a field made of digits only.
 * Can be either of type `string` or `int`.
 */

class Validation_Digits extends Validation_Abstract
{
	public function check($value)
	{
		$is_digits	=	(is_string($value) && ctype_digit($value))
						|| is_int($value);

		if( ! $is_digits)
			throw new Exception(Lib::i18n()->error_validation_digits);
	}
}