<?php
require __DIR__ . '/../../app/db.php';

$active = 'anggota';

if (isset($_POST['action']) && $_POST['action']==='create') {
  $nama = trim($_POST['nama'] ?? '');
  $no_ktp = trim($_POST['no_ktp'] ?? '');
  $alamat = trim($_POST['alamat'] ?? '');
  $no_hp = trim($_POST['no_hp'] ?? '');

  $stmt = $pdo->prepare('INSERT INTO anggota (nama,no_ktp,alamat,no_hp) VALUES (?,?,?,?)');
  $stmt->execute([$nama, $no_ktp !== '' ? $no_ktp : null, $alamat !== '' ? $alamat : null, $no_hp !== '' ? $no_hp : null]);
  header('Location: index.php');
  exit;
}

// Logika Hapus Anggota
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $stmt = $pdo->prepare('DELETE FROM anggota WHERE id = ?');
  $stmt->execute([$id]);
  header('Location: index.php');
  exit;
}

$anggota = $pdo->query('SELECT * FROM anggota ORDER BY id DESC LIMIT 50')->fetchAll();

ob_start();
?>
<div class="grid">
  <div class="card wide">
    <div class="kpi">
      <div>
        <div class="label">Data Anggota</div>
        <div style="font-size:18px; font-weight:800; margin-top:6px">Kelola anggota koperasi</div>
      </div>
    <div style="height:14px"></div>

    <div class="row">
      <div class="form">
        <form method="post">
          <input type="hidden" name="action" value="create" />
          <div class="row">
            <div><input name="nama" placeholder="Nama" required></div>
            <div><input name="no_ktp" placeholder="No KTP"></div>
          </div>
          <div class="row">
            <div><input name="alamat" placeholder="Alamat"></div>
            <div><input name="no_hp" placeholder="No HP"></div>
          </div>
          <div style="display:flex; gap:10px; margin-top:10px">
            <button class="btn primary" type="submit">Tambah Anggota</button>
          </div>
        </form>
      </div>
      <div>
        <table class="table">
          <thead>
            <tr><th>ID</th><th>Nama</th><th>No HP</th><th>Aksi</th></tr>
          </thead>
          <tbody>
            <?php foreach($anggota as $a): ?>
              <tr>
                <td><?php echo (int)$a['id'] ?></td>
                <td><?php echo htmlspecialchars($a['nama']) ?></td>
                <td><?php echo htmlspecialchars((string)$a['no_hp']) ?></td>
                <td>
                  <a href="edit.php?id=<?php echo $a['id'] ?>" style="color:var(--warn)">Edit</a>
                  <a href="index.php?delete=<?php echo $a['id'] ?>" style="color:var(--bad); margin-left:8px" onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
    </div>
  </div>
</div>
<?php
$pageContent = ob_get_clean();
require __DIR__ . '/../layout.php';
