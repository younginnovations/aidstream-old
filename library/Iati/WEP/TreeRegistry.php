<?php
/**
 * @author Diwaker Ghimire
 * @license BSD
 * @version 0.0.2
 *
 * A Tree Based Registry Class
 */

class Iati_WEP_TreeRegistry {
    
    private static $_tree;
    private static $_objects = array();
    private static $_instance;
    
    private function __construct () {}
    
    private function __clone () {}
    
    public static function getInstance () {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self;
            self::createTree('activity');
        }
        return self::$_instance;
    }
    
    /**
     * Initialises the tree
     * @param 
     * @param 
     */
    public function createTree ($root='root') {
        $str = sprintf("<%s></%s>", $root, $root);
        self::$_tree = new SimpleXMLElement($str);
    }
    
    /**
     * Add a node to tree
     * @param $object Object to add to tree
     * @param $parent Parent Object to which current object to be child of
     */
    public function addNode ($object, $parent=NULL) {
        if (array_key_exists($this->getNodeId($object), self::$_objects)) {
            return;
        }
        $parentNode = ($parent == NULL) ? self::$_tree :
                                            $this->selectNode($parent);
        
        $node = $parentNode->addChild(get_class($object));
        $nodeId = $this->getNodeId($object);
        $node->addAttribute('id', $nodeId);
        self::$_objects[$nodeId] = $object;
//        print_r(self::$_objects);exit;
    }
    
    /**
     * Get id for this node
     * @param $object Object
     * @return $id Object Id
     */
    public function getNodeId ($object) {
        return spl_object_hash($object);
    }
    
    /**
     * return xml path
     * @param $object
     * @return SimpleXml path object
     */
    public function selectNode ($object) {
        $xpath = sprintf("//%s[@id='%s']", get_class($object),
                                            $this->getNodeId($object));
        $path = self::$_tree->xpath($xpath);
        // this is because a single object can only belong to
        // one and only one path
        //print_r($path);
        return $path[0];
    }
    
    /**
     * Returns all child nodes of a current object
     * @param $obj Object
     * @return $childs List of Objects
     *
     */
    public function getChildNodes ($obj) {
        $childs = $this->selectNode($obj)->children();
        $objects = array();
        foreach ($childs as $child) {
            $attr = $child->attributes();
            $attr = (string)$attr['id'];
            array_push($objects, self::$_objects[$attr]);
        }
        return $objects;
    }
    
    
    /**
     * test function
     *
     */
    public function xml () {
        return self::$_tree->asXML();
    }
}

/*class TestA {
    
    public $apple = 'Apple';
    
    public function __construct () {
        
    }
}

class TestB {
    
    public $apple = 'NoApple';
    
    public function __construct () {
        
    }
}

$test1 = TreeRegistry::getInstance();
$test1->createTree('fruits');

$a = new TestA();
$aa = new TestA();
$aaa = new TestA();
$b = new TestB();
$bb = new TestB();
$bbb = new TestB();

$test1->addNode($a);
//var_dump($test1->xml());
$test1->addNode($b, $a);
$test1->addNode($bb, $a);
$test1->addNode($bbb, $a);
$test1->addNode($aa, $b);
$test1->addNode($aaa, $b);
var_dump($test1->xml());

$test1->getChildNodes($b);*/
?>