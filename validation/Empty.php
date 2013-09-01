<?php

/**
 * Validates a required but empty field.
 */

class Validation_Empty extends Validation_Abstract
{
	public function check($value)
	{
		if($value !== '')
			throw new Exception(Lib::i18n()->error_validation_empty);
	}
}