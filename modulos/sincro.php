<?php
	require_once("./library/PEAR.php");
	require_once("./library/IT.php");
      
    	$body= new HTML_Template_IT();
    	$body->loadTemplatefile("./modulos/sincro.tpl");

     	$body->setVariable("fin","");	 
	$plantilla->setVariable("contenido", $body->get()); 
?>
