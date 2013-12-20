## Project Description

AidStream is a data entry platform to produce IATI compliant aid information ( xml files)  for various NGOs and INGOs. It also registers the produced files to the IATI registry.

## System Structure

The system is developed using Zend Framework so it uses Zend's MVC approach . Various modules and library files ( under namespace IATI)  are developed as per various system requirements.



## Basic Elements

### IATI Elements

There are two base classes created for the purpose of handling IATI elements

- Iati_Core_BaseElement ( Base-Element ): 
This is the main class that has methods for all the operation on the elements like form generation , CURD operations &  xml generation. This element is the parent class that is extended by all other Element-Classes.

- Iati_Core_BaseForm ( Base-Form ):
This class is extended from Zend_Form and has form operations that is to be performed in all the elements. It is the base class that is extended by all the Form-Classes.

For every IATI Element, a class is created under 'Iati_Aidstream_Element' which defines attributes of the element as properties of the class. Every Element-Class Extends  the Base-Element. Also For every IATI Element-Class a class for form definition is created under  'Iati_Aidstream_Form' corresponding to the element class whose 'getFormDefination' method defines the exact form defination of the element. Every Form-Class Extends the Base-Form. Since the Base-Form extends the Zend_Form, form definition for the element follows rules for Zend_Form defination.

**note: All the classes follow Zend's folder hierarchy and naming convention.**


### XML

'Iati_Core_Xml' class handles the generation of xml. It uses the xml generation method of each Element-Class to generate xml for elements , wraps the xml and produces the required xml.

### Codelist Handler

'Iati_Core_Codelist' is used to fetch codelist values and codes for various element attributes. It stores information about relation between attributes and their respective codelist tables

##System Components:

The basic components of the system are :

- Data Entry
    - Defaults
    - Complete Iati Elements
    - Simplified ( for Nepal )
- Register to IATI Registry
    - Activity/Organisation States
    - Publish Files
    - Register Files
- Users and Permission
- Help ( Tooltips)

### Data Entry

Data Entry is the basic component of AidStream. It includes generation of forms IATI Elements and  performing the CURD operations for the form data.

#### Defaults 

Defaults are used for entering default values that are used by all the activity and organisation data. It includes data for reporting organisation and other activity defaults like flow-type, finance-typ , aid-type etc. Defaults also includes section for entering data related to registry   e.g publisher id & api key of the publisher  and publishing type of the files. 

The Class 'Iati_WEP_AccountDefaultFieldValues' is used for storing default values. The object of the class , with its properties set , is serialized and stored in the database for later use. When default data is required, the serialized object is retrieved , unserialized and its property values are used.
The model 'Model_DefaultFieldValues' is used to fetch default values.

It also includes section for choosing activity elements to be entered (Default Field Groups).
The Class 'Iati_WEP_AccountDisplayFieldGroup' is used for storing default field groups. It's usage is similar to default field values.

The main form used for defaults is 'Form_Wep_EditDefaults'.

#### Complete Iati Elements

In this form of data entry the user can enter all the elements defined by the IATI Standard. It consists of form for both Activity and Organsation Data. It uses library classes based under 'Iati_Aidstream' for form generation and data operations.  It is the default mode of data entry. It uses controllers and models from 'default' module.

#### Simplified ( For Nepal ) 

In this mode data entry for selected IATI elements are done. It only allows to enter Activity data. It uses controller and models from 'simplified' module. All the form definitions are created inside the module's forms directory. It uses 'Simplified_Form_Activity_Default' for form generation. 
The model 'Simplified_Model_Simplified' handles all the operations related to IATI elements used for simplified. The model uses Element-Class for fetching data and for saving data for Result. All other database operations are done by the model itself.

### Register To IATI Registry
AidStream produces xml files that are compliant with the IATI Standard.  AidStream also registers these files directly to IATI Registry in case proper values for publisher id and api key, from the defaults section, are entered. The process of registering to the IATI Registry involves 'Publishing' xml files and 'Registering' them to the IATI Registry.

The model 'Model_RegistryInfo' is used to fetch the publisher id and api key of the client required for publishing.

#### Activity/Organisation States

Publishing Activity/Organisation follows 4 states as Editing , Complete , Checked and Published. Activity/Organisation should go through all these steps before being published and registered to the IATI Registry.
The states of Activity/Organisation are handled using 'Iati_WEP_ActivityState' class. This is a singleton class which stores all the states , their transitions , permissions for states and all functionalities regarding states.

#### Publish Files

Publishing a file refers to generating xml files for Activity or Organisation.  AidStream generates two types of files:

##### Activity Files
Publishing of activity files are handled by 'Iati_WEP_Publish' class. It fetches activities to publish , segments the activities as per segmentation rules  , sends the activities to the 'Iati_Core_Xml' file to generate the xml and updates the databases about the published files. It uses model 'Model_Published' to fetch and update published information in the database.

##### Organisation Files
Publishing of Organisation File is handled by 'Iati_Core_Xml'. The model 'Model_OrganisationPublished' is used to fetch and update published information in the database.

#### Register Files

AidStream registers published files to the IATI Registry using API's provided by the IATI Registry. The Ckan Client Library is used for accessing the api from AidStream. The Library is placed under the namespace 'Ckan'. 
The 'Iati_Registry' class is created to use the Ckan Client to connect AidStream files to the Registry. It generates the required json data and calls the appropriate method of the Ckan Client to register the data to the IATI Registry.
The model 'Model_RegistryPublishedData ' is used to save the information of files published to the registry.

The update to register functionality include the ability to switch between previous CKAN version (1.01) and the new updated CKAN version(1.03).(1.02 is the version number that is to be used in AidStream as the version number 1.02  in AidStream corresponds to CKAN version 1.03) 

The version number can be mentioned in the application.ini config. Register operation is first handled by the model 'Model_Registry' which determines the version to use based on the config file, creates a file object (Iati_Core_Registry_File) and populates its properties. The file object is then passed to the library class 'Iati_Core_Registry'. New classes are created under Iati_Core_Registry_MetadataGenerator for each version i.e 'Iati_Core_Registry_MetadataGenerator_101' and 'Iati_Core_Registry_MetadataGenerator_102'. The classes generate the required JSON for each version. Also a new client file 'Iati_Core_Registry_Client' was created that extends the CKAN client and overrides the methor for package update. The Regitstry class 'Iati_Core_Registry' chooses the data generator and client as per the version provided.
The JSON  data is created, passed to client and pushed to registry. The process of updating the response from registry is handled by the 'Iati_Core_Regitry_File' class itself. 

For publishing organisation data the 'isOrganisationData' property of the file class is checked. If the property is set, it is handled as organisation data file. The process is same as that of the activity file.


### Users And Permissions
AidStream uses various roles and permissions to facilitate the data entry and publishing of files.

#### Users 
A new module 'User' is added to handler user related functionalities. It uses various forms and models under the module for performing user related functionalities like register , edit , delete, create other users etc.
Users are assigned three roles 'superadmin' , 'admin' and 'users' for controlled access.
The 'admin' user has all permissions for the account. The 'users' user has permission as granted by the admin user.

#### Permissions
User permissions are saved using the class ' Iati_WEP_UserPermission'. This class stores the permission assigned to user without the admin role. The object of the class is serialized and saved to database for future use.

#### Superadmin
Superadmin user has the functionality of controlling ( add , delete , disable , enable) all the account. It can also 'masquerade' as and 'admin' user into the account owned by the admin user. It can also select the account type ( normal or simplified). The Superadmin user can also edit help messages.

All the functionalities of the superadmin user are provided using the 'AdminController'. It uses various model from the 'default' module like 'Model_User' etc. 

### Help ( Tooltips )

To provide more information about the different elements during the data entry process a help tooltip is added. 
The tooltip is created using dojo dialog box. Help message for the dialog box is fetched through ajax using getHelpMessageAction of the WepController.
Model "Model_Help' is used to update and fetch help messages for all the help tooltip messages.
