<?php 

class Zend_View_Helper_BlockManager extends Zend_View_Helper_Abstract
{
	protected $enabledBlocks = array();
	public static $instance;
	
	public function blockManager()
	{
		if ( is_null(self::$instance) )
			self::$instance = $this;
  		return self::$instance;
  	}
  	
  	public function enable($blockName)
  	{
  		$obj = $this->blockManager();
  		if ( !in_array($blockName, $obj->enabledBlocks) ) 
  			$obj->enabledBlocks[] = $blockName;
  	}
  	
  	public function disable($blockName)
  	{
  	    unset($obj->enableBlocks[$blockName]);
  	}
  	
  	/**
  	 * The full path of the partial
  	 * 
  	 * @param string $blockName The relative path of the partial
  	 */
  	public function get($blockName)
  	{
  		$obj = $this->blockManager();
  		if ( in_array($blockName, $obj->enabledBlocks) )
  			return $obj->view->partial($blockName);
  		else 
  			return false;
  	}
}