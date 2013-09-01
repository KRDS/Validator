<?php

class Validation_Custom_CreativeImage extends Validation_Abstract
{
	protected $_images	=	[ ];
	
	public function __construct($images)
	{
		$this->_images	=	$images;		
	}
	
	public function check($value)
	{
		$value	=	json_decode($value, true);

		if($value === false || ! is_array($value))
			throw new Exception(Lib::i18n()->creative_error_invalid_image);
		
		foreach($value as $id_image)
		{
			if( ! empty($id_image) && ! ctype_digit((string)$id_image) && ! in_array($id_image, $this->_images))
				throw new Exception(Lib::i18n()->creative_error_invalid_data);
		}
	}
}