<?php
require_once("dbcontroller.php");
/* 
展示 RESTful Web  核心 對接資料庫
*/
Class Bbs {
	//暫存結果
	private $bbs = array();

	// 查詢 讀取
	public function getAllbbs(){
		if(isset($_GET['id'])){
			// 輸入過濾輸出
			$safeGet = filter_input_array(INPUT_GET, [
				"id" => FILTER_VALIDATE_INT,
			]);
			$id = $safeGet['id'];
			// 查找相關資訊
			$id = $_GET['id'];
			$query = 'SELECT * FROM bbs WHERE id = '.$id.' ';
		} else {
			// 查找全部
			$query = 'SELECT * FROM bbs ';
		}
		//實作 dbcontroller
		$dbcontroller = new DBController();
		$this->bbs = $dbcontroller->executeSelectQuery($query);
		return $this->bbs;
	}

	// 新增 POST
	public function addBbs(){
		
		// var_dump($_POST['name']);
		if(isset($_POST['news'])){

			// 輸入過濾輸出
			$safePost = filter_input_array(INPUT_POST, [
				"news" => FILTER_SANITIZE_STRING,
			]);
			$news = $safePost['news'];

			$query = "insert into bbs (news, date_time_s) values ('" . $news ."', now() )";

			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query);
			// 成功執行SQL
			if($result != 0){
				// 新增成功
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	// 刪除 使用GET 
	public function deleteBbs(){
		if(isset($_GET['id'])){
			// 輸入過濾輸出
			$safeGet = filter_input_array(INPUT_GET, [
				"id" => FILTER_VALIDATE_INT,
			]);
			$id = $safeGet['id'];
			$query = 'DELETE FROM bbs WHERE id = '.$id;

			$dbcontroller = new DBController();
			
			$result = $dbcontroller->executeQuery($query);
			
			// 成功執行SQL
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	// 更新 編輯 POST
	public function editBbs(){
		
		if(isset($_POST['news']) && isset($_GET['id'])){

			// 輸入過濾輸出
			$safePost = filter_input_array(INPUT_POST, [
				"news" => FILTER_SANITIZE_STRING,
			]);
			$safeGet = filter_input_array(INPUT_GET, [
				"id" => FILTER_VALIDATE_INT,
			]);
			$news = $safePost['news'];
			$news = $safeGet['id'];

			$query = "UPDATE bbs SET news = '".$news."',date_time_s = now() WHERE id = ".$_GET['id'];
		}else{
			$result = array('error'=>0);
			return $result;
		}

		$dbcontroller = new DBController();
		$result= $dbcontroller->executeQuery($query);
		// 成功執行SQL
		if($result != 0){
			$result = array('success'=>1);
			return $result;
		}
	}
	
}
?>