<?php
require_once("SimpleRest.php");
require_once("Bbs.php");
		
class BbsRestHandler extends SimpleRest {

	// Read 查詢
	function getAllbbs() {	

		$bbs = new Bbs();
		// 取全部資料
		$rawData = $bbs->getAllbbs();

	
		if(empty($rawData)) {
			// 沒有資料
			$statusCode = 404;
			$rawData = array('success' => 0);		
		} else {
			//有資料
			$statusCode = 200;
		}

		// 表頭
		$allHeaders = getallheaders();
        $contentType = $allHeaders['Content-Type'];
		// 接收header 類型
		if( is_null($_SERVER['CONTENT_TYPE'])){
			$requestContentType= "application/json";
		}else{
			$requestContentType = $_SERVER['CONTENT_TYPE'];
		}
		
		$this ->setHttpHeaders($requestContentType, $statusCode);
		$result["output"] = $rawData;
		
		// json 編碼
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($result);
			// 輸出
			echo $response;
		}
		if(strpos($requestContentType,'application/x-www-form-urlencoded') !== false){
			$response = $this->encodeJson($result);
			echo $response;
		}

	}
	
	function add() {	
		$bbs = new Bbs();
		$rawData = $bbs->addBbs();

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('success' => 0);		
		} else {
			$statusCode = 200;
		}
		
		// $requestContentType = $_SERVER['HTTP_ACCEPT'];
		// $requestContentType = $_SERVER['CONTENT_TYPE'];
		// 表頭
		// 接收header 類型
		if( is_null($_SERVER['CONTENT_TYPE'])){
			$requestContentType= "application/json";
		}else{
			$requestContentType = $_SERVER['CONTENT_TYPE'];
		}

		$this ->setHttpHeaders($requestContentType, $statusCode);
		$result = $rawData;
				
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($result);
			echo $response;
		}
		if(strpos($requestContentType,'application/x-www-form-urlencoded') !== false){
			$response = $this->encodeJson($result);
			echo $response;
		}
	}
    // 刪除
	function deleteBbsById() {	
		$bbs = new Bbs();
		$rawData = $bbs->deleteBbs();
		
		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('success' => 0);		
		} else {
			$statusCode = 200;
		}
		
		// $requestContentType = $_SERVER['HTTP_ACCEPT'];
		// 接收header 類型
		if( is_null($_SERVER['CONTENT_TYPE'])){
			$requestContentType= "application/json";
		}else{
			$requestContentType = $_SERVER['CONTENT_TYPE'];
		}

		$this ->setHttpHeaders($requestContentType, $statusCode);
		$result = $rawData;
				
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($result);
			echo $response;
		}
		if(strpos($requestContentType,'application/x-www-form-urlencoded') !== false){
			$response = $this->encodeJson($result);
			echo $response;
		}
	}
	// 更新 編輯
	function editBbsById() {	
		$bbs = new Bbs();
		// 獲取更新狀態
		$rawData = $bbs->editBbs();
		if(empty($rawData)) {
			// 找不到資料狀況下
			$statusCode = 404;
			$rawData = array('success' => 0);		
		} else {
			// 成功狀態
			$statusCode = 200;
		}
		
		// $requestContentType = $_SERVER['HTTP_ACCEPT'];
		// 接收header 類型
		if( is_null($_SERVER['CONTENT_TYPE'])){
			$requestContentType= "application/json";
		}else{
			$requestContentType = $_SERVER['CONTENT_TYPE'];
		}
		
		$this ->setHttpHeaders($requestContentType, $statusCode);
		$result = $rawData;
		// json 格式
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($result);
			echo $response;
		}
		// x-www-form-urlencoded 格式
		if(strpos($requestContentType,'application/x-www-form-urlencoded') !== false){
			$response = $this->encodeJson($result);
			echo $response;
		}
	}

	// json 編碼
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}


}
?>