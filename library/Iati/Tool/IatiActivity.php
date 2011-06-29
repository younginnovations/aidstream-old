<?php
class Iati_Tool_IatiActivity
{
    protected $iati_activity = "";
    protected $iati_activity_xml_lang = 0;
    protected $iati_activity_default_currency = 0;
    protected $iati_activity_hierarchy = 0;
    protected $iati_activity_last_updated_datetime = 0;
    protected $reporting_org = "";
    protected $reporting_org_ref = 0;
    protected $reporting_org_type = 0;
    protected $reporting_org_text = 0;
    protected $reporting_org_xml_lang = 0;
    protected $participating_org = "";
    protected $participating_org_role = 0;
    protected $participating_org_ref = 0;
    protected $participating_org_type = 0;
    protected $participating_org_text = 0;
    protected $participating_org_xml_lang = 0;
    protected $recipient_country = "";
    protected $recipient_country_code = 0;
    protected $recipient_country_text = 0;
    protected $recipient_country_percentage = 0;
    protected $recipient_country_xml_lang = 0;
    protected $recipient_region = "";
    protected $recipient_region_code = 0;
    protected $recipient_region_text = 0;
    protected $recipient_region_percentage = 0;
    protected $recipient_region_xml_lang = 0;
    protected $collaboration_type = "";
    protected $collaboration_type_code = 0;
    protected $collaboration_type_text = 0;
    protected $collaboration_type_xml_lang = 0;
    protected $default_flow_type = "";
    protected $default_flow_type_code = 0;
    protected $default_flow_type_text = 0;
    protected $default_flow_type_xml_lang = 0;
    protected $default_aid_type = "";
    protected $default_aid_type_code = 0;
    protected $default_aid_type_text = 0;
    protected $default_aid_type_xml_lang = 0;
    protected $default_finance_type = "";
    protected $default_finance_type_code = 0;
    protected $default_finance_type_text = 0;
    protected $default_finance_type_xml_lang = 0;
    protected $iati_identifier = "";
    protected $iati_identifier_text = 0;
    protected $other_identifier = "";
    protected $other_identifier_owner_ref = 0;
    protected $other_identifier_owner_name = 0;
    protected $other_identifier_text = 0;
    protected $title = "";
    protected $title_text = 0;
    protected $title_xml_lang = 0;
    protected $description = "";
    protected $description_type = 0;
    protected $description_text = 0;
    protected $description_xml_lang = 0;
    protected $sector = "";
    protected $sector_vocabulary = 0;
    protected $sector_code = 0;
    protected $sector_text = 0;
    protected $sector_percentage = 0;
    protected $sector_xml_lang = 0;
    protected $activity_date = "";
    protected $activity_date_type = 0;
    protected $activity_date_iso_date = 0;
    protected $activity_date_text = 0;
    protected $activity_date_xml_lang = 0;
    protected $default_tied_status = "";
    protected $default_tied_status_code = 0;
    protected $default_tied_status_text = 0;
    protected $default_tied_status_xml_lang = 0;
    protected $policy_marker = "";
    protected $policy_marker_significance = 0;
    protected $policy_marker_vocabulary = 0;
    protected $policy_marker_code = 0;
    protected $policy_marker_text = 0;
    protected $policy_marker_xml_lang = 0;
    protected $transaction = "";
    protected $transaction_transaction_type = 0;
    protected $transaction_transaction_type_code = 0;
    protected $transaction_transaction_type_text = 0;
    protected $transaction_provider_org = "";
    protected $transaction_provider_org_text = 0;
    protected $transaction_provider_org_ref = 0;
    protected $transaction_provider_org_provider_activity_id = 0;
    protected $transaction_receiver_org = "";
    protected $transaction_receiver_org_text = 0;
    protected $transaction_receiver_org_ref = 0;
    protected $transaction_receiver_org_receiver_activity_id = 0;
    protected $transaction_value = "";
    protected $transaction_value_text = 0;
    protected $transaction_value_currency = 0;
    protected $transaction_value_value_date = 0;
    protected $transaction_description = "";
    protected $transaction_description_text = 0;
    protected $transaction_description_xml_lang = 0;
    protected $transaction_transaction_date = "";
    protected $transaction_transaction_date_iso_date = 0;
    protected $transaction_transaction_date_text = 0;
    protected $transaction_flow_type = "";
    protected $transaction_flow_type_code = 0;
    protected $transaction_flow_type_text = 0;
    protected $transaction_flow_type_xml_lang = 0;
    protected $transaction_finance_type = "";
    protected $transaction_finance_type_code = 0;
    protected $transaction_finance_type_text = 0;
    protected $transaction_finance_type_xml_lang = 0;
    protected $transaction_aid_type = "";
    protected $transaction_aid_type_code = 0;
    protected $transaction_aid_type_text = 0;
    protected $transaction_aid_type_xml_lang = 0;
    protected $transaction_disbursement_channel = "";
    protected $transaction_disbursement_channel_code = 0;
    protected $transaction_disbursement_channel_text = 0;
    protected $transaction_tied_status = "";
    protected $transaction_tied_status_code = 0;
    protected $transaction_tied_status_text = 0;
    protected $transaction_tied_status_xml_lang = 0;
    protected $activity_status = "";
    protected $activity_status_code = 0;
    protected $activity_status_text = 0;
    protected $activity_status_xml_lang = 0;
    protected $contact_info = "";
    protected $contact_info_organisation_text = 0;
    protected $contact_info_person_name_text = 0;
    protected $contact_info_telephone_text = 0;
    protected $contact_info_email_text = 0;
    protected $contact_info_mailing_address_text = 0;
    protected $activity_website = "";
    protected $activity_website_text = 0;
    protected $related_activity = "";
    protected $related_activity_type = 0;
    protected $related_activity_ref = 0;
    protected $related_activity_text = 0;
    protected $related_activity_xml_lang = 0;
    protected $document_link = "";
    protected $document_link_url = 0;
    protected $document_link_format = 0;
    protected $document_link_xml_lang = 0;
    protected $document_link_category = 0;
    protected $document_link_category_code = 0;
    protected $document_link_category_text = 0;
    protected $document_link_title_text = 0;
    protected $location = "";
    protected $location_percentage = 0;
    protected $location_location_type_code = 0;
    protected $location_name_text = 0;
    protected $location_name_xml_lang = 0;
    protected $location_description_text = 0;
    protected $location_administrative = 0;
    protected $location_administrative_country = 0;
    protected $location_administrative_adm1 = 0;
    protected $location_administrative_adm2 = 0;
    protected $location_administrative_text = 0;
    protected $location_coordinates = "";
    protected $location_coordinates_latitude = 0;
    protected $location_coordinates_longitude = 0;
    protected $location_coordinates_percision = 0;
    protected $location_gazetteer_entry = 0;
    protected $location_gazetteer_entry_gazetteer_ref = 0;
    protected $location_gazetteer_entry_text = 0;

    public function getIati_activity() {
        return $this->iati_activity;
    }

    public function getIati_activity_xml_lang() {
        return $this->iati_activity_xml_lang;
    }

    public function getIati_activity_default_currency() {
        return $this->iati_activity_default_currency;
    }

    public function getIati_activity_hierarchy() {
        return $this->iati_activity_hierarchy;
    }

    public function getIati_activity_last_updated_datetime() {
        return $this->iati_activity_last_updated_datetime;
    }

    public function getReporting_org() {
        return $this->reporting_org;
    }

    public function getReporting_org_ref() {
        return $this->reporting_org_ref;
    }

    public function getReporting_org_type() {
        return $this->reporting_org_type;
    }

    public function getReporting_org_text() {
        return $this->reporting_org_text;
    }

    public function getReporting_org_xml_lang() {
        return $this->reporting_org_xml_lang;
    }

    public function getParticipating_org() {
        return $this->participating_org;
    }

    public function getParticipating_org_role() {
        return $this->participating_org_role;
    }

    public function getParticipating_org_ref() {
        return $this->participating_org_ref;
    }

    public function getParticipating_org_type() {
        return $this->participating_org_type;
    }

    public function getParticipating_org_text() {
        return $this->participating_org_text;
    }

    public function getParticipating_org_xml_lang() {
        return $this->participating_org_xml_lang;
    }

    public function getRecipient_country() {
        return $this->recipient_country;
    }

    public function getRecipient_country_code() {
        return $this->recipient_country_code;
    }

    public function getRecipient_country_text() {
        return $this->recipient_country_text;
    }

    public function getRecipient_country_percentage() {
        return $this->recipient_country_percentage;
    }

    public function getRecipient_country_xml_lang() {
        return $this->recipient_country_xml_lang;
    }

    public function getRecipient_region() {
        return $this->recipient_region;
    }

    public function getRecipient_region_code() {
        return $this->recipient_region_code;
    }

    public function getRecipient_region_text() {
        return $this->recipient_region_text;
    }

    public function getRecipient_region_percentage() {
        return $this->recipient_region_percentage;
    }

    public function getRecipient_region_xml_lang() {
        return $this->recipient_region_xml_lang;
    }

    public function getCollaboration_type() {
        return $this->collaboration_type;
    }

    public function getCollaboration_type_code() {
        return $this->collaboration_type_code;
    }

    public function getCollaboration_type_text() {
        return $this->collaboration_type_text;
    }

    public function getCollaboration_type_xml_lang() {
        return $this->collaboration_type_xml_lang;
    }

    public function getDefault_flow_type() {
        return $this->default_flow_type;
    }

    public function getDefault_flow_type_code() {
        return $this->default_flow_type_code;
    }

    public function getDefault_flow_type_text() {
        return $this->default_flow_type_text;
    }

    public function getDefault_flow_type_xml_lang() {
        return $this->default_flow_type_xml_lang;
    }

    public function getDefault_aid_type() {
        return $this->default_aid_type;
    }

    public function getDefault_aid_type_code() {
        return $this->default_aid_type_code;
    }

    public function getDefault_aid_type_text() {
        return $this->default_aid_type_text;
    }

    public function getDefault_aid_type_xml_lang() {
        return $this->default_aid_type_xml_lang;
    }

    public function getDefault_finance_type() {
        return $this->default_finance_type;
    }

    public function getDefault_finance_type_code() {
        return $this->default_finance_type_code;
    }

    public function getDefault_finance_type_text() {
        return $this->default_finance_type_text;
    }

    public function getDefault_finance_type_xml_lang() {
        return $this->default_finance_type_xml_lang;
    }

    public function getIati_identifier() {
        return $this->iati_identifier;
    }

    public function getIati_identifier_text() {
        return $this->iati_identifier_text;
    }

    public function getOther_identifier() {
        return $this->other_identifier;
    }

    public function getOther_identifier_owner_ref() {
        return $this->other_identifier_owner_ref;
    }

    public function getOther_identifier_owner_name() {
        return $this->other_identifier_owner_name;
    }

    public function getOther_identifier_text() {
        return $this->other_identifier_text;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getTitle_text() {
        return $this->title_text;
    }

    public function getTitle_xml_lang() {
        return $this->title_xml_lang;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDescription_type() {
        return $this->description_type;
    }

    public function getDescription_text() {
        return $this->description_text;
    }

    public function getDescription_xml_lang() {
        return $this->description_xml_lang;
    }

    public function getSector() {
        return $this->sector;
    }

    public function getSector_vocabulary() {
        return $this->sector_vocabulary;
    }

    public function getSector_code() {
        return $this->sector_code;
    }

    public function getSector_text() {
        return $this->sector_text;
    }

    public function getSector_percentage() {
        return $this->sector_percentage;
    }

    public function getSector_xml_lang() {
        return $this->sector_xml_lang;
    }

    public function getActivity_date() {
        return $this->activity_date;
    }

    public function getActivity_date_type() {
        return $this->activity_date_type;
    }

    public function getActivity_date_iso_date() {
        return $this->activity_date_iso_date;
    }

    public function getActivity_date_text() {
        return $this->activity_date_text;
    }

    public function getActivity_date_xml_lang() {
        return $this->activity_date_xml_lang;
    }

    public function getDefault_tied_status() {
        return $this->default_tied_status;
    }

    public function getDefault_tied_status_code() {
        return $this->default_tied_status_code;
    }

    public function getDefault_tied_status_text() {
        return $this->default_tied_status_text;
    }

    public function getDefault_tied_status_xml_lang() {
        return $this->default_tied_status_xml_lang;
    }

    public function getPolicy_marker() {
        return $this->policy_marker;
    }

    public function getPolicy_marker_significance() {
        return $this->policy_marker_significance;
    }

    public function getPolicy_marker_vocabulary() {
        return $this->policy_marker_vocabulary;
    }

    public function getPolicy_marker_code() {
        return $this->policy_marker_code;
    }

    public function getPolicy_marker_text() {
        return $this->policy_marker_text;
    }

    public function getPolicy_marker_xml_lang() {
        return $this->policy_marker_xml_lang;
    }

    public function getTransaction() {
        return $this->transaction;
    }

    public function getTransaction_transaction_type() {
        return $this->transaction_transaction_type;
    }

    public function getTransaction_transaction_type_code() {
        return $this->transaction_transaction_type_code;
    }

    public function getTransaction_transaction_type_text() {
        return $this->transaction_transaction_type_text;
    }

    public function getTransaction_provider_org() {
        return $this->transaction_provider_org;
    }

    public function getTransaction_provider_org_text() {
        return $this->transaction_provider_org_text;
    }

    public function getTransaction_provider_org_ref() {
        return $this->transaction_provider_org_ref;
    }

    public function getTransaction_provider_org_provider_activity_id() {
        return $this->transaction_provider_org_provider_activity_id;
    }

    public function getTransaction_receiver_org() {
        return $this->transaction_receiver_org;
    }

    public function getTransaction_receiver_org_text() {
        return $this->transaction_receiver_org_text;
    }

    public function getTransaction_receiver_org_ref() {
        return $this->transaction_receiver_org_ref;
    }

    public function getTransaction_receiver_org_receiver_activity_id() {
        return $this->transaction_receiver_org_receiver_activity_id;
    }

    public function getTransaction_value() {
        return $this->transaction_value;
    }

    public function getTransaction_value_text() {
        return $this->transaction_value_text;
    }

    public function getTransaction_value_currency() {
        return $this->transaction_value_currency;
    }

    public function getTransaction_value_value_date() {
        return $this->transaction_value_value_date;
    }

    public function getTransaction_description() {
        return $this->transaction_description;
    }

    public function getTransaction_description_text() {
        return $this->transaction_description_text;
    }

    public function getTransaction_description_xml_lang() {
        return $this->transaction_description_xml_lang;
    }

    public function getTransaction_transaction_date() {
        return $this->transaction_transaction_date;
    }

    public function getTransaction_transaction_date_iso_date() {
        return $this->transaction_transaction_date_iso_date;
    }

    public function getTransaction_transaction_date_text() {
        return $this->transaction_transaction_date_text;
    }

    public function getTransaction_flow_type() {
        return $this->transaction_flow_type;
    }

    public function getTransaction_flow_type_code() {
        return $this->transaction_flow_type_code;
    }

    public function getTransaction_flow_type_text() {
        return $this->transaction_flow_type_text;
    }

    public function getTransaction_flow_type_xml_lang() {
        return $this->transaction_flow_type_xml_lang;
    }

    public function getTransaction_finance_type() {
        return $this->transaction_finance_type;
    }

    public function getTransaction_finance_type_code() {
        return $this->transaction_finance_type_code;
    }

    public function getTransaction_finance_type_text() {
        return $this->transaction_finance_type_text;
    }

    public function getTransaction_finance_type_xml_lang() {
        return $this->transaction_finance_type_xml_lang;
    }

    public function getTransaction_aid_type() {
        return $this->transaction_aid_type;
    }

    public function getTransaction_aid_type_code() {
        return $this->transaction_aid_type_code;
    }

    public function getTransaction_aid_type_text() {
        return $this->transaction_aid_type_text;
    }

    public function getTransaction_aid_type_xml_lang() {
        return $this->transaction_aid_type_xml_lang;
    }

    public function getTransaction_disbursement_channel() {
        return $this->transaction_disbursement_channel;
    }

    public function getTransaction_disbursement_channel_code() {
        return $this->transaction_disbursement_channel_code;
    }

    public function getTransaction_disbursement_channel_text() {
        return $this->transaction_disbursement_channel_text;
    }

    public function getTransaction_tied_status() {
        return $this->transaction_tied_status;
    }

    public function getTransaction_tied_status_code() {
        return $this->transaction_tied_status_code;
    }

    public function getTransaction_tied_status_text() {
        return $this->transaction_tied_status_text;
    }

    public function getTransaction_tied_status_xml_lang() {
        return $this->transaction_tied_status_xml_lang;
    }

    public function getActivity_status() {
        return $this->activity_status;
    }

    public function getActivity_status_code() {
        return $this->activity_status_code;
    }

    public function getActivity_status_xml_lang() {
        return $this->activity_status_xml_lang;
    }

    public function getActivity_status_text() {
        return $this->activity_status_text;
    }

    public function getContact_info() {
        return $this->contact_info;
    }

    public function getContact_info_organisation_text() {
        return $this->contact_info_organisation_text;
    }

    public function getContact_info_person_name_text() {
        return $this->contact_info_person_name_text;
    }

    public function getContact_info_telephone_text() {
        return $this->contact_info_telephone_text;
    }

    public function getContact_info_email_text() {
        return $this->contact_info_email_text;
    }

    public function getContact_info_mailing_address_text() {
        return $this->contact_info_mailing_address_text;
    }

    public function getActivity_website() {
        return $this->activity_website;
    }

    public function getActivity_website_text() {
        return $this->activity_website_text;
    }

    public function getRelated_activity() {
        return $this->related_activity;
    }

    public function getRelated_activity_type() {
        return $this->related_activity_type;
    }

    public function getRelated_activity_ref() {
        return $this->related_activity_ref;
    }

    public function getRelated_activity_text() {
        return $this->related_activity_text;
    }

    public function getRelated_activity_xml_lang() {
        return $this->related_activity_xml_lang;
    }

    public function getDocument_link() {
        return $this->document_link;
    }

    public function getDocument_link_url() {
        return $this->document_link_url;
    }

    public function getDocument_link_format() {
        return $this->document_link_format;
    }

    public function getDocument_link_xml_lang() {
        return $this->document_link_xml_lang;
    }

    public function getDocument_link_category() {
        return $this->document_link_category;
    }

    public function getDocument_link_category_code() {
        return $this->document_link_category_code;
    }

    public function getDocument_link_category_text() {
        return $this->document_link_category_text;
    }

    public function getDocument_link_title_text() {
        return $this->document_link_title_text;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getLocation_percentage() {
        return $this->location_percentage;
    }

    public function getLocation_location_type_code() {
        return $this->location_location_type_code;
    }

    public function getLocation_name_text() {
        return $this->location_name_text;
    }

    public function getLocation_name_xml_lang() {
        return $this->location_name_xml_lang;
    }

    public function getLocation_description_text() {
        return $this->location_description_text;
    }

    public function getLocation_administrative() {
        return $this->location_administrative;
    }

    public function getLocation_administrative_country() {
        return $this->location_administrative_country;
    }

    public function getLocation_administrative_adm1() {
        return $this->location_administrative_adm1;
    }

    public function getLocation_administrative_adm2() {
        return $this->location_administrative_adm2;
    }

    public function getLocation_administrative_text() {
        return $this->location_administrative_text;
    }

    public function getLocation_coordinates() {
        return $this->location_coordinates;
    }

    public function getLocation_coordinates_latitude() {
        return $this->location_coordinates_latitude;
    }

    public function getLocation_coordinates_longitude() {
        return $this->location_coordinates_longitude;
    }

    public function getLocation_coordinates_percision() {
        return $this->location_coordinates_percision;
    }

    public function getLocation_gazetteer_entry() {
        return $this->location_gazetteer_entry;
    }

    public function getLocation_gazetteer_entry_gazetteer_ref() {
        return $this->location_gazetteer_entry_gazetteer_ref;
    }

    public function getLocation_gazetteer_entry_text() {
        return $this->location_gazetteer_entry_text;
    }

    public function setIati_activity($iati_activity) {
        $this->iati_activity = $iati_activity;
    }

    public function setIati_activity_xml_lang($iati_activity_xml_lang) {
        $this->iati_activity_xml_lang = $iati_activity_xml_lang;
    }

    public function setIati_activity_default_currency($iati_activity_default_currency) {
        $this->iati_activity_default_currency = $iati_activity_default_currency;
    }

    public function setIati_activity_hierarchy($iati_activity_hierarchy) {
        $this->iati_activity_hierarchy = $iati_activity_hierarchy;
    }

    public function setIati_activity_last_updated_datetime($iati_activity_last_updated_datetime) {
        $this->iati_activity_last_updated_datetime = $iati_activity_last_updated_datetime;
    }

    public function setReporting_org($reporting_org) {
        $this->reporting_org = $reporting_org;
    }

    public function setReporting_org_ref($reporting_org_ref) {
        $this->reporting_org_ref = $reporting_org_ref;
    }

    public function setReporting_org_type($reporting_org_type) {
        $this->reporting_org_type = $reporting_org_type;
    }

    public function setReporting_org_text($reporting_org_text) {
        $this->reporting_org_text = $reporting_org_text;
    }

    public function setReporting_org_xml_lang($reporting_org_xml_lang) {
        $this->reporting_org_xml_lang = $reporting_org_xml_lang;
    }

    public function setParticipating_org($participating_org) {
        $this->participating_org = $participating_org;
    }

    public function setParticipating_org_role($participating_org_role) {
        $this->participating_org_role = $participating_org_role;
    }

    public function setParticipating_org_ref($participating_org_ref) {
        $this->participating_org_ref = $participating_org_ref;
    }

    public function setParticipating_org_type($participating_org_type) {
        $this->participating_org_type = $participating_org_type;
    }

    public function setParticipating_org_text($participating_org_text) {
        $this->participating_org_text = $participating_org_text;
    }

    public function setParticipating_org_xml_lang($participating_org_xml_lang) {
        $this->participating_org_xml_lang = $participating_org_xml_lang;
    }

    public function setRecipient_country($recipient_country) {
        $this->recipient_country = $recipient_country;
    }

    public function setRecipient_country_code($recipient_country_code) {
        $this->recipient_country_code = $recipient_country_code;
    }

    public function setRecipient_country_text($recipient_country_text) {
        $this->recipient_country_text = $recipient_country_text;
    }

    public function setRecipient_country_percentage($recipient_country_percentage) {
        $this->recipient_country_percentage = $recipient_country_percentage;
    }

    public function setRecipient_country_xml_lang($recipient_country_xml_lang) {
        $this->recipient_country_xml_lang = $recipient_country_xml_lang;
    }

    public function setRecipient_region($recipient_region) {
        $this->recipient_region = $recipient_region;
    }

    public function setRecipient_region_code($recipient_region_code) {
        $this->recipient_region_code = $recipient_region_code;
    }

    public function setRecipient_region_text($recipient_region_text) {
        $this->recipient_region_text = $recipient_region_text;
    }

    public function setRecipient_region_percentage($recipient_region_percentage) {
        $this->recipient_region_percentage = $recipient_region_percentage;
    }

    public function setRecipient_region_xml_lang($recipient_region_xml_lang) {
        $this->recipient_region_xml_lang = $recipient_region_xml_lang;
    }

    public function setCollaboration_type($collaboration_type) {
        $this->collaboration_type = $collaboration_type;
    }

    public function setCollaboration_type_code($collaboration_type_code) {
        $this->collaboration_type_code = $collaboration_type_code;
    }

    public function setCollaboration_type_text($collaboration_type_text) {
        $this->collaboration_type_text = $collaboration_type_text;
    }

    public function setCollaboration_type_xml_lang($collaboration_type_xml_lang) {
        $this->collaboration_type_xml_lang = $collaboration_type_xml_lang;
    }

    public function setDefault_flow_type($default_flow_type) {
        $this->default_flow_type = $default_flow_type;
    }

    public function setDefault_flow_type_code($default_flow_type_code) {
        $this->default_flow_type_code = $default_flow_type_code;
    }

    public function setDefault_flow_type_text($default_flow_type_text) {
        $this->default_flow_type_text = $default_flow_type_text;
    }

    public function setDefault_flow_type_xml_lang($default_flow_type_xml_lang) {
        $this->default_flow_type_xml_lang = $default_flow_type_xml_lang;
    }

    public function setDefault_aid_type($default_aid_type) {
        $this->default_aid_type = $default_aid_type;
    }

    public function setDefault_aid_type_code($default_aid_type_code) {
        $this->default_aid_type_code = $default_aid_type_code;
    }

    public function setDefault_aid_type_text($default_aid_type_text) {
        $this->default_aid_type_text = $default_aid_type_text;
    }

    public function setDefault_aid_type_xml_lang($default_aid_type_xml_lang) {
        $this->default_aid_type_xml_lang = $default_aid_type_xml_lang;
    }

    public function setDefault_finance_type($default_finance_type) {
        $this->default_finance_type = $default_finance_type;
    }

    public function setDefault_finance_type_code($default_finance_type_code) {
        $this->default_finance_type_code = $default_finance_type_code;
    }

    public function setDefault_finance_type_text($default_finance_type_text) {
        $this->default_finance_type_text = $default_finance_type_text;
    }

    public function setDefault_finance_type_xml_lang($default_finance_type_xml_lang) {
        $this->default_finance_type_xml_lang = $default_finance_type_xml_lang;
    }

    public function setIati_identifier($iati_identifier) {
        $this->iati_identifier = $iati_identifier;
    }

    public function setIati_identifier_text($iati_identifier_text) {
        $this->iati_identifier_text = $iati_identifier_text;
    }

    public function setOther_identifier($other_identifier) {
        $this->other_identifier = $other_identifier;
    }

    public function setOther_identifier_owner_ref($other_identifier_owner_ref) {
        $this->other_identifier_owner_ref = $other_identifier_owner_ref;
    }

    public function setOther_identifier_owner_name($other_identifier_owner_name) {
        $this->other_identifier_owner_name = $other_identifier_owner_name;
    }

    public function setOther_identifier_text($other_identifier_text) {
        $this->other_identifier_text = $other_identifier_text;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setTitle_text($title_text) {
        $this->title_text = $title_text;
    }

    public function setTitle_xml_lang($title_xml_lang) {
        $this->title_xml_lang = $title_xml_lang;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDescription_type($description_type) {
        $this->description_type = $description_type;
    }

    public function setDescription_text($description_text) {
        $this->description_text = $description_text;
    }

    public function setDescription_xml_lang($description_xml_lang) {
        $this->description_xml_lang = $description_xml_lang;
    }

    public function setSector($sector) {
        $this->sector = $sector;
    }

    public function setSector_vocabulary($sector_vocabulary) {
        $this->sector_vocabulary = $sector_vocabulary;
    }

    public function setSector_code($sector_code) {
        $this->sector_code = $sector_code;
    }

    public function setSector_text($sector_text) {
        $this->sector_text = $sector_text;
    }

    public function setSector_percentage($sector_percentage) {
        $this->sector_percentage = $sector_percentage;
    }

    public function setSector_xml_lang($sector_xml_lang) {
        $this->sector_xml_lang = $sector_xml_lang;
    }

    public function setActivity_date($activity_date) {
        $this->activity_date = $activity_date;
    }

    public function setActivity_date_type($activity_date_type) {
        $this->activity_date_type = $activity_date_type;
    }

    public function setActivity_date_iso_date($activity_date_iso_date) {
        $this->activity_date_iso_date = $activity_date_iso_date;
    }

    public function setActivity_date_text($activity_date_text) {
        $this->activity_date_text = $activity_date_text;
    }

    public function setActivity_date_xml_lang($activity_date_xml_lang) {
        $this->activity_date_xml_lang = $activity_date_xml_lang;
    }

    public function setDefault_tied_status($default_tied_status) {
        $this->default_tied_status = $default_tied_status;
    }

    public function setDefault_tied_status_code($default_tied_status_code) {
        $this->default_tied_status_code = $default_tied_status_code;
    }

    public function setDefault_tied_status_text($default_tied_status_text) {
        $this->default_tied_status_text = $default_tied_status_text;
    }

    public function setDefault_tied_status_xml_lang($default_tied_status_xml_lang) {
        $this->default_tied_status_xml_lang = $default_tied_status_xml_lang;
    }

    public function setPolicy_marker($policy_marker) {
        $this->policy_marker = $policy_marker;
    }

    public function setPolicy_marker_significance($policy_marker_significance) {
        $this->policy_marker_significance = $policy_marker_significance;
    }

    public function setPolicy_marker_vocabulary($policy_marker_vocabulary) {
        $this->policy_marker_vocabulary = $policy_marker_vocabulary;
    }

    public function setPolicy_marker_code($policy_marker_code) {
        $this->policy_marker_code = $policy_marker_code;
    }

    public function setPolicy_marker_text($policy_marker_text) {
        $this->policy_marker_text = $policy_marker_text;
    }

    public function setPolicy_marker_xml_lang($policy_marker_xml_lang) {
        $this->policy_marker_xml_lang = $policy_marker_xml_lang;
    }

    public function setTransaction($transaction) {
        $this->transaction = $transaction;
    }

    public function setTransaction_transaction_type($transaction_transaction_type) {
        $this->transaction_transaction_type = $transaction_transaction_type;
    }

    public function setTransaction_transaction_type_code($transaction_transaction_type_code) {
        $this->transaction_transaction_type_code = $transaction_transaction_type_code;
    }

    public function setTransaction_transaction_type_text($transaction_transaction_type_text) {
        $this->transaction_transaction_type_text = $transaction_transaction_type_text;
    }

    public function setTransaction_provider_org($transaction_provider_org) {
        $this->transaction_provider_org = $transaction_provider_org;
    }

    public function setTransaction_provider_org_text($transaction_provider_org_text) {
        $this->transaction_provider_org_text = $transaction_provider_org_text;
    }

    public function setTransaction_provider_org_ref($transaction_provider_org_ref) {
        $this->transaction_provider_org_ref = $transaction_provider_org_ref;
    }

    public function setTransaction_provider_org_provider_activity_id($transaction_provider_org_provider_activity_id) {
        $this->transaction_provider_org_provider_activity_id = $transaction_provider_org_provider_activity_id;
    }

    public function setTransaction_receiver_org($transaction_receiver_org) {
        $this->transaction_receiver_org = $transaction_receiver_org;
    }

    public function setTransaction_receiver_org_text($transaction_receiver_org_text) {
        $this->transaction_receiver_org_text = $transaction_receiver_org_text;
    }

    public function setTransaction_receiver_org_ref($transaction_receiver_org_ref) {
        $this->transaction_receiver_org_ref = $transaction_receiver_org_ref;
    }

    public function setTransaction_receiver_org_receiver_activity_id($transaction_receiver_org_receiver_activity_id) {
        $this->transaction_receiver_org_receiver_activity_id = $transaction_receiver_org_receiver_activity_id;
    }

    public function setTransaction_value($transaction_value) {
        $this->transaction_value = $transaction_value;
    }

    public function setTransaction_value_text($transaction_value_text) {
        $this->transaction_value_text = $transaction_value_text;
    }

    public function setTransaction_value_currency($transaction_value_currency) {
        $this->transaction_value_currency = $transaction_value_currency;
    }

    public function setTransaction_value_value_date($transaction_value_value_date) {
        $this->transaction_value_value_date = $transaction_value_value_date;
    }

    public function setTransaction_description($transaction_description) {
        $this->transaction_description = $transaction_description;
    }

    public function setTransaction_description_text($transaction_description_text) {
        $this->transaction_description_text = $transaction_description_text;
    }

    public function setTransaction_description_xml_lang($transaction_description_xml_lang) {
        $this->transaction_description_xml_lang = $transaction_description_xml_lang;
    }

    public function setTransaction_transaction_date($transaction_transaction_date) {
        $this->transaction_transaction_date = $transaction_transaction_date;
    }

    public function setTransaction_transaction_date_iso_date($transaction_transaction_date_iso_date) {
        $this->transaction_transaction_date_iso_date = $transaction_transaction_date_iso_date;
    }

    public function setTransaction_transaction_date_text($transaction_transaction_date_text) {
        $this->transaction_transaction_date_text = $transaction_transaction_date_text;
    }

    public function setTransaction_flow_type($transaction_flow_type) {
        $this->transaction_flow_type = $transaction_flow_type;
    }

    public function setTransaction_flow_type_code($transaction_flow_type_code) {
        $this->transaction_flow_type_code = $transaction_flow_type_code;
    }

    public function setTransaction_flow_type_text($transaction_flow_type_text) {
        $this->transaction_flow_type_text = $transaction_flow_type_text;
    }

    public function setTransaction_flow_type_xml_lang($transaction_flow_type_xml_lang) {
        $this->transaction_flow_type_xml_lang = $transaction_flow_type_xml_lang;
    }

    public function setTransaction_finance_type($transaction_finance_type) {
        $this->transaction_finance_type = $transaction_finance_type;
    }

    public function setTransaction_finance_type_code($transaction_finance_type_code) {
        $this->transaction_finance_type_code = $transaction_finance_type_code;
    }

    public function setTransaction_finance_type_text($transaction_finance_type_text) {
        $this->transaction_finance_type_text = $transaction_finance_type_text;
    }

    public function setTransaction_finance_type_xml_lang($transaction_finance_type_xml_lang) {
        $this->transaction_finance_type_xml_lang = $transaction_finance_type_xml_lang;
    }

    public function setTransaction_aid_type($transaction_aid_type) {
        $this->transaction_aid_type = $transaction_aid_type;
    }

    public function setTransaction_aid_type_code($transaction_aid_type_code) {
        $this->transaction_aid_type_code = $transaction_aid_type_code;
    }

    public function setTransaction_aid_type_text($transaction_aid_type_text) {
        $this->transaction_aid_type_text = $transaction_aid_type_text;
    }

    public function setTransaction_aid_type_xml_lang($transaction_aid_type_xml_lang) {
        $this->transaction_aid_type_xml_lang = $transaction_aid_type_xml_lang;
    }

    public function setTransaction_disbursement_channel($transaction_disbursement_channel) {
        $this->transaction_disbursement_channel = $transaction_disbursement_channel;
    }

    public function setTransaction_disbursement_channel_code($transaction_disbursement_channel_code) {
        $this->transaction_disbursement_channel_code = $transaction_disbursement_channel_code;
    }

    public function setTransaction_disbursement_channel_text($transaction_disbursement_channel_text) {
        $this->transaction_disbursement_channel_text = $transaction_disbursement_channel_text;
    }

    public function setTransaction_tied_status($transaction_tied_status) {
        $this->transaction_tied_status = $transaction_tied_status;
    }

    public function setTransaction_tied_status_code($transaction_tied_status_code) {
        $this->transaction_tied_status_code = $transaction_tied_status_code;
    }

    public function setTransaction_tied_status_text($transaction_tied_status_text) {
        $this->transaction_tied_status_text = $transaction_tied_status_text;
    }

    public function setTransaction_tied_status_xml_lang($transaction_tied_status_xml_lang) {
        $this->transaction_tied_status_xml_lang = $transaction_tied_status_xml_lang;
    }

    public function setContact_info($contact_info) {
        $this->contact_info = $contact_info;
    }

    public function setContact_info_organisation_text($contact_info_organisation_text) {
        $this->contact_info_organisation_text = $contact_info_organisation_text;
    }

    public function setContact_info_person_name_text($contact_info_person_name_text) {
        $this->contact_info_person_name_text = $contact_info_person_name_text;
    }

    public function setContact_info_telephone_text($contact_info_telephone_text) {
        $this->contact_info_telephone_text = $contact_info_telephone_text;
    }

    public function setContact_info_email_text($contact_info_email_text) {
        $this->contact_info_email_text = $contact_info_email_text;
    }

    public function setContact_info_mailing_address_text($contact_info_mailing_address_text) {
        $this->contact_info_mailing_address_text = $contact_info_mailing_address_text;
    }

    public function setActivity_website($activity_website) {
        $this->activity_website = $activity_website;
    }

    public function setActivity_website_text($activity_website_text) {
        $this->activity_website_text = $activity_website_text;
    }

    public function setRelated_activity($related_activity) {
        $this->related_activity = $related_activity;
    }

    public function setRelated_activity_type($related_activity_type) {
        $this->related_activity_type = $related_activity_type;
    }

    public function setRelated_activity_ref($related_activity_ref) {
        $this->related_activity_ref = $related_activity_ref;
    }

    public function setRelated_activity_text($related_activity_text) {
        $this->related_activity_text = $related_activity_text;
    }

    public function setRelated_activity_xml_lang($related_activity_xml_lang) {
        $this->related_activity_xml_lang = $related_activity_xml_lang;
    }

    public function setDocument_link($document_link) {
        $this->document_link = $document_link;
    }

    public function setDocument_link_url($document_link_url) {
        $this->document_link_url = $document_link_url;
    }

    public function setDocument_link_format($document_link_format) {
        $this->document_link_format = $document_link_format;
    }

    public function setDocument_link_xml_lang($document_link_xml_lang) {
        $this->document_link_xml_lang = $document_link_xml_lang;
    }

    public function setDocument_link_category($document_link_category) {
        $this->document_link_category = $document_link_category;
    }

    public function setDocument_link_category_code($document_link_category_code) {
        $this->document_link_category_code = $document_link_category_code;
    }

    public function setDocument_link_category_text($document_link_category_text) {
        $this->document_link_category_text = $document_link_category_text;
    }

    public function setDocument_link_title_text($document_link_title_text) {
        $this->document_link_title_text = $document_link_title_text;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function setLocation_percentage($location_percentage) {
        $this->location_percentage = $location_percentage;
    }

    public function setLocation_location_type_code($location_location_type_code) {
        $this->location_location_type_code = $location_location_type_code;
    }

    public function setLocation_name_text($location_name_text) {
        $this->location_name_text = $location_name_text;
    }

    public function setLocation_name_xml_lang($location_name_xml_lang) {
        $this->location_name_xml_lang = $location_name_xml_lang;
    }

    public function setLocation_description_text($location_description_text) {
        $this->location_description_text = $location_description_text;
    }

    public function setLocation_administrative($location_administrative) {
        $this->location_administrative = $location_administrative;
    }

    public function setLocation_administrative_country($location_administrative_country) {
        $this->location_administrative_country = $location_administrative_country;
    }

    public function setLocation_administrative_adm1($location_administrative_adm1) {
        $this->location_administrative_adm1 = $location_administrative_adm1;
    }

    public function setLocation_administrative_adm2($location_administrative_adm2) {
        $this->location_administrative_adm2 = $location_administrative_adm2;
    }

    public function setLocation_administrative_text($location_administrative_text) {
        $this->location_administrative_text = $location_administrative_text;
    }

    public function setLocation_coordinates($location_coordinates) {
        $this->location_coordinates = $location_coordinates;
    }

    public function setLocation_coordinates_latitude($location_coordinates_latitude) {
        $this->location_coordinates_latitude = $location_coordinates_latitude;
    }

    public function setLocation_coordinates_longitude($location_coordinates_longitude) {
        $this->location_coordinates_longitude = $location_coordinates_longitude;
    }

    public function setLocation_coordinates_percision($location_coordinates_percision) {
        $this->location_coordinates_percision = $location_coordinates_percision;
    }

    public function setLocation_gazetteer_entry($location_gazetteer_entry) {
        $this->location_gazetteer_entry = $location_gazetteer_entry;
    }

    public function setLocation_gazetteer_entry_gazetteer_ref($location_gazetteer_entry_gazetteer_ref) {
        $this->location_gazetteer_entry_gazetteer_ref = $location_gazetteer_entry_gazetteer_ref;
    }

    public function setLocation_gazetteer_entry_text($location_gazetteer_entry_text) {
        $this->location_gazetteer_entry_text = $location_gazetteer_entry_text;
    }

    public function setActivity_status($activity_status) {
        $this->activity_status = $activity_status;
    }

    public function setActivity_status_code($activity_status_code) {
        $this->activity_status_code = $activity_status_code;
    }

    public function setActivity_status_xml_lang($activity_status_xml_lang) {
        $this->activity_status_xml_lang = $activity_status_xml_lang;
    }

    public function setActivity_status_text($activity_status_text) {
        $this->activity_status_text = $activity_status_text;
    }
}