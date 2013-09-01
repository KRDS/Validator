<?php

require __DIR__.'/validator.php';
require __DIR__.'/inc/validator_field.php';
require __DIR__.'/inc/validator_fielderror.php';

spl_autoload_register(function($function)
{
	if(substr($function, 0, 11) === 'Validation_')
	{
		$name	=	substr($function, 11);

		require __DIR__.'/validation/'.$name.'.php';
	}
});