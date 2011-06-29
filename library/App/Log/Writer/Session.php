<?php
/**
 * SpeedRFP
 * 
 * Custom writer that stores entries in the session.
 * 
 * @uses       Zend_Log_Writer_Abstract
 * @package    Custom
 * @version    $Id$
 */
class App_Log_Writer_Session extends Zend_Log_Writer_Abstract
{

    public function __construct()
    {
        $session = new Zend_Session_Namespace('Log');
        if ( !isset($session->events) ) $session->events = array();
    }


    public static function factory($config)
    {
        if ( $config instanceof Zend_Config ) {
            $config = $config->toArray();
        }

        if ( !is_array($config) ) {
            throw new Exception('factory expects an array or Zend_Config instance');
        }

        $default = array();

        $config = array_merge($default, $config);

        return new self();
    }    
    
    
    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    public function _write($event)
    {
        $session = new Zend_Session_Namespace('Log');
        $session->events[] = $event;
    }


    /**
     * Clears the session log.
     * 
     * @return void
     */
    public function clear()
    {
        $session = new Zend_Session_Namespace('Log');
        unset($session->events);
    } 
    
}