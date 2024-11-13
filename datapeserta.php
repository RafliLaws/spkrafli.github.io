<?php 
session_start();
include 'functions.php';

if ( !isset($_SESSION["login"]) ) {
  header("Location: login.php");
  exit;
}
$dataPeserta = query("SELECT * FROM alternatif");
$namaKriteria = queryassoc("SELECT nama FROM bobot");
$nilai = query("SELECT * FROM alternatif");


$jumlahData = count($dataPeserta);
$jumlahKriteria = count($namaKriteria);

//ignoring the id and name
for ($i=2; $i <= $jumlahKriteria + 2; $i++) { 
    for ($j=0; $j < $jumlahData; $j++) { 
        $nilaiPeserta[$j][$i-2] = $nilai[$j][$i];
    }
 }


// var_dump($jumlahData);
// var_dump($jumlahKriteria);
// var_dump($nilaiPeserta);
// var_dump($dataPeserta);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    <a class="nav-link active" href="datapeserta.php">Data Peserta</a>
  </li>
  <li class="nav-item me-2">
    <a class="nav-link" href="datakriteria.php">Kriteria</a>
  </li>
  <li class="nav-item me-2">
    <a class="nav-link" aria-disabled="true" href="perankingan.php">Perankingan</a>
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



<div class ="container-md mx-auto">
  <h2 style="margin-top: 50px">Data Peserta</h2>
  <a href="tambahpeserta.php?>"><button type="button" class="btn btn-success mb-2"> + | Tambah Peserta</button></a> 

<table class="table table-bordered ">
  <thead>
    <tr>
    <th scope="col" widt>No.</th>
    <th scope="col">Kode</th>
    <th scope="col">Nama</th>
    <?php foreach ($namaKriteria as $kriteria) : ?>
    <th scope="col"><?= $kriteria['nama'] ?></th>
    <?php endforeach; ?>
    <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>

    <tr>
    <?php $k= 1; for($i = 0; $i < $jumlahData; $i++) : ?>
      <th scope="row"><?= $k; $k++; ?></th>
      <td><?= $dataPeserta[$i][1] ?></td>
      <?php for($j = 0; $j <= $jumlahKriteria; $j++) : ?>
             <td><?= $nilaiPeserta[$i][$j]; ?></td>
    <?php endfor;?>
    <td>
      <a href="ubah.php?id=<?php echo $dataPeserta[$i][0] ?>"><button type="button" class="btn btn-primary">Ubah</button></a> 
      <a href="hapus.php?id=<?php echo $dataPeserta[$i][0]?>" onclick="return confirm('Hapus [ <?= $dataPeserta[$i][2] ?> ] dari daftar?')"><button type="button" class="btn btn-danger">Hapus</button></a>
    </td>
      </tr>
    <?php endfor;?>




  
    
  </tbody>
</table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
