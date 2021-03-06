<?php

/*
 * Copyright (c) 2011 Matthew Doll <mdoll at homenet.me>.
 *
 * This file is part of HomeNet.
 *
 * HomeNet is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * HomeNet is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with HomeNet.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @package HomeNet
 * @subpackage Room
 * @copyright Copyright (c) 2011 Matthew Doll <mdoll at homenet.me>.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 */
class HomeNet_NodeModelController extends Zend_Controller_Action {
    
    private $_id;
    
    /**
     * @var HomeNet_Model_NodeModel_Service 
     */
    private $service;

    public function init() {
        $this->service = new HomeNet_Model_NodeModel_Service();
        $this->view->id = $this->_id = $this->_getParam('id');
       $this->_setupCrumbs();
    }
    
    private function _setupCrumbs(){
        $this->view->breadcrumbs()->addPage(array(
            'label'  => 'Admin',
            'route'  => 'admin',   
        ));
        
    
        $this->view->breadcrumbs()->addPage(array(
            'label'  => 'HomeNet',
            'route'  => 'admin',  
            //'module' => 'admin',
            //'controller' => 'index'
        ));
        
        $this->view->breadcrumbs()->addPage(array(
            'label'  => 'Node Models',
            'route'  => 'homenet-admin',  
            'controller' => 'node-model'
        ));

         $this->view->heading = 'Node Model'; //for generic templates
    }

    public function indexAction() {
        $this->view->objects = $this->service->getObjects();
    }

    public function newAction() {
        $this->_helper->viewRenderer->setNoController(true); //use generic templates
        $form = new HomeNet_Form_NodeModel();

        $form->addElement('submit', 'submit', array('label' => 'Add'));

        if (!$this->getRequest()->isPost() || !$form->isValid($_POST)) {
            $this->view->form = $form;
            return;
        }

        $values = $form->getValues();

        $service = new HomeNet_Model_NodeModel_Service();
      
        $object = new HomeNet_Model_NodeModel();

        $values['settings'] = unflattenArray($values['settings']);

        $object->fromArray($values);

        $service->create($object);

        //redirect to the next step
        $this->view->messages()->add('Successfully added NodeModel &quot;'.$object->name.'&quot;');
        return $this->_redirect($this->view->url(array('controller'=>'NodeModel', 'action'=>'index'),'homenet-admin')); 
    }

    public function editAction() {
        $this->_helper->viewRenderer->setNoController(true); //use generic templates
        $form = new HomeNet_Form_NodeModel();
        $form->addElement('submit', 'submit', array('label' => 'Update'));
        
        $object = $this->service->getObjectById($this->_id);

        if (!$this->getRequest()->isPost()) {

            //load exsiting values
            $values = $object->toArray();
            $values['settings'] = flattenArray($values['settings']);

            $form->populate($values);

            $this->view->form = $form;
            return;
        }

        if (!$form->isValid($_POST)) {
            // Failed validation; redisplay form
            $this->view->form = $form;
            return;
        }

        $values = $form->getValues();

        $values['settings'] = unflattenArray($values['settings']);

        $object->fromArray($values);

        $this->service->update($object);
        $this->view->messages()->add('Successfully updated NodeModel &quot;'.$object->name.'&quot;');
        return $this->_redirect($this->view->url(array('controller'=>'NodeModel', 'action'=>'index'),'homenet-admin')); 
    }

    public function deleteAction() {
        $this->_helper->viewRenderer->setNoController(true); //use generic templates
        $form = new Core_Form_Confirm();

        $object = $this->service->getObjectById($this->view->id);

        if (!$this->getRequest()->isPost() || !$form->isValid($_POST)) {

            $form->addDisplayGroup($form->getElements(), 'node', array('legend' => 'Are you sure you want to delete "' . $object->name . '"?'));

            $this->view->form = $form;
            return;
        }

        $values = $form->getValues();
        
        if (!empty($_POST['confirm'])) {
            $name = $object->name;
            $this->service->delete($object);
            $this->view->messages()->add('Successfully deleted NodeModel &quot;'.$name.'&quot;');
        }
        
        return $this->_redirect($this->view->url(array('controller'=>'NodeModel', 'action'=>'index'),'homenet-admin')); 
    }

}