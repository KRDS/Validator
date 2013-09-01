<?php

/**
 * Describes an error in a field.
 */
class FieldError
{
	protected $_message;
	protected $_display_error;

	public function __construct($message, $display_error)
	{
		$this->_message			=	$message;
		$this->_display_error	=	$display_error;
	}

	public function getMessage()
	{
		return $this->_message;
	}

	public function isDisplayable()
	{
		return $this->_display_error;
	}
}
