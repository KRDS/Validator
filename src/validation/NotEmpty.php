<?php

/**
 * Validates a required and non-empty field.
 */

namespace Validation;

class NotEmpty extends \Validator\Validation_Abstract
{
	public $accept_missing_value	=	true;

	public function check($value)
	{
		if($value === null || $value === '')
			throw new \Exception(\Validator\i18n::get('error_validation_not_empty'));
	}
}