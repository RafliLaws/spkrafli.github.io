
<?php
$conn = mysqli_connect("localhost","root","","spkrafli"); 

   
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
    $nama = $data["nama"];
    global $conn;
    $nama = str_replace(" ","_",$nama);
    $data = array_values($data);
    $arrQuery = [];
    for ($i=0; $i < 4; $i++) { 
        $arrQuery[] = $data[$i];
    }
    // foreach ($data as $col => $value) {
    //         $arrQuery[] = $value;  
    // }
    for ($i = 4; $i < count($data); $i++) {
        if ($i % 2 == 0) {
            $opsi[] = $data[$i];
        } else {
            $nilai[] = $data[$i];
        }
    }
    $opsi = implode(",", $opsi);
    $nilai = implode(",", $nilai);
    $arrQuery[] = $opsi;
    $arrQuery[] = $nilai;
    $arrQuery = str_replace("'","''",$arrQuery);
    $tempqMid = "'" . implode("','",$arrQuery);
    $qMid = rtrim($tempqMid, ",' " ) . "'";
    $query = "INSERT INTO bobot VALUES ($qMid)";
    if (mysqli_query($conn,$query)) {
        $query = "ALTER TABLE alternatif
                ADD $nama FLOAT DEFAULT 0";
        mysqli_query($conn,$query);
        return 1;
    };
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
    $id = $data["id"];
    $nama = str_replace(" ","_",$data["nama"]);
    $bobot = $data["bobot"];
    $kriteria = $data["kriteria"];
    $old = $data["old_name"];
    $data = array_values($data);
    $arrQuery = [];
    // foreach ($data as $col => $value) {
    //         $arrQuery[] = $value;  
    // }
    for ($i = 5; $i < count($data); $i++) {
        if ($i % 2 == 0) {
            $opsi[] = $data[$i];
        } else {
            $nilai[] = $data[$i];
        }
    }
    $opsi = implode(",", $opsi);
    $nilai = implode(",", $nilai);

    $arrQuery["id"] = $id;
    $arrQuery["nama"] = str_replace("_"," ",$nama);
    $arrQuery["bobot"] = $bobot;
    $arrQuery["kriteria"] = $kriteria;
    $arrQuery["opsi"] = $opsi;
    $arrQuery["nilai"] = $nilai;
    foreach ($arrQuery as $column => $value) {
        if ($column != "id" || $column != "submit"){
        $query = "UPDATE bobot SET $column = '$value' WHERE id = $id";
        mysqli_query($conn,$query);
        $hasil = mysqli_affected_rows($conn);
        if ($hasil == 1) {
            
            $result = 1;
        }
        }
    }
    $query = "ALTER TABLE alternatif RENAME COLUMN $old to $nama";
            mysqli_query($conn,$query);
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


function countCari($keyword,$topsis) {
    if ($topsis == 1) {
        $dbName = "hasil_topsis";
    } else {
        $dbName = "hasil_smart";
    }
    $query = "SELECT ranking, nama, nilai FROM $dbName WHERE
            nama LIKE '%$keyword%'";
     return queryassoc($query);
}


function cari($keyword,$awalData,$jumlahDataHalaman,$topsis) {
    if ($topsis == 1) {
        $dbName = "hasil_topsis";
    } else {
        $dbName = "hasil_smart";
    }
    $query = "SELECT ranking, nama, nilai FROM $dbName WHERE
            nama LIKE '%$keyword%' ORDER BY nilai DESC
            LIMIT $awalData, $jumlahDataHalaman
            ";
     return queryassoc($query);
}


function cari_peserta($keyword,$awalData,$jumlahDataHalaman) {
    $dbName = "alternatif";
    $query = "SELECT * FROM $dbName WHERE
            nama LIKE '%$keyword%'
            LIMIT $awalData, $jumlahDataHalaman
            ";
     return query($query);
}

?>