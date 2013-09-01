<?php

/**
 * Validates a required field.
 */

class Validation_Required extends Validation_Abstract
{
	public $accept_missing_value	=	true;

	public function check($value)
	{
		/**
		 * Missing values are equal `null`.
		 * Values which are POSTed as `null` or (string) 'null' are turned to (string) ''
		 */

		if($value === null)
			throw new Exception(Lib::i18n()->error_validation_required);
	}
}