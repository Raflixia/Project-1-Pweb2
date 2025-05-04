<?php
require_once 'Controllers/Paramedik.php';
require_once 'Controllers/UnitKerja.php';
require_once 'Helpers/helper.php';

// Pastikan objek $paramedik dan $unitkerja diinisialisasi dengan benar
$paramedik = new Paramedik($pdo);
$unitkerja = new UnitKerja($pdo);

// Ambil data paramedik jika ada ID
$paramedik_id = isset($_GET['id']) ? $_GET['id'] : null;
$show_paramedik = $paramedik_id ? $paramedik->show($paramedik_id) : [];

// Ambil daftar unit kerja
$list_unitkerja = $unitkerja->index();

// Proses form submission
if (isset($_POST['type'])) {
    try {
        if ($_POST['type'] == 'create') {
            $id = $paramedik->create($_POST);
            echo "<script>alert('Data berhasil ditambahkan')</script>";
            echo "<script>window.location='?url=paramedik'</script>";
        } else if ($_POST['type'] == 'update') {
            $row = $paramedik->update($paramedik_id, $_POST);
            echo "<script>alert('Data berhasil diperbarui')</script>";
            echo "<script>window.location='?url=paramedik'</script>";
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
          <?= $paramedik_id ? 'Edit Paramedik' : 'Tambah Paramedik' ?>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label for="nama">Nama</label>
          <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars(getSafeFormValue($show_paramedik, 'nama')) ?>" required>
        </div>
        <div class="form-group">
          <label for="gender">Jenis Kelamin</label>
          <select class="form-control" id="gender" name="gender" required>
            <option value="L" <?= getSafeFormValue($show_paramedik, 'gender') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="P" <?= getSafeFormValue($show_paramedik, 'gender') == 'P' ? 'selected' : '' ?>>Perempuan</option>
          </select>
        </div>
        <div class="form-group">
          <label for="tmp_lahir">Tempat Lahir</label>
          <input type="text" class="form-control" id="tmp_lahir" name="tmp_lahir" value="<?= htmlspecialchars(getSafeFormValue($show_paramedik, 'tmp_lahir')) ?>" required>
        </div>
        <div class="form-group">
          <label for="tgl_lahir">Tanggal Lahir</label>
          <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?= htmlspecialchars(getSafeFormValue($show_paramedik, 'tgl_lahir')) ?>" required>
        </div>
        <div class="form-group">
          <label for="kategori">Kategori</label>
          <select class="form-control" id="kategori" name="kategori" required>
            <option value="" <?= getSafeFormValue($show_paramedik, 'kategori') == '' ? 'selected' : '' ?>>Tidak Ada</option>
            <option value="dokter" <?= getSafeFormValue($show_paramedik, 'kategori') == 'dokter' ? 'selected' : '' ?>>Dokter</option>
            <option value="perawat" <?= getSafeFormValue($show_paramedik, 'kategori') == 'perawat' ? 'selected' : '' ?>>Perawat</option>
            <option value="apoteker" <?= getSafeFormValue($show_paramedik, 'kategori') == 'apoteker' ? 'selected' : '' ?>>Apoteker</option>
          </select>
        </div>
        <div class="form-group">
          <label for="telepon">Telepon</label>
          <input type="text" class="form-control" id="telepon" name="telepon" value="<?= htmlspecialchars(getSafeFormValue($show_paramedik, 'telepon')) ?>" required>
        </div>
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars(getSafeFormValue($show_paramedik, 'alamat')) ?></textarea>
        </div>
        <div class="form-group">
          <label for="unit_kerja_id">Unit Kerja</label>
          <select class="form-control" id="unit_kerja_id" name="unit_kerja_id" required>
            <option value="">Pilih Unit Kerja</option>
            <?php foreach ($list_unitkerja as $unit): ?>
              <option value="<?= htmlspecialchars($unit['id']) ?>" <?= getSafeFormValue($show_paramedik, 'unit_kerja_id') == $unit['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($unit['nama']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="card-footer text-right">
        <input type="hidden" name="type" value="<?= $paramedik_id ? 'update' : 'create' ?>">
        <input type="hidden" name="id" value="<?= $paramedik_id ?>">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>

  </form>
</div>