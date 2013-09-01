<?php

/**
 * Validates a required field.
 */

namespace Validation;

class Required extends \Validator\Validation_Abstract
{
	public $accept_missing_value	=	true;

	public function check($value)
	{
		/**
		 * Missing values are equal `null`.
		 * Values which are POSTed as `null` or (string) 'null' are turned to (string) ''
		 */

		if($value === null)
			throw new \Exception(\Validator\i18n::get('error_required'));
	}
}