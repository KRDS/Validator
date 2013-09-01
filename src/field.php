<?php

/**
 * Describes a single field with its validation rules.
 *
 * @param string $name Name of the field
 * @param Validator $validator Validator for which the field is instantiated
 */

namespace Validator;

class Field
{
	const RULE_BUILTIN	=	'builtin'
		, RULE_CLOSURE	=	'closure'
		, RULE_FUNCTION	=	'function'
		, RULE_PHP		=	'php';

	protected $_name;
	protected $_rules	=	[ ];
	protected $_validator;

	protected $_error;
	protected $_error_forced	=	false;

	public function __construct($name, \Validator $validator)
	{
		$this->_name		=	$name;
		$this->_validator	=	$validator;
	}

	/**
	 * Add a validation rule.
	 *
	 * @param mixed $rule Validation rule
	 */
	public function rule($rule)
	{
		$this->_rules[]	=	$rule;
	}

	/**
	 * Add multiple validation rules.
	 *
	 * @param array $rules Array of validation rules
	 */
	public function rules($rules)
	{
		$this->_rules	=	array_merge($this->_rules, $rules);
	}

	/**
	 * Run the validation rules for the field.
	 *
	 * @param array $values List of values (only the field will be searched for and used)
	 * @return bool Whether the field is correct or not
	 */
	public function run(array $values, $custom_rule = null)
	{
		if($this->_error && $this->_error_forced)
		{
			if($custom_rule)
				$error	=	true;
		}
		else
		{
			$error			=	false;
			$this->_error	=	null;

			$value		=	self::getValue($this->_name, $values);

			$rules	=	$custom_rule ? [ $custom_rule ] : $this->_rules;

			foreach($rules as $rule)
			{
				$rule_type		=	self::_getRuleType($rule);
				$display_error	=	$rule_type === self::RULE_BUILTIN ? $rule->displayError() : true;

				if($value !== null || ($rule_type === self::RULE_BUILTIN && ! empty($rule->accept_missing_value)))
				{
					try
					{
						switch($rule_type)
						{
							case self::RULE_BUILTIN:
								$rule->check($value, $values, $this->_validator);
							break;

							case self::RULE_CLOSURE:
								$rule($value, $values, $this->_validator);
							break;

							case self::RULE_FUNCTION:
								call_user_func($rule, $value, $values, $this->_validator);
							break;

							case self::RULE_PHP:
								if( ! call_user_func($rule, $value))
									throw new \Exception(\Validator\i18n::get('error_generic'));
							break;
						}

					}
					catch(\Exception $e)
					{
						if($display_error)
						{
							if($custom_rule)
								$error	=	true;
							else
								$this->error($e->getMessage(), $display_error);
						}

						break;
					}
				}
			}
		}

		return $custom_rule ? ! $error : $this->_error === null;
	}

	/**
	 * Indicates if the fields passed the validation or not.
	 *
	 * @param bool $displayable Whether to display non-displayable messages (for example, `DependsOn` error)
	 * @return bool
	 */
	public function hasError($displayable = false)
	{
		return $this->_error !== null
			&& ( ! $displayable || $this->_error->isDisplayable());
	}

	/**
	 * Return the first error for the field.
	 *
	 * @return \Validator\FieldError
	 */
	public function getError()
	{
		return $this->_error;
	}

	/**
	 * Push an error to the field
	 *
	 * @param string $message Error message
	 * @param bool $display_error Whether the error is displayable or not
	 */
	public function error($message, $display_error = true, $forced = false)
	{
		$this->_error			=	new \Validator\FieldError($message, $display_error);
		$this->_error_forced	=	$forced;

		$this->_validator->setHasError(true);
	}

	/**
	 * Clear the error (if any).
	 */
	public function clearError()
	{
		$this->_error			=	null;
		$this->_error_forced	=	false;
	}

	/**
	 * Check if a key exists in a form and returns it.
	 *
	 * @param string $key Key of the field to be searched
	 * @param array $values Array of values to search for the key within
	 * @return mixed Value
	 */
	public static function getValue($key, $values)
	{
		if(array_key_exists($key, $values) && $values[$key] !== null)
		{
			if($values[$key] === 'null')
				$ret	=	'';
			else
				$ret	=	is_string($values[$key]) ? trim($values[$key]) : $values[$key];
		}
		else
		{
			$ret	=	null;
		}

		return $ret;
	}

	/**
	 * Detect the type of a rule.
	 *
	 * @param mixed $rule
	 */
	protected static function _getRuleType($rule)
	{
		if(is_string($rule))
		{
			if(function_exists($rule))
				$ret	=	self::RULE_PHP;
			else
				$ret	=	self::RULE_FUNCTION;
		}
		else
		{
			if($rule instanceof \Validator\Validation_Abstract)
				$ret	=	self::RULE_BUILTIN;
			else if(is_callable($rule))
				$ret	=	self::RULE_CLOSURE;
			else
				throw new \Exception('Wrong rule type. Unable to run the validation.');
		}

		return $ret;
	}
}