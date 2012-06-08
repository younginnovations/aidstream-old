<?php

/**
 * Display content of $value in proper format.
 *
 * @param array,int $value
 */
class Zend_View_Helper_Dpr {
	
	public function dprint_r($value) {
	  print "<pre class='devel' style='background:white; font-family:monospace; font-size:12px; color:black'>".print_r($value, true)."</pre>";
	}
	
	public function dpr($value) {
	  $this->dprint_r($value);
	}
}