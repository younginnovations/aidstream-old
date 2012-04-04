<?php
/**
 * Zend Framework addition by Iati
 *	http://eliteinformatiker.de/2011/09/02/thumbnails-upload-and-resize-images-with-zend_form_element_file/
 * @category   Iati
 * @package    Iati_Filter
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author     Stefan Koch <cct@stefan-koch.name>
 */

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Resizes a given file and saves the created file
 *
 * @category   Iati
 * @package    Iati_Filter
 */
class Iati_Filter_File_Resize implements Zend_Filter_Interface
{
    protected $_width = null;
    protected $_height = null;
    protected $_keepRatio = true;
    protected $_keepSmaller = true;
    protected $_directory = null;
    protected $_adapter = 'Iati_Filter_File_Resize_Adapter_Gd';

    /**
     * Create a new resize filter with the given options
     *
     * @param Zend_Config|array $options Some options. You may specify: width,
     * height, keepRatio, keepSmaller (do not resize image if it is smaller than
     * expected), directory (save thumbnail to another directory),
     * adapter (the name or an instance of the desired adapter)
     * @return Iati_Filter_File_Resize An instance of this filter
     */
    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } elseif (!is_array($options)) {
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Invalid options argument provided to filter');
        }

        if (!isset($options['width']) && !isset($options['height'])) {
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('At least one of width or height must be defined');
        }

        if (isset($options['width'])) {
            $this->_width = $options['width'];
        }
        if (isset($options['height'])) {
            $this->_height = $options['height'];
        }
        if (isset($options['keepRatio'])) {
            $this->_keepRatio = $options['keepRatio'];
        }
        if (isset($options['keepSmaller'])) {
            $this->_keepSmaller = $options['keepSmaller'];
        }
        if (isset($options['directory'])) {
            $this->_directory = $options['directory'];
        }
        if (isset($options['adapter'])) {
            if ($options['adapter'] instanceof Iati_Filter_File_Resize_Adapter_Abstract) {
                $this->_adapter = $options['adapter'];
            } else {
                $name = $options['adapter'];
                if (substr($name, 0, 33) != 'Iati_Filter_File_Resize_Adapter_') {
                    $name = 'Iati_Filter_File_Resize_Adapter_' . ucfirst(strtolower($name));
                }
                $this->_adapter = $name;
            }
        }

        $this->_prepareAdapter();
    }

    /**
     * Instantiate the adapter if it is not already an instance
     *
     * @return void
     */
    protected function _prepareAdapter()
    {
        if ($this->_adapter instanceof Iati_Filter_File_Resize_Adapter_Abstract) {
            return;
        } else {
            $this->_adapter = new $this->_adapter();
        }
    }

    /**
     * Defined by Zend_Filter_Interface
     *
     * Resizes the file $value according to the defined settings
     *
     * @param  string $value Full path of file to change
     * @return string The filename which has been set, or false when there were errors
     */
    public function filter($value)
    {
        if ($this->_directory) {
            $target = $this->_directory . '/' . basename($value);
        } else {
            $target = $value;
        }

        return $this->_adapter->resize($this->_width, $this->_height,
            $this->_keepRatio, $value, $target, $this->_keepSmaller);
    }
}