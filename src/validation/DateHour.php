<?php

/**
 * Validates a date + hour in YYYY-MM-DD HH:MM format.
 */

namespace Validation;

class DateHour extends \Validator\Validation_Abstract
{
	public function check($value)
	{
		$pattern	=	'#^(?P<year>(?:19|20)\d{2})-(?P<month>[0]\d|[1][0-2])-(?P<day>[0-2]\d|[3][0-1])';
		$pattern	.=	' (?P<hour>[01]?[0-9]|2[0-3]):(?P<min>[0-5][0-9])$#';

		if( ! preg_match($pattern, $value, $parts) || ! checkdate($parts['month'], $parts['day'], $parts['year']))
			throw new \Exception(\Validator\i18n::get('error_date'));
	}
}