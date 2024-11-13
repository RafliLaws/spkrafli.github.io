<?php 
require 'functions.php';

$id = $_GET["id"];
$kriteria = $_GET["kriteria"];

if ( hapus_kriteria($id,$kriteria) > 0) {
    echo "
                <script>
                    alert('data berhasil dihapus');
                    document.location.href = 'datakriteria.php'
                </script>
            ";

}


?>
