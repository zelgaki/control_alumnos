<?php

class Application_Model_DbTable_Usuarios extends Zend_Db_Table_Abstract
{

    protected $_name = 'usuarios';
    public function  registrar($datos){
        if($datos['id_usuario']){
            
        }
        else{
            $this->insert($datos);
        }
    }

}

