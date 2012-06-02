<?php

class Alumnos_IndexController extends Zend_Controller_Action {

    public function init() {
//        $this->_helper->layout->setLayout('principal');
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function registrarAction() {

        if ($this->getRequest()->isPost()) {
            $error = array();
            $bandera = true;
            $this->_request->getParams();
            try {
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender(TRUE);
                $datos = $this->_request->getParams();
                $c = Zend_Db_Table::getDefaultAdapter();
                $c->beginTransaction();
                $db = new Zend_Db_Table("alumnos");
                if (!$datos['email']) {
                    array_push($error, "Error correo invalido");
                    $bandera = false;
                }
//                $alumnos= Application_||
//                $alumnos = new Model_DbTable_Alumnos();
//                $cantidad=$alumnos->buscarAlumno($datos['no_control']);
//                if($cantidad['no_control']>0){
//                    array_push($error, "No de control repetido");
//                    $bandera = false;
//                }
                $insertar = "";
                $insertar = array(
                    "no_control" => $datos['no_contro'],
                    "nombre" => $datos['nombre'],
                    "apaterno" => $datos['apaterno'],
                    "amaterno" => $datos['amaterno'],
                    "fecha_nacimiento" => $datos['fecha'],
                    "sexo" => $datos['sexo'],
                    "email" => $datos['email'],
                    "telefono" => $datos['telefono'],
                );
                if ($bandera) {
                    $db->insert($insertar);
                    $c->commit();
                }
            } catch (Exception $exc) {
                array_push($error, $exc);
                $c->rollBack();
            }
            echo Zend_Json::encode($error);
        }
    }

//funccion comprobar sintaxis de un email
    function validar_correo($email) {
        $mail_correcto = 0;
        if ((strlen($email) >= 6) && (substr_count($email, "@") == 1) && (substr($email, 0, 1) != "@") && (substr($email, strlen($email) - 1, 1) != "@")) {
            if ((!strstr($email, "'")) && (!strstr($email, "\"")) && (!strstr($email, "\\")) && (!strstr($email, "\$")) && (!strstr($email, " "))) {
                //miro si tiene caracter . 
                if (substr_count($email, ".") >= 1) {
                    //obtengo la terminacion del dominio 
                    $term_dom = substr(strrchr($email, '.'), 1);
                    //compruebo que la terminación del dominio sea correcta 
                    if (strlen($term_dom) > 1 && strlen($term_dom) < 5 && (!strstr($term_dom, "@"))) {
                        //compruebo que lo de antes del dominio sea correcto 
                        $antes_dom = substr($email, 0, strlen($email) - strlen($term_dom) - 1);
                        $caracter_ult = substr($antes_dom, strlen($antes_dom) - 1, 1);
                        if ($caracter_ult != "@" && $caracter_ult != ".") {
                            $mail_correcto = 1;
                        }
                    }
                }
            }
        }
        if ($mail_correcto)
            return 1;
        else
            return 0;
    }

}

