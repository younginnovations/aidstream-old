<?php
/**
 * @uses       Zend_Application_Module_Bootstrap
 * @version    $Id$
 */
class Simplified_Bootstrap extends Zend_Application_Module_Bootstrap
{
    /**
     * This file is ABSOLUTELY NECESSARY to get module autoloading to work.  
     * Otherwise calls to "$form = new Module_Form_MyForm()" will fail.
     */
}