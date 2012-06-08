<?php
class Zend_View_Helper_L extends Zend_View_Helper_Abstract
{
    public function l($text, $url, $options = array())
    {
        // Merge in defaults.
        $options += array(
            'attributes' => array(),
            'html' => FALSE,
        );
        
        // Remove all HTML and PHP tags from a tooltip. For best performance, we act only
        // if a quick strpos() pre-check gave a suspicion (because strip_tags() is expensive).
        if (isset($options['attributes']['title']) && strpos($options['attributes']['title'], '<') !== FALSE) {
            $options['attributes']['title'] = strip_tags($options['attributes']['title']);
        }

        $fc = Zend_Controller_Front::getInstance();
        $baseUrl = $fc->getBaseUrl();
        
        return '<a href="'. $baseUrl . '/' . $url .'"'. $this->view->common()->htmlAttributes($options['attributes']) .'>'. ($options['html'] ? $text : $this->view->common()->checkPlain($text)) .'</a>';
        
        //$output = '<a href="' . $baseUrl . '/' . $url . '">' . $text . '</a>';
        //return $output;
    }
}