<?php
class Zend_View_Helper_Img extends Zend_View_Helper_Abstract
{
	public function img($path, $url, $options = array())
	{
		//Merge in defaults
		$options += array(
			'attributes' => array(),
			'html' => FALSE,
		);
		
		if (isset ($options['attributes']['title']) && strops($options['attributes']['title'],'<') !== FALSE) {
			$options['attributes']['title'] = strip_tags($options['attributes']['title']);
		}
		
		$fc = Zend_Controller_Front::getInstance();
		$baseUrl = $fc->getBaseUrl();
		
		return '<a href = "'.$baseUrl.'/'.$url.'"'.$this->view->common()->htmlAttributes($options['attributes']).'>'.'<img src= '.'"'.$this->view->baseUrl().$path.'"'. 'height = 200px width = 150px/>'.'</a>';
		
	}
}