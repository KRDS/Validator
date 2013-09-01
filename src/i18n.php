<?php

namespace Validator;

class i18n
{
	protected static $_language	=	'en';
	protected static $_labels	=	[ ];

	public static function get($label, array $params = null)
	{
		$labels	=	self::_getLabels(self::$_language);
		$label	=	$labels[$label];

		if($params)
		{
			uksort($params, function($a, $b) { return strlen($b) - strlen($a); });

			$tokens		=	array_map(function($t) { return '%'.$t; }, array_keys($params));
			$fragments	=	array_values($params);

			$label	=	str_replace($tokens, $fragments, $label);
		}

		return $label;
	}

	public static function setLanguage($language)
	{
		self::$_language	=	$language;
	}

	protected static function _getLabels($language)
	{
		if( ! isset(self::$_labels[$language]))
			self::$_labels[$language]	=	require __DIR__.'/../lang/'.$language.'.php';

		return self::$_labels[$language];
	}
}