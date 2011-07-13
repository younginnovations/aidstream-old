<?php

/**
 * @todo Write test for types
 * Enter description here ...
 * @author bibek
 *
 */
class Iati_Activity_ElementTest extends PHPUnit_Framework_TestCase
{
    public function testCreateElement()
    {
        $element = new Iati_Activity_Element();
        
        // Create a no name element
        $activityElement = $element->createElement('Activity');
        $element->addElement($activityElement);
        
        // Create an element with name
        $activityElement2 = $element->createElement('Activity', 'activity-1');
        $element->addElement($activityElement2);
        
        $actualElements = $element->getElements();
        $this->assertEquals(2, count($actualElements));
        
        $expectedElements = array(
            0 => array(
                'class' => 'Iati_Activity_Element_Activity',
                'name' => null,
                'type' => 'Activity',
            ),
            1 => array(
                'class' => 'Iati_Activity_Element_Activity',
                'name' => 'activity-1',
                'type' => 'Activity',
            ),
        );
        
        foreach ($actualElements as $key => $elementObject) {
            $this->assertEquals($expectedElements[$key]['class'], get_class($elementObject));
            $this->assertEquals($expectedElements[$key]['name'], $elementObject->getName());
            $this->assertEquals($expectedElements[$key]['type'], $elementObject->getType());
        }
        
        // Check get element by name
        $actualElement = $element->getElement('activity-1');
        $this->assertEquals('Iati_Activity_Element_Activity', get_class($actualElement));
        $this->assertEquals('activity-1', $actualElement->getName());
    }
    
    public function testCreateTwoElementsWithSameName()
    {
        $element = new Iati_Activity_Element();
        $activity1 = $element->createElement('Activity', 'activity-1');
        $activity2 = $element->createElement('Activity', 'activity-1');
        
        $element->addElement($activity1);
        $element->addElement($activity2);
        
        $actualElements = $element->getElements();
        
        $this->assertEquals(1, count($actualElements));
        $this->assertEquals('Activity', $actualElements[0]->getType());
        $this->assertEquals('activity-1', $actualElements[0]->getName());
    }
}
