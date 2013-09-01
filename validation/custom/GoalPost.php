<?php

/**
 * Validate Goal Post
 *
 */
class Validation_Custom_GoalPost extends Validation_Abstract
{
	protected $_auto_update;
	protected $_id_fb_parent;
	protected $_post_types;
			
	public function __construct($auto_update, $id_fb_parent, $post_types)
	{
		$this->_auto_update		=	$auto_update;		
		$this->_id_fb_parent	=	$id_fb_parent;		
		$this->_post_types		=	$post_types;		
	}
	
	//Posts field can contain either auto or array of post objects
	//------------------------------------------------>
	public function check($value)
	{	
		if($value !== $this->_auto_update)
		{
			$posts	=	json_decode($value, true);

			if( ! is_array($posts))
				throw new Exception(Lib::i18n()->goal_error_invalid_post1);
			
			foreach($posts as $post)
			{
				if(empty($post['id']))
					throw new Exception(Lib::i18n()->goal_error_invalid_post2);

				$id		=	explode('_', $post['id']); // [0] => Id_page, [1] => post_id

				if(empty($id[1]) || ! is_numeric($id[0]) || ! is_numeric($id[1]))
					throw new Exception(Lib::i18n()->goal_error_invalid_post3);

				if($id[0] != $this->_id_fb_parent)
					throw new Exception(Lib::i18n()->goal_error_invalid_post4);

				if(empty($post['type']) || ! in_array($post['type'], $this->_post_types))
					throw new Exception(Lib::i18n()->goal_error_invalid_post5);


				//Post will have either message / link as mandatory
				//------------------------------------------------>
				if(empty($post['message']) && empty($post['link']))
					throw new Exception(Lib::i18n()->goal_error_invalid_post);
			}	
		}
	}
}