<?
 //use same session from aidstream;
    DEFINE('SESSION_PATH' , dirname(__FILE__)."/../../data/session");

    //ini_set('error_reporting' , E_ALL);
    ini_set('session.save_path' ,SESSION_PATH);
    session_name('IATI');
    session_start();
?>