<?php
class Iati_ArrayToXml
{
public function array2xml($array, $xml = null, $rootNodeName = null, $nodeName = null)
    {
        /**
         * RootNodeName and $xml == null happens only for the first time when
         * the function is being called
         */
        $rootNodeName = is_null($rootNodeName)?'root':$rootNodeName;

        if ($xml == null) {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName/>");
        }

        /**
         * Loop through the child elements
         */
        foreach ($array as $key => $subarray) {
            $internalNodeName = is_null($nodeName)?$key:$nodeName;
            if (is_array($subarray)) {

                // create sub childs
                // make sure that value doesn't have ##, in that case do not create child and pass current
                // xml instead, and then also pass along nodeName
                if (in_array('##', array_keys($subarray))) {
                    $this->array2xml($subarray, $xml, null, $key);
                } else {
                    $child = $xml->addChild($internalNodeName);
                    $this->array2xml($subarray, $child);
                }

            } else {
                // The key does not contain array, it can be one of the three possible values
                // ##
                // #attribute
                // simply value
                if ($key == '##') {
                    // do nothing or just continue
                } else if (strpos($key, '#') === 0) {
                    $attrName = substr($key, 1);
                    $attrValue = $subarray;
                    $xml->addAttribute($attrName, $attrValue);
                } else {
                    if(strpos($subarray, '&') > -1){
                        $subarray = str_replace('&', '&amp;', $subarray);
                    }
                    if(strpos($subarray, '<') > -1){
                        $subarray = str_replace('<', '&lt;', $subarray);
                    }
                    if(strpos($subarray, '>') > -1){
                        $subarray = str_replace('>', '&gt;', $subarray);
                    }
                    if(strpos($subarray, "'")> -1){
                        $subarray = str_replace("'", '&apos;', $subarray);
                    }
                    if(strpos($subarray, '"')> -1){
                        $subarray = str_replace('"', '&quot;', $subarray);
                    }

                    $subarray =iconv('windows-1252', 'UTF-8//TRANSLIT', $subarray);
                    $xml->addChild($internalNodeName, $subarray);
                }
            }
        }

        return $xml->asXml();
    }
    
}