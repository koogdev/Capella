<?php 
/**
*Kullanım ilk harf dosyatipi ikinci harf anahtar

Configs
upload_dir=>resim yükleme yolu
types=>resim uzantısının neler olabileceği
random_name=>rastgele isim atansınmı atanmasın mı
max_file_size=>maximum yüklenebilecek dosya boyutu (0 sınırsız)

Geriye return olarak tam resim yolu döndürür

 */

class FI
{	
		//Tanımlamalar (html eleman adlarını (- den öncesi) burada tanımlayınız)
		public $s1;//Örnek <input type="file" name="a-resim">

		function __construct()
		{
			$this->s1=(object)array(//s1-resim objesi için ayarlar
				"upload_dir"=>"images/",
				"types"=>array("jpg","bmp","png","gif","webm"),
				"random_name"=>true,
				"max_file_size"=>0
			);

		}
		function isimOlustur($size){
			$karakterler=["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","r","s","t","u","v","y","z","w","x","q","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","R","S","T","U","V","Y","Z","W","X","Q","0","1","2","3","4","5","6","7","8","9"];
			$ret="";
			for($i=0;$i<$size;$i++){
				$ret.=$karakterler[rand(0,count($karakterler)-1)];
			}
			return $ret;

		}
		function dosyaYukle($file)
		{
			$name=$file[0]["elementName"];
			
			$sp=explode('-',$name);
			$bol=$sp[0];
			@$degisken=@$this->$bol;

			if(@$degisken!=null){

				$hata = $file['error'];
				if($hata != 0) {
					return json_encode(array("error"=>"Resim Yükeleme Hatası!"),JSON_UNESCAPED_UNICODE);
					
				} else {


					$isim = $file['name'];
					$uzanti = explode('.', $isim);
					$uzanti =strtolower($uzanti[count($uzanti)-1]);
					
					if(in_array($uzanti,$degisken->types)) {
						$dosya = $file['tmp_name'];
						if($degisken->random_name){
							$isim= $this->isimOlustur(10).".".$uzanti;
						}
						if($file["size"]<$degisken->max_file_size || $degisken->max_file_size==0){
						$cop=copy($dosya,$degisken->upload_dir. $isim);
						if($cop){
							return json_encode(array("error"=>"no","url"=>$degisken->upload_dir. $isim),JSON_UNESCAPED_UNICODE);

						}else{
							return json_encode(array("error"=>"Resim Sunucuya Aktarılırken Sorun"),JSON_UNESCAPED_UNICODE);

						}
					}else{
							return json_encode(array("error"=>"Dosya boyut sınırı aşıldı."),JSON_UNESCAPED_UNICODE);

					}


					} else {
						return json_encode(array("error"=>"İzin Verilmeyen Dosya Uzantısı"),JSON_UNESCAPED_UNICODE);
						
					}
				}

			}else{
				return json_encode(array("error"=>"Resim file_init Dosyasında Tanımlanmamış!"),JSON_UNESCAPED_UNICODE);

			}
		}
	}



	?>