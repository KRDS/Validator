<?php

/**
 * Validate Goal Settings
 *
 */
class Validation_Custom_GoalSettings extends Validation_Abstract
{
	protected $_settings;	
			
	public function __construct($settings)
	{
		$this->_settings		=	$settings;				
	}
		
	public function check($value)
	{	
		$goal_settings	=	json_decode($value, true);
				
		foreach($this->_settings as $key => $values)
		{
			if( ! isset($goal_settings[$key]))
				continue;
			
			switch($key)
			{
				case Model_Ads_Goal::GOAL_SETTINGS_ID_TAB:					
					$id_tab	=	(string) $goal_settings[$key];

					if(empty($id_tab))
						throw new Exception(Lib::i18n()->goal_error_invalid_goal_configuration);
				break;

				case Model_Ads_Goal::GOAL_SETTINGS_ACTIONS:					
						$settings	=	$goal_settings[$key];
						$diff		=	array_diff($settings, $values);
						
						if( ! empty($diff))
							throw new Exception(Lib::i18n()->goal_error_invalid_goal_configuration);
				break;
				
				case Model_Ads_Goal::GOAL_SETTINGS_PLATFORM:
						$platform	=	$goal_settings[$key];
												
						if( ! in_array($platform, $values))
							throw new Exception(Lib::i18n()->goal_error_invalid_goal_configuration);
				break;

				case Model_Ads_Goal::GOAL_SETTINGS_DOMAIN:
					$domain	=	$goal_settings[$key];

					if( ! is_bool($domain))
						throw new Exception(Lib::i18n()->goal_error_invalid_goal_configuration);
				break;

				case Model_Ads_Goal::GOAL_SETTINGS_CONVERSION_NAME:
					$conversion_name	=	$goal_settings[$key];
					
					if(empty($conversion_name))
						throw new Exception(Lib::i18n()->goal_error_invalid_goal_configuration);
				break;

				case Model_Ads_Goal::GOAL_SETTINGS_CONVERSION_TYPE:

					$conversion_type	=	$goal_settings[$key];

					if(empty($goal_settings[Model_Ads_Goal::GOAL_SETTINGS_CONVERSION_NAME]) 
							|| ! in_array($conversion_type, $values))
						throw new Exception(Lib::i18n()->goal_error_invalid_goal_configuration);	
				break;

				default:				
					throw new Exception(Lib::i18n()->goal_error_invalid_goal_configuration);
			}
		}
	}
}