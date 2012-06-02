<?php

class IndexController extends Zend_Controller_Action {
    protected $session;
    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function loginAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
    }

}

