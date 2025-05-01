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
//query mahasiswa berdasarkan id
$dataPeserta = queryassoc("SELECT * FROM bobot limit 0,1");


    if ( isset($_POST["submit"]) ) {
        //ambil data dari tiap elemen dalam form
       
        array_pop($_POST);
        //query insert data
       
        // cek apakah data berhasil berubah
        if ( tambah_kriteria($_POST) > 0) {
            echo "
                <script>
                    alert('data berhasil ditambah');
                    document.location.href = 'datakriteria.php'
                </script>
            ";
        } else {
            echo "DATA GAGAL BLOK!";
        }

    }
// var_dump($dataPeserta[0]["id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kriteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<nav class="navbar bg-success" data-bs-theme="dark">
<a href="datakriteria.php">

<div class="container-fluid d-flex justify-content-between">
<h4 aria-disabled="true" style="color: white;">
      < | Kembali
    </h4></a>
    <h4 style="color: white;">
      Tambah Data Kriteria
    </h4>
    <h4 style="color: white;">
      
    </h4>
    

</nav>
 
<div class="card text-bg-warning mt-5 mb-3 mx-auto" style="max-width: 30rem;">
  <div class="card-header">Perhatikan</div>
  <div class="card-body">
    <ul>
        <li class="card-text">
            Jangan menggunakan Aposthrope/Tanda Kutip (') dalam penamaan kriteria. Contoh: Jum'at
        </li>
    </ul>
  </div>
</div>

    <div class="container-sm">


    <form action="" method="post" >
    <table class="table d-flex justify-content-center" id="myTable">
    <input type="hidden" name="id" value="0">
  
           <?php foreach ($dataPeserta as $dp) :?>
           <?php foreach ($dp as $key => $value) :?>
           <?php if ($key != "id" && $key != "opsi" && $key != "nilai" ) :?>
           <tr>
                <td class="text-end">
               <label for="<?= $key?>"><?= $key?> :</label>
                </td>
               <td colspan="3">
               <?php if ($key != "kriteria") { ?>
                <input required class="form-control" value="" type="text" name="<?= $key?>" id="<?= $key?>">
                <?php } else { ?>
                    <select class="form-select" aria-label="Default select example" name="<?= $key?>" id="<?= $key?>">
                        <option selected>Pilih Jenis Kriteria</option>
                        <option value="Benefit">Benefit</option>
                        <option value="Cost">Cost</option>
                    </select>
            </td>
           </tr>
          <?php } ?>
          <?php endif; ?>
          <?php endforeach ?>
           <?php endforeach ?>
            <tr id="opsi">
                <td> Opsi</td>
                <td>
                <input required class="form-control nama-opsi" value="" type="text" name="opsi-1"> 
                </td>
                <td> Nilai</td>
                <td>  
                <input required class="form-control nilai-opsi" value="" type="text" name="nilai-1"> 
            </td>
            </tr>
      
            
                
                </table>
               
            <div style="display: flex; justify-content: center; align-text: center;">
                <button class="btn btn-primary" type="submit" name="submit">Tambah Data Kriteria</button>
            </div>

  

    <button  onclick="tambah()"> Tambah Opsi </button>
            

    </form> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>

const tbodyRef = document.getElementById('myTable').getElementsByTagName('tbody')[0];
        let i = 2;
    let parent = document.getElementById("form")

// tambah.addEventListener("click", function() {
//         let opsi = document.getElementById("opsi")
//         opsi.setAttribute("id", i++)
//         form.appendChild(opsi);
//         console.log(opsi);
// })
function tambah() {

    let opsi = document.getElementById("opsi").cloneNode(true)
    let namaOpsi = opsi.querySelector(".nama-opsi")
    let nilaiOpsi = opsi.querySelector(".nilai-opsi")
    console.log(namaOpsi);
    namaOpsi.setAttribute("name", "opsi-" + i)
    nilaiOpsi.setAttribute("name", "nilai-" + i)
    let pilihan = opsi.cloneNode(true);
        pilihan.setAttribute("id", i++)
        tbodyRef.appendChild(pilihan);
        console.log(opsi);
}

    
    </script>
</html>