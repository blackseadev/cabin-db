<?php


	// default global config variables
	$config = array
	(
		'name'=>'Cabin DB',
		'db_auto_connect' => false,
		'db_username'=>'forge',
		'db_password'=>'Dg46RG525pZohN1yuYEb',
		'db_host'=>'localhost',
		'db_name'=>'forge',

		// when set to auto, we dynamically work out what it is
		'base_url' => 'auto',
		
		// include these modules
		'startup'=>array
		(
			'handlebars',
			'db_auth',
			'db_mysql'
		)
	);
	




	// server/environment specific, uses php's gethostname() method 
	// as a unique identifier for the config options
	$environment = array
	(
		'Gerwins-Macbook-Air.local' => array
		(

		)
	);



?>
