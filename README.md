# Capella



Capella admin panellerin için geliştirilmiş, gönderilen formların name'leri ile veri tabanındaki sütun(column)'ları eşleştirerek sql komutu yazmadan hızla veri eklemeye yarar.

# Özellikler

  - Formlardan gelen verileri, veri tabanına ekleme (mysqli)
  - Formlardan gelen verileri, veri tabanına güncelleme (mysqli)
  - Formlardan gelen dosyaları, file_init.php dosyasında belirtilen koşullara göre upload edip veri tabanına ekleme.

# Ayarlamalar & Kullanım
- Capella.php içersinde dbCon classında veri tabanı ayarlarınızı yapınız.
- Eğer debug modunu açacaksanız (Hataları görmek için) admin oturumunu yoksayacağını unutmayınız. Herkesin veri ekleyebileceğini hesaba katarak offline olarak açınız.
- Capella.php ilk satırlarda yer alan $_SESSION["username"] oturumunu yerine login olan adminin oturumunu yazmayı unutmayınız
- Eğer resim vb. dosya yükleme işlemi içinde kullanacaksanız file_init.php içersinde düzenlemeniz gereken şeyler;
> Örnek dosya upload formu;
```html
<form action="Capella.php?comm=Insert" method="post">
<input type="hidden" name="table" value="images">
<input type="file" name="s1-resim" class="form-control">
<input type="text" name="alt" class="form-control">
<input type="submit" name="btn-sub" class="form-control">
</form>
```
> ÖNEMLİ  : Buton ismi btn-sub olmak zorundadır.

> ÖNEMLİ  : Tablo hidden türünden ismi table olarak ve tablo adı value'sinde belirtilmelidr

> ÖNEMLİ  : Yüklenecek dosyanın name'i file_include.php dosyasında belirtilen(yada sizin yeni yazacağınız isim) başta olmak üzere (default s1) s1-column_adi olmalıdır

> ÖNEMLİ : Tüm form elemanları (text/select/password vb.) belirtilen tablodaki column isimleriyle aynı olmalıdır ve formda tablo columnlerinde bulunmayan eleman olmamalıdır!

file_include.php dosyasında yeni bir dosya tanımlama;
Construct Fonksiyonuna yeni bir obje oluşturulur örnek:
 ```php
<?php
/*file_name yerine formda file inputtan gelen name'in '-' den önceki kısmı (örnekteki s1 gibi)*/
$this->file_name=(object)array(
				"upload_dir"=>"Dosyanın nereye yükleneceği",
				"types"=>array("jpg","bmp","png","gif","webm"),//Dosyanın izin verilen uzantıları
				"random_name"=>true,//Dosya yüklendiğinde rastgele bi isim verilsin mi
				"max_file_size"=>0//Maximum dosya yükleme boyutu, sınırsız için 0
			);
?>
```
# Veri Ekleme

> Verileri eklemede post göndereğiniz sorgu "Capella.php?comm=Insert" yukarı html kullanımında bulunulduğu gibi action kısmına yazabilir yada ajaxla post metoduyla gönderebilirsiniz.

# Veri Güncelleme
> Veri güncellemek için post göndereceğiniz sorgu "Capella.php?comm=Update" yine formların action kısmına yada ajaxla post metodu ile gönderebilirsiniz

# Soru Sormak ve İletişim İçin
[Instagram](https://instagram.com/oguuzkoc)
[Whatsapp](https://wa.me/905524953226?text=Merhaba%20Oğuz%20Bir%20Sorum%20Var)
