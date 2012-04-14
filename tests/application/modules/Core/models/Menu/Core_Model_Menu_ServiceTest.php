<?php

//require_once dirname(__FILE__) . '/../../../../../../application/modules/Content/models/Menu/Service.php';

/**
 * Test class for Core_Model_Menu_Service.
 * Generated by PHPUnit on 2011-06-22 at 08:00:46.
 */
class Core_Model_Menu_ServiceTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Content_Model_Category_Service
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Core_Model_Menu_Service();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        $this->object->deleteAll();
    }

    public function testGetMapper() {

        $this->assertInstanceOf('Core_Model_Menu_MapperInterface', $this->object->getMapper());
    }

    /**
     * @todo Implement testSetMapper().
     */
    public function testSetMapper() {
        
        $mapper = new Core_Model_Menu_MapperDbTable();
         $this->object->setMapper($mapper);
        
        $this->assertInstanceOf('Core_Model_Menu_MapperInterface', $this->object->getMapper());
        $this->assertEquals($mapper, $this->object->getMapper());
        //$this->ass
    }

    private function createValidMenu() {
        $menu = new Core_Model_Menu();
        $menu->type = Core_Model_Menu::SYSTEM;
        $menu->package = 'testPackage';
        $menu->title = 'testTitle';
        $menu->visible = true;

        $result = $this->object->create($menu);

        $this->assertInstanceOf('Core_Model_Menu_Interface', $result);
        return $result;
    }
    
    public function testCreateNullTypeMenu() {
        $menu = new Core_Model_Menu();
        $menu->type = null;
        $menu->package = 'testPackage';
        $menu->title = 'testTitle';
        $menu->visible = true;
        $result = $this->object->create($menu);
        $this->assertInstanceOf('Core_Model_Menu_Interface', $result);
    }
    
    public function testCreateInvalidMenu() {
        $menu = new Core_Model_Menu();
        $menu->type = null;
        //$menu->title = 'testTitle';
        $menu->package = 'testPackage';
        $menu->visible = true;
        $this->setExpectedException('Exception');
        $result = $this->object->create($menu);
    } 
    
    public function testCreateValidFromObject() {

        $result = $this->createValidMenu();

        $this->assertNotNull($result->id);
        $this->assertEquals(Core_Model_Menu::SYSTEM, $result->type);
        $this->assertEquals('testPackage', $result->package);
        $this->assertEquals('testTitle', $result->title);
        $this->assertEquals(true, $result->visible);
    }
    
    public function testCreateFromArray() {

        $menu = array(
        'type' => Core_Model_Menu::SYSTEM,
        'package' => 'testPackage',
        'title' => 'testTitle',
        'visible' => false);

        $result = $this->object->create($menu);

        $this->assertInstanceOf('Core_Model_Menu_Interface', $result);

        $this->assertNotNull($result->id);
        $this->assertEquals(Core_Model_Menu::SYSTEM, $result->type);
        $this->assertEquals('testPackage', $result->package);
        $this->assertEquals('testTitle', $result->title);
        $this->assertEquals(false, $result->visible);
    }

    public function testCreateException() {
        $this->setExpectedException('InvalidArgumentException');

        $badObject = new StdClass();
        $create = $this->object->create($badObject);
    }

    public function testGetObjectById() {

        //setup
        $menu = $this->createValidMenu();

        //test getObject
        $result = $this->object->getObjectById($menu->id);
        
        $this->assertInstanceOf('Core_Model_Menu_Interface', $result);

        $this->assertEquals($menu->id, $result->id);
        $this->assertEquals(Core_Model_Menu::SYSTEM, $result->type);
        $this->assertEquals('testPackage', $result->package);
        $this->assertEquals('testTitle', $result->title);
        $this->assertEquals(true, $result->visible);
    }

    public function testUpdateFromObject() {

        //setup
        $menu = $this->createValidMenu();

        //update values
        $menu->type = Core_Model_Menu::TEMPLATE;
        $menu->package = 'testPackage2';
        $menu->title = 'testTitle2';
        $menu->visible = false;

        $result = $this->object->update($menu);

        $this->assertInstanceOf('Core_Model_Menu_Interface', $result);

        $this->assertEquals($menu->id, $result->id);
        $this->assertEquals(Core_Model_Menu::TEMPLATE, $result->type);
        $this->assertEquals('testPackage2', $result->package);
        $this->assertEquals('testTitle2', $result->title);
        $this->assertEquals(false, $result->visible);
    }
    
    public function testUpdateFromArray() {

        //setup
        $menu = $this->createValidMenu();

        $array = $menu->toArray();
        
        //update values
        $array['type'] = Core_Model_Menu::TEMPLATE;
        $array['package'] = 'testPackage2';
        $array['title'] = 'testTitle2';
        $array['visible'] = false;

        $result = $this->object->update($array);

        $this->assertInstanceOf('Core_Model_Menu_Interface', $result);

        $this->assertEquals($menu->id, $result->id);
        $this->assertEquals(Core_Model_Menu::TEMPLATE, $result->type);
        $this->assertEquals('testPackage2', $result->package);
        $this->assertEquals('testTitle2', $result->title);
        $this->assertEquals(false, $result->visible);
    }
    

    public function testUpdateException() {
        $this->setExpectedException('InvalidArgumentException');

        $badObject = new StdClass();
        $create = $this->object->update($badObject);
    }

    public function testDeleteObject() {

        //setup
        $menu = $this->createValidMenu();

        //test delete
        $this->object->delete($menu);

        //verify that it was deleted
        $this->setExpectedException('NotFoundException');
        $result = $this->object->getObjectById($menu->id);
    }

    public function testDeleteId() {

        //setup
        $menu = $this->createValidMenu();
       // $this->fail("id: ".$menu->id);
        $this->object->delete((int)$menu->id);
        
        $this->setExpectedException('NotFoundException');
        $result = $this->object->getObjectById($menu->id); 
    }
    
    public function testDeleteArray() {

        //setup
        $menu = $this->createValidMenu();
       // $this->fail("id: ".$menu->id);
        $this->object->delete($menu->toArray());
        
        $this->setExpectedException('NotFoundException');
        $result = $this->object->getObjectById($menu->id); 
    }

    public function testDeleteException() {
        $this->setExpectedException('InvalidArgumentException');

        $badObject = new StdClass();
        $create = $this->object->delete($badObject);
    }

    public function testDeleteAll() {
//        $this->object->deleteAll();
    }

}