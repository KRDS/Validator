<?php

/**
 * Validates an email address.
 */

class Validation_Email extends Validation_Abstract
{
	public function check($value)
	{
		if( ! is_string($value))
			throw new Exception(\Validator\i18n::get('error_validation_email_string'));

		if( ! filter_var($value, FILTER_VALIDATE_EMAIL))
			throw new Exception(\Validator\i18n::get('error_validation_email'));
	}
}