<?php

/**
 * View Lib Configuration file
 *
 * This file is part of Views template engine
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

switch($_SERVER['SERVER_NAME']) {
	default:
		$config['template_dir'] = "/templates/"; //MUST HAVE TRAILING SLASH slash '/'
		$config['title_prefix'] = "CI Startup Demo: ";
	break;
}


/*
  Template Layout:
  Document
   _
    |- Style Sheets
    |- Scripts
    |- Header
    |- Content
    |- Footer

*/
$config['header']   = "header.php";
$config['footer']   = "footer.php";
$config['content']  = "content.php";
$config['document'] = "document.php";

$config['optionals']   = array("navigation");


?>