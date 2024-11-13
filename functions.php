
<?php
$conn = mysqli_connect("localhost","root","","test"); 

   
function query($query){
   global $conn;
   $result = mysqli_query($conn, $query);
   $rows = [];
   while ( $row = mysqli_fetch_row($result) ) {
       $rows[] = $row;
   }
   return $rows;
}

function queryassoc($query){
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }
    return $rows;
 }


 function hapus($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM alternatif WHERE id = $id");

return mysqli_affected_rows($conn);


}


function hapus_user($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM user WHERE id = $id");

return mysqli_affected_rows($conn);


}


function hapus_kriteria($id,$kriteria) {
    global $conn;
    $kriteria = str_replace(" ","_",$kriteria);
    mysqli_query($conn, "ALTER TABLE alternatif DROP COLUMN $kriteria");
    mysqli_query($conn, "DELETE FROM bobot WHERE id = $id");

return mysqli_affected_rows($conn);


}



function tambah_kriteria($data) {
    global $conn;
    var_dump($data);
    $nama = $data["nama"];
    $nama = str_replace(" ","_",$nama);
    $arrQuery =[];
    foreach ($data as $col => $value) {
            $arrQuery[] = $value;  
    }
    $arrQuery = str_replace("'","''",$arrQuery);
    $tempqMid = "'" . implode("','",$arrQuery);
    $qMid = rtrim($tempqMid, ",' " ) . "'";
    $query = "ALTER TABLE alternatif
                ADD $nama FLOAT DEFAULT 0";
    mysqli_query($conn,$query);
    $query = "INSERT INTO bobot VALUES ($qMid)";
    mysqli_query($conn,$query);
return mysqli_affected_rows($conn);
}


function tambah_peserta($data) {
    global $conn;
    $arrQuery =[];
    $jumlahData = count($data);
    foreach ($data as $col => $value) {
            $arrQuery[] = $value;  
    }
    $arrQuery = str_replace("'","''",$arrQuery);
    $tempqMid = "'" . implode("','",$arrQuery);
    $qMid = rtrim($tempqMid, ",' " ) . "'";
    $query = "INSERT INTO alternatif VALUES ($qMid)";
    mysqli_query($conn,$query);
return mysqli_affected_rows($conn);
}




function ubah($data) {
    global $conn;
    $result = 0;
    $data = str_replace("'","''",$data);
    $id = $data["id"];
    foreach ($data as $column => $value) {
        if ($column != "id" || $column != "submit"){
        $query = "UPDATE alternatif SET $column = '$value' WHERE id = $id";
        mysqli_query($conn,$query);
        $hasil = mysqli_affected_rows($conn);
        if ($hasil == 1) {
            $result = 1;
        }
        }
    }
return $result;
    
}


function ubah_kriteria($data) {
    global $conn;
    $result = 0;
    var_dump($data);
    $id = $data["id"];
    foreach ($data as $column => $value) {
        if ($column != "id" || $column != "submit"){
        $query = "UPDATE bobot SET $column = '$value' WHERE id = $id";
        mysqli_query($conn,$query);
        $hasil = mysqli_affected_rows($conn);
        if ($hasil == 1) {
            $result = 1;
        }
        }
    }
return $result;
    
}


function registrasi($data) {
    global $conn;
    $username = strtolower(stripslashes($data["username"]));
    $role = strtolower(stripslashes($data["role"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);
    $validate = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
    if (mysqli_fetch_assoc($validate)) {
        echo "<script>
                alert ('username sudah terdaftar')
                </script>";
                return false;
    }
    // cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>
        alert('Password tidak sesuai');
        </script>";
        return false;
    } 

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // tambahkan user ke database
    mysqli_query($conn, "INSERT INTO user VALUES ('', '$username', '$password', '$role')");

    return mysqli_affected_rows($conn);
}

function nilai_topsis($data) {
    global $conn;
    $nama = $data["nama"];
    $nilai = $data["nilai"];

    $query = "INSERT INTO hasil_topsis VALUES ('', '$nama', '$nilai')";
    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);

}


?>