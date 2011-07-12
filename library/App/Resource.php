<?php
class App_Resource implements Zend_Acl_Resource_Interface
{
    /*
     * ownerUser_id (From Db)
     * resourceId is for linking with the ACL class ; This value should be same as in Acl
     * resource could be anything which requires dynamic ACL like comment, blog post etc...
     */
    public $resourceId  = 'resource';
    public $ownerUserId = null;

    public function getResourceId()
    {
        return $this->resourceId;
    }
}
?>
