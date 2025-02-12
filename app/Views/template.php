<?php

	/*
	 * Proyect:		Medicapp
	 * Year:		2024
	 * Authors:		
	 * File Desc.:	General template of System; automatic load head, body and footer
	 *
	 * */

	/*
	 * $DEFAULT_TITLE:	Represents the default value of title if title is empty or null
	 * $DEFAULT_META:	Represents the default value of the meta tag if this is not specified
	 *
	 * */

	$DEFAULT_TITLE = 'MEDICAPP';
	$DEFAULT_META  = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $isHead        = true; 

	/*
	 * $head_data['title']:	Is the title of the Browser caption
	 * $head_data['css']:	Is the array with all the CSS name to be loaded
	 * $head_data['meta']:	Is the html META tag
	 *
	 * */

	$head_data['title']    = (isset($title))? $title : $DEFAULT_TITLE;
	$head_data['css']      = (isset($css))? $css : null;
	$head_data['meta']     = (isset($meta))? $meta : $DEFAULT_META;
    $head_data['showHead'] = (isset($showHead)? $showHead : $isHead);
    
    $view_data['data']	= (isset($data_view))? $data_view : null;
    
	$footer_data['js'] = (isset($js))? $js : null;
    $footer_data['library'] = (isset($library))? $library : null;

	/*
	 * Begin of Template section
	 *
	 * */
    	    echo view('templates/header', $head_data);
			
			echo view($view, $view_data);
		
			echo view('templates/footer', $footer_data);
    	
	/*
	 * End of Template section
	*
	* */