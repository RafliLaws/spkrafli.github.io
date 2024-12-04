<?php 
session_start();
include 'functions.php';

if ( !isset($_SESSION["login"]) ) {
  header("Location: login.php");
  exit;
}

$_SESSION["activePage"] = "datapeserta";
include 'header.php';

$namaKriteria = queryassoc("SELECT nama FROM bobot");



// $jumlahData = 4;

$jumlahDataHalaman = 5;
$onPage = (isset($_GET["halaman"])) ? $_GET["halaman"] : 1;
$awalData = ( $jumlahDataHalaman * $onPage ) - $jumlahDataHalaman;
if (!isset($_GET["keyword"])) {
   
    $jumlahData = count(query("SELECT * FROM alternatif"));
    $jumlahDataTampil = count(query("SELECT * FROM alternatif LIMIT $awalData, $jumlahDataHalaman"));
    $jumlahHalaman = ceil($jumlahData / $jumlahDataHalaman);
    $awalData = ( $jumlahDataHalaman * $onPage ) - $jumlahDataHalaman;
}
//Pagination

$i = 1;
// tombol cari diklik
if (isset($_GET["cari"]) || isset($_GET["keyword"])) {
    $dataPeserta = cari_peserta($_GET["keyword"],$awalData,$jumlahDataHalaman);
    $nilai = cari_peserta($_GET["keyword"],$awalData,$jumlahDataHalaman);
    $jumlahDataTampil = count($dataPeserta);
    $jumlahHalaman = ceil($jumlahDataTampil / $jumlahDataHalaman);


}  else {
    $dataPeserta = query("SELECT * FROM alternatif LIMIT $awalData, $jumlahDataHalaman");
    $nilai = query("SELECT * FROM alternatif LIMIT $awalData, $jumlahDataHalaman");
}

$jumlahKriteria = count($namaKriteria);

//ignoring the id and name
for ($i=2; $i <= $jumlahKriteria + 2; $i++) { 
    for ($j=0; $j < $jumlahDataTampil; $j++) { 
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
    <title>Data Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
<div class ="container-md mx-auto">
  <h2 style="margin-top: 50px">Data Peserta</h2>
  <div class="row row-cols-1 row-cols-md-2 g-4">
    <div class="col-md-4">
    <a href="tambahpeserta.php?>"><button type="button" class="btn btn-success mb-2"> + | Tambah Peserta</button></a> 
    </div>
    <div class="col-md-4 offset-md-4">
    <form action="" method="get">
    <input type="text" name="keyword" autofocus placeholder="Masukkan Keyword Pencarian" autocomplete="off">
    <button type="submit" name="cari">Cari</button>
</form>
    </div>
  </div>

  <div style="overflow-x: auto;">
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
    <?php $k= 1; for($i = 0; $i < $jumlahDataTampil; $i++) : ?>
      <th scope="row"><?= $k + ( $jumlahDataHalaman * $onPage - $jumlahDataHalaman ); ceil($k++); ?></th>
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
<nav aria-label="...">
  <ul class="pagination">
  <?php if ($onPage > 1 ) {?>
        <li class="page-item ">
      <a class="page-link" href="?halaman=<?= $_GET["halaman"] - 1;?>">Previous</a>
    </li>
    <?php } else { ?>
      <li class="page-item disabled ">
      <a class="page-link">Previous</a>
    </li>
    <?php }; ?>

    <?php for ($i = 1;  $i <= $jumlahHalaman ; $i++ ) :?>
        <?php if( $i == $onPage ) : ?>
            <li class="page-item active" aria-current="page">
      <a class="page-link" href="?halaman=<?= $i;?>"><?php echo $i; ?></a>
    </li>
        <?php else : ?>
            <li class="page-item"><a class="page-link" href="?halaman=<?= $i;?>"><?php echo $i; ?></a></li>
        <?php endif; ?>
    <?php endfor; ?>


    <?php if ($onPage < $jumlahHalaman ) :?>
    <li class="page-item">
      <a class="page-link" href="?halaman=<?= $onPage + 1;?>">Next</a>
    </li>
    <?php else : ?>
    <li class="page-item disabled">
      <a class="page-link">Next</a>
    </li>
      
    <?php endif;?>
    </div>
    </ul>
</nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
