<?php
    require_once('Config.php');
    require_once(BASE_PATH . '/Lib/IatiDatasets.php');
    require_once(BASE_PATH . '/Lib/AidstreamConnector.php');
    
    //$oConnector = new AidstreamConnector('root' , 'yipl123' , 'backup');
    $oConnector = new AidstreamConnector();
    $files = $oConnector->getFileUrls();
    
    $oDataset = new IatiDatasets($files);
    $oDataset->updateIatiData();
    exec('php GenerateJson.php');
?>
