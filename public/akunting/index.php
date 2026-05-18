<?php
require __DIR__ . '/../../app/db.php';
$active = 'akunting';

if (isset($_POST['action']) && $_POST['action']==='create') {
  $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
  $akun = trim($_POST['akun'] ?? '');
  $debet = (float)($_POST['debet'] ?? 0);
  $kredit = (float)($_POST['kredit'] ?? 0);
  $ket = trim($_POST['keterangan'] ?? '');

  $stmt = $pdo->prepare('INSERT INTO akunting_jurnal (tanggal,akun,debet,kredit,keterangan) VALUES (?,?,?,?,?)');
  $stmt->execute([$tanggal,$akun,$debet,$kredit,$ket !== '' ? $ket : null]);
  header('Location: index.php');
  exit;
}

$rows = $pdo->query('SELECT * FROM akunting_jurnal ORDER BY id DESC LIMIT 60')->fetchAll();

ob_start();
?>
<div class="grid">
  <div class="card wide">
    <div class="kpi">
      <div>
        <div class="label">Akunting</div>
        <div style="font-size:18px; font-weight:800; margin-top:6px">Entri jurnal sederhana</div>
      </div>
      <div class="chip">Baris: <?php echo count($rows) ?> (max 60)</div>
    </div>

    <div style="height:14px"></div>

    <div class="row">
      <div>
        <form method="post" class="form">
          <input type="hidden" name="action" value="create" />
          <div class="row">
            <div><input name="tanggal" type="date" value="<?php echo date('Y-m-d') ?>" required></div>
            <div><input name="akun" placeholder="Nama akun" required></div>
          </div>
          <div class="row">
            <div><input name="debet" type="number" step="0.01" placeholder="Debet (opsional)" value="0"></div>
            <div><input name="kredit" type="number" step="0.01" placeholder="Kredit (opsional)" value="0"></div>
          </div>
          <div><input name="keterangan" placeholder="Keterangan"></div>
          <button class="btn primary" type="submit">Simpan Jurnal</button>
        </form>
      </div>

      <div>
        <table class="table">
          <thead>
            <tr><th>ID</th><th>Tanggal</th><th>Akun</th><th>Debet</th><th>Kredit</th><th>Ket</th></tr>
          </thead>
          <tbody>
            <?php foreach($rows as $r): ?>
              <tr>
                <td><?php echo (int)$r['id'] ?></td>
                <td><?php echo htmlspecialchars($r['tanggal']) ?></td>
                <td><?php echo htmlspecialchars($r['akun']) ?></td>
                <td><?php echo (float)$r['debet'] > 0 ? 'Rp ' . number_format((float)$r['debet'],0,',','.') : '-' ?></td>
                <td><?php echo (float)$r['kredit'] > 0 ? 'Rp ' . number_format((float)$r['kredit'],0,',','.') : '-' ?></td>
                <td class="muted"><?php echo htmlspecialchars((string)$r['keterangan']) ?></td>
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

