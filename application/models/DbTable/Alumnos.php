<?php

class Model_DbTable_Alumnos extends Zend_Db_Table_Abstract
{

    protected $_name = 'alumnos<';
    public function buscarAlumno($nocontrol){
        return $this->fetchRow("select count(*) as cantidad from alumnos where no_control='".$nocontrol."'");
    }

}

