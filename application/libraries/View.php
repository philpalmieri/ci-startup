<?php
class View
{
  private $config     = array();
  private $_error     = "";

  private $scripts    = array();
  private $styles     = array();
  
  private $basePath   = "";
  private $templates  = array();

  public 	$_content   = array("document"  =>  array(
                                  "styles"  => array(),
                                  "scripts" => array(),
                                  "metas"   => array()
                                ),
                              "footer"    =>  array(),
                              "header"    =>  array(),
                              "content"   =>  array());
  
  public function __construct()
  {
    require(APPPATH . 'config/views' . EXT);
    $this->config = $config;
    
    $this->_CI = get_instance();
    $this->_CI->load->helper('directory');

    $this->basePath = $_SERVER['DOCUMENT_ROOT'].$this->config['template_dir'];
	
    $this->wwwPath = $this->config['template_dir'];  
    $this->templates = directory_map($this->basePath);
    if($this->_check_defaults()){
      $this->_set_defaults();
      $this->_build($this->_CI->uri->segment_array());
    } else {
      die($this->_error);
    }
  }
  
  public function render($partial = FALSE)
  {
    
    //Parse parital views only, good for AJAX calls and print views
    if($partial === TRUE)
    {
      $output = $this->parse($this->_useContent, $this->_content['content'], TRUE);
    }
    else
    {
      $this->set("document", array("header"   => $this->parse($this->_useHeader,  $this->_content['header'],  TRUE)));
      $this->set("document", array("footer"   => $this->parse($this->_useFooter,  $this->_content['footer'],  TRUE)));
      $this->set("document", array("content"  => $this->parse($this->_useContent, $this->_content['content'], TRUE)));
			
	  $this->_content['document']['all_styles'] = $this->load_styles();
      $output = $this->parse($this->_useDocument, $this->_content['document'], TRUE);
    }
    //Render
    $this->_output($output);
  }
  
  public function set($page, $keys, $value=NULL)
  {
    if(array_key_exists($page, $this->_content)){
      if(is_array($keys) || is_object($keys)){
        foreach($keys as $K=>$V)
        {
          $this->_content[$page][$K] = $V;
        }
      } 
      elseif($value != NULL) {
        $this->_content[$page][$keys] = $value;
      }
    }
  }
  
  
  public function override($new, $area = "content")
  {
    switch($area) {
      case 'content':
        $this->_useContent = $this->basePath.$new.".php";
      break;
      case 'document':
        $this->_useDocument = $this->basePath.$new.".php";
      break;
			case 'header':
        $this->_useHeader = $this->basePath.$new.".php";
      break;
    }
    
  }
  
  public function append($page, $item, $value)
  {
    if(array_key_exists($page, $this->_content)){
      if(isset($this->_content[$page][$item])) {
        $this->_content[$page][$item] .= $value;
      } else {
        $this->set($page, $item, $value);
      }
    }
  }

  public function partial_parse($html, $values, $return = TRUE)
  {
    foreach($values as $F=>$R) {
      $html = str_replace("{".$F."}", $R, $html);
    }
    ob_start();
    print($html);
    if ($return === TRUE) {
      $buffer = ob_get_contents();
      @ob_end_clean();
      return $buffer;
    }
  }
  
  public function parse($path, $values, $return = TRUE)
  {
    ob_start();
    extract($values, EXTR_SKIP);
    include($path);
    if ($return === TRUE) {
      $buffer = ob_get_contents();
      @ob_end_clean();
      return $buffer;
    }
  }
  
  /**/
  private function _output($out, $header="HTML")
  {
   print($out);
  }

  private function _check_defaults()
  {
    if( is_array($this->templates) ) {
      //Check for Layout
      if(!in_array($this->config['document'], $this->templates)){
        $this->_error .= "Error: Default Document Not Found!<br />";
      }
      //Check for Content
      if(!in_array($this->config['content'], $this->templates)){
        $this->_error .= "Error: Default Content Not Found!<br />";
      }
      //Check for Head
      if(!in_array($this->config['header'], $this->templates)){
        $this->_error .= "Error: Default Head Not Found!<br />";
      }
      //Check for Foot
      if(!in_array($this->config['footer'], $this->templates)){
        $this->_error .= "Error: Default foot Not Found!<br />";
      }
    } else {
      $this->_error .= "Error: Template Directory Not Found!<br />";
    }
    if($this->_error != "" && !$this->_CI->input->is_cli_request()){
      return false;
    } else {
      return true;
    }
  }
  
  private function _set_defaults()
  {
    $this->_useHeader    = $this->basePath.$this->config['header'];
    $this->_useFooter    = $this->basePath.$this->config['footer'];
    $this->_useContent   = $this->basePath.$this->config['content'];
    $this->_useDocument  = $this->basePath.$this->config['document'];
    foreach($this->config['optionals'] as $optional) {
      $this->_content[$optional] = array();
      $this->_use{$optional} = $this->basePath.$optional.".php";

    }
   //Defaul Layout
    if(isset($this->templates['css']))
    {
     foreach($this->templates['css'] as $css){
       $this->_content['document']['styles'][] = $this->wwwPath."css/".$css;
     }
    }

    if(isset($this->templates['js']))
    {
       foreach($this->templates['js'] as $js){
         $this->_content['document']['scripts'][] = $this->wwwPath."js/".$js;
       }
    }
   $this->set("document", array("pageTitle"=>$this->config['title_prefix']));
  }
  
  private function _build($loc)
  {
    $currentWWW = $this->wwwPath;
    $currentBase = $this->basePath;
    $currentStep = $this->templates;
    
    foreach($loc as $l){
     if(isset($currentStep[$l]) && is_array($currentStep[$l])){
       $currentStep = $currentStep[$l];
       $currentWWW .="{$l}/";
       $currentBase .="{$l}/";
       
	
       if(isset($currentStep['css']) && is_array($currentStep['css'])){
         foreach($currentStep['css'] as $css){
           $this->_content['document']['styles'][] = $currentWWW."css/".$css;
         }
       }
       if(isset($currentStep['js']) && is_array($currentStep['js'])){
          foreach($currentStep['js'] as $js){
            $this->_content['document']['scripts'][] = $currentWWW."js/".$js;
          }
       }
       if(in_array($this->config['document'], $currentStep)){
         $this->_useDocument = $currentBase.$this->config['document'];
       }
       //Check for Content
       if(in_array($this->config['content'], $currentStep)){
         $this->_useContent = $currentBase.$this->config['content'];
       }
       //Check for Content named same as folder
       if(in_array($l.".php", $currentStep)){
         $this->_useContent = $currentBase.$l.".php";
       }
       //Check for Head
       if(in_array($this->config['header'], $currentStep)){
         $this->_useHead = $currentBase.$this->config['header'];
         
       }
       //Check for Foot
       if(in_array($this->config['footer'], $currentStep)){
         $this->_useFooter = $currentBase.$this->config['footer'];
       }
       
       //Check for optionals
       foreach($this->config['optionals'] as $optional) {
         if(in_array($optional.".php", $currentStep)){
             $this->_use{$optional} = $currentBase.$optionl.".php";
          }
       }
       
      }
    }
    

  }

	public function load_styles()
	{
		
		$all_styles = "";

		foreach($this->_content['document']['styles'] as $style)
		{
			if(strpos($style, "Array") <= 0)
			{
				if(strpos($style, '_ie') <= 0)
				{
					$all_styles .= '
					<link rel="stylesheet" href="'.$style.'" type="text/css" media="screen, print" charset="utf-8" />';
				}
				else
				{
					$all_styles .='
					<!--[if lte IE 6]>
						<link rel="stylesheet" href="'.$style.
													'" type="text/css" media="screen" title="no title" charset="utf-8" /><![endif]-->';
				}
			}
		}
		return $all_styles;
	}
  
  /**/
  public function is_ajax()
  {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER ['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest'){
      return true;
    } else {
      return false;
    }
  }
}