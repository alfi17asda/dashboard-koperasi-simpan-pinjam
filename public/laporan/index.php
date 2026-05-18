<?php
require __DIR__ . '/../../app/db.php';
$active = 'laporan';

$from = $_GET['from'] ?? date('Y-m-01');
$to = $_GET['to'] ?? date('Y-m-d');

$stmt = $pdo->prepare('SELECT 
  SUM(CASE WHEN tipe IN ("pemasukan","setoran") THEN nominal ELSE 0 END) AS masuk,
  SUM(CASE WHEN tipe IN ("pengeluaran","penarikan") THEN nominal ELSE 0 END) AS keluar
FROM kasir_transaksi
WHERE tanggal BETWEEN ? AND ?');
$stmt->execute([$from . ' 00:00:00', $to . ' 23:59:59']);
$sum = $stmt->fetch();
$masuk = (float)($sum['masuk'] ?? 0);
$keluar = (float)($sum['keluar'] ?? 0);
$saldo = $masuk - $keluar;

ob_start();
?>
<div class="grid">
  <div class="card wide">
    <div class="kpi">
      <div>
        <div class="label">Laporan Keuangan</div>
        <div style="font-size:18px; font-weight:800; margin-top:6px">Ringkasan berdasarkan periode kasir</div>
      </div>
      <div class="chip">Filter: <?php echo htmlspecialchars($from) ?> s/d <?php echo htmlspecialchars($to) ?></div>
    </div>

    <div style="height:14px"></div>

    <form method="get" class="form" style="max-width:720px">
      <div class="row">
        <div><input type="date" name="from" value="<?php echo htmlspecialchars($from) ?>" required></div>
        <div><input type="date" name="to" value="<?php echo htmlspecialchars($to) ?>" required></div>
      </div>
      <button class="btn primary" type="submit">Tampilkan</button>
    </form>

    <div style="height:14px"></div>

    <div class="grid">
      <div class="card small" style="grid-column: span 4">
        <div class="kpi">
          <div>
            <div class="label">Total Pemasukan</div>
            <div class="value">Rp <?php echo number_format($masuk,0,',','.') ?></div>
          </div>
        </div>
      </div>
      <div class="card small" style="grid-column: span 4">
        <div class="kpi">
          <div>
            <div class="label">Total Pengeluaran</div>
            <div class="value">Rp <?php echo number_format($keluar,0,',','.') ?></div>
          </div>
        </div>
      </div>
      <div class="card small" style="grid-column: span 4">
        <div class="kpi">
          <div>
            <div class="label">Saldo</div>
            <div class="value" style="color: <?php echo $saldo>=0 ? 'var(--good)' : 'var(--bad)' ?>">Rp <?php echo number_format($saldo,0,',','.') ?></div>
          </div>
        </div>
      </div>
    </div>

    <div style="height:14px"></div>

    <table class="table">
      <thead>
        <tr><th>ID</th><th>Tanggal</th><th>Tipe</th><th>Metode</th><th>Nominal</th><th>Keterangan</th></tr>
      </thead>
      <tbody>
        <?php
          $rows = $pdo->prepare('SELECT * FROM kasir_transaksi WHERE tanggal BETWEEN ? AND ? ORDER BY id DESC LIMIT 100');
          $rows->execute([$from . ' 00:00:00', $to . ' 23:59:59']);
          foreach($rows as $r):
        ?>
          <tr>
            <td><?php echo (int)$r['id'] ?></td>
            <td><?php echo htmlspecialchars($r['tanggal']) ?></td>
            <td><?php echo htmlspecialchars($r['tipe']) ?></td>
            <td><?php echo htmlspecialchars($r['metode']) ?></td>
            <td><?php echo 'Rp ' . number_format((float)$r['nominal'],0,',','.') ?></td>
            <td class="muted"><?php echo htmlspecialchars((string)$r['keterangan']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>
</div>
<?php
$pageContent = ob_get_clean();
require __DIR__ . '/../layout.php';

