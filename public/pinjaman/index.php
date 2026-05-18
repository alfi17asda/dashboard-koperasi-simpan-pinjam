<?php
require __DIR__ . '/../../app/db.php';
$active = 'pinjaman';

$anggotaList = $pdo->query('SELECT id,nama FROM anggota ORDER BY nama')->fetchAll();

if (isset($_POST['action']) && $_POST['action']==='create') {
  $anggota_id = (int)($_POST['anggota_id'] ?? 0);
  $jenis = $_POST['jenis'] ?? 'konsumtif';
  $pokok = (float)($_POST['pokok'] ?? 0);
  $tenor_bulan = (int)($_POST['tenor_bulan'] ?? 0);
  $bunga_percent = (float)($_POST['bunga_percent'] ?? 0);
  $tanggal_pengajuan = $_POST['tanggal_pengajuan'] ?? date('Y-m-d');
  $status = $_POST['status'] ?? 'diajukan';

  $stmt = $pdo->prepare('INSERT INTO pinjaman (anggota_id,jenis,pokok,tenor_bulan,bunga_percent,tanggal_pengajuan,status) VALUES (?,?,?,?,?,?,?)');
  $stmt->execute([$anggota_id,$jenis,$pokok,$tenor_bulan,$bunga_percent,$tanggal_pengajuan,$status]);
  header('Location: index.php');
  exit;
}

$pinjaman = $pdo->query('SELECT p.*, a.nama FROM pinjaman p JOIN anggota a ON a.id=p.anggota_id ORDER BY p.id DESC LIMIT 50')->fetchAll();

ob_start();
?>
<div class="grid">
  <div class="card wide">
    <div class="kpi">
      <div>
        <div class="label">Pinjaman</div>
        <div style="font-size:18px; font-weight:800; margin-top:6px">Pengajuan pinjaman anggota</div>
      </div>
      <div class="chip">Total baris: <?php echo count($pinjaman) ?> (max 50)</div>
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
                <option value="konsumtif">Konsumtif</option>
                <option value="produktif">Produktif</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div><input name="pokok" type="number" step="0.01" placeholder="Pokok" required></div>
            <div><input name="tenor_bulan" type="number" placeholder="Tenor (bulan)" required></div>
          </div>
          <div class="row">
            <div><input name="bunga_percent" type="number" step="0.01" placeholder="Bunga %" value="0"></div>
            <div><input name="tanggal_pengajuan" type="date" value="<?php echo date('Y-m-d') ?>" required></div>
          </div>
          <div>
            <select name="status">
              <option value="diajukan">diajukan</option>
              <option value="disetujui">disetujui</option>
              <option value="ditolak">ditolak</option>
              <option value="aktif">aktif</option>
              <option value="dilunasi">dilunasi</option>
            </select>
          </div>
          <button class="btn primary" type="submit">Simpan Pengajuan</button>
        </form>
      </div>

      <div>
        <table class="table">
          <thead>
            <tr><th>ID</th><th>Anggota</th><th>Jenis</th><th>Pokok</th><th>Status</th></tr>
          </thead>
          <tbody>
            <?php foreach($pinjaman as $p): ?>
              <tr>
                <td><?php echo (int)$p['id'] ?></td>
                <td><?php echo htmlspecialchars($p['nama']) ?></td>
                <td><?php echo htmlspecialchars($p['jenis']) ?></td>
                <td><?php echo 'Rp ' . number_format((float)$p['pokok'],0,',','.') ?></td>
                <td><?php echo htmlspecialchars($p['status']) ?></td>
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

