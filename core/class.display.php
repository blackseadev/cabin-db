<?php


class display
{

	function render()
	{
		global $vars, $config;

		// check for ajax or full request
		if(isset($vars[0]))
		{
			if($vars[0] == 'a')
			{
				return $this->get_ajax_response();
			}
		}
		
    return $this->get_full_response();
	}


	//
	//	Returns a controllers partial response
	//
	function get_ajax_response()
	{
		global $vars, $config;
		
		set_error_handler("display::get_ajax_error");

		// pull the end point and controller name from the requested url
		$end_point = clean(array_pop($vars));
		$controller_name = clean(array_pop($vars));

		// remove first item and form the controller location path
		array_shift($vars);

		$path = implode('/', $vars);

		
		if($path != '')
		{
			$path .= '/';
		}

		// include and execute controller
		$controller_path = CONTROLLERS . '/' . $path . $controller_name . '.php';

		if(file_exists($controller_path))
		{
			include_once($controller_path);
			$controller = new $controller_name();
			if(method_exists($controller, $end_point))
			{
				return $controller->$end_point($this->clean_params($_REQUEST));
			}
			else
			{
				return format_response('Route end point does not exist', 'error');
			}
		}
	}

	//
	//	Recursively cleans all params passed to it
	//
	function clean_params($params)
	{
		$cleaned = array();
		foreach($params as $key => $value)
		{
			if(ini_get('magic_quotes_gpc'))
			{
				if(is_array($value))
				{
					$cleaned[$key] = $this->clean_params($value);
				}
				else
				{
					$value = stripslashes($value);
					$cleaned[$key] = mysql_real_escape_string(strip_tags($value));
				}
			}
			else
			{
				if(is_array($value))
				{
					$cleaned[$key] = $this->clean_params($value);
				}
				else
				{
					$cleaned[$key] = ($value);
				}
			}
		}
		return $cleaned;
	}

	//
	//	Simple enough error catch
	//
	public static function get_ajax_error($error_number, $error_string, $error_file, $error_line, $error_context)
	{
		$error = array
		(
			'number' 	=> $error_number,
			'error' 	=> $error_string,
			'file' 		=> $error_file,
			'line'		=> $error_line,
			'context'	=> $error_context
		);

		echo format_response($error, 'error');
		die();
	}


	//
	//	Returns a full page request wrapped in a layout
	//
	function get_full_response()
	{
		global $twig, $layout, $output, $assets, $vars, $config;

		// resolve the route and find what controller to use
		$routes = new routes();
		$controller_name = $routes->resolve();

		// include and execute controller
		$controller_path = CONTROLLERS.'/'.$controller_name.'.php';
		if(file_exists($controller_path))
		{
			include_once($controller_path);
			$controller = new $controller_name();
			$output->set('content.main', $controller->index($vars));
		}
		else
		{
			$output->set('content.main', 'Rock "'.$controller_name.'" could not be found.');
		}

		// get the layout and return
		$layout_contents = file_get_contents(LAYOUTS.'/'.$layout.'.html');
		$layout = $twig->loadTemplate($layout_contents);

		return $layout->render($output->get());
	}

}


?>