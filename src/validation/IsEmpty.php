<?php

/**
 * Validates a required but empty field.
 */

namespace Validation;

class IsEmpty extends \Validator\Validation_Abstract
{
	public function check($value)
	{
		if($value !== '')
			throw new \Exception(\Validator\i18n::get('error_empty'));
	}
}