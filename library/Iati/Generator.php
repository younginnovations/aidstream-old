<?php
class Iati_Generator
{
    /**
     * Gets the names of the files that the folder has
     * eg: /var/www/iati_masters/en/codes/organisation/.
     * @param $path
     * @return array
     */

    /*
     * to get only file names use basename("organisation_identifier_bilateral.csv", ".csv").PHP_EOL;
     * you can also use "pathinfo("path to file")
     * gives the output in array
     * eg ['basename'], ['dirname'], ['extension'], ['filename']
     */

    public function getDirectoryFiles($path)
    {
        //$path_parts = pathinfo($filename);
         //$folder_string = dirname($path).PHP_EOL;
        $foldername = explode('/', $path);
        $count =  count($foldername);
        $folder = $foldername[$count-1];
        $directoryFiles = array();
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    /*echo "$file\n";*/
                    if(Iati_Generator::getExtension($file) == TRUE){
                        $directoryFiles[$folder][] = $path."/".$file;
                      //  $directoryFiles['lang'][] = $this->getLang($path);
                    }
                }
            }
            closedir($handle);
        }
        return $directoryFiles;

    }


    /**
     * Gets the extension of the filename
     *
     * @param $filename
     * $filename can be just file name or path to the file
     * @return boolean
     */
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

    /**
     * Reads the csv file and puts the content in the array row wise
     * @param $file   (path to the file, eg "/var/www/organisation_identifier_bilateral.csv")
     * @return array of the content
     */
    public function getFileContent($file)
    {
        $path_parts = pathinfo($file);
        $filename = $path_parts['filename'];
        
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                $num = count($data);
                $row++;
                for ($c=0; $c < $num; $c++) {
                    if($data[$c] != ''){
                        $new_array[$row][] = $data[$c];
                    }
                }
            }
            fclose($handle);
            /* print "<pre>";
             print_r ($new_array);*/
        $file_array = array();
        $array_count = count($new_array);
        for($num = 1; $num< $array_count; $num++){
            $cn = count($new_array[$num]);
            for($n = 1; $n<$cn; $n++){
                $code = (string)"codeid".$new_array[$num][0];
                //print $code; print "<br>";
                $file_array[$filename][(string) $code]['desc'.$n]=utf8_encode($new_array[$num][$n]);// print "<br>";
                //$file_array[$filename][(string) $code]['desc'.$n]=$new_array[$num][$n];

            }

        }//exit();
           // return $new_array;
           return $file_array;
            /*$formatted_array = $this->getFormattedArray($new_array);
            return $formatted_array;*/

        }
    }

    public function getFormattedArray($new_array)
    {
        $filename = "organisation_identifier_multilateral";
        $file_array = array();
        $array_count = count($new_array);
        for($num = 1; $num< $array_count; $num++){
            $cn = count($new_array[$num]);
            for($n = 1; $n<$cn; $n++){
                $code = (string)"codeid".$new_array[$num][0];
                //print $code; print "<br>";
                $file_array[$filename][(string) $code]['desc'.$n]=$new_array[$num][$n];// print "<br>";
                //$file_array[$filename][(string) $code]['desc'.$n]=$new_array[$num][$n];

            }

        }//exit();
     //   print "<pre>";
       // print_r($file_array);exit();
       return $file_array; 
    }


    /**
     * Gets the folder name eg: en, fr or es
     * @param $path
     * @return string
     */

    public function getLang($path)
    {
        //  $p = dirname("/var/www/iati_masters/en/codes/organisation/organisation_identifier_bilateral.csv").PHP_EOL;
        $folder_string = dirname($path).PHP_EOL;
        $foldername = explode('/', $folder_string);
        $count = count($foldername);
        /*print $count."<br>";
         var_dump($foldername);
         print "<br>". $foldername[$count-3];*/
        return $foldername[$count-3];
    }


    public function getVersion($path)
    {

    }


}