<?php
require_once 'Controllers/Periksa.php';
require_once 'Controllers/Pasien.php';
require_once 'Controllers/Paramedik.php';
require_once 'Helpers/helper.php';

// Pastikan objek $periksa, $pasien, dan $paramedik diinisialisasi dengan benar
$periksa = new Periksa($pdo);
$pasien = new Pasien($pdo);
$paramedik = new Paramedik($pdo);

// Ambil data pemeriksaan jika ada ID
$periksa_id = isset($_GET['id']) ? $_GET['id'] : null;
$show_periksa = $periksa_id ? $periksa->show($periksa_id) : [];

// Ambil daftar pasien dan paramedik
$list_pasien = $pasien->index();
$list_paramedik = $paramedik->index();

// Proses form submission
if (isset($_POST['type'])) {
    try {
        if ($_POST['type'] == 'create') {
            $id = $periksa->create($_POST);
            echo "<script>alert('Data berhasil ditambahkan')</script>";
            echo "<script>window.location='?url=periksa'</script>";
        } else if ($_POST['type'] == 'update') {
            $row = $periksa->update($periksa_id, $_POST);
            echo "<script>alert('Data berhasil diperbarui')</script>";
            echo "<script>window.location='?url=periksa'</script>";
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
          <?= $periksa_id ? 'Edit Pemeriksaan' : 'Tambah Pemeriksaan' ?>
        </div>
      </div>
      <div class="card-body">

        <!-- Dropdown Pasien -->
        <div class="form-group">
          <label for="pasien_id">Pasien</label>
          <select class="form-control" id="pasien_id" name="pasien_id" required>
            <option value="">Pilih Pasien</option>
            <?php foreach ($list_pasien as $pasien): ?>
              <option value="<?= htmlspecialchars($pasien['id']) ?>" <?= getSafeFormValue($show_periksa, 'pasien_id') == $pasien['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($pasien['nama']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Dropdown Paramedik -->
        <div class="form-group">
          <label for="paramedik_id">Paramedik</label>
          <select class="form-control" id="paramedik_id" name="paramedik_id" required>
            <option value="">Pilih Paramedik</option>
            <?php foreach ($list_paramedik as $paramedik): ?>
              <option value="<?= htmlspecialchars($paramedik['id']) ?>" <?= getSafeFormValue($show_periksa, 'paramedik_id') == $paramedik['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($paramedik['nama']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Input Tanggal -->
        <div class="form-group">
          <label for="tanggal">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars(getSafeFormValue($show_periksa, 'tanggal')) ?>" required>
        </div>

        <!-- Input Berat -->
        <div class="form-group">
          <label for="berat">Berat (kg)</label>
          <input type="number" step="0.1" class="form-control" id="berat" name="berat" value="<?= htmlspecialchars(getSafeFormValue($show_periksa, 'berat')) ?>" required>
        </div>

        <!-- Input Tinggi -->
        <div class="form-group">
          <label for="tinggi">Tinggi (cm)</label>
          <input type="number" step="0.1" class="form-control" id="tinggi" name="tinggi" value="<?= htmlspecialchars(getSafeFormValue($show_periksa, 'tinggi')) ?>" required>
        </div>

        <!-- Input Tensi -->
        <div class="form-group">
          <label for="tensi">Tensi</label>
          <input type="text" class="form-control" id="tensi" name="tensi" value="<?= htmlspecialchars(getSafeFormValue($show_periksa, 'tensi')) ?>" required>
        </div>

        <!-- Input Keterangan -->
        <div class="form-group">
          <label for="keterangan">Keterangan</label>
          <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= htmlspecialchars(getSafeFormValue($show_periksa, 'keterangan')) ?></textarea>
        </div>
      </div>

      <div class="card-footer text-right">
        <input type="hidden" name="type" value="<?= $periksa_id ? 'update' : 'create' ?>">
        <input type="hidden" name="id" value="<?= $periksa_id ?>">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>

  </form>
</div>