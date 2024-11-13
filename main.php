<?php 
include 'functions.php';
session_start();


if ( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}

$query = "SELECT * FROM alternatif";
$queryKriteria = "SELECT nama FROM bobot";
$resultPeserta = mysqli_query($conn, $query);
$resultKriteria = mysqli_query($conn, $queryKriteria);
$hitungPeserta = mysqli_num_rows($resultPeserta);
$hitungKriteria= mysqli_num_rows($resultKriteria);



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
    <a class="nav-link active" aria-current="page" href="#">Home</a>
  </li>
  <li class="nav-item me-2">
    <a class="nav-link text-primary-emphasis" href="datapeserta.php">Data Peserta</a>
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
  </div>
</nav>

<div class="container flex-column text-center mt-5 ">
  <div class="row align-items-center">
    <div class="col">
    <img src="assets/smkn43.png" height="450">
    </div>
    <div class="col align-items-bottom text-start">
        
    <h1>Halo, User. <br>Sistem Penunjang Keputusan Penerima KIP</h1>

    
<div class="row row-cols-1 row-cols-md-2 g-4">
<div class="col">  
<div class="card text-bg-success mb-3" style="max-width: 18rem;">
  <div class="card-header">Jumlah Data Peserta Didik</div>
  <div class="card-body">
    <h3 class="card-title"><?= $hitungPeserta?> Siswa</h3>
   
  </div>
</div>
</div>

<div class="col">  
<div class="card text-bg-primary mb-3 ml-2" style="max-width: 18rem;">
  <div class="card-header">Jumlah Kriteria</div>
  <div class="card-body">
    <h3 class="card-title"><?= $hitungKriteria?> Kriteria</h3>
    
  </div> 
</div>
</div>
</div>


   
  </div>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>