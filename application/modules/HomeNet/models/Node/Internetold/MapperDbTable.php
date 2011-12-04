<?php

/*
 * RoomMapperDbTable.php
 *
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
 * @subpackage Node_Internet
 * @copyright Copyright (c) 2011 Matthew Doll <mdoll at homenet.me>.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 */
class HomeNet_Model_Node_Internet_MapperDbTable implements HomeNet_Model_Node_Internet_MapperInterface {

    protected $_table = null;

    /**
     *
     * @return HomeNet_Model_DbTable_Nodes;
     */
    public function getTable() {
        if ($this->_table === null) {
            $this->_table = new HomeNet_Model_DbTable_InternetNodes();
        }
        return $this->_table;
    }

    public function setTable($table) {
        $this->_table = $table;
    }


     public function fetchObjectById($id){
       return $this->getTable()->find($id)->current();
  }

    public function save(HomeNet_Model_Node_Interface $object) {

        if($object->type != HomeNet_Model_Node_Service::INTERNET){
            throw new Zend_Exception('Can\'t save a non internet node');
        }


        if (!is_null($object->id)) {
            $row = $this->getTable()->find($object->id)->current();
            if(empty($row)){
                $row = $this->getTable()->createRow();
            }
        } else {
            $row = $this->getTable()->createRow();
        }

        $row->id = $object->id;
        $row->status = $object->status;
        $row->ipaddress = $object->ipaddress;
        $row->direction = $object->direction;
        $row->save();
//die(debugArray($row));
        return $row;
    }

    public function delete(HomeNet_Model_Node_Interface $object) {

        if (!is_null($object->id)) {
            $row = $this->getTable()->find($object->id)->current()->delete();
            return;
        }

        throw new HomeNet_Model_Exception('Invalid Room');
    }
    
    public function deleteAll(){
        if(APPLICATION_ENV != 'production'){
            $this->getTable()->getAdapter()->query('TRUNCATE TABLE `'. $this->getTable()->info('name').'`');
        }
    }
}