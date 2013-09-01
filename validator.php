<?php

/**
 * Validates a list of values against a set of validation rules.
 * It has to be instantiated for each form to check.
 *
 * @see https://github.com/KRDS/Validator
 */

class Validator
{
	const OPERATOR_AND	=	'and'
		, OPERATOR_OR	=	'or';

	protected $_has_run	=	false;

	protected $_values;

	protected $_current_field;
	protected $_fields	=	[ ];

	protected $_has_error;
	protected $_global_errors	=	[ ];

	// Hold temporary 'and' and 'or' rules
	protected $_temp_and	=	[ ];
	protected $_temp_or		=	[ ];

	// Hold final 'or' rules
	protected $_or	=	[ ];

	/**
	 * Declare a field
	 *
	 * @param string $field Field name
	 * @return \Validator
	 */
	public function field($field)
	{
		$this->_current_field	=	$field;

		if( ! isset($this->_fields[$field]))
			$this->_fields[$field]	=	new Field($field, $this);

		// Initializes it with global 'and' rules
		if($this->_temp_and)
			$this->_fields[$field]->rules($this->_temp_and);

		// Add the field to 'or' array
		foreach($this->_temp_or as &$or)
			$or['fields'][]	=	$field;

		return $this;
	}

	/**
	 * @method $this rule(mixed $rule) Add a rule to the field
	 * @method $this ruleBefore(mixed $rule) Add a rule to the field at the top of the validation stack
	 * @method $this rules(array $rules) Add multiple rules to the field
	 * @method $this getError() Return the error as a `FieldError` object
	 */
	public function __call($name, $arguments)
	{
		try
		{
			if( ! $this->_current_field)
				throw new Exception('Please declare a field before calling '.$name.', or use `ruleUntilBreak`', E_ERROR);

			$field	=	$this->_fields[$this->_current_field];

			call_user_func_array([$field, $name], $arguments);
		}
		catch(Exception $e)
		{
			trigger_error($e->getMessage(), E_ERROR); // Cannot throw an Exception in magic methods
		}

		return $this;
	}

	/**
	 * Declare a global rule to be applied to multiple field.
	 * A global rule is disabled with `breakRule` and `breakRules` functions.
	 *
	 *   - An 'and' rule is applied to each field added after declaring it.
	 *
	 *   - An 'or' rule will pass if at least one of the fields added after declaring it passes it.<br>
	 *     It is typically used with `Validation_Required`, when at least one of X fields must be filled
	 *	   (for example, at least the user landline or mobile phone number).
	 *
	 * @param mixed $rule Validation rule
	 * @param string $operator Can be Validator::OPERATOR_AND or Validator::OPERATOR_OR
	 * @param string $message Error message (in the case of 'or' rule)
	 * @return \Validator
	 */
	public function ruleUntilBreak($rule, $operator = self::OPERATOR_AND, $message = null)
	{
		switch($operator)
		{
			case self::OPERATOR_AND:

				$this->_temp_and[]	=	$rule;

			break;

			case self::OPERATOR_OR:

				if( ! $message)
					trigger_error('\'or\' rules must have an error message', E_WARNING);

				$this->_temp_or[]	=	[
					'rule'	  => $rule,
					'message' => $message,
					'fields'  => [ ],
				];

			break;
		}

		return $this;
	}

	/**
	 * Break the latest global rule declared.
	 *
	 * @return \Validator
	 */
	public function breakRule()
	{
		// Merge temporary 'or' rules
		if($this->_temp_or)
			$this->_or[]	=	array_pop($this->_temp_or);

		// Remove latest pushed 'and' rule
		array_pop($this->_temp_and);

		return $this;
	}

	/**
	 * Break all global rules declared till now.
	 *
	 * @return \Validator
	 */
	public function breakRules()
	{
		// Merge temporary 'or' rules
		if($this->_temp_or)
			$this->_or	=	array_merge($this->_or, $this->_temp_or);

		$this->_temp_and	=	[ ];
		$this->_temp_or		=	[ ];

		return $this;
	}

	/**
	 * Push an error to a field.
	 *
	 * @param string $message Error message
	 * @return \Validator
	 */
	public function error($message)
	{
		if( ! $this->_current_field)
			throw new Exception('Please declare a field before calling error, or use `globalError`', E_ERROR);

		$this->_fields[$this->_current_field]->error($message, true, true); // Force the error to be shown

		return $this;
	}

	/**
	 * Clear the error of a field.
	 *
	 * @return \Validator
	 */
	public function clearError()
	{
		$this->_fields[$this->_current_field]->clearError();

		return $this;
	}

	/**
	 * Push an error related to the whole form.
	 *
	 * @param string $message Error message
	 * @return \Validator
	 */
	public function globalError($message)
	{
		$this->_global_errors[]	=	$message;

		return $this;
	}

	/**
	 * Run the validation rules against a list of values.
	 *
	 * @param array $values List of values to check
	 * @return bool Whether all the fields are correct or not
	 */
	public function run(array $values)
	{
		$this->_has_run	=	true;
		$this->_values	=	$values;

		if( ! $this->_fields)
			throw new Exception('You must declare fields rules in the constructor before calling `run` function');

		$this->_has_error	=	false;

		// Process 'or' errors
		foreach($this->_or as $or)
		{
			$has_check		=	false;
			$has_success	=	false;

			foreach($or['fields'] as $field)
			{
				$value		=	Field::getValue($field, $values);

				if($value !== null || ! empty($or['rule']->accept_missing_value))
				{
					$has_check	=	true;

					if($this->_fields[$field]->run($values, $or['rule']))
						$has_success	=	true;
				}
			}

			if($has_check && ! $has_success)
				$this->globalError($or['message']);
		}

		// Process each field
		foreach($this->_fields as $field)
			$field->run($values);

		return ! $this->_has_error;
	}

	/**
	 * An OK field is not empty and has passed the validation
	 *
	 * @param string $field Field name
	 * @return bool
	 */
	public function ok($field)
	{
		if( ! $this->_has_run)
			throw new Exception('Validation must be run with `run` function before calling ok');

		return isset($this->_values[$field])
				&& $this->_values[$field] !== null
				&& ( ! $this->_hasField($field) || ($this->_hasField($field) && ! $this->getField($field)->hasError()));
	}

	/**
	 * Returns the list of values given in `run` function.
	 *
	 * @return array List of values
	 */
	public function getValues()
	{
		if( ! $this->_has_run)
			throw new Exception('Validation must be run with `run` function before calling getValues');

		return $this->_fields;
	}

	/**
	 * Indicates if all the form values passed the validation or not.
	 *
	 * @return bool
	 */
	public function hasError()
	{
		if( ! $this->_has_run)
			throw new Exception('Validation must be run with `run` function before calling hasError');

		return $this->_has_error;
	}

	/**
	 * Return the error messages after the validation has been run.
	 *
	 * @param bool $displayable Whether to display non-displayable messages (for example, `DependsOn error)
	 * @return array List of field names with their error message. Empty array if no error
	 */
	public function getErrors($displayable = false)
	{
		if( ! $this->_has_run)
			throw new Exception('Validation must be run with `run` function before calling getErrors');

		$ret	=	[ ];

		foreach($this->_fields as $name => $field)
		{
			if($field->hasError($displayable))
				$ret[$name]	=	$field->getError()->getMessage();
		}

		if($this->_global_errors)
			$ret['_global']	=	$this->_global_errors;

		return $ret;
	}

	/**
	 * Set whether the validation passed or nt.
	 *
	 * @param bool $has_error
	 */
	public function setHasError($has_error)
	{
		$this->_has_error	=	$has_error;
	}

	/**
	 * Checks if a field has been declared when instantiating the function.
	 *
	 * @param string $name Field name
	 * @return bool
	 */
	protected function _hasField($name)
	{
		return isset($this->_fields[$name]);
	}
}