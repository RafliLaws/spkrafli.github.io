<?php 
require 'functions.php';
include 'hitungtopsis.php';
include 'hitungsmart.php';
session_start();
//koneksi ke databas

if ( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}


$_SESSION["activePage"] = "perankingan";
include 'header.php';

$hasilSmart = hitung_smart();
$hasilTopsis = hitung_topsis();


if (isset($_POST['proses'])) {


// Clean data dalam database TOPSIS lalu memasukkan data baru TOPSIS
  $query = "DELETE FROM hasil_topsis";
  mysqli_query($conn,$query);

foreach ($hasilTopsis as $ht) {
  $i=0;
  foreach ($ht as $huh) {
    
    $data[$i] = $huh;
    $i++;
   
  }
  $nama = str_replace("'","''",$data[0]);
  $nilai = $data[1];

  $query = "INSERT INTO hasil_topsis VALUES ('','','$nama', '$nilai')";
  mysqli_query($conn,$query);
}


$query = "SELECT nama, nilai FROM hasil_topsis ORDER BY nilai DESC";
$urutanTopsis = queryassoc($query); 
$query = "DELETE FROM hasil_topsis";
  mysqli_query($conn,$query);
$j = 1;
// Urutkan didalam database
foreach ($urutanTopsis as $ht) {
  $i=0;
  foreach ($ht as $huh) {
    
    $data[$i] = $huh;
    $i++;
   
  }
  $nama = str_replace("'","''",$data[0]);
  $nilai = $data[1];

  $query = "INSERT INTO hasil_topsis VALUES ('','$j','$nama', '$nilai')";
  mysqli_query($conn,$query);
  $j++;
}


// Clean data dalam database SMART lalu memasukkan data baru SMART
$query = "DELETE FROM hasil_smart";
  mysqli_query($conn,$query);
foreach ($hasilSmart as $hs) {
  $i=0;
  foreach ($hs as $huh) {
    
    $data[$i] = $huh;
    $i++;
   
  }
  $nama = str_replace("'","''",$data[0]);
  $nilai = $data[1];

  $query = "INSERT INTO hasil_smart VALUES ('', '', '$nama', '$nilai')";
  mysqli_query($conn,$query);
}


$query = "SELECT nama, nilai FROM hasil_smart ORDER BY nilai DESC";
$urutanSmart = queryassoc($query); 
$query = "DELETE FROM hasil_smart";
  mysqli_query($conn,$query);
$j = 1;
// Urutkan didalam database
foreach ($urutanSmart as $ht) {
  $i=0;
  foreach ($ht as $huh) {
    
    $data[$i] = $huh;
    $i++;
   
  }
  $nama = str_replace("'","''",$data[0]);
  $nilai = $data[1];

  $query = "INSERT INTO hasil_smart VALUES ('','$j','$nama', '$nilai')";
  mysqli_query($conn,$query);
  $j++;
}

}


// Pagination

$jumlahDataHalaman = 5;
$onPageSmart = (isset($_GET["halamanSmart"])) ? $_GET["halamanSmart"] : 1;
$onPageTopsis = (isset($_GET["halamanTopsis"])) ? $_GET["halamanTopsis"] : 1;


$_GET["keyword"]["smart"] = (isset($_GET["keyword"]["smart"])) ? $_GET["keyword"]["smart"] : null;
$_GET["keyword"]["topsis"] = (isset($_GET["keyword"]["topsis"])) ? $_GET["keyword"]["topsis"] : null;


// Refresh Value didalam
// $_SESSION["metode"] = null;
// $_POST["metode"]  = null;
// $_GET["metode"]  = null;

// Set default value
$_SESSION["metode"] = (isset($_SESSION["metode"])) ? $_SESSION["metode"] : null;
$_POST["metode"]  = (isset($_POST["metode"])) ? $_POST["metode"] : null;
$_GET["metode"]  = (isset($_GET["metode"])) ? $_GET["metode"] : null;


// PAGINATION TOPSIS
if (!isset($_GET["keyword"]["topsis"])) {
   
    $jumlahDataTopsis = count(query("SELECT * FROM hasil_topsis"));
    $jumlahHalamanTopsis = ceil($jumlahDataTopsis / $jumlahDataHalaman);
    $awalDataTopsis = ( $jumlahDataHalaman * $onPageTopsis ) - $jumlahDataHalaman;
}

$i = 1;
// tombol cari diklik
if (isset($_GET["cari"]) && isset($_GET["keyword"]["topsis"])) {
  $topsis = 1;
  $awalDataTopsis = ( $jumlahDataHalaman * $onPageTopsis ) - $jumlahDataHalaman;
    $nilaiTopsis = cari($_GET["keyword"]["topsis"],$awalDataTopsis,$jumlahDataHalaman,$topsis);
    $jumlahData = count(countCari($_GET["keyword"]["topsis"],$topsis));
    $jumlahHalamanTopsis = ceil($jumlahData / $jumlahDataHalaman);
  
    // var_dump($jumlahData);
    // var_dump($awalDataTopsis);

}  else {
   $awalDataTopsis = ( $jumlahDataHalaman * $onPageTopsis ) - $jumlahDataHalaman;
    $nilaiTopsis = queryassoc("SELECT ranking, nama, nilai FROM hasil_topsis ORDER BY nilai DESC LIMIT $awalDataTopsis, $jumlahDataHalaman");
    // var_dump($jumlahData);
    // var_dump($awalData);
}

// -----------------------------------------------------------------------------------------------------------
// PAGINATION SMART
if (!isset($_GET["keyword"]["smart"])) {
   
  $jumlahDataSmart = count(query("SELECT * FROM hasil_smart"));
  $jumlahHalamanSmart = ceil($jumlahDataSmart / $jumlahDataHalaman);
}

$i = 1;
// tombol cari diklik
if (isset($_GET["cari"]) && isset($_GET["keyword"]["smart"])) {
 
  $awalDataSmart = ( $jumlahDataHalaman * $onPageSmart ) - $jumlahDataHalaman;
  $topsis = 2;
  $nilaiSmart = cari($_GET["keyword"]["smart"],$awalDataSmart,$jumlahDataHalaman,$topsis);
  $jumlahData = count(countCari($_GET["keyword"]["smart"],$topsis));
  $jumlahHalamanSmart = ceil($jumlahData / $jumlahDataHalaman);
 

  // var_dump($jumlahData);

}  else {
  $awalDataSmart = ( $jumlahDataHalaman * $onPageSmart ) - $jumlahDataHalaman;
  $nilaiSmart = queryassoc("SELECT ranking, nama, nilai FROM hasil_smart ORDER BY nilai DESC LIMIT $awalDataSmart, $jumlahDataHalaman");
  // var_dump($nilaiSmart);
  // var_dump($jumlahData);
  // var_dump($awalData);

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perankingan </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<form action="perankingan.php" method="post">
  <div class="mt-5 px-5"><h3>Perankingan</h3>
<div class="d-flex justify-content-between">
<select class="form-select" aria-label="Default select example" name="metode" id="metode">
                        <option selected>Pilih Jenis Metode</option>
                        <option value="topsis">Metode Topsis</option>
                        <option value="smart">Metode Smart</option>
                    </select>
<button class="btn btn-primary" type="submit" name="proses">Proses Nilai</button>

</form>
</div>
</div>



<!-- BAckup -->

<?php if (isset($_POST["proses"]) || isset($_SESSION["proses"])) :?>
  <?php unset($_SESSION["metode"])?>
  <?php  if (!isset($_SESSION["proses"])) { $_SESSION["proses"] = $_POST["proses"]; }?>
  <!-- <?php  if (!isset($_SESSION["metode"])) { $_SESSION["metode"] = $_POST["metode"]; }?> -->

  <?php if ($_POST["metode"]  == "topsis" || $_SESSION["metode"] == "topsis" || $_GET["metode"] == "topsis"){ ?>
<div class="container-sm mt-3">
   
      <h3>Metode TOPSIS</h3>
      <form action="" method="get">
      <input type="hidden" name="metode" value="topsis" >
    <input type="text" name="keyword[topsis]" size="40" autofocus placeholder="Masukkan Keyword Pencarian" autocomplete="off">
    <button type="submit" name="cari">Cari</button>
</form>
<table class="table table-success table-striped mt-2">
    <th style="text-align: center;" >Ranking</th>
    <th>Nama</th>
    <th>Nilai Preferensi</th>
    <?php $k=1;foreach ($nilaiTopsis as $key) :?>
        <tr>
          <td style="width: 10%; text-align: center;"><?php echo $key["ranking"]?></td>
          <td><?php echo $key["nama"]?></td>
          <td><?php echo $key["nilai"]?></td>
        </tr>
   <?php endforeach; ?>
</table>


<nav aria-label="...">
  <ul class="pagination">
  <?php if ($onPageTopsis > 1 ) {?>
        <li class="page-item ">
      <a class="page-link" href="?halamanTopsis=<?= $_GET["halamanTopsis"] - 1;?>&metode=topsis">Previous</a>
    </li>
    <?php } else { ?>
      <li class="page-item disabled ">
      <a class="page-link">Previous</a>
    </li>
    <?php }; ?>

    <?php for ($i = 1;  $i <= $jumlahHalamanTopsis ; $i++ ) :?>
        <?php if( $i == $onPageTopsis ) : ?>
            <li class="page-item active" aria-current="page">
      <a class="page-link" href="?halamanTopsis=<?= $i;?>&metode=topsis"><?php echo $i; ?></a>
    </li>
        <?php else : ?>
            <li class="page-item"><a class="page-link" href="?halamanTopsis=<?= $i;?>&metode=topsis"><?php echo $i; ?></a></li>
        <?php endif; ?>
    <?php endfor; ?>


    <?php if ($onPageTopsis < $jumlahHalamanTopsis ) :?>
    <li class="page-item">
      <a class="page-link" href="?halamanTopsis=<?= $onPageTopsis + 1;?>&metode=topsis">Next</a>
    </li>
    <?php else : ?>
    <li class="page-item disabled">
      <a class="page-link">Next</a>
    </li>
      
    <?php endif;?>
    </div>
    </ul>
</nav>








    <?php } elseif ($_POST["metode"]  == "smart" || $_SESSION["metode"] == "smart" || $_GET["metode"]  == "smart"){ ?>
    <div class="container-sm mt-3">
      <h3>Metode SMART</h3>
      <form action="" method="get">
        <input type="hidden" name="metode" value="smart" >
    <input type="text" name="keyword[smart]" size="40" autofocus placeholder="Masukkan Keyword Pencarian" autocomplete="off">
    <button type="submit" name="cari">Cari</button>
</form>
<table class="table table-primary table-striped mt-2">
<th style="text-align: center;" >Ranking</th>
    <th>Nama</th>
    <th>Nilai Preferensi</th>
    <?php $k=1;foreach ($nilaiSmart as $key) :?>
        <tr>
          <td style="width: 10%; text-align: center;"><?php echo $key["ranking"]?></td>
          <td><?php echo $key["nama"]?></td>
          <td><?php echo $key["nilai"]?></td>
        </tr>
   <?php endforeach; ?>
</table>


<nav aria-label="...">
  <ul class="pagination">
  <?php if ($onPageSmart > 1 ) {?>
        <li class="page-item ">
            <a class="page-link" href="?halamanSmart=<?= $onPageSmart - 1;?>&metode=smart">Previous</a>
        </li>
    <?php } else { ?>
        <li class="page-item disabled ">
            <a class="page-link">Previous</a>
        </li>
    <?php }; ?>

    <?php for ($i = 1;  $i <= $jumlahHalamanSmart ; $i++ ) :?>
        <?php if( $i == $onPageSmart ) : ?>
            <li class="page-item active" aria-current="page">
                <a class="page-link" href="?halamanSmart=<?= $i;?>&metode=smart"><?php echo $i; ?></a>
            </li>
        <?php else : ?>
            <li class="page-item"><a class="page-link" href="?halamanSmart=<?= $i;?>&metode=smart"><?php echo $i; ?></a></li>
        <?php endif; ?>
    <?php endfor; ?>


    <?php if ($onPageSmart < $jumlahHalamanSmart ) :?>
            <li class="page-item">
                <a class="page-link dsi" href="?halamanSmart=<?= $onPageSmart + 1;?>&metode=smart">Next</a>
            </li>
    <?php else : ?>
            <li class="page-item">
                <a class="page-link disabled">Next</a>
            </li>
    <?php endif;?>
    </div>
    </ul>
</nav>

 <?php }; ?>
 
























<!-- 
<?php if ($onPageSmart > 1 ) :?>
        <a href="?halamanSmart=<?= 1;?>&metode=smart">&lt;</a>
    <?php endif; ?>

    <?php for ($i = 1;  $i <= $jumlahHalamanSmart ; $i++ ) :?>
        <?php if( $i == $onPageSmart ) : ?>
            <a style="font-weight: bold; color: red;"><?php echo $i; ?></a>
        <?php else : ?>
            <a href="?halamanSmart=<?= $i;?>&metode=smart"> <?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>


    <?php if ($onPageSmart < $jumlahHalamanSmart ) :?>
            <a href="?halamanSmart=<?= $i - 1;?>&metode=smart">&gt;</a>
    <?php endif;?>
    </div>
  </div>
<?php endif; ?> -->





<!--- dumb -->


<!-- 

<?php if (isset($_POST["proses"]) || isset($_SESSION["proses"])) :?>
  <?php  if (!isset($_SESSION["proses"])) { $_SESSION["proses"] = $_POST["proses"]; }?>

  <?php if (isset($_POST["metode"]) || isset($_SESSION["metode"]) && $_POST["metode"] == "topsis") {?> 
    <?php  if (!isset($_SESSION["metode"])) { $_SESSION["metode"] = $_POST["metode"]; }?>
<div class="row justify-content-around mt-5">
    <div class="col-4">
      <h3>Metode TOPSIS</h3>
      <form action="" method="get">
      <input type="hidden" name="keyword[smart]" value="<?= $_GET["keyword"]["smart"] ?>" >
    <input type="text" name="keyword[topsis]" size="40" autofocus placeholder="Masukkan Keyword Pencarian" autocomplete="off">
    <button type="submit" name="cari">Cari</button>
</form>
<table class="table table-success table-striped">
    <th>No</th>
    <th>Nama</th>
    <th>Nilai Preferensi</th>
    <?php $k=1;foreach ($nilaiTopsis as $key) :?>
        <tr>
            <td>
                <?php echo $k; $k++; ?>
            </td>
            <?php foreach ($key as $nama => $nilai) :?>
                <td>
                    <?= $nilai ?>
                </td>
            <?php endforeach; ?>
        </tr>
   <?php endforeach; ?>
</table>

<?php if ($onPageTopsis > 1 ) :?>
        <a href="?halamanSmart=<?= $onPageSmart;?>&halamanTopsis=<?= 1;?>&metode=<?= $_POST["metode"] ?>>&lt;</a>
    <?php endif; ?>

    <?php for ($i = 1;  $i <= $jumlahHalaman ; $i++ ) :?>
        <?php if( $i == $onPageTopsis ) : ?>
            <a style="font-weight: bold; color: red;"><?php echo $i; ?></a>
        <?php else : ?>
            <a href="?halamanSmart=<?= $onPageSmart;?>&halamanTopsis=<?= $i;?>&metode=<?= $_POST["metode"] ?>"> <?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>


    <?php if ($onPageTopsis < $jumlahHalaman ) :?>
            <a href="?halamanSmart=<?= $onPageSmart;?>&halamanTopsis=<?= $i -1;?>&metode=<?= $_POST["metode"] ?>">&gt;</a>
    <?php endif;?>
    </div>

<?php } else if (isset($_POST["metode"]) || isset($_SESSION["metode"]) && $_POST["metode"] == "smart") { ?>
  <?php  if (!isset($_SESSION["metode"])) { $_SESSION["metode"] = $_POST["metode"]; }?>
    <div class="col-4">
      <h3>Metode SMART</h3>
      <form action="" method="get">
        <input type="hidden" name="keyword[topsis]" value="<?= $_GET["keyword"]["topsis"] ?>" >
    <input type="text" name="keyword[smart]" size="40" autofocus placeholder="Masukkan Keyword Pencarian" autocomplete="off">
    <button type="submit" name="cari">Cari</button>
</form>
<table class="table table-primary table-striped">
<th>No</th>
    <th>Nama</th>
    <th>Nilai Preferensi</th>
    <?php $k=1;foreach ($nilaiSmart as $key) :?>
        <tr>
            <td>
                <?php echo $k; $k++; ?>
            </td>
            <?php foreach ($key as $nama => $nilai) :?>
                <td>
                    <?= $nilai ?>
                </td>
            <?php endforeach; ?>
        </tr>
   <?php endforeach; ?>
</table>


<?php if ($onPageSmart > 1 ) :?>
        <a href="?halamanSmart=<?= 1;?>&halamanTopsis=<?= $onPageTopsis?>&metode=<?= $_POST["metode"] ?>">&lt;</a>
    <?php endif; ?>

    <?php for ($i = 1;  $i <= $jumlahHalaman ; $i++ ) :?>
        <?php if( $i == $onPageSmart ) : ?>
            <a style="font-weight: bold; color: red;"><?php echo $i; ?></a>
        <?php else : ?>
            <a href="?halamanSmart=<?= $i;?>&halamanTopsis=<?= $onPageTopsis?>&metode=<?= $_POST["metode"] ?>"> <?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>


    <?php if ($onPageSmart < $jumlahHalaman ) :?>
            <a href="?halamanSmart=<?= $i - 1;?>&halamanTopsis=<?= $onPageTopsis?>&metode=<?= $_POST["metode"] ?>">&gt;</a>
    <?php endif;?>
    </div>
  </div>
<?php }; ?>
<?php endif; ?>  -->






<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>