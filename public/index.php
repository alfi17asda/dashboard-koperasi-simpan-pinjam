<?php
require __DIR__ . '/../app/db.php';

// Ringkasan dashboard sederhana
$today = date('Y-m-d');

$cntAnggota = (int)$pdo->query('SELECT COUNT(*) AS c FROM anggota')->fetch()['c'];

$todayKasir = $pdo->prepare("SELECT 
  SUM(CASE WHEN tipe IN ('pemasukan','setoran') THEN nominal ELSE 0 END) AS masuk,
  SUM(CASE WHEN tipe IN ('pengeluaran','penarikan') THEN nominal ELSE 0 END) AS keluar
FROM kasir_transaksi
WHERE DATE(tanggal)=?");
$todayKasir->execute([$today]);
$kasir = $todayKasir->fetch();
$masuk = (float)($kasir['masuk'] ?? 0);
$keluar = (float)($kasir['keluar'] ?? 0);
$saldoHariIni = $masuk - $keluar;

$cntPinjamanAktif = (int)$pdo->query("SELECT COUNT(*) AS c FROM pinjaman WHERE status IN ('aktif','disetujui')")->fetch()['c'];

$cntTransPulsa = (int)$pdo->query("SELECT COUNT(*) AS c FROM transaksi_pulsa WHERE DATE(tanggal)=CURDATE()")->fetch()['c'];

function rupiah($n){
  return 'Rp ' . number_format((float)$n, 0, ',', '.');
}

$active = 'dashboard';

ob_start();
?>
<div class="grid">
  <div class="card small">
    <div class="kpi">
      <div>
        <div class="label">Total Anggota</div>
        <div class="value"><?php echo $cntAnggota ?></div>
      </div>
    </div>
  </div>
  <div class="card small">
    <div class="kpi">
      <div>
        <div class="label">Saldo Kasir Hari Ini</div>
        <div class="value" style="color: <?php echo $saldoHariIni >= 0 ? 'var(--good)' : 'var(--bad)' ?>">
          <?php echo rupiah($saldoHariIni) ?>
        </div>
      </div>
    </div>
  </div>
  <div class="card small">
    <div class="kpi">
      <div>
        <div class="label">Pinjaman Aktif</div>
        <div class="value"><?php echo $cntPinjamanAktif ?></div>
      </div>
    </div>
  </div>
  <div class="card small">
    <div class="kpi">
      <div>
        <div class="label">Trx Pulsa Hari Ini</div>
        <div class="value"><?php echo $cntTransPulsa ?></div>
      </div>
    </div>
  </div>
</div>
<?php
$pageContent = ob_get_clean();
require __DIR__ . '/layout.php';
