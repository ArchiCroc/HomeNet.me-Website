<?php

require_once dirname(__FILE__) . '/../../../../application/models/Acl/Service.php';

/**
 * Test class for Core_Model_Acl_Service.
 * Generated by PHPUnit on 2011-06-27 at 19:48:52.
 */
class Core_Model_Acl_ManagerTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Core_Model_Acl_Service
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        
        Core_Model_Installer::install();

        $uService = new Core_Model_User_Service();
        $user = $uService->getObjectById(Core_Model_Installer::$userMember);
        
        $this->object = new Core_Model_Acl_Manager($user);
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        Core_Model_Installer::uninstall();
    }


    public function testSetUser() {
        $user = new Core_Model_User();
        $this->object->setUser($user);
        
        $this->assertInstanceOf('Core_Model_User', $this->object->getUser());
        
    }

    public function testGetResources() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    public function testGetResourcesByModule() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    
    public function testGetGroupAcl() {
        
        
        $result = $this->object->getGroupAcl('core');
        $this->assertInstanceOf('Zend_Acl', $result);
        $this->assertTrue($result->isAllowed('g'.Core_Model_Installer::$groupGuest, 'index', null));
         
         
    }
    
    
    public function testGetUserAcl() {
        $result = $this->object->getUserAcl('core');
        $this->assertInstanceOf('Zend_Acl', $result);
    }


    public function testGetGroupAclObjects() {
        $result = $this->object->getGroupAclObjects('core','test',array(1,2,3));
        $this->assertInstanceOf('Zend_Acl', $result);
    }


    public function testGetUserAclObjects() {
       $result = $this->object->getUserAclObjects('core','test',array(1,2,3));
       $this->assertInstanceOf('Zend_Acl', $result);
    }

}