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
 * @package HomeNet
 * @subpackage Datapoint
 * @copyright Copyright (c) 2011 Matthew Doll <mdoll at homenet.me>.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 */
class HomeNet_Model_Datapoint_Service {

    /**
     * Storage mapper
     * 
     * @var HomeNet_Model_DatapointsMapperInterface
     */
    protected $_mapper;
    
    /**
     * Datapoint type
     * 
     * @var string
     */
    protected $_type;
    
    protected $_house;
    
    public function __construct($house, $type = null) {
        $this->_house = $house;
        $this->_type = $type;
    }
    

    /**
     * Get storage mapper
     * 
     * @return HomeNet_Model_DatapointsMapperInterface
     */
    public function getMapper() {

//        if (empty($this->_type)) {
//            throw new Exception('Type not valid');
//        }
        if (empty($this->_house)) {
            throw new Exception('House not valid');
        }

        if (empty($this->_mapper)) {
            $this->_mapper = new HomeNet_Model_Datapoint_MapperDbTable($this->_house->id, $this->_house->database, $this->_type);
        }

        return $this->_mapper;
    }

    /**
     * Set storage mapper
     * 
     * @param HomeNet_Model_DatapointsMapperInterface $mapper
     */
    public function setMapper(HomeNet_Model_Datapoint_MapperInterface $mapper) {
        $this->_mapper = $mapper;
    }

    /**
     * Set datapoint type: int, byte etc.
     * 
     * @param string $type Datapoint type. Determines which table to use 
     */
    public function setType($type) {
        //@todo validate type
        $this->_type = $type;
    }
    
    /**
     * @param $house 
     */
    public function createTables(){
        $this->getMapper()->createTables();
    }
    /**
     * @param $house 
     */
    public function deleteTables(){
        $this->getMapper()->deleteTables();
    }

    /**
     * Get the last datapoint of a component
     * 
     * @param int $id
     * @return HomeNet_Model_Datapoint (HomeNet_Model_Datapoint_Interface)
     */
    public function getLastObjectById($id) {
        $result = $this->getMapper()->fetchLastObjectById($id);

        if (empty($result)) {
            // throw new HomeNet_Model_Exception('Datapoint not found', 404);
        }
        return $result;
    }

    /**
     * Get average values over a period of time
     * 
     * @param integer $id  Component Id
     * @param Zend_Date $start
     * @param Zend_Date $end
     * @param int $points number of points to return
     * @return HomeNet_Model_Datapoint[] (HomeNet_Model_Datapoint_Interface[]) 
     */
    public function getAveragesByIdTimespan($id, Zend_Date $start, Zend_Date $end, $points = 100) {
        $results = $this->getMapper()->fetchAveragesByIdTimespan($id, $start, $end, $points);

        if (empty($results)) {
            // throw new HomeNet_Model_Exception('Datapoint not found', 404);
        }
        return $results;
    }

    /**
     * Get all datapoints over a time period
     * 
     * @param integer $id
     * @param Zend_Date $start
     * @param Zend_Date $end
     * @return HomeNet_Model_Datapoint[] (HomeNet_Model_Datapoint_Interface[])  
     */
    public function getObjectsByIdTimespan($id, Zend_Date $start, Zend_Date $end) {
        $results = $this->getMapper()->fetchObjectsByIdTimespan($id, $start, $end);

        if (empty($results)) {
            //  throw new HomeNet_Model_Exception('Datapoint not found', 404);
        }
        return $results;
    }

    /**
     * Add datapoint helper
     * 
     * @param string $type
     * @param integer $id
     * @param mixed $value
     * @param type $timestamp 
     */
    public function add($id, Zend_Date $timestamp, $value) {
        $datapoint = new HomeNet_Model_Datapoint();
        $datapoint->id = $id;
        $datapoint->timestamp = $timestamp->toString('YYYY-MM-dd HH:mm:ss');
        $datapoint->value = $value;
        $this->create($datapoint);
    }

    /**
     * Create Datapoint
     * 
     * @param HomeNet_Model_Datapoint_Interface|array $mixed
     * @return HomeNet_Model_Datapoint (HomeNet_Model_Datapoint_Interface)
     * @throws InvalidArgumentException 
     */
    public function create($mixed) {
        if ($mixed instanceof HomeNet_Model_Datapoint_Interface) {
            $object = $mixed;
        } elseif (is_array($mixed)) {
            $object = new HomeNet_Model_Datapoint(array('data' => $mixed));
        } else {
            throw new InvalidArgumentException('Invalid Datapoint');
        }

        return $this->getMapper()->save($object);
    }

    /**
     * Update an existing Datapoint
     * 
     * @param HomeNet_Model_Datapoint_Interface|array $mixed
     * @return HomeNet_Model_Datapoint (HomeNet_Model_Datapoint_Interface)
     * @throws InvalidArgumentException 
     */
    public function update($mixed) {
        if ($mixed instanceof HomeNet_Model_Datapoint_Interface) {
            $object = $mixed;
        } elseif (is_array($mixed)) {
            $object = new HomeNet_Model_Datapoint(array('data' => $mixed));
        } else {
            throw new InvalidArgumentException('Invalid Apikey');
        }

        return $this->getMapper()->save($object);
    }

    /**
     * Delete Datapoint
     * 
     * @param HomeNet_Model_Datapoint_Interface|array|integer $mixed
     * @return boolean Success
     * @throws InvalidArgumentException 
     */
    public function delete($mixed) {
        if (is_int($mixed)) {
            $object = new HomeNet_Model_Datapoint();
            $object->id = $mixed;
        } elseif ($mixed instanceof HomeNet_Model_Datapoint_Interface) {
            $object = $mixed;
        } elseif (is_array($mixed)) {
            $object = new HomeNet_Model_Datapoint(array('data' => $mixed));
        } else {
            throw new InvalidArgumentException('Invalid Datapoint');
        }

        return $this->getMapper()->delete($object);
    }

    /**
     * Delete all Datapoints. Used for unit testing/Will not work in production 
     *
     * @return boolean Success
     * @throws NotAllowedException
     */
    public function deleteAll() {
        if (APPLICATION_ENV == 'production') {
            throw new Exception("Not Allowed");
        }
        $this->getMapper()->deleteAll();
    }
}