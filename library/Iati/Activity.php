<?php
class Iati_Activity
{
    protected $iati_activity = "";

    protected $reporting_org = "";

    protected $participating_org = array();

    protected $recipient_country = "";

    protected $recipient_region = "";

    protected $collaboration_type = "";

    protected $default_flow_type = "";

    protected $default_aid_type = "";

    protected $default_finance_type = "";

    protected $other_identifier = "";

    protected $iati_identifier = "";

    protected $title = "";

    protected $description = "";

    protected $sector = "";

    protected $activity_date = "";

    protected $default_tied_status = "";

    protected $policy_marker = "";

    protected $transaction = "";

    protected $activity_status = "";

    protected $contact_info = "";

    protected $activity_website = "";

    protected $related_activity = "";

    protected $document_link = "";

    public function setIati_activity($iati_activity) {
        $this->iati_activity = $iati_activity;
    }

    public function setReporting_org($reporting_org) {
        $this->reporting_org = $reporting_org;
    }

    public function setParticipating_org($participating_org) {
        $this->participating_org = $participating_org;
    }

    public function setRecipient_country($recipient_country) {
        $this->recipient_country = $recipient_country;
    }

    public function setRecipient_region($recipient_region) {
        $this->recipient_region = $recipient_region;
    }

    public function setCollaboration_type($collaboration_type) {
        $this->collaboration_type = $collaboration_type;
    }

    public function setDefault_flow_type($default_flow_type) {
        $this->default_flow_type = $default_flow_type;
    }

    public function setDefault_aid_type($default_aid_type) {
        $this->default_aid_type = $default_aid_type;
    }

    public function setDefault_finance_type($default_finance_type) {
        $this->default_finance_type = $default_finance_type;
    }

    public function setOther_identifier($other_identifier) {
        $this->other_identifier = $other_identifier;
    }

    public function setIati_identifier($iati_identifier) {
        $this->iati_identifier = $iati_identifier;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setSector($sector) {
        $this->sector = $sector;
    }

    public function setActivity_date($activity_date) {
        $this->activity_date = $activity_date;
    }

    public function setDefault_tied_status($default_tied_status) {
        $this->default_tied_status = $default_tied_status;
    }

    public function setPolicy_marker($policy_marker) {
        $this->policy_marker = $policy_marker;
    }

    public function setTransaction($transaction) {
        $this->transaction = $transaction;
    }

    public function setActivity_status($activity_status) {
        $this->activity_status = $activity_status;
    }

    public function setContact_info($contact_info) {
        $this->contact_info = $contact_info;
    }

    public function setActivity_website($activity_website) {
        $this->activity_website = $activity_website;
    }

    public function setRelated_activity($related_activity) {
        $this->related_activity = $related_activity;
    }

    public function setDocument_link($document_link) {
        $this->document_link = $document_link;
    }

    public function getIati_activity() {
        return $this->iati_activity;
    }

    public function getReporting_org() {
        return $this->reporting_org;
    }

    public function getParticipating_org() {
        return $this->participating_org;
    }

    public function getRecipient_country() {
        return $this->recipient_country;
    }

    public function getRecipient_region() {
        return $this->recipient_region;
    }

    public function getCollaboration_type() {
        return $this->collaboration_type;
    }

    public function getDefault_flow_type() {
        return $this->default_flow_type;
    }

    public function getDefault_aid_type() {
        return $this->default_aid_type;
    }

    public function getDefault_finance_type() {
        return $this->default_finance_type;
    }

    public function getOther_identifier() {
        return $this->other_identifier;
    }

    public function getIati_identifier() {
        return $this->iati_identifier;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getSector() {
        return $this->sector;
    }

    public function getActivity_date() {
        return $this->activity_date;
    }

    public function getDefault_tied_status() {
        return $this->default_tied_status;
    }

    public function getPolicy_marker() {
        return $this->policy_marker;
    }

    public function getTransaction() {
        return $this->transaction;
    }

    public function getActivity_status() {
        return $this->activity_status;
    }

    public function getContact_info() {
        return $this->contact_info;
    }

    public function getActivity_website() {
        return $this->activity_website;
    }

    public function getRelated_activity() {
        return $this->related_activity;
    }

    public function getDocument_link() {
        return $this->document_link;
    }


}