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
 * along with HomeNet.  If not, see <http ://www.gnu.org/licenses/>.
 */

/**
 * Base for HomeNet Node Drivers
 *
 * @author Matthew Doll <mdoll at homenet.me>
 */
abstract class HomeNet_Model_Component_Abstract implements HomeNet_Model_Component_Interface {

    public $settings = array();
    public $id;
    public $status;
    public $house;
    public $room;
    //public $node;
    public $device;
    public $model;
    public $position = 0;
    public $order = 0;
    
    public $name;
    //public $permissions = '';

    //imported from ComponentModel
    public $plugin;
    public $model_name;
    public $datatype;

    private $_house;
    private $_controls;

    private $_controlForm;

    /**
     * @var HomeNet_Model_DbTable_DatapointsAbstract
     */
    private $_datapointService;

    //public $units = '';
    
    const NONE = null;
    const BOOLEAN = 1;
    const BYTE = 2;
    const INTEGER = 3;
    const FLOAT = 4;
    const LONG = 5;
    const STRING = 6;
    const BINARY = 7;
    
    const STATUS_LIVE = 1;
    const STATUS_TRASHED = 0;

     public function __construct(array $config = array()) {
        //load data
        if (isset($config['data'])) {
            $this->fromArray($config['data']);
        }
        
        //load model
        if (isset($config['model']) && $config['model'] instanceof HomeNet_Model_ComponentModel_Interface) {
            $this->loadModel($config['model']);
        }
    }
    
    public function getDatatypes(){
        return array(
            'None'    => self::NONE,
            'Boolean' => self::BOOLEAN,
            'Byte'    => self::BYTE,
            'Integer' => self::INTEGER,
            'Float'   => self::FLOAT,
            'Long'    => self::LONG,
            'String'  => self::STRING,
            'Binary'  => self::BINARY);
    }
    
     public function fromArray(array $array) {

        $vars = array('id', 'status', 'house', 'room', 'device', 'model',  'position', 'order', 'name', 
            'plugin', 'model_name', 'datatype');

        foreach ($array as $key => $value) {
            if (in_array($key, $vars)) {
                $this->$key = $value;
            }
        }

        if(!empty($array['settings']) && is_array($array['settings'])){
            $this->settings = array_merge($this->settings, $array['settings']);
        }

    }

     /**
     * @return array
     */
    public function toArray() {

        $array = array(
            'id' => $this->id,
            'status' => $this->status,
            'house' => $this->house,
            'room' => $this->room,
            'device' => $this->device,
            'model' => $this->model,
            'position' => $this->position,
            'order' => $this->order,
            'name' => $this->name,
            'settings' => $this->settings);

        return $array;
    }

    public function getSetting($setting){
        if(isset($this->settings[$setting])){
            return $this->settings[$setting];
        }
        return null;
    }

    public function setSetting($setting, $value){
        $this->settings[$setting] = $value;
    }

    public function clearSetting($setting){
        unset($this->settings[$setting]);
    }

    /**
     * @param HomeNet_Model_ComponentModelInterface $model
     */
    public function loadModel(HomeNet_Model_ComponentModel_Interface $model) {
        $this->name = $this->model_name = $model->name;
        $this->model = $model->id;
        $this->plugin = $model->plugin;
        $this->datatype = $model->datatype;
        //$this->type = $model->type;
        if(is_array($model->settings)){
        $this->settings = array_merge($this->settings, $model->settings);
        }
    }

    

    public function setHouse($house) {
        $this->_house = $house;
    }

    public function getHouse() {
        if($this->_house === null){
            $this->_house = HomeNet_Model_House_Manager::getHouseById($this->house);
        }
        
        return $this->_house;
    }

    public function setRoom($room) {
        $this->room = $room;
    }

    public function getRoom() {
        return $this->room;
    }

    

    /**
     * Form for setting settings
     *
     * @return Zend_Form
     */
    public function getSettingsForm() {
        $form = new CMS_Form();

        $showControl = $form->createElement('checkbox', 'showControl');
        $showControl->setLabel('Show Controls: ');
        $form->addElement($showControl);

        $showGraphs = $form->createElement('checkbox', 'showGraphs');
        $showGraphs->setLabel('Show Graphs: ');
        $form->addElement($showGraphs);

        return $form;
    }

    /**
     * Form for user config
     *
     * @return Zend_Form
     */
    public function getConfigForm() {

        $sub = new Zend_Form_SubForm();

        $name = $sub->createElement('text', 'name');
        $name->setLabel('Name: ');
        if (empty($this->name)) {
            $name->setValue($this->model_name);
        } else {
            $name->setValue($this->name);
        }
        $name->addFilter('StripTags');
        $sub->addElement($name);

        $room = $sub->createElement('select', 'room');
        $room->setLabel('Room: ');
        $room->setValue($this->room);
        $sub->addElement($room);

        return $sub;
    }

    public function hasSummary() {
        return false;
    }

    public function getSummary() {
        return false;
    }

    public function hasLastDataPoint() {
        return false;
    }

    public function getLastDataPoint() {
        return array();
    }


    public function hasGraphs() {
        return false;
    }

    /**
     * Get graph array
     *
     * @return null
     */
    public function getGraphPresets() {
        return null;
    }

    /**
     * Get stats graph
     *
     * @return Zend_Form
     */
    public function getGraph(Zend_Date $start, Zend_Date $end, $width = 200, $height = 100) {
        return 'Graph Abstract';
    }

    /**
     * Get Datapoints
     *
     * @return Zend_Form
     */
    public function getDataPoints($start, $end, $density = null) {
        return '';
        //  throw new Zend_Exception('This component doesn\'t have datapoints');
    }

    /**
     * Process config form
     *
     * @return Zend_Form
     */
    public function processConfigForm($values) {
        $this->name = $values['name'];
        $this->room = $values['room'];
    }

    /**
     * Does this component have a control form
     *
     * @return boolean
     */
    public function addControl($name, $element, $elementOptions, $action, $actionOptions, $visible = true) {
        $this->_controls[$name] = array(
            'element' => $element,
            'elementOptions' => $elementOptions,
            'action' => $action,
            'actionOptions' => $actionOptions,
            'visible' => $visible);
    }

    /**
     * Does this component have a control form
     *
     * @return boolean
     */
    public function hasControls() {
        return false;
    }

    /**
     * Build the controls for this component
     *
     * @return null
     */
    public function buildControls() {
        return null;
    }

    /**
     * Form for user control form
     *
     * @return Zend_Form
     */
    public function getControlForm() {

        //die(debugArray($this->_controls));

        if(!empty($this->_controlForm)){
            return $this->_controlForm;
        }

        $this->buildControls();

        
        if (empty($this->_controls)) {
            return '';
        }

        $form = new CMS_Form(array('disableLoadDefaultDecorators' => true));
        //$form->setAction('/homenet/setup/step1');
        //$form->setDecorators(array('FormElements','Form'));
        $form->setDecorators(array('FormElements','Form'));
        $id = $form->createElement('hidden', 'component');
        $id->setDecorators(array('ViewHelper'));
        $id->setValue($this->id);
        $form->addElement($id);
        
        foreach ($this->_controls as $name => $control) {
            if ($control['element'] !== null) {


                $control['elementOptions']['id'] = $control['element'].$this->id;

                $element = $form->createElement($control['element'], $name, $control['elementOptions'] );
                //$element->setAttrib('id', $control['element'].$this->id);
                $element->removeDecorator('DtDdWrapper')
                     ->removeDecorator('HtmlTag');
            //->removeDecorator('Label');

                $form->addElement($element);
                if ($control['element'] != 'submit') {
                    $element2 = $form->createElement('submit', $name . 'Submit', array('Label' => 'Go'));
                    $element2->removeDecorator('DtDdWrapper');
                    $form->addElement($element2);
                }
            }
        }
        $form->removeDecorator('DtDdWrapper');
        $this->_controlForm = $form;
        return $form;
    }

    /**
     * Build the controls for this component
     *
     * @return null
     */
    public function processControlForm($post){

        $form = $this->getControlForm();

        if (empty($form)) {
            return false;
        }

        if(!$form->isValid($post)){
            return false;
        }

        $values = $form->getValues($post);

        unset($post['component']);

        $action = null;

        //die(debugArray($post));

        foreach($this->_controls as  $name => $control){

            if(!empty($post[$name])){

                if ($control['element'] != 'submit' && empty($post[$name.'Submit'])) {
                    continue;
                }

                if($control['element'] == 'submit'){
                    $values[$name] = true;
                }
                
                $class = 'HomeNet_Model_Action_'. ucfirst($control['action']);

                if(!class_exists($class)){
                    throw new HomeNet_Model_Exception('Can\'t find class '.$name);
                }

                $action = new $class($control['actionOptions']);
                $action->action($values[$name]);
                break;
            }
        }

        if($action === null){
            throw new HomeNet_Model_Exception('Can\'t find matching action');
        }
        return true;

    }



    

//    public function add() {
//        $table = new HomeNet_Model_DbTable_Components();
//        $row = $table->createRow();
//
//        //$row->node = $this->node;
//        $row->device = $this->device;
//        $row->model = $this->model;
//        $row->order = $this->order;
//        $row->name = $this->name;
//        $row->position = $this->position;
//        $row->room = $this->room;
//        $row->settings = serialize($this->settings);
//
//        $row->save();
//
//        return $row->id;
//    }
//
//    public function update() {
//        $table = new HomeNet_Model_DbTable_Components();
//        $row = $table->fetchRowById($this->id);
//
//        //$row->node = $this->node;
//        $row->device = $this->device;
//        $row->model = $this->model;
//        $row->order = $this->order;
//        $row->name = $this->name;
//        $row->position = $this->position;
//        $row->room = $this->room;
//        $row->settings = serialize($this->settings);
//
//        $row->save();
//
//        return $row->id;
//    }
//
//    public function delete() {
//        $table = new HomeNet_Model_DbTable_Components();
//        $row = $table->fetchRowById($this->id);
//        $row->delete();
//    }


    /**
     * @return HomeNet_Model_Datapoint_Service
     */
    public function getDatapointService() {

        if ($this->_datapointService === null) {          
            
            if (empty($this->datatype)) {
                throw new Zend_Exception($this->model_name . ' ' . $this->name . ' doesn\'t have a datatype');
            }

            $this->_datapointService = new HomeNet_Model_Datapoint_Service($this->getHouse(), $this->datatype);
        }


        // $this->_datapointService->setType($this->settings['datatype']);
//        $class = 'HomeNet_Model_DbTable_Datapoints' . ucfirst($this->settings['datatype']);
//
//        if (!class_exists($class)) {
//            throw new Zend_Exception('Invalid Datatype: ' . $class);
//        }
//
//        $this->_datapointService = new $class();
//
        return $this->_datapointService;
    }


    /**
     * @param type $timestamp 
     * @param type $value 
     */
    public function saveDatapoint(Zend_Date $timestamp, $value) {

        if ($this->datatype === null) {
            throw new NotSupportedException('This component doesn\'t save data');
        }

        $dService = new HomeNet_Model_Datapoint_Service($this->getHouse(), $this->datatype);
        $dService->add($this->id,$timestamp, $value);
    }

}