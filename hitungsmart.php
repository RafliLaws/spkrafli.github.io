<?php 
   
  
   $conn = mysqli_connect("localhost","root","","test"); 


function hitung_smart() {
$sql = "SELECT * FROM alternatif";
$sqlNama = "SELECT nama FROM alternatif;";
$sqlBobot = "SELECT bobot, kriteria FROM bobot";


$nama = query($sqlNama);
$hasilAlternatif = query($sql);
$bobot = query($sqlBobot);
$tempMinMax = [];
$nilaiMax = [];
$nilaiMin = [];
$jumlahKriteria = count($bobot);
$jumlahData = count($nama);
$tempNilaiNormalisasi = [];
$nilaiNormalisasi = [];
$nilaiUtility = [];
$total = [];

// Konversi bobot Topsis ke Bobot Smart dengan nilai maksimal 1
$hasilBobot = $bobot;
for ($i=0; $i < $jumlahKriteria ; $i++) { 
    $bobotContain[] = $bobot[$i][0];
   }
$tempBobot = array_sum($bobotContain);
for ($i=0; $i < $jumlahKriteria ; $i++) { 
     $bobot[$i][0] = $bobot[$i][0] / $tempBobot;
    }

// Operasi dibawah khusus untuk Ignore Name, Id, Kode
$jumlahKriteria = $jumlahKriteria + 3;
//ignoring the Id, Kode, Nama. Nilai berasal dari Index ke = 3
for ($i=3; $i < $jumlahKriteria; $i++) { 
    for ($j=0; $j < $jumlahData; $j++) { 
        $hasil[$j][$i-3] = $hasilAlternatif[$j][$i];
    }
 }


 $jumlahKriteria = count($bobot);
// Mencari Nilai Maksimal dan Minimal
for ($i=0; $i < $jumlahKriteria ; $i++) { 
    for ($j=0; $j < $jumlahData ; $j++) { 
        $tempMinMax[$j] = $hasil[$j][$i];
    }
    $nilaiMax[] = max($tempMinMax);
    $nilaiMin[] = min($tempMinMax);
}

//Melakukan Matriks Normalisasi
for ($i=0; $i < $jumlahKriteria ; $i++) { 
    for ($j=0; $j < $jumlahData ; $j++) { 
        if ($bobot[$i][1] == "Cost") {
            $tempNilaiNormalisasi[$j][$i] = (($nilaiMax[$i] - $hasil[$j][$i]) / ($nilaiMax[$i] - $nilaiMin[$i])) * 1;
        } else {
            $tempNilaiNormalisasi[$j][$i] = (($hasil[$j][$i] - $nilaiMin[$i]) / ($nilaiMax[$i] - $nilaiMin[$i])) * 1;
        }
        $nilaiNormalisasi[$j][$i] = $tempNilaiNormalisasi[$j][$i];
    }
   
}


// Mencari Nilai Utility
for ($i=0; $i < $jumlahKriteria ; $i++) { 
    for ($j=0; $j < $jumlahData ; $j++) { 
     $nilaiUtility[$j][$i] = $nilaiNormalisasi[$j][$i] * $bobot[$i][0];
    }
   
}


//Menghitung Hasil Akhir
foreach ($nilaiUtility as $nilai) {
    $total[] = array_sum($nilai);
   
}
for ($i=0; $i < $jumlahData ; $i++) { 
    $namaContain[] = $nama[$i][0];
}




$temp = array_combine($namaContain, $total);

foreach ($temp as $key => $value) {
   
    $endResults[] = array('nama' => $key,'nilai' => $value);
    
}

// var_dump($jmlKrit);
// var_dump($endResults);
// echo $jumlahKriteria;
// var_dump($hasil);
// var_dump($nilaiMin);
// var_dump($nilaiMax);

return $endResults;

}


?>