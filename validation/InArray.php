<?php

/**
 * Validates a value part of a pre-defined list.
 *
 * @param array $list List of values the validated value should belong to
 * @param bool $ignore_case If true, the case will be ignored for searching through the array
 */

namespace Validation;

class InArray extends \Validator\Validation_Abstract
{
	protected $_list;
	protected $_ignore_case;

	public function __construct(array $list, $ignore_case = false)
	{
		$this->_list	=	$ignore_case ? array_map('strtolower', $list) : $list;;
	}

	public function check($value)
	{
		if($this->_ignore_case)
			$value	=	strtolower($value);

		if( ! in_array($value, $this->_list))
			throw new \Exception(\Validator\i18n::get('error_validation_in_array'));
	}
}