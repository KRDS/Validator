<?php

/**
 * Validates a date in YYYY-MM-DD format.
 */

class Validation_Date extends Validation_Abstract
{
	public function check($value)
	{
		$pattern	=	'#^(?P<year>(?:19|20)\d{2})-(?P<month>[0]\d|[1][0-2])-(?P<day>[0-2]\d|[3][0-1])';

		if( ! preg_match($pattern, $value, $parts) || ! checkdate($parts['month'], $parts['day'], $parts['year']))
			throw new Exception(Lib::i18n()->error_validation_date);
	}
}