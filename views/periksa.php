<?php
require_once 'Controllers/Periksa.php';
require_once 'Helpers/helper.php';

$periksa = new Periksa($pdo);

// Panggil metode index()
$data = $periksa->index();

if (isset($_POST['type'])) {
    if ($_POST['type'] == 'delete') {
        $row = $periksa->delete($_POST['id']);
        echo "<script>alert('Data berhasil dihapus')</script>";
        echo "<script>window.location='?url=periksa'</script>";
    }
}
?>

<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="mb-2">
        <a class="btn btn-success btn-sm" href="?url=periksa-input">
          Tambah Periksa
        </a>
      </div>

      <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Berat</th>
            <th>Tinggi</th>
            <th>Tensi</th>
            <th>Keterangan</th>
            <th>Nama Pasien</th>
            <th>Nama Paramedik</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
          <?php foreach ($data as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['berat'] ?></td>
                <td><?= $row['tinggi'] ?></td>
                <td><?= $row['tensi'] ?></td>
                <td><?= $row['keterangan'] ?></td>
                <td><?= $row['nama_pasien'] ?></td>
                <td><?= $row['nama_paramedik'] ?></td>
                <td>
                    <div class="d-flex">
                        <a href="?url=periksa-input&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mr-2">Edit</a>
                        <form action="" method="post" onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="type" value="delete">
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Berat</th>
            <th>Tinggi</th>
            <th>Tensi</th>
            <th>Keterangan</th>
            <th>Nama Pasien</th>
            <th>Nama Paramedik</th>
            <th>Aksi</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>