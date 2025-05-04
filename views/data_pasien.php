<?php
// 1. sertakan koneksi database
require_once 'dbkoneksi.php';
// 2. definisikan query
$sql = "SELECT * FROM pasien";
// 3. jalankan query
$query = $dbh->query($sql);
// 4. tampilkan hasil query
?>

<table class="table table-bordered">
    <thead>
        <tr><th width="15">No</th><th>Kode</th><th>Nama Pasien</th>
        <th>Alamat</th><th>Email</th></tr></thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach($query as $row){
            echo "<tr><td>".$nomor."</td><td>".$row['kode'].
            "</td><td>".$row['nama']."</td><td>".$row['alamat'].
            "</td><td>".$row['email']."</td></tr>";
        }
?>
</tbody>
</table>