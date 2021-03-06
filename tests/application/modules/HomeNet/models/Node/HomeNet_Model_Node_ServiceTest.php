<?php

/**
 * Test class for HomeNet_Model_Node_Service.
 * Generated by PHPUnit on 2011-11-21 at 16:35:57.
 */
class HomeNet_Model_Node_ServiceTest extends PHPUnit_Framework_TestCase {

    /**
     * @var HomeNet_Model_Node_Service
     */
    protected $service;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->service = new HomeNet_Model_Node_Service(1);
        $this->service->deleteAll();
        
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
        $service = new HomeNet_Model_NodeModel_Service;
        $service->deleteAll();
    }
    
     private function _fillObject($object, $seed = 0) {
        $data = $this->_getTestData($seed);
        foreach ($data as $key => $value) {
            $object->$key = $value;
        }
        return $object;
    }
    private function _fillArray($array, $seed = 0) {
        if(is_object($array)){
            $array = $array->toArray();
        }
        
        return array_merge($array, $this->_getTestData($seed));
    }
    
    private function _getTestData($seed = 0) {
        
          //create node model
        $array = array('type' => HomeNet_Model_Node::SENSOR,
            'status' => HomeNet_Model_ComponentModel::LIVE,
            'plugin' => 'Jeenode',
            'network_types' => array(1),
            'name' => 'testModel',
            'description' => 'test description',
            'image' => 'test.jpg',
            'max_devices' => 4,
            'settings' => array('key' => 'value'));
        $service = new HomeNet_Model_NodeModel_Service;
        $model = $service->create($array);
        
        $this->service->setHouseId(4 + $seed);

        return $array = array(
            'status' => HomeNet_Model_Node::STATUS_LIVE,
            'address' => 1 + $seed,
            'networks'=>array(1=>'192.168.1.'.$seed),
            'model' => $model->id,
            'uplink' => 1 + $seed,
            'house' => $this->service->getHouseId(),
            'room' => 5 + $seed,
            'description' => 'test description' . $seed,
            'settings' => array('key' => 'value' . $seed));
    }
    
    private function _createValidObject($seed = 0) {
        $object = new HomeNet_Model_Node();
        $object = $this->_fillObject($object, $seed);
  
        $result = $this->service->create($object);

        $this->assertInstanceOf('HomeNet_Model_Node_Interface', $result);
        return $result;
    }
    
    private function _validateResult($result, $seed = 0){
        
        $this->assertInstanceOf('HomeNet_Model_Node_Interface', $result);
        $this->assertNotNull($result->id);
        $this->assertEquals(1+$seed, $result->address);
      //  $this->assertEquals(2, $result->model);
        $this->assertEquals('192.168.1.'.$seed, $result->networks[1]);
        $this->assertEquals(1+$seed, $result->uplink);
        $this->assertEquals(4+$seed, $result->house);
        $this->assertEquals(5+$seed, $result->room);
        $this->assertEquals('test description'.$seed, $result->description);
        $this->assertTrue(is_array($result->settings));
        $this->assertEquals('value'.$seed, $result->settings['key']);
    }

    

//$this->service->getMapper()///////////////////////////////////////////////////    
    public function testGetMapper() {
       $this->assertInstanceOf('HomeNet_Model_Node_MapperInterface', $this->service->getMapper());
    }

//$this->service->setMapper($mapper)////////////////////////////////////////////
    public function testSetMapper() {
        $mapper = new HomeNet_Model_Node_MapperDbTable();
         $this->service->setMapper($mapper);
        
        $this->assertInstanceOf('HomeNet_Model_Node_MapperInterface', $this->service->getMapper());
        $this->assertEquals($mapper, $this->service->getMapper());
    }
//$this->service->getObjectsByNetwork($network)////////////////////////////////////////////  
     public function testGetObjectsByNetwork_valid() {
        $object = $this->_createValidObject();
        $result = $this->service->getObjectsByNetwork(1);

         $this->_validateResult($result[0]);
    }
    
     public function testGetObjectsByNetwork_null() {
        $this->setExpectedException('InvalidArgumentException');
        $this->service->getObjectsByNetwork(null);

    }
    
//$this->service->getObjectByNetworkAddress($network, $address)////////////////////////////////////////////
    public function testGetObjectByNetworkAddress_valid() {
        $object = $this->_createValidObject();
         $result = $this->service->getObjectByNetworkAddress(1, $object->networks[1]);

          $this->_validateResult($result);
    }
    
    public function testGetObjectByNetworkAddress_null() {
$this->setExpectedException('InvalidArgumentException');
         $this->service->getObjectByNetworkAddress(null, 'test');
    }
    
    
//$this->service->getObjectById($id)////////////////////////////////////////////
   public function testGetObjectById_valid() {
        $object = $this->_createValidObject();
        
        $result = $this->service->getObjectById($object->id);
        
        $this->_validateResult($result);
    }

    public function testGetObjectById_invalid() {
        $this->setExpectedException('NotFoundException');
        $this->service->getObjectById(1000);
    }

    public function testGetObjectById_null() {
        $this->setExpectedException('InvalidArgumentException');
        $this->service->getObjectById(null);
    }

//$this->service->getObject()///////////////////////////////////////////////////
    public function testGetObjects_valid() {
        $object = $this->_createValidObject();
        
        $results = $this->service->getObjects();

        $this->assertEquals(1, count($results));
        $this->_validateResult($results[0]);
    }
    
//$this->service->getTrashedObjectsByHouse($house)//////////////////////////////
    public function testGetTrashedObjects_valid() {
        $object = $this->_createValidObject();
        $results = $this->service->getTrashedObjects();
        $this->assertEquals(0, count($results));
       // $this->_validateResult($results[0]);
    }
    
//$this->service->getObjectByRoom($room)////////////////////////////////////////
    public function testGetObjectsByRoom_valid() {
        $object = $this->_createValidObject();
        $results = $this->service->getObjectsByRoom($object->room);
        $this->assertEquals(1, count($results));
        $this->_validateResult($results[0]);
    }
    
    public function testGetObjectsByRoom_null() {
        $this->setExpectedException('InvalidArgumentException');
        $results = $this->service->getObjectsByRoom(null);
    }
    
//$this->service->getObjectByAddress($address)//////////////////////////////////
    public function testGetObjectByAddress_valid() {
        $object = $this->_createValidObject();
        $result = $this->service->getObjectByAddress($object->address);
        
        $this->_validateResult($result);
    }
    
    public function testGetObjectByAddress_null() {
        $this->setExpectedException('InvalidArgumentException');
        $result = $this->service->getObjectByAddress(null);
    }

    public function testGetObjectByAddress_notFound() {
        $this->setExpectedException('NotFoundException');
        $result = $this->service->getObjectByAddress(100);
    }

//$this->service->getNextAddressByHouse($house)/////////////////////////////////
    public function testGetNextAddress_valid() {
        $object = $this->_createValidObject();
        $result = $this->service->getNextAddress();

        $this->assertGreaterThan($object->address, $result);
    }
//$this->service->getObjectsByType($type)///////////////////////////////////////
    public function testGetObjectsByType_valid() {
        $object = $this->_createValidObject();
        
        $results = $this->service->getObjectsByType(HomeNet_Model_Node::SENSOR);
        $this->assertEquals(1, count($results));
        $this->_validateResult($results[0]);
    }

     public function testGetObjectsByHouseType_null() {
        $this->setExpectedException('InvalidArgumentException');
        $results = $this->service->getObjectsByType(null);
    }
//$this->service->getUplinks()////////////////////////////////////////////////////
    /**
     * @todo create internet node to test against
     */
    public function testGetUplinks_valid() {
        $object = $this->_createValidObject();
        
        $results = $this->service->getUplinks();
        $this->assertEquals(0, count($results));
    }

//$this->service->newObjectFromModel($model)////////////////////////////////////
    public function testNewObjectByModel_valid() {
        $object = $this->_createValidObject();
        $result = $this->service->newObjectFromModel($object->model);
        
        $this->assertInstanceOf('HomeNet_Model_Node_Abstract', $result);
    }
//    public function testNewObjectByModel_missingPlugin() {
//        $object = $this->_createValidObject();
//        $object->plugin = null;
//        $this->setExpectedException('InvalidArgumentException');
//        $result = $this->service->newObjectFromModel($object->model);
//
//    }
//    public function testNewObjectByModel_badPlugin() {
//        $this->setExpectedException('InvalidArgumentException');
//        $result = $this->service->newObjectFromModel(null);
//    }
    
//$this->service->setStatusToDeleted($object)///////////////////////////////////
    public function testTrash(){
        $object = $this->_createValidObject();
        $result = $this->service->trash($object);
        $this->assertEquals(HomeNet_Model_Node::STATUS_TRASHED, $result->status);
    }
    
//$this->service->setStatusToLive($object)//////////////////////////////////////
    public function testUntrash(){
        $object = $this->_createValidObject();
        $object->status = HomeNet_Model_Node::STATUS_TRASHED;
        $result = $this->service->untrash($object);
        $this->assertEquals(HomeNet_Model_Node::STATUS_LIVE, $result->status);
        
    }
    
//$this->service->create($mixed)////////////////////////////////////////////////
    public function testCreate_validObject() {
        $result = $this->_createValidObject();

        $this->_validateResult($result);
    }

    public function testCreate_validArray() {
        $array = $this->_getTestData();
        
        $result = $this->service->create($array);

        $this->_validateResult($result);
    }

    public function testCreate_invalidObject() {
        $this->setExpectedException('InvalidArgumentException');

        $badObject = new StdClass();
        $this->service->create($badObject);
    }
    
//$this->service->update($mixed)////////////////////////////////////////////////
    public function testUpdate_validObject() {
        //setup
        $object = $this->_createValidObject();

        //update values
        $object = $this->_fillObject($object, 1);

        $result = $this->service->update($object);

        $this->_validateResult($result, 1);
    }

    public function testUpdate_validArray() {
        //setup
        $object = $this->_createValidObject();
        $array = $object->toArray();

        //update values
        $array = $this->_fillArray($array, 1);

        $result = $this->service->update($array);

        $this->_validateResult($result, 1);
    }

    public function testUpdate_invalidObject() {
        $this->setExpectedException('InvalidArgumentException');

        $badObject = new StdClass();
        $create = $this->service->update($badObject);
    }

//$this->service->delete($mixed)////////////////////////////////////////////////    
    public function testDelete_validObject() {
        //setup
        $object = $this->_createValidObject();

        //test delete
        $this->service->delete($object);

        //verify that it was deleted
        $this->setExpectedException('NotFoundException');
        $result = $this->service->getObjectById($object->id);
    }

    public function testDelete_validArray() {
        //setup
        $object = $this->_createValidObject();

        //test delete
        $this->service->delete($object->toArray());

        //verify that it was deleted
        $this->setExpectedException('NotFoundException');
        $result = $this->service->getObjectById($object->id);
    }

    public function testDelete_validId() {
        //setup
        $object = $this->_createValidObject();

        //test delete
        $this->service->delete($object->id);

        //verify that it was deleted
        $this->setExpectedException('NotFoundException');
        $result = $this->service->getObjectById($object->id);
    }

    public function testDelete_invalidObject() {
        $this->setExpectedException('InvalidArgumentException');

        $badObject = new StdClass();
        $create = $this->service->delete($badObject);
    }
    
 //$this->newObjectFromModel(16)////////////////////////////////////////////////    
    //this should test that the devices properly cascade
    public function testFullStack() {
        $homenetInstaller = new HomeNet_Installer();
        $homenetInstaller->installTest(array('house', 'room'));
       // $homenetInstaller->installOptionalContent($homenetInstaller->getOptionalContent());


        $object = $this->service->newObjectFromModel(13); //jeelink
        $object->address = 1;
        $object->house = $homenetInstaller->house->id;
        $object->room = $homenetInstaller->room->id;
        //$result->
        $result = $this->service->create($object);

        $this->assertInstanceOf('HomeNet_Model_Node_Abstract', $result);
        $this->assertEquals(1, $result->address);
        $this->assertEquals(13, $result->model);
        //$this->assertEquals('192.168.1.'+$seed, $result->networks[1]);
        // $this->assertEquals(1+$seed, $result->uplink);
        $this->assertEquals($homenetInstaller->house->id, $result->house);
        $this->assertEquals($homenetInstaller->room->id, $result->room);
    }
}