<?php
require __DIR__ . '/../../app/db.php';
$active = 'transaksi';

if (isset($_POST['action']) && $_POST['action']==='create') {
  $jenis = $_POST['jenis'] ?? 'pulsa_hp';
  $pelanggan = trim($_POST['pelanggan'] ?? '');
  $nomor = trim($_POST['nomor'] ?? '');
  $nominal = (float)($_POST['nominal'] ?? 0);
  $biaya_admin = (float)($_POST['biaya_admin'] ?? 0);
  $total = (float)($_POST['total'] ?? ($nominal + $biaya_admin));
  $tanggal = $_POST['tanggal'] ?? date('Y-m-d H:i:s');

  $stmt = $pdo->prepare('INSERT INTO transaksi_pulsa (jenis,pelanggan,nomor,nominal,biaya_admin,total,tanggal) VALUES (?,?,?,?,?,?,?)');
  $stmt->execute([$jenis,$pelanggan !== '' ? $pelanggan : null,$nomor,$nominal,$biaya_admin,$total,$tanggal]);
  header('Location: index.php');
  exit;
}

$rows = $pdo->query('SELECT * FROM transaksi_pulsa ORDER BY id DESC LIMIT 60')->fetchAll();

ob_start();
?>
<div class="grid">
  <div class="card wide">
    <div class="kpi">
      <div>
        <div class="label">Transaksi</div>
        <div style="font-size:18px; font-weight:800; margin-top:6px">Pulsa HP, Pulsa Listrik, Bayar Listrik, dll</div>
      </div>
      <div class="chip">Baris: <?php echo count($rows) ?> (max 60)</div>
    </div>

    <div style="height:14px"></div>

    <div class="row">
      <div>
        <form method="post" class="form" id="trxForm">
          <input type="hidden" name="action" value="create" />
          <div class="row">
            <div>
              <select name="jenis" required>
                <option value="pulsa_hp">Pulsa HP</option>
                <option value="pulsa_listrik">Pulsa Listrik</option>
                <option value="bayar_listrik">Bayar Listrik</option>
                <option value="paket_data">Paket Data</option>
                <option value="lainnya">Lainnya</option>
              </select>
            </div>
            <div><input name="nomor" placeholder="Nomor tujuan (HP/Token/ID Pelanggan)" required></div>
          </div>
          <div><input name="pelanggan" placeholder="Nama pelanggan (opsional)"></div>
          <div class="row">
            <div><input name="nominal" id="nominal" type="number" step="0.01" placeholder="Nominal" required></div>
            <div><input name="biaya_admin" id="biaya_admin" type="number" step="0.01" placeholder="Biaya admin" value="0"></div>
          </div>
          <div><input name="total" id="total" type="number" step="0.01" placeholder="Total" required></div>
          <div><input name="tanggal" type="datetime-local" value="<?php echo date('Y-m-d\TH:i'); ?>" required></div>

          <button class="btn primary" type="submit">Simpan Transaksi</button>
        </form>
      </div>

      <div>
        <table class="table">
          <thead>
            <tr><th>ID</th><th>Jenis</th><th>Nomor</th><th>Nominal</th><th>Total</th><th>Tanggal</th></tr>
          </thead>
          <tbody>
            <?php foreach($rows as $r): ?>
              <tr>
                <td><?php echo (int)$r['id'] ?></td>
                <td><?php echo htmlspecialchars($r['jenis']) ?></td>
                <td><?php echo htmlspecialchars($r['nomor']) ?></td>
                <td><?php echo 'Rp ' . number_format((float)$r['nominal'],0,',','.') ?></td>
                <td><?php echo 'Rp ' . number_format((float)$r['total'],0,',','.') ?></td>
                <td><?php echo htmlspecialchars($r['tanggal']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="footer-note">Untuk integrasi vendor pulsa: tambahkan proses API & validasi status.</div>
      </div>
    </div>

  </div>
</div>
<script>
  const nominal = document.getElementById('nominal');
  const biaya = document.getElementById('biaya_admin');
  const total = document.getElementById('total');

  function calc(){
    const n = parseFloat(nominal.value || '0');
    const b = parseFloat(biaya.value || '0');
    const t = n + b;
    if (total) total.value = t;
  }
  if(nominal && biaya && total){
    nominal.addEventListener('input', calc);
    biaya.addEventListener('input', calc);
    calc();
  }
</script>
<?php
$pageContent = ob_get_clean();
require __DIR__ . '/../layout.php';

