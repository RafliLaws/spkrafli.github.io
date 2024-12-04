<?php 
session_start();
require 'functions.php';

if ( isset($_COOKIE['aydi']) && isset($_COOKIE['key']) ) {
    $aydi = $_COOKIE['aydi'];
    $key = $_COOKIE['key'];

    //ambil username berdasarkan
    $result = mysqli_query($conn, "SELECT username FROM user WHERE id = $aydi");
    $row = mysqli_fetch_assoc($result);

    // cek cookie dan username
    if ( $key == hash('sha256', $row['username']) ) {
        $_SESSION['login'] = true;
        
    }
}

//koneksi ke database
if ( isset($_SESSION["login"]) ) {
    header("Location: main.php");
    exit;
}

if ( isset($_POST["login"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];


    //cek username
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {

        //cek password
        $row = mysqli_fetch_assoc($result);
        if ( password_verify($password, $row["password"]) ){

            
            // set session
            $_SESSION["login"] = true;
            // cek remember me
            // if ( isset($_POST['remember']) ) {
                //  buat cookie
                setcookie('aydi', $row['id'], time() + 6000000);
                setcookie('key', hash('sha256', $row['username']),time() + 6000000 );
                if ($row["role"] == "admin") {
                    $_SESSION["role"] = "admin";
                } else {
                    $_SESSION["role"] = "pengguna";
                }
             
            // }

            header("Location: main.php");
            exit;
        }

    }
    $error = true;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Lomgin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
<div class="position-absolute top-50 start-50 translate-middle">

    <div class="container-sm">
    <h1>SPK Rafli</h1>
    <table class="table d-flex justify-content-center">

    <form action="" method="post">
    <tr>
        <tr>
            <td> <label for="username">Username :</label> </td>
            <td> <input type="text" name="username" id="username"></td>
    </tr>
     <tr>
            <td><label for="password">Password :</label></td>    
            <td><input type="password" name="password" id="password"></td>
    </tr>
     
   
       
    <tr class="d-flex justify-content-center">
              <td colspan="2">
                <button class="btn btn-primary" type="submit" name="login">Login</button>
            </td>
            </tr>
    </tr>


    </table>

<?php if ( isset($error) ) : ?>
    <p style="color: red; font-style:italic;">Username / Password salah </p>
    <?php endif; ?>



</form>
    <!-- <?php if ( isset($error) ) : ?>
    <p style="color: red; font-style:italic;">Username / Password salah </p>
    <?php endif; ?>
    <form action="" method="post">

    <ul>
        <li>
            <label for="username">Username</label>
            <input type="text" name="username" id="username">
        </li>
        <li>
            <label for="password">password</label>
            <input type="password" name="password" id="password">
        </li>
        <li>
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember me</label>
        </li>
        <li>
            <button type="submit" name="login">Login</button>
        </li>
    </ul>

    </form> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </div>
</body>
</html>