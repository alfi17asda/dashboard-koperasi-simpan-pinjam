<?php
if (!isset($active)) $active = '';

function urlFor($path) {
  return $path;
}

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Koperasi</title>
  <style>
    :root{
      --bg:#0b1220;
      --card:#111b2e;
      --muted:#9fb0d0;
      --text:#eaf1ff;
      --primary:#6d5efc;
      --primary2:#23c9ff;
      --good:#2dd4bf;
      --warn:#fbbf24;
      --bad:#fb7185;
      --ring: rgba(109,94,252,.35);
    }
    *{box-sizing:border-box}
    body{margin:0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial; background:linear-gradient(180deg, #070d1a 0%, var(--bg) 60%); color:var(--text)}
    a{color:inherit; text-decoration:none}
    .app{display:flex; min-height:100vh}
    .sidebar{width:270px; padding:18px; border-right:1px solid rgba(255,255,255,.06); background:rgba(255,255,255,.01)}
    .brand{display:flex; align-items:center; gap:10px; padding:10px 8px 18px}
    .logo{width:40px; height:40px; border-radius:14px; background:linear-gradient(135deg, var(--primary), var(--primary2)); box-shadow:0 10px 30px rgba(109,94,252,.25)}
    .brand h1{font-size:14px; margin:0; line-height:1.1}
    .brand p{margin:0; color:var(--muted); font-size:12px}
    .nav{display:flex; flex-direction:column; gap:6px}
    .nav a{padding:10px 12px; border-radius:14px; color:var(--muted); border:1px solid transparent}
    .nav a:hover{background:rgba(255,255,255,.03); border-color:rgba(255,255,255,.06)}
    .nav a.active{background:linear-gradient(135deg, rgba(109,94,252,.18), rgba(35,201,255,.10)); color:var(--text); border-color:rgba(109,94,252,.35); box-shadow:0 0 0 4px var(--ring) inset}

    .content{flex:1; padding:24px}
    .topbar{display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:18px}
    .topbar .title{display:flex; flex-direction:column; gap:2px}
    .topbar h2{margin:0; font-size:18px}
    .topbar .sub{color:var(--muted); font-size:13px}
    .grid{display:grid; grid-template-columns: repeat(12, 1fr); gap:14px}
    .card{background:linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.01)); border:1px solid rgba(255,255,255,.08); border-radius:18px; padding:16px; grid-column: span 6}
    .card.small{grid-column: span 4}
    .card.wide{grid-column: span 12}
    .kpi{display:flex; align-items:flex-start; justify-content:space-between; gap:10px}
    .kpi .label{color:var(--muted); font-size:13px}
    .kpi .value{font-size:26px; font-weight:800; letter-spacing:-.02em; margin-top:6px}
    .chip{display:inline-flex; align-items:center; gap:8px; padding:7px 10px; border-radius:999px; border:1px solid rgba(255,255,255,.10); background:rgba(255,255,255,.03); color:var(--muted); font-size:12px}
    .btn{display:inline-flex; align-items:center; justify-content:center; padding:10px 12px; border-radius:14px; border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.03); color:var(--text); cursor:pointer}
    .btn.primary{border-color:rgba(109,94,252,.45); background:linear-gradient(135deg, rgba(109,94,252,.22), rgba(35,201,255,.10))}

    .table{width:100%; border-collapse:separate; border-spacing:0; overflow:hidden; border-radius:16px; border:1px solid rgba(255,255,255,.08)}
    .table th,.table td{padding:12px 12px; border-bottom:1px solid rgba(255,255,255,.06); font-size:13px}
    .table th{color:var(--muted); font-weight:600; background:rgba(255,255,255,.02)}
    .table tr:last-child td{border-bottom:none}
    .muted{color:var(--muted)}

    .form{display:grid; gap:12px}
    .row{display:grid; grid-template-columns: 1fr 1fr; gap:12px}
    input, select, textarea{width:100%; padding:11px 12px; border-radius:14px; border:1px solid rgba(255,255,255,.12); background:rgba(0,0,0,.12); color:var(--text); outline:none}
    input:focus, select:focus, textarea:focus{box-shadow:0 0 0 4px var(--ring); border-color:rgba(109,94,252,.55)}
    textarea{min-height:92px; resize:vertical}

    .footer-note{margin-top:16px; color:var(--muted); font-size:12px}

    @media (max-width: 980px){
      .sidebar{display:none}
      .card{grid-column: span 12}
      .card.small{grid-column: span 12}
    }
  </style>
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">
        <div class="logo"></div>
        <div>
          <h1>Koperasi Simpan Pinjam</h1>
          <p>Sistem Operasional & Keuangan</p>
        </div>
      </div>

      <nav class="nav">
        <a class="<?php echo $active==='dashboard'?'active':''?>" href="/index.php">Dashboard</a>
        <a class="<?php echo $active==='anggota'?'active':''?>" href="/anggota/index.php">Anggota</a>
        <a class="<?php echo $active==='simpanan'?'active':''?>" href="/simpanan/index.php">Simpanan</a>
        <a class="<?php echo $active==='pinjaman'?'active':''?>" href="/pinjaman/index.php">Pinjaman</a>
        <a class="<?php echo $active==='kasir'?'active':''?>" href="/kasir/index.php">Menu Kasir</a>
        <a class="<?php echo $active==='akunting'?'active':''?>" href="/akunting/index.php">Akunting</a>
        <a class="<?php echo $active==='transaksi'?'active':''?>" href="/transaksi/index.php">Transaksi</a>
        <a class="<?php echo $active==='laporan'?'active':''?>" href="/laporan/index.php">Laporan Keuangan</a>
      </nav>
    </aside>

    <main class="content">
      <div class="topbar">
        <div class="title">
          <h2>Dashboard Koperasi</h2>
          <div class="sub">Ringkasan & modul operasional</div>
        </div>
       
      <?php /* page content is rendered by each page */ ?>
      <?php if (isset($pageContent)) echo $pageContent; ?>

      <?php
      ?>
    </main>
  </div>
</body>
</html>
