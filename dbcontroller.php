<?php
class DBController {
	// 測試用的資料庫
	// 連接狀態保存
	private $conn = "";

	private $host = "localhost";
	private $user = "root";
	private $password = "";
	private $database = "bbs";

	//建構式 先執行一次
	function __construct() {
		$conn = $this->connectDB();
		if(!empty($conn)) {
			$this->conn = $conn;			
		}
	}

	// 連接資料庫
	function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}

	// 進行寫入 資料庫 新增 刪除 查詢
	function executeQuery($query) {
        $conn = $this->connectDB();    
        $result = mysqli_query($conn, $query);
        if (!$result) {
            //檢查錯誤 重複條目
            if($conn->errno == 1062) {
                return false;
            } else {
                trigger_error (mysqli_error($conn),E_USER_NOTICE);
				
            }
        }		
        $affectedRows = mysqli_affected_rows($conn);
		return $affectedRows;
    }
	
	// 進行資料查詢
	function executeSelectQuery($query) {
		$result = mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}
		if(!empty($resultset))
			return $resultset;
	}
}

?>
