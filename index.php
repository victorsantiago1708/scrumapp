<?php
require_once("init/ApplicationPHP.php");
require_once("routes/Routes.php");
ApplicationPHP::getInstance()->runApp();
Routes::routeRedirect();
//require_once("websocket/bin/chat-server.php");
?>
