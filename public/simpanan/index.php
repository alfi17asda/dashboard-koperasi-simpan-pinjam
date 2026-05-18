<?php
require __DIR__ . '/../../app/db.php';
$active = 'simpanan';

$anggotaList = $pdo->query('SELECT id,nama FROM anggota ORDER BY nama')->fetchAll();

if (isset($_POST['action']) && $_POST['action']==='create') {
  $anggota_id = (int)($_POST['anggota_id'] ?? 0);
  $jenis = $_POST['jenis'] ?? 'pokok';
  $jumlah = (float)($_POST['jumlah'] ?? 0);
  $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
  $keterangan = trim($_POST['keterangan'] ?? '');

  $stmt = $pdo->prepare('INSERT INTO simpanan (anggota_id,jenis,jumlah,tanggal,keterangan) VALUES (?,?,?,?,?)');
  $stmt->execute([$anggota_id,$jenis,$jumlah,$tanggal,$keterangan !== '' ? $keterangan : null]);
  header('Location: index.php');
  exit;
}

if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $stmt = $pdo->prepare('DELETE FROM simpanan WHERE id = ?');
  $stmt->execute([$id]);
  header('Location: index.php');
  exit;
}

$simpanans = $pdo->query('SELECT s.*, a.nama FROM simpanan s JOIN anggota a ON a.id=s.anggota_id ORDER BY s.id DESC LIMIT 50')->fetchAll();

ob_start();
?>
<div class="grid">
  <div class="card wide">
    <div class="kpi">
      <div>
        <div class="label">Simpanan</div>
        <div style="font-size:18px; font-weight:800; margin-top:6px">Setor simpanan anggota</div>
      </div>
      <div class="chip">Total baris: <?php echo count($simpanans) ?> (max 50)</div>
    </div>
    <div style="height:14px"></div>

    <div class="row">
      <div>
        <form method="post" class="form">
          <input type="hidden" name="action" value="create" />
          <div class="row">
            <div>
              <select name="anggota_id" required>
                <option value="">Pilih Anggota</option>
                <?php foreach($anggotaList as $a): ?>
                  <option value="<?php echo (int)$a['id'] ?>"><?php echo htmlspecialchars($a['nama']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <select name="jenis" required>
                <option value="pokok">Pokok</option>
                <option value="wajib">Wajib</option>
                <option value="sukarela">Sukarela</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div><input name="jumlah" type="number" step="0.01" placeholder="Jumlah" required></div>
            <div><input name="tanggal" type="date" value="<?php echo date('Y-m-d') ?>" required></div>
          </div>
          <div><input name="keterangan" placeholder="Keterangan"></div>
          <button class="btn primary" type="submit">Simpan Transaksi</button>
        </form>
      </div>
      <div>
        <table class="table">
          <thead>
            <tr><th>ID</th><th>Anggota</th><th>Jenis</th><th>Jumlah</th><th>Tanggal</th><th>Aksi</th></tr>
          </thead>
          <tbody>
            <?php foreach($simpanans as $s): ?>
              <tr>
                <td><?php echo (int)$s['id'] ?></td>
                <td><?php echo htmlspecialchars($s['nama']) ?></td>
                <td><?php echo htmlspecialchars($s['jenis']) ?></td>
                <td><?php echo 'Rp ' . number_format((float)$s['jumlah'],0,',','.') ?></td>
                <td><?php echo htmlspecialchars($s['tanggal']) ?></td>
                <td>
                  <a href="index.php?delete=<?php echo $s['id'] ?>" style="color:var(--bad)" onclick="return confirm('Hapus transaksi simpanan ini?')">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php
$pageContent = ob_get_clean();
require __DIR__ . '/../layout.php';
