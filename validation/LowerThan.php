<?php

/**
 * Validates a value lower than another value.
 * Can validate numbers or dates.
 *
 * @param int $max Number that should be the greatest
 * @param array $type `\Validation\LowerThan::TYPE_DATE` to compare dates
 */

namespace Validation;

class LowerThan extends \Validator\Validation_Abstract
{
	const TYPE_NUMBER	=	'number'
		, TYPE_DATE		=	'date';

	protected $_max;
	protected $_type;

	public function __construct($max, $type = self::TYPE_NUMBER)
	{
		$this->_max		=	$max;
		$this->_type	=	$type;
	}

	/**
	 * @param int|string $value Digit or date in YYYY-MM-DD format
	 * @throws \Exception
	 */
	public function check($value)
	{
		if($this->_type === self::TYPE_NUMBER && $value > $this->_max)
			throw new \Exception(\Validator\i18n::get('error_validation_lower_than', $this->_max));
		else if($this->_type === self::TYPE_DATE && strtotime($value) > $this->_max)
			throw new \Exception(\Validator\i18n::get('error_validation_lower_than_date', Library_Utils::formatDate($this->_max)));
	}
}