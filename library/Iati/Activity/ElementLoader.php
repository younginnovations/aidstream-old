<?php
/**
 * Loader wraps Zend_Loader_PluginLoader into a Singleton.
 *
 * The Iati_Activity_Element(s) follow composite pattern. All of the elements can instantiate its sub element. 
 * Wrapping PluginLoader in a singleton makes sure all the Iati_Activity_Elements use the same Loader to load
 * their children Elements.
 */
class Iati_Activity_ElementLoader
{
    protected static $_instance;
    
    protected function __construct()
    {}
    
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Zend_Loader_PluginLoader(
                array('Iati_Activity_Element_' => 'Iati/Activity/Element/')
            );
        }
        return self::$_instance;
    }
}
