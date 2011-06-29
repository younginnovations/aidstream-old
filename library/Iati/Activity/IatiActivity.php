<?php
class Iati_Activity_IatiActivity
{
    private $default_currency = "";
    private $hierarchy = "";
    private $last_update_datetime = "";

    public function getDefault_currency() {
        return $this->default_currency;
    }

    public function setDefault_currency($default_currency) {
        $this->default_currency = $default_currency;
    }

    public function getHierarchy() {
        return $this->hierarchy;
    }

    public function setHierarchy($hierarchy) {
        $this->hierarchy = $hierarchy;
    }

    public function getLast_update_datetime() {
        return $this->last_update_datetime;
    }

    public function setLast_update_datetime($last_update_datetime) {
        $this->last_update_datetime = $last_update_datetime;
    }

    public static function Process($xmlObject)
    {
        $iatiActivity = new Iati_Activity_IatiActivity();
        $iatiActivity->setDefault_currency((string)$xmlObject->{'default-currency'});
        $iatiActivity->setHierarchy((string)$xmlObject->{'hierarchy'});
        $iatiActivity->setLast_update_datetime((string)$xmlObject->{'last-updated-datetime'});
        return $iatiActivity;
    }

}