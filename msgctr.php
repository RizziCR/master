<?php
  $use_lib = 18; // MSG_MSGCTR

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  require_once 'include/MessageCenterController.php';

  require_once 'Zend/Controller/Router/Rewrite.php';
  require_once 'Zend/Controller/Router/Route/Module.php';

  if ($_SESSION['sitt_login'])
    ErrorMessage(MSG_GENERAL,e000);  // Die Funktion ist für Sitter gesperrt

  if (ErrorMessage(0))
  {
    echo ErrorMessage();
    die();
  }

  $router = new Zend_Controller_Router_Rewrite();
  $router->addRoute('default', new Zend_Controller_Router_Route_Module(array(), null, new Zend_Controller_Request_Http()));
  Zend_Controller_Front::getInstance()->setRouter($router);

  $cc = new MessageCenterController(new Zend_Controller_Request_Http(), new Zend_Controller_Response_Http());
  try {
      while($cc->getRequest()->isDispatched() == false)
          $response = $cc->run();
      $response->sendResponse();
  }
  catch(Exception $e) {
      echo $e->getMessage();
  }

?>
