<?php

/**
 * Validate Goal Url
 *
 */
class Validation_Custom_GoalUrl extends Validation_Abstract
{
	//url field can be string or array of urls
	//------------------------------------------------>
	
	public function check($value)
	{	
		if( ! empty($value))
		{
			$urls	=	json_decode($value, true);
					
			if(is_array($urls))
			{
				foreach($urls as $url)
				{
					if( ! filter_var($url, FILTER_VALIDATE_URL) )
						throw new Exception_WrongIdentifierFormat;
				}
			}		
			else if( ! filter_var($value, FILTER_VALIDATE_URL))
				throw new Exception_WrongIdentifierFormat;
		}
	}
}