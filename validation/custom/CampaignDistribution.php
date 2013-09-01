<?php

/**
 * Validates a campaign distribution structure:
 *
 *		{
 *			"1": [3, 4, 5, 6, 7, 22],
 *			"2": [6, 7, 8, 9, 10]
 *		}
 *
 * Keys are the day in the week (from 1 = Monday to 7 = Sunday).
 * Values are the hours of distribution from 0 to 23.
 */
class Validation_Custom_CampaignDistribution extends Validation_Abstract
{
	public function check($value)
	{
		$value	=	json_decode($value, true);

		if($value === false || ! is_array($value))
			throw new Exception(Lib::i18n()->campaign_error_invalid_distribution);

		foreach($value as $id_week => $hours_slots)
		{
			if($id_week < 1 || $id_week > 7)
				throw new Exception(Lib::i18n()->campaign_error_invalid_distribution);

			foreach($hours_slots as $hour)
			{
				$is_int	=	is_int($hour)
							|| (is_string($hour) && ctype_digit($hour));

				if( ! $is_int)
					throw new Exception(Lib::i18n()->campaign_error_invalid_distribution);

				if($hour < 0 || $hour > 23)
					throw new Exception(Lib::i18n()->campaign_error_invalid_distribution);
			}
		}
	}
}