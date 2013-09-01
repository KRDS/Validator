<?php

class Validation_Custom_CreativePreview extends Validation_Abstract
{	
	protected $_titles;
	protected $_images;
	protected $_bodies;
	
	public function __construct($titles, $images, $bodies)
	{
		$this->_titles	=	json_decode($titles, true);
		$this->_images	=	json_decode($images, true);
		$this->_bodies	=	json_decode($bodies, true);
	}
	
	public function check($value)
	{
		$value	=	json_decode($value, true);

		if($value === false || ! is_array($value))
			throw new Exception(Lib::i18n()->creative_error_invalid_preview);
		
		foreach($value as $preview)
		{
			if(empty($preview['title']) || ! in_array($preview['title'], $this->_titles))
				throw new Exception(Lib::i18n()->creative_error_invalid_preview);
			
			if(empty($preview['body']) || ! in_array($preview['body'], $this->_bodies))
				throw new Exception(Lib::i18n()->creative_error_invalid_preview);
			
			if(empty($preview['image']) || ! in_array($preview['image'], $this->_images))
				throw new Exception(Lib::i18n()->creative_error_invalid_preview);
						
			if( ! ctype_digit((string)$preview['image']))
				throw new Exception(Lib::i18n()->creative_error_invalid_preview);			
		}
	}
}