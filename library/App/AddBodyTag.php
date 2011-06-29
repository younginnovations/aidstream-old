<?php
/**
 * A body tag
 *
 * @author David Weinraub <david@papayasoft.com>
 */
class App_AddBodyTag extends Zend_View_Helper_Placeholder_Container_Standalone
{
    public function bodyTag()
    {
        return $this;
    }

    public function toString()
    {
        $tag = array();
        $tag[] = '<body';

        $storage = $this->getContainer();

        if (count($storage) > 0){
            $classes = array();
            foreach ($storage as $class){
                $classes[] = $class;
            }
            $tag[] = sprintf(' class="%s"', implode(' ', $classes));
        }
        $tag[] = '>';
        return implode('', $tag);
    }

    public function addClass($class)
    {
        $this->getContainer()->append($class);
        return $this;
    }
}
