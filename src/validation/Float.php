<?php

/**
 * Validates a float value.
 *
 * Can be either of type `string` or `int`.
 * The decimal separator can be either a point (.) or a comma (,).
 */

namespace Validation;

class Float extends \Validator\Validation_Abstract
{
	const PATTERN_FLOAT	=	'/^[0-9]+([,\.][0-9]+)?$/';

	public function check($value)
	{
		$is_float	=	(is_string($value) && ctype_digit($value))
						|| is_int($value)
						|| is_float($value)
						|| preg_match(self::PATTERN_FLOAT, $value);

		if( ! $is_float)
			throw new \Exception(\Validator\i18n::get('error_validation_float'));
	}
}