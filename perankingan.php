<?php 
require 'functions.php';
include 'hitungfinal.php';
session_start();
//koneksi ke databas

if ( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}


$_SESSION["activePage"] = "perankingan";
include 'header.php';


$hasilTopsis = hitung_topsis();





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

  $query = "INSERT INTO hasil_topsis VALUES ('0','0','$nama', '$nilai')";
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

  $query = "INSERT INTO hasil_topsis VALUES ('0','$j','$nama', '$nilai')";
  mysqli_query($conn,$query);
  $j++;
}






// Pagination

$jumlahDataHalaman = 10;
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
    $nilaiPDF = queryassoc("SELECT ranking, nama, nilai FROM hasil_topsis ORDER BY nilai DESC LIMIT 0, 25");
  
    // var_dump($jumlahData);
    // var_dump($awalDataTopsis);

}  else {
   $awalDataTopsis = ( $jumlahDataHalaman * $onPageTopsis ) - $jumlahDataHalaman;
    $nilaiTopsis = queryassoc("SELECT ranking, nama, nilai FROM hasil_topsis ORDER BY nilai DESC LIMIT $awalDataTopsis, $jumlahDataHalaman");
    $nilaiPDF = queryassoc("SELECT ranking, nama, nilai FROM hasil_topsis ORDER BY nilai DESC LIMIT 0, 25");
    // var_dump($jumlahData);
    // var_dump($awalData);
}

// -----------------------------------------------------------------------------------------------------------
// PAGINATION SMART

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perankingan </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
    .pdf-header {
        display: none;
    }

    @media print {
        .pdf-header {
            display: block;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
    }
</style>


  </head>
<body>
<!-- 
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
</div> -->
<br><br><br>


<!-- BAckup -->

<!-- Table Content -->

<table style="display: none;" id="myTable" class="table mt-2">
<thead>
    <tr>
    <th style="text-align: center;" >Ranking</th>
    <th>Nama</th>
    <th>Nilai Preferensi</th>
    </tr>
    </thead>
    <tbody>
    <?php $k=1;foreach ($nilaiPDF as $key) :?>
        <tr>
          <td style="width: 10%; text-align: center;"><?php echo $key["ranking"]?></td>
          <td><?php echo $key["nama"]?></td>
          <td><?php echo $key["nilai"]?></td>
        </tr>
   <?php endforeach; ?>
    </tbody>
</table>




<div class="container-sm mt-3">
   
      <h3>Metode TOPSIS</h3>
      <form action="" method="get">
      <input type="hidden" name="metode" value="topsis" >
    <input type="text" name="keyword[topsis]" size="40" autofocus placeholder="Masukkan Keyword Pencarian" autocomplete="off">
    <button type="submit" name="cari">Cari</button>
</form>

<button style="margin-right: 0px;" onclick="generatePDF()">Download PDF</button>
<table class="table table-success table-striped mt-2">
  <thead>
    <tr>
    <th style="text-align: center;" >Ranking</th>
    <th>Nama</th>
    <th>Nilai Preferensi</th>
    </tr>
    </thead>
    <tbody>
    <?php $k=1;foreach ($nilaiTopsis as $key) :?>
        <tr>
          <td style="width: 10%; text-align: center;"><?php echo $key["ranking"]?></td>
          <td><?php echo $key["nama"]?></td>
          <td><?php echo $key["nilai"]?></td>
        </tr>
   <?php endforeach; ?>
    </tbody>
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.text("Hasil Perankingan Penerima Beasiswa", 14, 15);
    doc.text("Generated on: " + new Date().toLocaleDateString(), 14, 23);

    doc.autoTable({
        html: '#myTable',
        startY: 30
    });

    doc.save("Laporan Perankingan.pdf");
}
</script>
</body>
</html>