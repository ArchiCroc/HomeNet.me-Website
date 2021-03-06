<?php

/**
 * Test class for UserController.
 * Generated by PHPUnit on 2011-11-17 at 05:57:26.
 */
class UserControllerTest extends Zend_Test_PHPUnit_ControllerTestCase {

    /**
     * @var UserController
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        Core_Model_Installer::install();
        $this->bootstrap = new Zend_Application('testing', APPLICATION_PATH . '/configs/application.ini'); //
        $this->view = Zend_Registry::get('view');

        parent::setUp();
        
        $request = $this->getRequest();
        $request->setModuleName('core');
        $request->setControllerName('user');
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        Core_Model_Installer::uninstall();

    }

    public function testIndexAction() {
        //setup
        $this->getRequest()->setActionName('index');
        
        //run
        $this->dispatch();
        //$this->assertModule('core');
        $this->assertController('user');
        $this->assertAction('index');
        $this->assertNotRedirect();
    }

 
    public function testNewAction() {
       //setup
        $this->getRequest()->setActionName('new');
        
        //run
        $this->dispatch();
        //$this->assertModule('core');
        $this->assertController('user');
        $this->assertAction('new');
        $this->assertNotRedirect();
    }


    public function testProfileAction() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }


    public function testNextStepsAction() {
        //setup
        $this->getRequest()->setActionName('next-steps');
        
        //run
        $this->dispatch();
        //$this->assertModule('core');
        $this->assertController('user');
        $this->assertAction('index');
        $this->assertNotRedirect();
    }

    public function testSendActivationAction() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testActivateAction().
     */
    public function testActivateAction() {
 // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    public function testActivatedAction() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    public function testEditAction() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    public function testSettingsAction() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}