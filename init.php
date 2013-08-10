<?php


//
//	Include core
//
include_once('core/Twig/Autoloader.php');
include_once('core/class.crack.php');
include_once('core/class.output.php');
include_once('core/class.assets.php');
include_once('core/class.display.php');
include_once('core/class.routes.php');

include_once('core/func.core.php');
include_once('core/func.display.php');




//
//	Define globals
//
$crack = new crack();
$app = $crack->get_details();
$vars = $app['vars'];
$base_route = '';

// Output
$output = new output();
$assets = new assets();
$layout = 'default';

// Start twig template engine
Twig_Autoloader::register();
$loader = new Twig_Loader_String();
$twig = new Twig_Environment($loader, array('autoescape'=>false));
$twig->addGlobal('assets', $assets);



//
//	Startup
//
if(isset($app['startup']))
{
	foreach($app['startup'] as $liveClass)
	{
		include_once(STARTUP.'/'.$liveClass.'.php');
		$$liveClass = new $liveClass();
	}
}



?>