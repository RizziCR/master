<?php

require_once 'PHPTAL.php';
require_once 'include/PHPTAL_EtsTranslator.php';
require_once 'Zend/View/Interface.php';

function phptal_tales_action( $src, $nothrow ) {
    $src = trim($src); 
    return '$tpl->getController()->getHelper("url")->simple("'.$src.'")';
}

class ETS_View extends PHPTAL implements Zend_View_Interface {

	protected $_controller;

	public function __construct() {
		parent::__construct();
		parent::setTranslator(new PHPTAL_EtsTranslator());
		parent::setEncoding('ISO-8859-1');
	}

	public function getEngine() {
		return $this;
	}

	public function setScriptPath($path) {}
	public function getScriptPaths() {}
	public function setBasePath($path, $classPrefix = 'Zend_View') {}
	public function addBasePath($path, $classPrefix = 'Zend_View') {}
	public function __set($key, $val) {}
	public function __isset($key) {}
	public function __unset($key) {}

	public function assign($spec, $value = null) {
		parent::set($spec, $value);
	}

	public function clearVars() {}

	public function render($name) {
		try {
			parent::setTemplate($name);
		    echo parent::execute();
		}
		catch (Exception $e){
		    echo $e->getMessage();
		}
	}

	public function setController($controller) {
		$this->_controller = $controller;
	}

	public function getController() {
		return $this->_controller;
	}
}

?>