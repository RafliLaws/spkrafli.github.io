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

  $query = "INSERT INTO hasil_topsis VALUES ('', '$nama', '$nilai')";
  mysqli_query($conn,$query);
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

  $query = "INSERT INTO hasil_smart VALUES ('', '$nama', '$nilai')";
  mysqli_query($conn,$query);
}

}
$jumlahDataHalaman = 10;
$onPageSmart = (isset($_GET["halamanSmart"])) ? $_GET["halamanSmart"] : 1;
$onPageTopsis = (isset($_GET["halamanTopsis"])) ? $_GET["halamanTopsis"] : 1;
$_GET["keyword"]["smart"] = (isset($_GET["keyword"]["smart"])) ? $_GET["keyword"]["smart"] : null;
$_GET["keyword"]["topsis"] = (isset($_GET["keyword"]["topsis"])) ? $_GET["keyword"]["topsis"] : null;



// PAGINATION TOPSIS
if (!isset($_GET["keyword"]["topsis"])) {
   
    $jumlahDataTopsis = count(query("SELECT * FROM hasil_topsis"));
    $jumlahHalaman = ceil($jumlahDataTopsis / $jumlahDataHalaman);
    $awalDataTopsis = ( $jumlahDataHalaman * $onPageTopsis ) - $jumlahDataHalaman;
}

$i = 1;
// tombol cari diklik
if (isset($_GET["cari"]) && isset($_GET["keyword"]["topsis"])) {
  $topsis = 1;
  $awalDataTopsis = ( $jumlahDataHalaman * $onPageTopsis ) - $jumlahDataHalaman;
    $nilaiTopsis = cari($_GET["keyword"]["topsis"],$awalDataTopsis,$jumlahDataHalaman,$topsis);
    $jumlahData = count(countCari($_GET["keyword"]["topsis"],$topsis));
    $jumlahHalaman = ceil($jumlahData / $jumlahDataHalaman);
  
    // var_dump($jumlahData);
    // var_dump($awalDataTopsis);

}  else {
   $awalDataTopsis = ( $jumlahDataHalaman * $onPageTopsis ) - $jumlahDataHalaman;
    $nilaiTopsis = queryassoc("SELECT nama, nilai FROM hasil_topsis ORDER BY nilai DESC LIMIT $awalDataTopsis, $jumlahDataHalaman");
    // var_dump($jumlahData);
    // var_dump($awalData);
}

// -----------------------------------------------------------------------------------------------------------
// PAGINATION SMART
if (!isset($_GET["keyword"]["smart"])) {
   
  $jumlahDataSmart = count(query("SELECT * FROM hasil_smart"));
  $jumlahHalaman = ceil($jumlahDataSmart / $jumlahDataHalaman);
}

$i = 1;
// tombol cari diklik
if (isset($_GET["cari"]) && isset($_GET["keyword"]["smart"])) {
 
  $awalDataSmart = ( $jumlahDataHalaman * $onPageSmart ) - $jumlahDataHalaman;
  $topsis = 2;
  $nilaiSmart = cari($_GET["keyword"]["smart"],$awalDataSmart,$jumlahDataHalaman,$topsis);
  $jumlahData = count(countCari($_GET["keyword"]["smart"],$topsis));
  $jumlahHalaman = ceil($jumlahData / $jumlahDataHalaman);
 

  // var_dump($jumlahData);

}  else {
  $awalDataSmart = ( $jumlahDataHalaman * $onPageSmart ) - $jumlahDataHalaman;
  $nilaiSmart = queryassoc("SELECT nama, nilai FROM hasil_smart ORDER BY nilai DESC LIMIT $awalDataSmart, $jumlahDataHalaman");
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

<nav class="navbar bg-primary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      
      SMKN 43 Jakarta
    </a>

    
    <ul class="nav nav-underline me-4">
  <li class="nav-item me-2">
    <a class="nav-link" aria-current="page" href="main.php">Home</a>
  </li>
  <li class="nav-item me-2">
    <a class="nav-link"  aria-disabled="true" href="datapeserta.php">Data Peserta</a>
  </li>
  <li class="nav-item me-2">
    <a class="nav-link" href="datakriteria.php">Kriteria</a>
  </li>
  <li class="nav-item me-2">
    <a class="nav-link active">Perankingan</a>
  </li>
  <?php if ($_SESSION["role"] == "admin") :?>
  <li class="nav-item me-2">
    <a class="nav-link" aria-disabled="true" href="user.php">User</a>
  </li>
  <?php endif; ?>
  <li class="nav-item me-2">
    <a class="nav-link text-danger-emphasis"  href="logout.php">Log Out</a>
  </li>
</ul>
</ul>
  </div>
</nav>


<form action="" method="post">
<div class="d-flex justify-content-between mt-5 px-5">
<select class="form-select" aria-label="Default select example" name="<?= $key?>" id="<?= $key?>">
                        <option selected>Pilih Jenis Metode</option>
                        <option value="topsis">Metode Topsis</option>
                        <option value="smart">Metode Smart</option>
                    </select>
<button class="btn btn-primary" type="submit" name="proses">Proses Nilai</button>
</div>
</form>

<?php if (isset($_POST["proses"]) || isset($_SESSION["proses"]) ||isset($_SESSION["satu"])) :?>
  <?php  if (!isset($_SESSION["proses"])) { $_SESSION["proses"] = $_POST["proses"]; }?>
<div class="row justify-content-around mt-5">
    <div class="col-4">
      <h3>Metode TOPSIS</h3>
      <form action="" method="get">
      <input type="hidden" name="keyword[smart]" value="<?= $_GET["keyword"]["smart"] ?>" >
    <input type="text" name="keyword[topsis]" size="40" autofocus placeholder="Masukkan Keyword Pencarihan" autocomplete="off">
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
        <a href="?halamanSmart=<?= $onPageSmart;?>&halamanTopsis=<?= 1;?>">&lt;</a>
    <?php endif; ?>

    <?php for ($i = 1;  $i <= $jumlahHalaman ; $i++ ) :?>
        <?php if( $i == $onPageTopsis ) : ?>
            <a style="font-weight: bold; color: red;"><?php echo $i; ?></a>
        <?php else : ?>
            <a href="?halamanSmart=<?= $onPageSmart;?>&halamanTopsis=<?= $i;?>"> <?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>


    <?php if ($onPageTopsis < $jumlahHalaman ) :?>
            <a href="?halamanSmart=<?= $onPageSmart;?>&halamanTopsis=<?= $i -1;?>">&gt;</a>
    <?php endif;?>
    </div>


    <div class="col-4">
      <h3>Metode SMART</h3>
      <form action="" method="get">
        <input type="hidden" name="keyword[topsis]" value="<?= $_GET["keyword"]["topsis"] ?>" >
    <input type="text" name="keyword[smart]" size="40" autofocus placeholder="Masukkan Keyword Pencarihan" autocomplete="off">
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
        <a href="?halamanSmart=<?= 1;?>&halamanTopsis=<?= $onPageTopsis?>">&lt;</a>
    <?php endif; ?>

    <?php for ($i = 1;  $i <= $jumlahHalaman ; $i++ ) :?>
        <?php if( $i == $onPageSmart ) : ?>
            <a style="font-weight: bold; color: red;"><?php echo $i; ?></a>
        <?php else : ?>
            <a href="?halamanSmart=<?= $i;?>&halamanTopsis=<?= $onPageTopsis?>"> <?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>


    <?php if ($onPageSmart < $jumlahHalaman ) :?>
            <a href="?halamanSmart=<?= $i - 1;?>&halamanTopsis=<?= $onPageTopsis?>">&gt;</a>
    <?php endif;?>
    </div>
  </div>

<?php endif; ?>



<!-- backup -->
<!-- 
<?php if (isset($_POST["proses"]) || isset($_SESSION["proses"]) ||isset($_SESSION["satu"])) :?>
  <?php  if (!isset($_SESSION["proses"])) { $_SESSION["proses"] = $_POST["proses"]; }?>
<div class="row justify-content-around mt-5">
    <div class="col-4">
      <h3>Metode TOPSIS</h3>
      <form action="" method="get">
      <input type="hidden" name="keyword[smart]" value="<?= $_GET["keyword"]["smart"] ?>" >
    <input type="text" name="keyword[topsis]" size="40" autofocus placeholder="Masukkan Keyword Pencarihan" autocomplete="off">
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
        <a href="?halamanSmart=<?= $onPageSmart;?>&halamanTopsis=<?= 1;?>">&lt;</a>
    <?php endif; ?>

    <?php for ($i = 1;  $i <= $jumlahHalaman ; $i++ ) :?>
        <?php if( $i == $onPageTopsis ) : ?>
            <a style="font-weight: bold; color: red;"><?php echo $i; ?></a>
        <?php else : ?>
            <a href="?halamanSmart=<?= $onPageSmart;?>&halamanTopsis=<?= $i;?>"> <?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>


    <?php if ($onPageTopsis < $jumlahHalaman ) :?>
            <a href="?halamanSmart=<?= $onPageSmart;?>&halamanTopsis=<?= $i -1;?>">&gt;</a>
    <?php endif;?>
    </div>


    <div class="col-4">
      <h3>Metode SMART</h3>
      <form action="" method="get">
        <input type="hidden" name="keyword[topsis]" value="<?= $_GET["keyword"]["topsis"] ?>" >
    <input type="text" name="keyword[smart]" size="40" autofocus placeholder="Masukkan Keyword Pencarihan" autocomplete="off">
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
        <a href="?halamanSmart=<?= 1;?>&halamanTopsis=<?= $onPageTopsis?>">&lt;</a>
    <?php endif; ?>

    <?php for ($i = 1;  $i <= $jumlahHalaman ; $i++ ) :?>
        <?php if( $i == $onPageSmart ) : ?>
            <a style="font-weight: bold; color: red;"><?php echo $i; ?></a>
        <?php else : ?>
            <a href="?halamanSmart=<?= $i;?>&halamanTopsis=<?= $onPageTopsis?>"> <?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>


    <?php if ($onPageSmart < $jumlahHalaman ) :?>
            <a href="?halamanSmart=<?= $i - 1;?>&halamanTopsis=<?= $onPageTopsis?>">&gt;</a>
    <?php endif;?>
    </div>
  </div>

<?php endif; ?> -->









<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>