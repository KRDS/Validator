<?php

/**
 * Validates a value greater than another value.
 * Can validate numbers or dates.
 *
 * @param int $min Number that should be the lowest
 * @param array $type `Validation_GreaterThan::TYPE_DATE` to compare dates
 */

class Validation_GreaterThan extends Validation_Abstract
{
	const TYPE_NUMBER	=	'number'
		, TYPE_DATE		=	'date';

	protected $_min;
	protected $_type;

	public function __construct($min, $type = self::TYPE_NUMBER)
	{
		$this->_min		=	$min;
		$this->_type	=	$type;
	}

	/**
	 * @param int|string $value Digit or date in YYYY-MM-DD format
	 * @throws Exception
	 */
	public function check($value)
	{
		if($this->_type === self::TYPE_NUMBER && $value < $this->_min)
			throw new Exception(Lib::i18n()->error_validation_greater_than($this->_min));
		else if($this->_type === self::TYPE_DATE && strtotime($value) < $this->_min)
			throw new Exception(Lib::i18n()->error_validation_greater_than_date(Library_Utils::formatDate($this->_min)));
	}
}