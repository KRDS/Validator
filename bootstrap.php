<?php

require __DIR__.'/src/validator.php';
require __DIR__.'/src/abstract.php';
require __DIR__.'/src/field.php';
require __DIR__.'/src/fielderror.php';
require __DIR__.'/src/i18n.php';

spl_autoload_register(function($function)
{
	if(substr($function, 0, 11) === 'Validation\\')
	{
		$name	=	substr($function, 11);

		require __DIR__.'/src/validation/'.$name.'.php';
	}
});