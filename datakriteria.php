<?php 
session_start();
include 'functions.php';

if ( !isset($_SESSION["login"]) ) {
  header("Location: login.php");
  exit;
}


$_SESSION["activePage"] = "datakriteria";
include 'header.php';

$dataPeserta = query("SELECT * FROM bobot");
$namaKriteria = queryassoc("SELECT nama FROM bobot");
$nilai = query("SELECT bobot FROM bobot");


$jumlahData = count($dataPeserta);
$jumlahKriteria = count($namaKriteria);

//ignoring the id and name


// var_dump($dataPeserta);
// var_dump($jumlahKriteria);
// var_dump($nilaiPeserta);
// var_dump($dataPeserta);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kriteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>


<div class ="container-md">
<h2 style="margin-top: 50px">Data Kriteria</h2>
<a href="tambahkriteria.php?>"><button type="button" class="btn btn-success mb-2">+ | Tambah Kriteria</button></a> 
<table class="table table-bordered">
  <thead>
    <tr>
    <th scope="col">No.</th>
    <th scope="col">Nama</th>
    
    <th scope="col">Bobot</th>
    <th scope="col">Jenis Kriteria</th>
    <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>

    <tr>
    <?php $k= 1; for($i = 0; $i < $jumlahData; $i++) : ?>
      <th scope="row"><?= $k; $k++; ?></th>
      <td><?= $dataPeserta[$i][1] ?></td>
      <?php for($j = 0; $j < 1; $j++) : ?>
             <td><?= $nilai[$i][0]; ?></td>
    <?php endfor;?>
    <td><?= $dataPeserta[$i][3] ?></td>
    <td>
      <a href="ubahkriteria.php?id=<?php echo $dataPeserta[$i][0] ?>"><button type="button" class="btn btn-primary">Ubah</button></a> 
      <a href="hapuskriteria.php?id=<?php echo $dataPeserta[$i][0]?>&kriteria=<?= $dataPeserta[$i][1] ?>" onclick="return confirm('Hapus [ <?= $dataPeserta[$i][1] ?> ] dari daftar?')"><button type="button" class="btn btn-danger">Hapus</button></a>
    </td>
      </tr>
    <?php endfor;?>




  
    
  </tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
