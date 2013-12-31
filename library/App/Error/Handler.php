<?php
/**
 * Custom error/exception handler to do something more with PHP errors.  Writes to our zend logger instance.
 * 
 * From http://zend-framework-community.634137.n4.nabble.com/capture-STDERR-with-Zend-Log-td640235.html
 * 
 * Parse, compile, and fatal errors will never trigger this error handler.
 *
 */

class App_Error_Handler
{
    
    // cannot create a App_Exception_Handler object, static functions only
    private function __construct()
    {}
    
    
    public static function register()
    {
        set_exception_handler(array(
            'App_Error_Handler', 
            'exceptionHandler'
        ));
        
        set_error_handler(array(
            'App_Error_Handler', 
            'errorHandler'
        ));
    }
    
    
    /**
     * Custom PHP error handler
     * 
     * @param integer $errno
     * @param string $errstr
     * @param string $errfile
     * @param integer $errline
     * @param mixed $errcontext
     */
    public static function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        // this is to bypass @ error suppression
        if ( error_reporting() == 0 )
            return;

        switch ( $errno ) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_PARSE:
            case E_RECOVERABLE_ERROR:
                Zend_Registry::get('logger')->err("An error occured in {$errfile} (line: {$errline}): [{$errno}] {$errstr}");
                break;
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                Zend_Registry::get('logger')->warn("An error occured in {$errfile} (line: {$errline}): [{$errno}] {$errstr}");
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
            case E_STRICT:
            default:
                Zend_Registry::get('logger')->notice("An error occured in {$errfile} (line: {$errline}): [{$errno}] {$errstr}");
                break;
        }
    }
    
    
    /**
     * Custom PHP exception handler
     * 
     * @param string $errno
     */
    public static function exceptionHandler($exception)
    {
        Zend_Registry::get('logger')->err("An error occured in {$exception->getFile()} (line: {$exception->getCode()}): [{$exception->getCode()}] {$exception->getMessage()}");
    }
}
