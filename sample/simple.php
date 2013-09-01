<?php

/**
 * Most simple way to validate a form.
 */

require_once __DIR__.'/../bootstrap.php';

// Set up the environment
//--------------------------------------------------------->

$data	=	[
	'firstname'	=> '',
	'lastname'	=> '',
	'email'		=> 'john.doe',
];

// Run the validation
//--------------------------------------------------------->

$validator	=	new Validator;

$validator->field('firstname')
			->rule(new \Validation\Required)

		->field('lastname')
			->rule(new \Validation\Required)

		->field('email')
			->rule(new \Validation\Required)
			->rule(new \Validation\Email);

if( ! $validator->run($data))
	print_r($validator->getErrors());