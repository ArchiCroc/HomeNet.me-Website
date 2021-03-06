<?php

/**
 * Test class for Content_SectionController.
 * Generated by PHPUnit on 2011-11-19 at 15:55:10.
 */
class Content_SectionControllerTest extends Zend_Test_PHPUnit_ControllerTestCase {

    private $service;
    private $installer;
    private $contentInstaller;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {

        $this->installer = new Core_Installer();
        $this->installer->installTest();
        $this->installer->loginAsSuperAdmin();
        
        $this->bootstrap = new Zend_Application('testing', APPLICATION_PATH . '/configs/application.ini'); //
        $this->view = Zend_Registry::get('view');
        
        $manager = new Core_Model_User_Manager();
        $manager->login(array('username'=>'testSuperAdmin','password'=>'password'));
     
        $this->contentInstaller = new Content_Installer();
        $this->contentInstaller->installTest();

        $this->service = new Content_Model_Section_Service();
        parent::setUp();
        
        $request = $this->getRequest();
        $request->setModuleName('Content');
        $request->setControllerName('Section');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    
    }
    
    /**
     * @see Content_Model_Section_ServiceTest
     * @return Content_Model_Section
     */
     private function createValidObject() {
        $section = new Content_Model_Section();
        $section->package = 'test';
        $section->title = 'testTitlegsdgsdfgs';
        $section->url = 'testUrl';
        $section->description = null;
        $section->visible = true;
        $result = $this->service->create($section);

        $this->assertInstanceOf('Content_Model_Section_Interface', $result);
        return $result;
    }

    public function testIndexAction() {
        //setup
        $object = $this->createValidObject();
        
        $this->getRequest()->setActionName('Index');
        
        //run
        $this->dispatch();

        $this->assertModule('Content');
        $this->assertController('Section');
        $this->assertAction('Index');
        $this->assertContains($object->title, $this->response->outputBody());
    }
    

    public function testNewAction_firstView() {
        //setup
        $this->getRequest()->setActionName('New');
        
        //run
        $this->dispatch();
        $this->assertModule('Content');
        $this->assertController('Section');
        $this->assertAction('New');
        $this->assertNotRedirect();

    }
    
    public function testNewAction_submitInvalid() {
        //setup
        $this->getRequest()->setActionName('New');

        $this->getRequest()->setMethod('POST')
                ->setPost(array(
                    'title' => '',
                    'url' => '',
                    'description' => ''));

        //run
        $this->dispatch();
        $this->assertModule('Content');
        $this->assertController('Section');
        $this->assertAction('New');
        $this->assertNotRedirect();
    }
    
    public function testNewAction_submitValid() {
        //setup
        $this->getRequest()->setActionName('New');
        $this->getRequest()->setMethod('POST')
                ->setPost(array(
                    'template' => 'Base',
                    'title' => 'test123',
                    'url' => 'test123',
                    'description' => 'afasdf sdfasdf'));

        //run
        $this->dispatch();
        $this->assertModule('Content');
        $this->assertController('Section');
        $this->assertAction('New');
        $this->assertRedirect();
    }

    public function testEditAction_firstView() {
        //setup
        $object = $this->createValidObject();
        $this->getRequest()->setActionName('Edit');
        $this->getRequest()->setParam('id', $object->id);

        //run
        $this->dispatch();
        $this->assertModule('Content');
        $this->assertController('Section');
        $this->assertAction('Edit');
        $this->assertContains($object->title, $this->response->outputBody()); //make sure data is in the form
        $this->assertNotRedirect();
    }
    
    public function testEditAction_submitInvalid() {
        //setup
        $object = $this->createValidObject();
        $this->getRequest()->setActionName('Edit');
        $this->getRequest()->setParam('id', $object->id);
         $this->getRequest()->setMethod('POST')
                ->setPost(array(
                    'title' => '',
                    'url' => '',
                    'description' => ''));
        //run
        $this->dispatch();
        $this->assertModule('Content');
        $this->assertController('Section');
        $this->assertAction('Edit');
        $this->assertNotRedirect();
    }
    
    public function testEditAction_submitValid() {
        //setup
        $object = $this->createValidObject();
        $this->getRequest()->setActionName('Edit');
        $this->getRequest()->setParam('id', $object->id);
        $this->getRequest()->setMethod('POST')
                ->setPost(array(
                    'title' => 'testTitle2',
                    'url' => 'test_title',
                    'description' => 'sdfsdfasdf sdfsdf sdfsfd'));
        
        //run
        $this->dispatch();
        $this->assertModule('Content');
        $this->assertController('Section');
        $this->assertAction('Edit');
        $this->assertRedirect();
    }

    public function testDeleteAction_firstView() {
        //setup
        $object = $this->createValidObject();
        $this->getRequest()->setActionName('Delete');
        $this->getRequest()->setParam('id', $object->id);

        //show form
        $this->dispatch();
        $this->assertModule('Content');
        $this->assertController('Section');
        $this->assertAction('Delete');
        $this->assertContains($object->title, $this->response->outputBody()); //make sure data is in the form
        $this->assertNotRedirect();
    }
    public function testDeleteAction_submitCancel() {
        //setup
        $object = $this->createValidObject();
        $this->getRequest()->setActionName('Delete');
        $this->getRequest()->setParam('id', $object->id);
        $this->getRequest()->setMethod('POST')
                ->setPost(array('cancel' => 'cancel'));

        //run
        $this->dispatch();
        $this->assertModule('Content');
        $this->assertController('Section');
        $this->assertAction('Delete');
        $this->assertRedirect();
    }
    public function testDeleteAction_submitDelete() {
        //setup
        $object = $this->createValidObject();
        $this->getRequest()->setActionName('Delete');
        $this->getRequest()->setParam('id', $object->id);
        $this->getRequest()->setMethod('POST')
                ->setPost(array('confirm' => 'confirm'));

        //run
        $this->dispatch();
        $this->assertModule('Content');
        $this->assertController('Section');
        $this->assertAction('Delete');
        $this->assertRedirect();
    }
}