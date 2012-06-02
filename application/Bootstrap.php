<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
	protected function _initAutoload() {
		$moduleLoader = new Zend_Application_Module_Autoloader ( array ('namespace' => '', 'basePath' => APPLICATION_PATH ) );
		return $moduleLoader;
	}
	
	protected function _initViewHelpers() {
		$this->bootstrap ( 'layout' );
		$layout = $this->getResource ( 'layout' );
		$view = $layout->getView ();
	}
        protected function __initSession() {
            Zend_Session::start();
        }
}
