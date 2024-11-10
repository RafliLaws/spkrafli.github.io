<?php 
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
// var_dump($hasilTopsis);
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





<div class="row justify-content-around mt-5">
    <div class="col-4">
      <h3>Metode TOPSIS</h3>

<table class="table table-success table-striped">
    <th>No</th>
    <th>Nama</th>
    <th>Nilai Preferensi</th>
    <?php $k=1;foreach ($hasilTopsis as $key) :?>
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

    </div>


    <div class="col-4">
      <h3>Metode SMART</h3>
<table class="table table-primary table-striped">
<th>No</th>
    <th>Nama</th>
    <th>Nilai Preferensi</th>
    <?php $k=1;foreach ($hasilSmart as $key) :?>
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

    </div>
  </div>












<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>