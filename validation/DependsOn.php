<?php

/**
 * This validation rule has to be placed at the top of the rules declaration for a field.
 * It will block the other validation rules in the stack if another field is missing of invalid.
 *
 * This validation rule won’t generate an error message by default in case of failure.
 * It can be shown anyway if `$displayable` param of `Validator::run` is set to `true`.
 *
 * @param string $field Name of the field it depends on
 */

class Validation_DependsOn extends Validation_Abstract
{
	protected $_field;

	public function __construct($field)
	{
		$this->_field	=	$field;
	}

	public function check($value, $values, $validator)
	{
		$value; // Unused

		$is_empty			=	false;
		$validator_required	=	new Validation_Required();

		try
		{
			if( ! array_key_exists($this->_field, $values))
				throw new Exception('Unknown key');

			$validator_required->check($values[$this->_field]);
		}
		catch(Exception $e)
		{
			$is_empty	=	true;
		}

		if($is_empty)
			throw new Exception(Lib::i18n()->error_validation_depends_on_empty($this->_field));
		else if($validator->getField($this->_field)->hasError())
			throw new Exception(Lib::i18n()->error_validation_depends_on_check($this->_field));
	}
}