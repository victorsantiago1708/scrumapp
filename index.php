<?php
require_once("init/ApplicationPHP.php");
ApplicationPHP::getInstance()->runApp();

$httpRequest = new HttpRequest();
echo $httpRequest->getController();
echo "<br/>".$httpRequest->getAction();
print_r($httpRequest::$params);
?>
