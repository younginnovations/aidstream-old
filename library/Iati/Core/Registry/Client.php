<?php

class Iati_Core_Registry_Client extends Ckan_Client
{
    protected $version = '1.03';
    protected $resources = array(
        'package_register' => 'package_create',
	'package_entity' => 'package_update',
    );
    
    public function put_package_entity($package , $data)
    {
        return $this->make_request('POST', 
			$this->resources['package_entity'], 
			$data);
    }
}