## INSTALLATION

Please follow the following steps for installing AidStream.
** The root directory should have 'Allow override on'.
** mod_rewrite should be enabled in apache for the framework to work.

1. Clone the code from the git repository.

2. Create a database and import the sql files in the schema directory of the root folder.
The files to be imported are  schema.sql, codelists.sql, help.sql, secotrdacthree.sql and superadmin.sql file.
schema.sql should be the first file to be imported as it contains the db schema.
    
3. Make a copy of default.application.ini as application.ini in application/configs.
    - Change the db parameters to match your db configuration
    - Aidstream uses subdomain name to determine various environment as
        - aidstream.org , http://localhost/aidstream => PRODUCTION
        - dev.aidstream.org => DEVELOPMENT
        - stage.aidstream.org => STAGING
        - demo.aidstream.org => DEMO
    
    ** PRODUCTION environment is the default environment.

4. Make sure the data/log, data/session folders are writable.(Please create the
folders in case they are missing). The system log file 'zf.iati.log' is written
in the log folder and the session folder contains the session files for different
users.

5. Make sure the public/files/xml , public/files/csv/uploads , public/files/documents
and public/uploads/image are writable (Please create the folders in case they
are missing ).

6. Copy .htaccess_bak to .htaccess in the root folder and in the public folder.
The .htaccess in the root folder is used for removing /public baseurl so can be
used only if required.

7. Zend Framework should be in your php include path. In case zend framework is
not in include path ( e.g you get a reqired file 'Zend/Application.php' not found error),
    - Set up zend framework and add it to include path
    OR
    - Download Zend Framework and place it in library folder.( library/Zend )

** Note that the version of Zend Framework tested with AidStream is 1.12.3

** AidStream does not support ZendFramework 2.

## OPTIONAL

1. For snapshot and organisations page: copy Config.php.bak to Config.php in 
library/Iati/Snapshot folder and provide the correct database credentials in the 
file. Make sure all the folders inside data folder are writable. Execute PHP Script 
'UpdateData.php' using crontab or manually "php UpdateData.php" via unix terminal. 
This will grab all data for the snapshot and organisation page.

2. Clone [aidstream-howto](https://github.com/younginnovations/aidstream-howto), [simplified-aidstream-howto](https://github.com/younginnovations/simplified-aidstream-howto) from their respective repositories to the public folder. Rename the folders to `how-to` and `how-to-simplified` respectively.
