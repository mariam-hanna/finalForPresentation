<?php

class Application_Model_Category extends Zend_Db_Table_Abstract
{
    protected $_name = 'categories';
    
    
    function addcategory($data,$img) {
        $row = $this->createRow();
        $row->name = $data['name'];
        $row->description = $data['desc'];
        $row->image = $img;
        return $row->save();
    }
    
    function listAllCategory() {    
        return $this->fetchAll()->toArray();
    }
}

