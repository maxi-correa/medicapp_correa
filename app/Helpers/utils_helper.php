<?php

if (!function_exists('formato_oracion'))
{
    function formato_oracion($texto)
    {
        return strtoupper(substr($texto, 0,1)) . strtolower(substr($texto, 1));
    }
}

if (!function_exists('generate_links_css'))
{
	function generate_links_css($css)
	{
		$head = '';

		if (isset($css) && !empty($css)){
			foreach ($css as $link){
				$head .= '<link rel="stylesheet" href="' . base_url() . '/assets/css/' . $link . '.css" />'.PHP_EOL;
			}
		}

		return $head;
	}
}

if (!function_exists('generate_links_js'))
{
	function generate_links_js($js, $isLibrary = false)
	{
		$footer = '';

		if (isset($js) && !empty($js)){
			foreach ($js as $script){
                if ($isLibrary) {
                    $footer .= '<script type="text/javascript" src="' . base_url() . '/assets/librarys/' . $script . '.js"></script>'.PHP_EOL;
                }else{
                    $footer .= '<script type="text/javascript" src="' . base_url() . '/assets/js/' . $script . '.js"></script>'.PHP_EOL;
                }
            }
		}
        
		return $footer;
	}
}