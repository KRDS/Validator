<?php


class Validation_Custom_CreativeTextField extends Validation_Abstract
{
	protected $_length;
	protected $_field;
	
	public function __construct($length, $field)
	{
		$this->_length	=	$length;
		$this->_field	=	$field;
	}
	
	public function check($value)
	{
		$value	=	json_decode($value, true);

		if($value === false || ! is_array($value))
			throw new Exception(Lib::i18n()->creative_error_invalid_text($this->_field));
		
		$validator_length	=	new Validation_LengthGreaterThan($this->_length, $this->_field);

		foreach($value as $title => $id_title)
		{			
			if( ! empty($id_title) && ! ctype_digit((string)$id_title))
				throw new Exception(Lib::i18n()->creative_error_invalid_data);
			
			$validator_length->check($title);
		}
	}
}