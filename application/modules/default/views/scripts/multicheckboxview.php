<?php
    $elem = $this->element;
    $values = $elem->getValue(true);
    
    $checked = array();
    foreach($elem->getMultiOptions() as $option =>$value){
        $checked[] = $option;
    }
  
    $titleArray = array('Identification','Basic Activity Information','Participating Organizations','Geopolitical Information','Classifications','Financial','Related Documents','Relations','Performance');            
    $elements = array(
                                array('other_activity_identifier','recipient_country'),
                                array('title','description','activity_status','activity_date','contact_info'),
                                array ('participating_org'),
                                array('recipient_country','recipient_region','location'),
                                array('sector','policy_marker','collaboration_type','default_flow_type','default_finance_type','default_aid_type','default_tied_status'),
                                array ('budget','planned_disbursement','transaction'),
                                array ('document_link','activity_website'),
                                array ('related_activity'),
                                array ('conditions','result'),
                            );
        $i = '0';
        foreach($elements as $key){            
            $result = array_intersect( $key , $checked );          
            $array[$titleArray[$i]] = $result;                 
            $i++;
        }
       
        
?>

<?php foreach($array as $option => $value) {?>
    <?php echo $option ?><br/>
    <?php foreach($value as $value) {?>
    <input type="checkbox" id = "<?php echo $value ?>" name =  "default_fields[]" value ="<?php echo $value ?>" "<?php if(in_array($value , $values)){ echo ' checked="checked"'; }?>/>
        <?php echo ucwords(str_replace("_", " ", $value)) ?><br/>
    <?php } ?>
<?php } ?>