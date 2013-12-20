<?php

class Iati_ElementSorter
{    
    public static function sortElementsData($data , $primarySort , $secSort )
    {
        $primaryElement = array_pop(array_keys($primarySort));
        $primaryAtrrib = $primarySort[$primaryElement];
        $secElement = array_pop(array_keys($secSort));
        $secAttrib = $secSort[$secElement];
        foreach ($data as $key => $row) {
            $primary[$key]  = $row[$primaryElement][$primaryAtrrib];
            $secondary[$key] = $row[$secElement][$secAttrib];
        }
        
        array_multisort($primary, SORT_DESC, $secondary, SORT_DESC, $data);
        return $data;
    }    
}