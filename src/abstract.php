<?php

/**
 * Set the validator used in case of a validation rule needs it.
 * For example, DependsOn need to access the validator to check the values of other fields.
 *
 * @param Validator $validator
 */

namespace Validator;

abstract class Validation_Abstract
{
	/**
	 * Whether the validator should be run if the value is missing
	 * (i.e. not POSTed with the form)
	 *
	 * @var bool
	 */
	public $accept_missing_value	=	false;
	
	protected $_validator;
	protected $_display_error	=	true;

	public function setValidator(\Validator $validator)
	{
		$this->_validator	=	$validator;
	}

	public function displayError()
	{
		return $this->_display_error;
	}
}