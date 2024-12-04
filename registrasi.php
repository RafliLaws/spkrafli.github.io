<?php 
session_start();
require 'functions.php';

if ( !isset($_SESSION["login"] ) ) {
    header("Location: login.php");
    exit;
}


if ( $_SESSION["role"] != "admin" ) {
    header("Location: login.php");
    exit;
}

if (isset($_POST["register"])) {
    if ( registrasi($_POST) > 0) {
        echo "<script>
        alert('User Baru berhasil ditambahkan');
        document.location.href = 'user.php'

        </script>";
    } else {
        echo "data gagal ditambahkan";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>


<nav class="navbar bg-success" data-bs-theme="dark">
<a href="user.php">

<div class="container-fluid d-flex justify-content-between">
<h4 aria-disabled="true" style="color: white;">
      < | Kembali
    </h4></a>
    <h4 style="color: white;">
      Tambah Data User
    </h4>
    <h4 style="color: white;">
      
    </h4>
    

</nav>
 

    <div class="container-sm mt-5">
    <table class="table d-flex justify-content-center">

<form action="" method="post">
    <tr>
        <tr>
            <td> <label ftd="username">Username :</label> </td>
            <td> <input type="text" name="username" id="username"></td>
    </tr>
     <tr>
            <td><label for="password">Password :</label></td>    
            <td><input type="password" name="password" dd="password"></td>
    </tr>
     <tr>
            <td><label for="password2">Confirm Password :</label></td>
            <td><input type="password" name="password2" id="password2"></td>
    </tr>
   
        <tr>
        <td><label for="role">Tingkat User</label></td>
            <td><select class="form-select" aria-label="Defatrt select example" name="role" id="role">
                        <option selected>Pilih Tingkat</option>
                        <option value="admin">Admin</option>
                        <option value="pengguna">Pengguna</option>
                    </select></td>
        
    </tr>
    <tr class="d-flex justify-content-center">
              <td colspan="2">
                <button class="btn btn-primary" type="submit" name="register">Tambah Data Pengguna</button>
            </td>
            </tr>
    </tr>


    </table>

<?php if ( isset($error) ) : ?>
    <p style="color: red; font-style:italic;">Username / Password salah </p>
    <?php endif; ?>



</form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>