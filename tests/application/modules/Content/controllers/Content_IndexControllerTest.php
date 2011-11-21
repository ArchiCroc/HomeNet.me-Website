<?php

/**
 * Test class for Content_IndexController.
 * Generated by PHPUnit on 2011-11-19 at 15:55:10.
 */
class Content_IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase {

        private $service;
    private $installer;
    private $contentInstaller;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {

        $this->installer = new Installer();
        $this->installer->installTest();
        $this->installer->loginAsSuperAdmin();
        
        
        $this->bootstrap = new Zend_Application('testing', APPLICATION_PATH . '/configs/application.ini'); //
        $this->view = Zend_Registry::get('view');
        
        $manager = new Core_Model_User_Manager();
        $manager->login(array('username'=>'testSuperAdmin','password'=>'password'));
     
        $this->contentInstaller = new Content_Installer();
        $this->contentInstaller->installTest();

        $this->service = new Content_Model_Template_Service();
        parent::setUp();
        
        $request = $this->getRequest();
        $request->setModuleName('Content');
        $request->setControllerName('Index');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    public function testIndexAction_missingSection() {
       $this->getRequest()->setActionName('Index');

        //show form
       $this->setExpectedException('Exception');
        $this->dispatch();
    }
}