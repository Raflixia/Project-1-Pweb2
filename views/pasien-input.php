<?php
require_once 'Controllers/Pasien.php';
require_once 'Controllers/Kelurahan.php';
require_once 'Helpers/helper.php';

// Pastikan objek $pasien dan $kelurahan diinisialisasi dengan benar
$pasien = new Pasien($pdo);
$kelurahan = new Kelurahan($pdo);

// Ambil data pasien jika ada ID
$pasien_id = isset($_GET['id']) ? $_GET['id'] : null;
$show_pasien = $pasien_id ? $pasien->show($pasien_id) : [];

// Ambil data pasien terbaru untuk kode otomatis
$latest_pasien = $pasien->getLatestPasien();
$latest_kode = $latest_pasien ? $latest_pasien['kode'] : 'P000';

// Ambil daftar kelurahan
$list_kelurahan = $kelurahan->index();

// Proses form submission
if (isset($_POST['type'])) {
    try {
        if ($_POST['type'] == 'create') {
            $id = $pasien->create($_POST);
            echo "<script>alert('Data berhasil ditambahkan')</script>";
            echo "<script>window.location='?url=pasien'</script>";
        } else if ($_POST['type'] == 'update') {
            $row = $pasien->update($pasien_id, $_POST);
            echo "<script>alert('Data berhasil diperbarui')</script>";
            echo "<script>window.location='?url=pasien'</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "')</script>";
    }
}
?>

<div class="container">
  <form method="post">

    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <?= $pasien_id ? 'Edit Pasien' : 'Tambah Pasien' ?>
        </div>
      </div>
      <div class="card-body">
        <!-- Input Kode -->
        <div class="form-group">
          <label for="kode">Kode</label>
          <input type="text" class="form-control" id="kode" name="kode" 
            value="<?= getSafeFormValue($show_pasien, 'kode') != "" 
                ? getSafeFormValue($show_pasien, 'kode') 
                : 'P' . str_pad((int)substr($latest_kode, 1) + 1, 3, '0', STR_PAD_LEFT) ?>" 
            required>
        </div>

        <!-- Input Nama -->
        <div class="form-group">
          <label for="nama">Nama</label>
          <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars(getSafeFormValue($show_pasien, 'nama')) ?>" required>
        </div>

        <!-- Input Tempat Lahir -->
        <div class="form-group">
          <label for="tmp_lahir">Tempat Lahir</label>
          <input type="text" class="form-control" id="tmp_lahir" name="tmp_lahir" value="<?= htmlspecialchars(getSafeFormValue($show_pasien, 'tmp_lahir')) ?>" required>
        </div>

        <!-- Input Tanggal Lahir -->
        <div class="form-group">
          <label for="tgl_lahir">Tanggal Lahir</label>
          <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?= htmlspecialchars(getSafeFormValue($show_pasien, 'tgl_lahir')) ?>" required>
        </div>

        <!-- Input Jenis Kelamin -->
        <div class="form-group">
          <label for="gender">Jenis Kelamin</label>
          <select class="form-control" id="gender" name="gender" required>
            <option value="L" <?= getSafeFormValue($show_pasien, 'gender') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="P" <?= getSafeFormValue($show_pasien, 'gender') == 'P' ? 'selected' : '' ?>>Perempuan</option>
          </select>
        </div>

        <!-- Input Email -->
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars(getSafeFormValue($show_pasien, 'email')) ?>" required>
        </div>

        <!-- Input Alamat -->
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars(getSafeFormValue($show_pasien, 'alamat')) ?></textarea>
        </div>

        <!-- Dropdown Kelurahan -->
        <div class="form-group">
          <label for="kelurahan_id">Kelurahan</label>
          <select class="form-control" id="kelurahan_id" name="kelurahan_id" required>
            <option value="">Pilih Kelurahan</option>
            <?php foreach ($list_kelurahan as $kelurahan): ?>
              <option value="<?= htmlspecialchars($kelurahan['id']) ?>" <?= getSafeFormValue($show_pasien, 'kelurahan_id') == $kelurahan['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($kelurahan['nama']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="card-footer text-right">
        <input type="hidden" name="type" value="<?= $pasien_id ? 'update' : 'create' ?>">
        <input type="hidden" name="id" value="<?= $pasien_id ?>">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>

  </form>
</div>