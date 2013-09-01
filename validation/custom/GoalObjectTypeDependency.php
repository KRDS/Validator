<?php

/**
 * Validate Goal Object type dependency on other fields
 *
 */
class Validation_Custom_GoalObjectTypeDependency extends Validation_Abstract
{
	protected $_id_fb_object;
	protected $_id_fb_parent;
	protected $_post;
	protected $_url;
			
	public function __construct($id_fb_object, $id_fb_parent, $post, $url)
	{
		$this->_id_fb_object	=	$id_fb_object;		
		$this->_id_fb_parent	=	$id_fb_parent;		
		$this->_post			=	$post;		
		$this->_url				=	$url;	
	}
	
	public function check($value)
	{	
		//Validation specific to object type
		//------------------------------------------------>
		if( in_array($value, [Model_Ads::OBJECT_PAGE, Model_Ads::OBJECT_APP, Model_Ads::OBJECT_EVENT]) 
				&& ! $this->_id_fb_object)
			throw new Exception(Lib::i18n()->goal_error_invalid_object);

		if(in_array($value, [Model_Ads::OBJECT_POST, Model_Ads::OBJECT_OFFER]) && ! $this->_id_fb_parent)
			throw new Exception(Lib::i18n()->goal_error_invalid_parent);

		if($value === Model_Ads::OBJECT_POST && ! $this->_post)
			throw new Exception(Lib::i18n()->goal_error_invalid_post);

		if(in_array($value, [Model_Ads::OBJECT_FEED, Model_Ads::OBJECT_WEBSITE]) && ! $this->_url)
			throw new Exception(Lib::i18n()->goal_error_invalid_url);
	}
}