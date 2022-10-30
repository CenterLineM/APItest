#  使用PHP 建置 API公佈欄系統 

## API 的呼叫方法

### 取得公佈欄資料

 Api Url /list/
 Api 呼叫方法: GET
|  header 參數| 格式   | 必填| 說明 |
|  ----       | ----  | ---- | ---- |
| Content-Type  | application/json | Required | 內容格式 |
| Authorization | Basic YWRtaW46YWRtaW4= | Required | 基本驗證 帳號密碼 admin/admin |

|  body 參數   | 格式 | 必填| 說明 |
|  ----       | ----  | ---- | ---- |
| news | x-www-form-urlencoded | Required | 資料內容 |

Response
```json
{
    "output": [
        {
            "id": "1",
            "news": "消息發布一",
            "date_time_s": "2022-10-29 11:38:53"
        },
         {
            "id": "2",
            "news": "消息發布2",
            "date_time_s": "2022-10-28 17:10:44"
        },
      ]
}
```



### 取得單一公佈欄資料

 Api Url /list/$id/
 $id = 資料id ex: 1
 Api 呼叫方法: GET
|  header 參數| 格式   | 必填| 說明 |
|  ----       | ----  | ---- | ---- |
| Content-Type  | application/json | Required | 內容格式 |
| Authorization | Basic YWRtaW46YWRtaW4= | Required | 基本驗證 帳號密碼 admin/admin |

Response
```json
{
    "output": [
        {
            "id": "1",
            "news": "消息發布一",
            "date_time_s": "2022-10-29 11:38:53"
        },
      ]
}
```


### 新增公佈欄資料

 Api Url /create/([0-9]+)/$ 
 Api 呼叫方法: POST
|  header 參數| 格式   | 必填| 說明 |
|  ----       | ----  | ---- | ---- |
| Content-Type  | application/json | Required | 內容格式 |
| Authorization | Basic YWRtaW46YWRtaW4= | Required | 基本驗證 帳號密碼 admin/admin |


|  body 參數   | 格式 | 必填| 說明 |
|  ----       | ----  | ---- | ---- |
| news | x-www-form-urlencoded | Required | 資料內容(string) |


成功 Response 
```json
{
    "success": 1
}
```
失敗 Response 
```json
{
    "success": 0
}
```



### 刪除公佈欄資料

 Api Url /delete/([0-9]+)/$ 
 Api 呼叫方法: GET
|  header 參數| 格式   | 必填| 說明 |
|  ----       | ----  | ---- | ---- |
| Content-Type  | application/json | Required | 內容格式 |
| Authorization | Basic YWRtaW46YWRtaW4= | Required | 基本驗證 帳號密碼 admin/admin |

成功 Response 
```json
{
    "success": 1
}
```
失敗 Response 
```json
{
    "success": 0
}
```

### 修改公佈欄資料

 Api Url /update/([0-9]+)/$ 
 Api 呼叫方法: POST
|  header 參數| 格式   | 必填| 說明 |
|  ----       | ----  | ---- | ---- |
| Content-Type  | application/json | Required | 內容格式 |
| Authorization | Basic YWRtaW46YWRtaW4= | Required | 基本驗證 帳號密碼 admin/admin |

|  body 參數   | 格式 | 必填| 說明 |
|  ----       | ----  | ---- | ---- |
| news | x-www-form-urlencoded' | Required | 資料內容 |

Response
成功 Response 
```json
{
    "success": 1
}
```
失敗 Response 
```json
{
    "success": 0
}
```


## 後端資料庫部分 先建立 路由和核心檔功能
使用
Linux，作業系統 (Ubuntu)
安裝 Apache，網頁伺服器
安裝MySQL，資料庫管理系統（或者資料庫伺服器）
安裝 PHP 8.0、後端程式語言

建立 .htaccess 檔案 進行路由重寫

```htaccess

# 開啟 rewrite 功能
Options +FollowSymlinks
RewriteEngine on

# 將外部的URL 映射到 PHP內部 URL
# CRUD 讀取
RewriteRule ^bbs/list/$   RestController.php?page_key=list [nc,qsa]
RewriteRule ^bbs/list$   RestController.php?page_key=list [nc,qsa]
# 創建
RewriteRule ^bbs/create/$   RestController.php?page_key=create [L]
RewriteRule ^bbs/create$   bbs/create/ [L,R=301]
# 刪除
RewriteRule ^bbs/delete/([0-9]+)/$   RestController.php?page_key=delete&id=$1 [L]
RewriteRule ^bbs/delete([0-9]+)$   bbs/delete/$1 [L,R=301]
# 更新
RewriteRule ^bbs/update/([0-9]+)/$   RestController.php?page_key=update&id=$1 [L]
RewriteRule ^bbs/update/([0-9]+)$   bbs/update/$1/ [L,R=301]

```

### 建立 資料庫 與 資料庫控制核心程式

MySQL設定
```sql

-- 資料表索引 `bbs`
ALTER TABLE `bbs`
  ADD PRIMARY KEY (`id`);
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
-- 使用資料表自動遞增(AUTO_INCREMENT) `bbs`
ALTER TABLE `bbs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

```
使用OOP 物件導向模式
資料庫程式設定
建立 class DBController
創建 connectDB() 使用 mysqli_connect 連接資料庫
變數 $query 存放 sql指令
函式 executeQuery 進行寫入 資料庫 新增 刪除 查詢
函式 executeSelectQuery  進行資料查詢
mysqli_query 返回查詢紀錄
mysqli_query() 檢查是否查詢成功
```php
// 使用OOP 
class DBController {
	// 測試用的資料庫
	private $conn = "";
	private $host = "localhost";
	private $user = "bbsAdmine";
	private $password = "sdmakcwuey66281625";
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
```


## 建立 路由 RestControlle 與 RESTful 狀態核心

### 建立 RestController.php 檔案 
使用依賴反轉 抽象化 處理BBS 的CRUD 操做

```php
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
```

### 建立RESTful 基本核心 SimpleRest.php 與HTTTP 設定和狀態代碼資訊顯示
HTTP版本
建立 header 表頭訊息
添加 HTTP 狀態訊息與 header 訊息
HTTP各類狀態碼設定

### 建立RESTful 基本核心 SimpleRest.php 與HTTTP 設定和狀態代碼資訊顯示

200：一般成功回應
201：POST 方法成功回應
400：伺服器無法處理的錯誤請求
404：找不到資源

### 取得公佈欄訊息
 Url /bbs/list/

建立 class BbsRestHandler  
使用 function getAllbbs() 呼叫訊息
載入 Bbs.php 檔案 使用 Class Bbs
實作  getAllbbs 對接資料庫 查找資料 根據ID或是全部找出

```php
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
```


### 建立公佈欄訊息 
 Url bbs/create/

使用 class BbsRestHandler  
使用 function add() 新增資料
載入 Bbs.php 檔案 使用 Class Bbs
實作  addBbs 對接資料庫 查找資料 根據ID或是全部找出

```php
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
```
### 刪除公佈欄訊息 
 Url bbs/delete/([0-9]+)/$

使用 class BbsRestHandler  
使用 function deleteBbsById() 刪除資料
載入 Bbs.php 檔案 使用 Class Bbs
實作  deleteBbs 對接資料庫 根據ID刪除資料 

```php
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
```

### 更新公佈欄訊息 
 Url bbs/update/([0-9]+)/$ 

使用 class BbsRestHandler  
使用 function editBbsById() 更新資料
載入 Bbs.php 檔案 使用 Class Bbs
實作 function editBbs 對接資料庫 根據ID進行資料更新

```php
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
```

### 添加錯誤訊息
在 .htaccess 新增
ErrorDocument 404 /api-crud/404.html

### 過濾輸入

```php
	// 輸入過濾輸出
			$safePost = filter_input_array(INPUT_POST, [
				"news" => FILTER_SANITIZE_STRING,
			]);
			$safeGet = filter_input_array(INPUT_GET, [
				"id" => FILTER_VALIDATE_INT,
			]);
			$news = $safePost['news'];
			$news = $safeGet['id'];
```

### 基本驗證 在 SimpleRest.php 檔案

```php
// 基本認證 SimpleRest.php
		$has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
		$is_not_authenticated = (
			!$has_supplied_credentials ||
			$_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
			$_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
		);
		if ($is_not_authenticated) {
			header('HTTP/1.1 401 Authorization Required');
			header('WWW-Authenticate: Basic realm="Access denied"');
			header("Content-Type:". $contentType);
			exit;
		}else{
		   // 添加 HTTP 狀態訊息與 header 訊息
		   header($this->httpVersion. " ". $statusCode ." ". $statusMessage);		
		   header("Content-Type:". $contentType);
		}
```



## 感謝你的閱讀

