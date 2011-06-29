<?php

class App_Acl extends Zend_Acl {

    public function __construct() {


        $this->add(new Zend_Acl_Resource('default'))
        ->add(new Zend_Acl_Resource('default:error'),'default')
        ->add(new Zend_Acl_Resource('default:index'),'default')
        ->add(new Zend_Acl_Resource('default:code-list'),'default')
        ->add(new Zend_Acl_Resource('default:form'),'default')
        ->add(new Zend_Acl_Resource('default:addelement'), 'default')
        ->add(new Zend_Acl_Resource('default:iati'),'default')
        ->add(new Zend_Acl_Resource('default:iatixmlcompliance'),'default')
        ->add(new Zend_Acl_Resource('default:crstoiati'),'default')
        ->add(new Zend_Acl_Resource('default:api'),'default')
        ->add(new Zend_Acl_Resource('default:activityviewer'),'default')
        ->add(new Zend_Acl_Resource('default:wep'),'default')
        ->add(new Zend_Acl_Resource('default:admin'),'default')
        ->add(new Zend_Acl_Resource('default:iatixmlController'),'default');

        $this->add(new Zend_Acl_Resource('nullresources'));



        $this->add(new Zend_Acl_Resource('user'))
        ->add(new Zend_Acl_Resource('user:user'),'user');
        //user controller of user module has been inherited from user module


        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('user'),'guest');
        $this->addRole(new Zend_Acl_Role('admin'), 'user');
        $this->addRole(new Zend_Acl_Role('superadmin'), 'admin');



        $this->allow('guest', 'user:user', 'login');
        $this->deny('guest', 'user:user', 'register');
        $this->allow('guest', 'user:user', 'forgotpassword');
        $this->allow('guest', 'user:user', 'resetpassword');
        $this->allow('guest', 'default:index', 'index');
        $this->allow('guest', 'default:error', 'error');
        $this->allow('guest', 'default:code-list','code-list-index');
        $this->allow('guest', 'default:code-list','view-code');
        $this->allow('guest', 'default:code-list','view-category');
        $this->allow('guest', 'default:form');
        $this->allow('guest', 'default:iati');
        $this->allow('guest', 'default:iatixmlcompliance');
        $this->allow('guest', 'default:crstoiati');
        $this->allow('guest', 'default:api');
        $this->allow('guest', 'default:activityviewer');
        $this->allow('guest', 'default:iatixmlController');
        $this->allow('guest', 'default:wep', 'register');
        $this->allow('guest', 'default:wep', 'index');
        $this->allow('guest', 'nullresources');


        //this allows the role user to use user module's user controller's logout action
        $this->deny('user', 'user:user', 'login');//deny users from trying to login again
        $this->deny('user', 'user:user', 'register');
        $this->allow('user', 'user:user', 'logout');
        $this->allow('user', 'user:user', 'changepassword');
        $this->allow('user', 'user:user', 'myaccount');
        $this->allow('user', 'user:user', 'edit');
        $this->allow('user', 'default:wep', 'list-activities');
        $this->allow('user', 'default:wep', 'view-activities');
        $this->allow('user', 'default:wep', 'view-activity');
        $this->allow('user', 'default:wep', 'add-activities');
        $this->allow('user', 'default:wep', 'add-activity');
        $this->allow('user', 'default:wep', 'activitybar');
        $this->allow('user', 'default:wep', 'add-activity-elements');
        $this->allow('user', 'default:wep', 'edit-activity-elements');
        $this->allow('user', 'default:addelement');
        $this->allow('user', 'default:wep', 'delete');
        $this->allow('user', 'default:wep', 'edit-activity');
        $this->allow('user', 'default:wep', 'dashboard');
        $this->allow('user', 'default:wep', 'edit-defaults');
        $this->allow('user', 'default:wep', 'remove-elements');
        //        $this->allow('user', 'default:compose');
        //        $this->allow('user', 'default:contacts');
        //        $this->allow('user', 'default:report');
        //        $this->allow('user', 'default:profile');


        $this->allow('admin', 'user');

        $this->allow('admin', 'default:code-list');
        $this->deny('user', 'user:user', 'register');
        $this->allow('superadmin', 'default:admin');


    }

    public function isAllowed($role = null, $resource = null, $privilege = null) {
        // by default, undefined resources are allowed to all
        if (!$this->has($resource)) {
            $resource = 'nullresources';
        }
        return parent::isAllowed($role,$resource, $privilege);
    }

}
