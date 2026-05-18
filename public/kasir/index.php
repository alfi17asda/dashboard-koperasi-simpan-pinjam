<?php
require __DIR__ . '/../../app/db.php';
$active = 'kasir';

$anggotaList = $pdo->query('SELECT id,nama FROM anggota ORDER BY nama')->fetchAll();

if (isset($_POST['action']) && $_POST['action']==='create') {
  $tipe = $_POST['tipe'] ?? 'setoran';
  $metode = $_POST['metode'] ?? 'tunai';
  $nominal = (float)($_POST['nominal'] ?? 0);
  $keterangan = trim($_POST['keterangan'] ?? '');
  $tanggal = $_POST['tanggal'] ?? date('Y-m-d H:i:s');
  $anggota_id = !empty($_POST['anggota_id']) ? (int)$_POST['anggota_id'] : null;

  $stmt = $pdo->prepare('INSERT INTO kasir_transaksi (tipe,metode,nominal,keterangan,tanggal,anggota_id) VALUES (?,?,?,?,?,?)');
  $stmt->execute([$tipe,$metode,$nominal,$keterangan !== '' ? $keterangan : null,$tanggal,$anggota_id]);
  header('Location: index.php');
  exit;
}

$rows = $pdo->query('SELECT k.*, a.nama FROM kasir_transaksi k LEFT JOIN anggota a ON a.id=k.anggota_id ORDER BY k.id DESC LIMIT 50')->fetchAll();

ob_start();
?>
<div class="grid">
  <div class="card wide">
    <div class="kpi">
      <div>
        <div class="label">Menu Kasir</div>
        <div style="font-size:18px; font-weight:800; margin-top:6px">Transaksi kas koperasi</div>
      </div>
      <div class="chip">Baris: <?php echo count($rows) ?> (max 50)</div>
    </div>

    <div style="height:14px"></div>

    <div class="row">
      <div>
        <form method="post" class="form">
          <input type="hidden" name="action" value="create" />
          <div class="row">
            <div>
              <select name="tipe" required>
                <option value="setoran">setoran</option>
                <option value="penarikan">penarikan</option>
                <option value="pemasukan">pemasukan</option>
                <option value="pengeluaran">pengeluaran</option>
              </select>
            </div>
            <div>
              <select name="metode" required>
                <option value="tunai">tunai</option>
                <option value="transfer">transfer</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div><input name="nominal" type="number" step="0.01" placeholder="Nominal" required></div>
            <div><input name="tanggal" type="datetime-local" value="<?php echo date('Y-m-d\TH:i'); ?>" required></div>
          </div>
          <div>
            <select name="anggota_id">
              <option value="">(Opsional) Anggota</option>
              <?php foreach($anggotaList as $a): ?>
                <option value="<?php echo (int)$a['id'] ?>">[<?php echo (int)$a['id'] ?>] <?php echo htmlspecialchars($a['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div><input name="keterangan" placeholder="Keterangan"></div>
          <button class="btn primary" type="submit">Simpan Kas</button>
        </form>
      </div>

      <div>
        <table class="table">
          <thead>
            <tr><th>ID</th><th>Tipe</th><th>Nominal</th><th>Tanggal</th><th>Anggota</th></tr>
          </thead>
          <tbody>
            <?php foreach($rows as $r): ?>
              <tr>
                <td><?php echo (int)$r['id'] ?></td>
                <td><?php echo htmlspecialchars($r['tipe']) ?></td>
                <td><?php echo 'Rp ' . number_format((float)$r['nominal'],0,',','.') ?></td>
                <td><?php echo htmlspecialchars($r['tanggal']) ?></td>
                <td class="muted"><?php echo htmlspecialchars((string)($r['nama'] ?? '')) ?></td>
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

