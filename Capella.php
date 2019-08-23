<?php


/**
 ***************** Capella V.0.1 *******************
 * Coded by Oğuz Koç 2019 All rights reserved.
 * Capella Admin panelleri için geliştirilmiş BackEnd FarameWork'üdür
 ***************************************************
 */
session_start();
ob_start();

// Lütfen admin sessionunu girmeyi unutmayın. Kritik Önem!!!!
define("USER_ACCESS",@$_SESSION["username"]); 
require_once("file_init.php");

class dbCon
{
	private $host="localhost";
	private $sql_user="root";
	private $sql_pass="";
	private $sql_db="getat";
	var $_cn=null;
	


	public function connect(){
		$this->_cn=mysqli_connect($this->host,$this->sql_user,$this->sql_pass);
		$dbConn=mysqli_select_db($this->_cn,$this->sql_db);	

		if($this->_cn && $dbConn){
			mysqli_query($this->_cn,"SET NAMES UTF8");
			return $this->_cn;
		}else{
			return false;
		}
	}

}

class Capella extends dbCon
{
	var $debug=0;//0: Debug Off -  1: Debug Open
	//Veri tabanına bağlan
	
	var $functionRules=["upload","accessControl"];//Çağrılamaz Fonksiyonlar

	function __construct()
	{
		$baglan=$this->connect();
		if(!$baglan){
			echo json_encode(array("error"=>"Veri Tabanı yada MYSQL Bağlantı sorunu"),JSON_UNESCAPED_UNICODE);
			
		}

	}

	function accessControl(){

		if(USER_ACCESS!=null||$this->debug==1){
			return true;
		}else{
			return false;
		}
	}
	function enj($queryString){
		$illegals=["'",'"','==','%27'];
		$legals=["\'",'\"',"","\'"];
		for($i=0;$i<4;$i++){
			$queryString=str_replace($illegals[$i],$legals[$i] , $queryString);
		}

		return $queryString;
	}
	function upload($file){
		$fu=new FI();
		return $fu->dosyaYukle($file);
	}


	function Insert(){
		try{
			if($this->accessControl()){
				$postData=$_POST;
				$table=@$_POST["table"];
				$btn=@$_POST["btn-sub"];
				unset($postData["table"]);
				unset($postData["btn-sub"]);
				$keys=array_keys($postData);
				$columns=join(",",$keys);
				$veriler="";
				$hata=0;
				foreach ($postData as $key => $value) {
					$this->enj($value);

					$veriler.="'".$value."',";
				}
				if($_FILES!=null){

					$fileKeysA=array_keys($_FILES);
					$urls="";
					for($i=0;$i<count($fileKeysA);$i++){
						array_push($_FILES[$fileKeysA[$i]],array("elementName"=>$fileKeysA[$i]));
						$yukle=json_decode($this->upload($_FILES[$fileKeysA[$i]]));
						$bol=explode("-", $fileKeysA[$i]);
						$fileKeysA[$i]=$bol[1];

						if($yukle->error=="no"){
							$urls.="'".$yukle->url."'";
						}else{
							echo json_encode(array("error"=>$yukle->error),JSON_UNESCAPED_UNICODE);
							$hata=1;
						}

					}
					$dosyaKeys=join($fileKeysA);
					$columns.=",".$dosyaKeys;
					$veriler.=$urls;

				}
				$veriler=rtrim($veriler,",");

				if($hata==0){
					$query="INSERT INTO ".$table."(".$columns.") VALUES (".$veriler.")";

					$q=mysqli_query($this->_cn,$query);
					if($q){
						echo json_encode(array("error"=>"no","message"=>"Veriler Başarıyla Eklendi"),JSON_UNESCAPED_UNICODE);

					}
				}
			}else{
				echo json_encode(array("error"=>"Erişim Reddedildi"),JSON_UNESCAPED_UNICODE);

			}
		}catch(Exception $e){
			if($debug==1){
				echo "Hata : ".$e;
			}else{
				echo "Sistem Hatası (if you want see exception error, please open debug mode/Eğer hatayı görmek istiyorsanız debug modunu açınız.)";
			}
		}


	}

	function Update(){
		try{
			if($this->accessControl()){


				$postData=$_POST;
				$table=@$_POST["table"];
				$btn=@$_POST["btn-sub"];
				$id=$this->enj(@$_POST["id"]);
				unset($postData["table"]);
				unset($postData["id"]);
				unset($postData["btn-sub"]);
				$keys=array_keys($postData);
				$columns=join(",",$keys);
				$veriler="";
				$hata=0;
				foreach ($postData as $key => $value) {
					$this->enj($value);

					$veriler.="'".$value."',";
				}
				if(!empty($_FILES["name"])){

					$fileKeysA=array_keys($_FILES);
					$urls="";
					for($i=0;$i<count($fileKeysA);$i++){
						array_push($_FILES[$fileKeysA[$i]],array("elementName"=>$fileKeysA[$i]));
						$yukle=json_decode($this->upload($_FILES[$fileKeysA[$i]]));
						$bol=explode("-", $fileKeysA[$i]);
						$fileKeysA[$i]=$bol[1];

						if($yukle->error=="no"){
							$urls.="'".$yukle->url."'";
						}else{
							echo json_encode(array("error"=>$yukle->error),JSON_UNESCAPED_UNICODE);
							$hata=1;
						}

					}
					$dosyaKeys=join($fileKeysA);
					$columns.=",".$dosyaKeys;
					$veriler.=$urls;

				}
				$veriler=rtrim($veriler,",");
				$veriler=explode(',',$veriler);
				$columns=explode(",",$columns);
				$qp="SET ";
				for($i=0;$i<count($veriler);$i++){
					$qp.=$columns[$i]."=".$veriler[$i].",";
				}
				$qp=rtrim($qp,",");

				if($hata==0){
					$query="UPDATE ".$table." ".$qp." WHERE id=".$id;
				
					$q=mysqli_query($this->_cn,$query);
					if($q){
						echo json_encode(array("error"=>"no","message"=>"Veriler Başarıyla Düzenlendi"),JSON_UNESCAPED_UNICODE);

					}
				}



			}else{
				echo json_encode(array("error"=>"Erişim Reddedildi"),JSON_UNESCAPED_UNICODE);
			}
		}catch(Exception $e){
			if($debug==1){
				echo "Hata : ".$e;
			}else{
				echo "Sistem Hatası (If you want see exception error, please open debug mode/Eğer hatayı görmek istiyorsanız debug modunu açınız.)";
			}
		}
	}
}

$Capella=new Capella();
$comm=@$_GET["comm"];

if($comm!=null){
	
	if(method_exists(@$Capella,$comm) && !in_array($comm, $Capella->functionRules)){

		echo $query=$Capella->$comm();

	}else{
		echo json_encode(array("error"=>"Metod bulunamadı yada erişim yasak lütfen Capella'yı kontrol ediniz."),JSON_UNESCAPED_UNICODE);

	}
}





?>