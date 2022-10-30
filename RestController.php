<?php
require_once("BbsRestHandler.php");

$method = $_SERVER['REQUEST_METHOD'];

$view = "";
// 檢查ID
if(isset($_GET["page_key"]))
	$page_key = $_GET["page_key"];
/*
 RESTful 服務控制
URL 映射
*/
	switch($page_key){

		case "list":
			// handle REST Url /bbs/list/
			$bbsRestHandler = new BbsRestHandler();
			$result = $bbsRestHandler->getAllbbs();
			break;
	
		case "create":
			// handle REST Url /bbs/create/
			$bbsRestHandler = new BbsRestHandler();
			$bbsRestHandler->add();
		break;
		
		case "delete":
			// handle REST Url /bbs/delete/<row_id>
			$bbsRestHandler = new BbsRestHandler();
			$result = $bbsRestHandler->deleteBbsById();
		break;
		
		case "update":
			// handle REST Url /bbs/update/<row_id>
			$bbsRestHandler = new BbsRestHandler();
			$bbsRestHandler->editBbsById();
		break;
}
?>
