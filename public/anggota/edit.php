<?php
require __DIR__ . '/../../app/db.php';
$active = 'anggota';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM anggota WHERE id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch();

if (!$row) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['action']) && $_POST['action']==='update') {
  $nama = trim($_POST['nama'] ?? '');
  $no_ktp = trim($_POST['no_ktp'] ?? '');
  $alamat = trim($_POST['alamat'] ?? '');
  $no_hp = trim($_POST['no_hp'] ?? '');

  $stmt = $pdo->prepare('UPDATE anggota SET nama=?, no_ktp=?, alamat=?, no_hp=? WHERE id=?');
  $stmt->execute([$nama, $no_ktp !== '' ? $no_ktp : null, $alamat !== '' ? $alamat : null, $no_hp !== '' ? $no_hp : null, $id]);
  header('Location: index.php');
  exit;
}

ob_start();
?>
<div class="grid">
  <div class="card wide">
    <div class="kpi">
      <div>
        <div class="label">Edit Anggota</div>
        <div style="font-size:18px; font-weight:800; margin-top:6px">ID: <?php echo $id ?></div>
      </div>
    </div>
    <div style="height:14px"></div>

    <div class="form">
      <form method="post">
        <input type="hidden" name="action" value="update" />
        <div class="row">
          <div><input name="nama" placeholder="Nama" value="<?php echo htmlspecialchars($row['nama']) ?>" required></div>
          <div><input name="no_ktp" placeholder="No KTP" value="<?php echo htmlspecialchars((string)$row['no_ktp']) ?>"></div>
        </div>
        <div class="row">
          <div><input name="alamat" placeholder="Alamat" value="<?php echo htmlspecialchars((string)$row['alamat']) ?>"></div>
          <div><input name="no_hp" placeholder="No HP" value="<?php echo htmlspecialchars((string)$row['no_hp']) ?>"></div>
        </div>
        <div style="display:flex; gap:10px; margin-top:10px">
          <button class="btn primary" type="submit">Simpan Perubahan</button>
          <a href="index.php" class="btn">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
$pageContent = ob_get_clean();
require __DIR__ . '/../layout.php';