<?php 

// session_start();

// if ( !isset($_SESSION["login"]) ) {
//     header("Location: login.php");
//     exit;
// }
require 'functions.php';
session_start();
//koneksi ke databas

if ( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"];
//query datapeserta
$dataPeserta = queryassoc("SELECT * FROM alternatif WHERE id = $id");


    if ( isset($_POST["submit"]) ) {
      
        // cek apakah data berhasil berubah
        if ( ubah($_POST) > 0) {
            echo "
                <script>
                    alert('data berhasil diubah');
                    document.location.href = 'datapeserta.php'
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Data Sama / Tidak Berubah');
                </script>
            ";;
        }

    }
// var_dump($dataPeserta[0]["id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dta Mahasiswa Dityambah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
    
<nav class="navbar bg-success" data-bs-theme="dark">
<a href="datapeserta.php">

<div class="container-fluid d-flex justify-content-between">
<h4 aria-disabled="true" style="color: white;">
      < | Kembali
    </h4></a>
    <h4 style="color: white;">
      Ubah Data Peserta
    </h4>
    <h4 style="color: white;">
      
    </h4>
    

</nav>
 

    <div class="container-sm mt-5">
    <table class="table d-flex justify-content-center">

    <form action="" method="post">
    <input type="hidden" name="id" value="<?= $id ?>">
   
           <?php foreach ($dataPeserta as $dp) :?>
           <?php foreach ($dp as $key => $value) :?>
           <?php if ($key != "id") :?>
           <tr>
                <td class="text-end">
               <label for="<?= $key?>"><?= $key?> :</label>
                </td>
               <td>
                <input required class="form-control" value="<?= $value; ?>" type="text" name="<?= $key?>" id="<?= $key?>">
            </td>
           </tr>
          <?php endif ?>
          <?php endforeach ?>
           <?php endforeach ?>

            <tr class="d-flex justify-content-center">
              <td colspan="2">
                <button class="btn btn-primary" type="submit" name="submit">Ubah Data</button>
            </td>
            </tr>
    </form> 



    </table>







    <!-- <h1>Tambah Data Mahasiswa</h1>
   
    <form action="" method="post">
        <input type="hidden" name="id" value="<?= $dataPeserta[0]["id"] ?>">
       
        <ul>
            <?php foreach ($dataPeserta as $dp) :?>
            <?php foreach ($dp as $key => $value) :?>
            <?php if ($key != "id") :?>
            <li>
                <label for="<?= $key?>"><?= $key?></label>
                <input required value="<?= $value?>" type="text" name="<?= $key?>" id="<?= $key?>">
           </li>
           <?php endif ?>
           <?php endforeach ?>
            <?php endforeach ?>
            <li>
                <button type="submit" name="submit">BKirim</button>
            </li>
        </ul>
    </form>
    <a href="datapeserta.php">Kembali</a> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>l