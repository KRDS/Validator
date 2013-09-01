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
	protected $_temp_or			=	[ ];
	protected $_temp_and		=	[ ];

	// Hold final 'or' rules
	protected $_or	=	[ ];

	// Hold the stack of 'and' and 'or' to break in the right order
	private $__and_or_stack	=	[ ];

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
			$this->_fields[$field]	=	new \Validator\Field($field, $this);

		// Initializes it with global 'and' rules
		if($this->_temp_and)
			$this->_fields[$field]->rules($this->_temp_and);

		// Add the field to 'or' array
		foreach($this->_temp_or as &$or)
			$or['fields'][]	=	$field;

		return $this;
	}

	/**
	 * Forward function calls to the current \Validator\Field object.
	 *
	 * @method $this rule(mixed $rule) Add a rule to the field
	 * @method $this rules(array $rules) Add multiple rules to the field
	 * @method $this getError() Return the error as a `\Validator\FieldError` object
	 */
	public function __call($name, $arguments)
	{
		try
		{
			if( ! $this->_current_field)
				throw new Exception('Please declare a field before calling '.$name.', or use `globalRule`', E_ERROR);

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
	 * A global rule is disabled with `endGlobalRule` function.
	 *
	 *   - An 'and' rule is applied to each field added after declaring it.
	 *
	 *   - An 'or' rule will pass if at least one of the fields added after declaring it passes it.<br>
	 *     It is typically used with `\Validation\Required`, when at least one of X fields must be filled
	 *	   (for example, at least the user landline or mobile phone number).
	 *
	 * @param mixed $rule Validation rule
	 * @param string $operator Can be Validator::OPERATOR_AND or Validator::OPERATOR_OR
	 * @param string $message Error message (in the case of 'or' rule)
	 * @return \Validator
	 */
	public function globalRule($rule, $operator = self::OPERATOR_AND, $message = null)
	{
		$this->__and_or_stack[]	=	$operator;

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

			default:

				throw new Exception('Unknown operator \''.$operator.'\' for global rule');
		}

		return $this;
	}

	/**
	 * Disable the latest global rule declared.
	 *
	 * @return \Validator
	 */
	public function endGlobalRule()
	{
		switch(array_pop($this->__and_or_stack))
		{
			case self::OPERATOR_AND:

				// Remove latest pushed 'and' rule
				array_pop($this->_temp_and);

			break;

			case self::OPERATOR_OR:

				// Merge temporary 'or' rules
				if($this->_temp_or)
					$this->_or[]	=	array_pop($this->_temp_or);

			break;

			default:

				// No more rule in the stack
				throw new Exception('Called `endGlobalRule` when no global rule remaining');
		}

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
			throw new Exception('Please declare a field before calling error, or use `globalError`');

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
			throw new Exception('You must declare validation rules before calling `run`');

		$this->_has_error	=	false;

		$this->_endAllGlobalRules();

		// First, run all 'or' validation rules
		foreach($this->_or as $or)
		{
			$has_check		=	false;
			$has_success	=	false;

			foreach($or['fields'] as $field)
			{
				$value		=	\Validator\Field::getValue($field, $values);

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

		// Then, run the other rules for each field
		foreach($this->_fields as $field)
			$field->run($values);

		return ! $this->_has_error;
	}

	/**
	 * An OK field is not empty and has passed the validation.
	 *
	 * @param string $field Field name
	 * @return bool
	 */
	public function ok($field)
	{
		if( ! $this->_has_run)
			throw new Exception('Validation must be run with function before calling `ok`');

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
			throw new Exception('Validation must be run with function before calling `getValues`');

		return $this->_fields;
	}

	/**
	 * Indicates if all the form values passed the validation or not.
	 *
	 * @return bool
	 */
	public function hasError()
	{
		return $this->_has_error;
	}

	/**
	 * Return the error messages after the validation has been run.
	 *
	 * @param bool $displayable Whether to display non-displayable messages (for example, `DependsOn` error)
	 * @return array List of field names with their error message. Empty array if no error
	 */
	public function getErrors($displayable = false)
	{
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
	 * Set whether the validation passed or not.
	 *
	 * @param bool $has_error
	 */
	public function setHasError($has_error)
	{
		$this->_has_error	=	$has_error;
	}

	/**
	 * Close and disable all the remaining global rules.
	 *
	 * @return \Validator
	 */
	protected function _endAllGlobalRules()
	{
		// Merge temporary 'or' rules
		if($this->_temp_or)
			$this->_or	=	array_merge($this->_or, $this->_temp_or);

		$this->_temp_and	=	[ ];
		$this->_temp_or		=	[ ];
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