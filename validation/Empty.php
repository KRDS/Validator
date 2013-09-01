<?php

/**
 * Validates a required but empty field.
 */

class Validation_Empty extends Validation_Abstract
{
	public function check($value)
	{
		if($value !== '')
			throw new Exception(\Validator\i18n::get('error_validation_empty'));
	}
}