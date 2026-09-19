<?php
require_once __DIR__ . '/../api/auth.php';
require_login();

if (isset($_GET['logout'])) do_logout();

$posts_dir = __DIR__ . '/../api/posts/';
$posts = [];
if (is_dir($posts_dir)) {
    foreach (glob($posts_dir . '*.json') as $f) {
        $p = json_decode(file_get_contents($f), true);
        if ($p) $posts[] = $p;
    }
}
usort($posts, fn($a,$b) => strcmp($b['data'],$a['data']));
$total = count($posts);
$pub   = count(array_filter($posts, fn($p) => ($p['status']??'') === 'publicado'));
$rasc  = $total - $pub;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Admin Jacqueline Alves</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,600;0,700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-j">J</div>
    <div><div class="sidebar-name">Jacqueline Alves</div><div class="sidebar-tag">Painel de publicações</div></div>
  </div>
  <nav style="background:#aa6062">
  <div class="nav-inner">
    <a href="/" class="nav-logo">
      <img src="/logo0-jacqueline-alves.png" alt="Jacqueline Alves" width="40" height="40" style="flex-shrink:0;border-radius:8px">
      <div>
        <span class="nav-logo-name" style="color:#FFFAFA">Jacqueline Alves</span>
        <span class="nav-logo-tag" style="color:rgba(255,250,250,.7)">escritora · criadora · especialista em marketing</span>
      </div>
    </a>
    <ul class="nav-links" id="navLinks">
      <li><a href="/" style="color:#FFFAFA;opacity:.88">Home</a></li>
      <li><a href="/sobre-mim" style="color:#FFFAFA;opacity:.88">Sobre mim</a></li>
      <li><a href="/curriculo" style="color:#FFFAFA;opacity:.88">Currículo</a></li>
      <li><a href="/projetos" style="color:#FFFAFA;opacity:.88">Projetos</a></li>
      <li><a href="/publicacoes/" style="color:#FFFAFA;opacity:.88">Publicações</a></li>
      <li><a href="https://orbitandonomarketing.com.br" target="_blank" style="color:#FFFAFA;opacity:.88">Soluções</a></li>
      <li><a href="/contato" class="nav-cta" style="background:#FFFAFA!important;color:#aa6062!important;opacity:1!important">Contato</a></li>
    </ul>
    <div class="nav-toggle" onclick="toggleNav()" style="padding:.5rem">
      <span style="display:block;width:22px;height:2px;background:#FFFAFA;border-radius:2px;margin-bottom:5px"></span>
      <span style="display:block;width:22px;height:2px;background:#FFFAFA;border-radius:2px;margin-bottom:5px"></span>
      <span style="display:block;width:22px;height:2px;background:#FFFAFA;border-radius:2px"></span>
    </div>
  </div>
</nav>
</aside>
<main class="main">
  <div class="main-header">
    <h1>Dashboard</h1>
    <a href="/admin/editor.php" class="btn-admin">+ Nova publicação</a>
  </div>

  <div class="stats-row">
    <div class="stat-card"><div class="stat-num"><?= $total ?></div><div class="stat-label">Total de textos</div></div>
    <div class="stat-card accent"><div class="stat-num"><?= $pub ?></div><div class="stat-label">Publicados</div></div>
    <div class="stat-card"><div class="stat-num"><?= $rasc ?></div><div class="stat-label">Rascunhos</div></div>
  </div>

  <div class="card-admin">
    <div class="card-admin-header">
      <h2>Publicações</h2>
    </div>
    <?php if (empty($posts)): ?>
    <div class="empty-state">
      <p>Nenhuma publicação ainda.</p>
      <a href="/admin/editor.php" class="btn-admin">Criar a primeira</a>
    </div>
    <?php else: ?>
    <table class="posts-table">
      <thead><tr><th>Título</th><th>Categoria</th><th>Data</th><th>Status</th><th>Ações</th></tr></thead>
      <tbody>
        <?php foreach ($posts as $p): ?>
        <tr>
          <td><strong><?= htmlspecialchars($p['titulo']) ?></strong></td>
          <td><?= htmlspecialchars($p['categoria'] ?? '') ?></td>
          <td><?= date('d/m/Y', strtotime($p['data'] ?? 'now')) ?></td>
          <td><span class="status-badge <?= $p['status']==='publicado' ? 'pub' : 'rasc' ?>"><?= $p['status']==='publicado' ? 'Publicado' : 'Rascunho' ?></span></td>
          <td class="actions">
            <a href="/admin/editor.php?slug=<?= urlencode($p['slug']) ?>">Editar</a>
            <?php if ($p['status']==='publicado'): ?>
            <a href="/publicacoes/post.php?slug=<?= urlencode($p['slug']) ?>" target="_blank">Ver</a>
            <?php endif; ?>
            <a href="#" class="del" onclick="deletePost('<?= htmlspecialchars($p['slug']) ?>');return false">Deletar</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</main>

<script>
async function deletePost(slug) {
  if (!confirm('Tem certeza que quer deletar este texto? A ação não pode ser desfeita.')) return;
  const res = await fetch('/api/posts.php?action=delete&slug=' + encodeURIComponent(slug), {method:'DELETE'});
  const data = await res.json();
  if (data.ok) location.reload();
  else alert('Erro ao deletar: ' + (data.msg || 'Tente novamente.'));
}
</script>
</body>
</html>
