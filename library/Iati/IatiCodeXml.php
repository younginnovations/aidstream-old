<?php
class Iati_IatiCodeXml
{

    protected $language;
    protected $type;
    protected $version;
    protected $sourcePath;
    protected $destPath;

    public function setLanguage($lang)
    {
        $this->language = $lang;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setVersion($xmlversion)
    {
        $this->version = $xmlversion;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setSource($sourcepath)
    {
        $this->sourcePath = $sourcepath;
    }

    public function getSource()
    {
        return $this->sourcePath;
    }

    public function setDest($destpath)
    {
        $this->destPath = $destpath;
    }

    public function getDest()
    {
        return $this->destPath;
    }

    public function getLang($path)
    {
        $folder_string = dirname($path).PHP_EOL;
        $foldername = explode('/', $folder_string);
        $count = count($foldername);
        return $foldername[$count-3];
    }

    public function getExtension($filename)
    {
        $path_parts = pathinfo($filename);
        $ext = $path_parts['extension'];
        if($ext == 'csv'){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    public function parseDirectory()
    {
        if ($handle = opendir($this->getSource())) {
            while (false !== ($each = readdir($handle))) {
                if ($each != "." && $each != "..") {
                    $folderPath = $this->getSource().$each;
                    if (is_dir($folderPath)) {
                        $folders[]  = $folderPath;
                    }
                }
            }
            closedir($handle);
        }
        sort($folders);
        $iatiDirectory = array('organisation', 'sector', 'standard');
        //                                print_r($folders); exit();//gives language eg "en", "fr"
        foreach($folders as $innerfolder){
            if ($handle1 = opendir($innerfolder)) {
                while (false !== ($inner = readdir($handle1))) {
                    if ($inner != "." && $inner != "..") {
                        $i = $innerfolder.'/'.$inner;
                        if($inner != 'codes'){
                            continue;
                        }
                        //                         print_r($inner); exit(); //inside language folder eg "codes"
                        if ($handle2 = opendir($i)) {
                            while (false !== ($e = readdir($handle2))) {
                                if ($e != "." && $e!= "..") {
                                    if(in_array($e, $iatiDirectory)){
                                        $files[]  = $i.'/'.$e;
                                    }
                                }
                            }
                            closedir($handle2);
                        }
                        // $innerdirectory[] = $inner;
                    }
                }
                closedir($handle1);
            }
        }//gives the inner directory eg "standard", "organisation", "sector"
        sort($files);
        return $files;
    }


    /**
     *
     */
    public function getDirectoryFiles()
    {
        $path = $this->getSource();
        $foldername = explode('/', $path);
        $count =  count($foldername);
        $folder = $foldername[$count-1];
        $directoryFiles = array();
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if($this->getExtension($file) == TRUE){
                        $directoryFiles[] = $path."/".$file;
                        //  $directoryFiles['lang'][] = $this->getLang($path);
                    }
                }
            }
            closedir($handle);
        }
        sort($directoryFiles);
        return $directoryFiles;
        /* $final_result = $this->buildFromFiles($directoryFiles);
         $this->writeToFile($final_result);*/
        //        return $final_result;
    }
     
    public function buildFromFiles(){
        $arrayFiles = $this->getDirectoryFiles();
        //        sort($arrayFiles);
        $lang = $this->getLang($arrayFiles[0]);
        $date = date('Y-m-d');

        $file_content_array = array(
            '#version' => $this->version,
            '#xml:lang' => $lang,
            '#publish-date' => $date,
            'iati-codelist'=> array( // Set iaticodelist as multiple interative code
                '##' => 1,
        )
        );

        foreach($arrayFiles as $eachFile){
            $path_parts = pathinfo($eachFile);
            $filename = $path_parts['filename'];
            $modified_date = date ("Y-m-d", filemtime($eachFile));
            if(strpos($filename, 'organisation') > -1){
                // @todo call the getFileContentForOrganisation function
                $file_content_array['iati-codelist'][] = array(
                                                '#name'=>$filename,
                                                '#updated'=>$modified_date,
                                                'code'=>    $this->getFileContentForOrganisation($eachFile)//individual file
                );

            }
            else{
                $file_content_array['iati-codelist'][] = array(
                                                '#name'=>$filename,
                                                '#updated'=>$modified_date,
                                                'code'=>    $this->getFileContent($eachFile)//individual file
                );
            }
        }
        $xml = $this->array2xml($file_content_array,null,'iati-codelists');
        //        return $xml;
        $this->writeToFile($xml);
    }

    /**
     * Build array structure from a csv file
     *
     * Rules followed when converting csv into array
     * - The first row will contain column headers and should be skipped
     * - The first value on each subsequent row is the {code}
     * - Rows that do not contain a first value should be skipped.
     * - The second *NON EMPTY* value on each row contains the {first description}
     * - Additional {descriptions} may be found in some files in the third and fourth values
     * - The xml for a row has the following format
     *   <code id =”{code}”>
     *       <desc1>{first_description}</desc1>
     *       <desc2>{second_description}</desc2>
     *       <desc3>{third_description}</desc3>
     *   </code>
     * - Elements containing no value should not be reported.
     * - If empty descriptions are found between fields, the consequent non - empty field is taken as the next description
     * - Values are trimmed for preceding and trailing spaces.
     *
     * @param string $file full path of the file
     * @return array
     */
    public function getFileContent($file)
    {
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                //                $data = array_filter($data, 'trim');
                $num = count($data);
                $data[0] = trim($data[0]);
                if (!$data[0]) {
                    continue;
                }

                for ($c=0; $c < $num; $c++) {
                    $data[$c] = trim($data[$c]);
                    if($data[$c]){
                        $new_array[$row][] = $data[$c];
                    }
                }

                $row++;
            }
            fclose($handle);
            $file_array = array();
            $array_count = count($new_array);
            $file_array = array('##' =>1,);
            for ($num = 1; $num< $array_count; $num++){

                // Skip the code deleted section

                /*if(strtolower($new_array[$num][1]) == 'code deleted - do not use' ||
                 strtolower($new_array[$num][1]) == 'code deleted – do not use'){
                 continue;
                 }*/


                $cn = count($new_array[$num]);
                $codeid = $new_array[$num][0];
                $code = (string) $codeid;

                $file_subarray = array('#id' => $new_array[$num][0]);
                for ($c1 = 1; $c1 < $cn; $c1++){
                    if(!$new_array[$num][$c1]){
                        continue;
                    }
                    /*if($c1 ==1){
                     if(strtolower($new_array[$num][1]) == 'code deleted - do not use' ||
                     strtolower($new_array[$num][1]) == 'code deleted – do not use'){
                     continue;
                     }
                     }*/

                    $index = 'desc'.$c1;
                    $file_subarray[$index] = $new_array[$num][$c1];
                }
                $file_array[] = $file_subarray;
            }
            return $file_array;
        }
    }

    public function getFileContentForOrganisation($file)
    {
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                //                $data = array_filter($data, 'trim');
                $num = count($data);
                $data[0] = trim($data[0]);
                if (!$data[0]) {
                    continue;
                }

                for ($c=0; $c < $num; $c++) {
                    $data[$c] = trim($data[$c]);
                   // if($data[$c]){
                        if($row == 0){
                            $heading[] = $data[$c];
                        }
                        $new_array[$row][] = $data[$c];
                    //}
                }
                $row++;
            }
            fclose($handle);
            $file_array = array();
            $array_count = count($new_array);
            $file_array = array('##' =>1,);
            for ($num = 1; $num< $array_count; $num++){

                $cn = count($new_array[$num]);
                $codeid = $new_array[$num][0];
                $code = (string) $codeid;

                $file_subarray = array('#id' => $new_array[$num][0]);
                for ($c1 = 1; $c1 < $cn; $c1++){
                    if(empty($new_array[$num][$c1])){
                        continue;
                    }
                    
                    $index = $heading[$c1];
                    $file_subarray[$index] = $new_array[$num][$c1];
                }
                $file_array[] = $file_subarray;
            }
            return $file_array;
        }
    }

    public function writeToFile($obj)
    {
        $destination = $this->getDest();
        $fp = fopen($destination, 'w');
        fwrite($fp, $obj);
        fclose($fp);
    }

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