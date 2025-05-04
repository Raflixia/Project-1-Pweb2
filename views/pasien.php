<?php
require_once 'Controllers/Pasien.php';
require_once 'Helpers/helper.php';

// Pastikan objek $pasien diinisialisasi dengan benar
$pasien = new Pasien($pdo);

// Ambil daftar pasien
try {
    $list_pasien = $pasien->index();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Proses penghapusan data
if (isset($_POST['type']) && $_POST['type'] == 'delete') {
    try {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $row = $pasien->delete($_POST['id']);
            echo "<script>alert('Data berhasil dihapus')</script>";
            echo "<script>window.location='?url=pasien'</script>";
        } else {
            echo "<script>alert('ID pasien tidak valid.')</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "')</script>";
    }
}
?>

<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="mb-2">
        <a class="btn btn-success btn-sm" href="?url=pasien-input">
          Tambah Pasien
        </a>
      </div>

      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Kelurahan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          <?php foreach ($list_pasien as $row): ?>
          <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['kode']) ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['tmp_lahir']) ?></td>
              <td><?= htmlspecialchars($row['tgl_lahir']) ?></td>
              <td><?= htmlspecialchars($row['gender'] == 'L' ? 'Laki-laki' : 'Perempuan') ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['alamat']) ?></td>
              <td><?= htmlspecialchars($row['nama_kelurahan']) ?></td>
              <td>
                  <div class="d-flex">
                      <a href="?url=pasien-input&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mr-2">Edit</a>
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
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Kelurahan</th>
            <th>Aksi</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>