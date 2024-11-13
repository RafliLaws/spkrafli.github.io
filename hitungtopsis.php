<?php

  
    $conn = mysqli_connect("localhost","root","","test"); 

    
// function query($query){
//     global $conn;
//     $result = mysqli_query($conn, $query);
//     $rows = [];
//     while ( $row = mysqli_fetch_row($result) ) {
//         $rows[] = $row;
//     }
//     return $rows;
// }

function hitung_topsis() { 

$sql = "SELECT * FROM alternatif";
$sqlNama = "SELECT nama FROM alternatif;";
$sqlBobot = "SELECT bobot, kriteria FROM bobot";

// Don't worry about these ;)
$nama = query($sqlNama);
$hasilAlternatif = query($sql);
$bobot = query($sqlBobot);
$tempR = array(array());
$tempXProcess = array(array());
$tempX = array();
$tempY = array(array());
$hasil = [];
$idealPosA = [];
$idealMinA = [];
$containerMin =[];
$containerMax =[];
$tempJarakPos = [];
$jarakPos = [];
$tempJarakMin = [];
$jarakMin = [];
$hasilAkhir = [];
$jumlahData = count($nama);
$jumlahKriteria = count($bobot);
// var_dump($jumlahKriteria);
$jumlahKriteria = $jumlahKriteria +  3;
//ignoring the name and id
for ($i=3; $i < $jumlahKriteria; $i++) { 
    for ($j=0; $j < $jumlahData; $j++) { 
        $hasil[$j][$i-3] = $hasilAlternatif[$j][$i];
    }
 }

 $jumlahKriteria = count($bobot);
//  var_dump($hasil);
// Matrix Ternormalisasi R
 for ($i=0; $i < $jumlahKriteria ; $i++) { 
    for ($j=0; $j < $jumlahData; $j++) { 
        $tempXProcess[$i][$j] = pow($hasil[$j][$i],2);
    }
    $tempX[$i] = sqrt(array_sum($tempXProcess[$i]));
 }


 for ($i=0; $i < $jumlahKriteria ; $i++) { 
    for ($j=0; $j < $jumlahData; $j++) { 
        //Nilai Normalisasi 
        $tempR[$i][$j] =  $hasil[$j][$i] / $tempX[$i];

        //Nilai Y
        $tempY[$i][$j] = $tempR[$i][$j] * $bobot[$i][0];
    }
 }

 // Solusi Ideal positif A+
 for ($i=0; $i < $jumlahKriteria ; $i++) { 
    for ($j=0; $j < $jumlahData; $j++) { 
    $idealPosA[$j] = $tempY[$i][$j];
    }
    if ($bobot[$i][1] == "Cost") {
        $containerMax[] = min($idealPosA);
    } else {
        $containerMax[] = max($idealPosA);
    }

 }

 
 // Solusi Ideal negatif A-
 for ($i=0; $i < $jumlahKriteria ; $i++) { 
    for ($j=0; $j < $jumlahData; $j++) { 
    $idealMinA[$j] = $tempY[$i][$j];
    }
    if ($bobot[$i][1] == "Cost") {
        $containerMin[] = max($idealMinA);
    } else {
        $containerMin[] = min($idealMinA);
    }

 }

 // Jarak Positif
 for ($i=0; $i < $jumlahData ; $i++) { 
    for ($j=0; $j < $jumlahKriteria; $j++) { 
    $tempJarakPos[$j] = pow(($tempY[$j][$i] - $containerMax[$j]), 2);
    }
    $jarakPos[] = sqrt(array_sum($tempJarakPos));
 }

 
 // Jarak Minus
 for ($i=0; $i < $jumlahData ; $i++) { 
    for ($j=0; $j < $jumlahKriteria; $j++) { 
    $tempJarakMin[$j] = pow(($tempY[$j][$i] - $containerMin[$j]), 2);
    }
    $jarakMin[] = sqrt(array_sum($tempJarakMin));
 }


 // Final Calculation
 for ($i=0; $i < $jumlahData ; $i++) { 
    $hasilAkhir[] = $jarakMin[$i] / ($jarakMin[$i] + $jarakPos[$i]);
 }

 $namaContain = [];

 for ($i=0; $i < $jumlahData ; $i++) { 
    $namaContain[] = $nama[$i][0];
 }

 $endResults = array_combine($namaContain, $hasilAkhir);
 

 foreach ($endResults as $key => $value) {
   
    $hasilTopsis[] = array('nama' => $key,'nilai' => $value);
    
}


// var_dump($container);
//  var_dump($tempY);
//  var_dump($tempY);
// echo "COBA YAAAAAAAAAA";
// var_dump($jarakPos);
// var_dump($tempJarakPos);
// var_dump($jarakMin);
// var_dump($testObject);
// var_dump($containerMax);
// var_dump($containerMin);
return $hasilTopsis;
}
?>