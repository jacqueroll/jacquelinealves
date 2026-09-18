<?php
$slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'] ?? ''));
if (!$slug) { header('Location: /publicacoes/'); exit; }

$file = __DIR__ . '/../api/posts/' . $slug . '.json';
if (!file_exists($file)) { header('HTTP/1.0 404 Not Found'); ?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Texto não encontrado | Jacqueline Alves</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/style.css"></head><body>
<div style="text-align:center;padding:6rem 2rem"><h1>Texto não encontrado</h1><p style="margin:1rem 0;color:var(--muted)">O texto que você procura não existe ou foi removido.</p><a class="btn" href="/publicacoes/">Voltar às publicações</a></div>
</body></html>
<?php exit; }

$post = json_decode(file_get_contents($file), true);
if (!$post || $post['status'] !== 'publicado') { header('Location: /publicacoes/'); exit; }

$cat_icons = ['Contos'=>'📖','Poemas'=>'✍️','Fanfics'=>'🌸','Ensaios'=>'💬','Diário de leitura'=>'📚'];
$icon = $cat_icons[$post['categoria']] ?? '✨';
$data_fmt = date('d \d\e F \d\e Y', strtotime($post['data'] ?? 'now'));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($post['titulo']) ?> | Jacqueline Alves</title>
<meta name="description" content="<?= htmlspecialchars(mb_substr(strip_tags($post['corpo'] ?? ''), 0, 160)) ?>">
<meta property="og:title" content="<?= htmlspecialchars($post['titulo']) ?>">
<meta property="og:description" content="<?= htmlspecialchars(mb_substr(strip_tags($post['corpo'] ?? ''), 0, 160)) ?>">
<?php if (!empty($post['imagem'])): ?>
<meta property="og:image" content="<?= htmlspecialchars($post['imagem']) ?>">
<?php endif; ?>
<link rel="canonical" href="https://jacquelinealves.com.br/publicacoes/post.php?slug=<?= urlencode($slug) ?>">
<link rel="icon" type="image/png" href="/favicon-512.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<nav>
  <div class="nav-inner">
    <a href="/" class="nav-logo">
      <div class="nav-logo-j">J</div>
      <div><span class="nav-logo-name">Jacqueline Alves</span><span class="nav-logo-tag">escritora · criadora · especialista em marketing</span></div>
    </a>
    <ul class="nav-links" id="navLinks">
      <li><a href="/">Home</a></li><li><a href="/sobre-mim.html">Sobre mim</a></li>
      <li><a href="/curriculo.html">Currículo</a></li><li><a href="/projetos.html">Projetos</a></li>
      <li><a href="/publicacoes/" class="active">Publicações</a></li>
      <li><a href="https://orbitandonomarketing.com.br" target="_blank">Soluções</a></li>
      <li><a href="/contato.html" class="nav-cta">Contato</a></li>
    </ul>
    <div class="nav-toggle" onclick="toggleNav()"><span></span><span></span><span></span></div>
  </div>
</nav>

<div class="article-header">
  <div style="margin-bottom:.75rem">
    <a href="/publicacoes/?categoria=<?= urlencode($post['categoria'] ?? '') ?>" class="tag tag-accent" style="text-decoration:none"><?= $icon . ' ' . htmlspecialchars($post['categoria'] ?? '') ?></a>
  </div>
  <h1><?= htmlspecialchars($post['titulo']) ?></h1>
  <p class="meta" style="margin-top:.75rem">
    Por <a href="/sobre-mim.html" style="color:var(--accent)">Jacqueline Alves</a>
    &nbsp;·&nbsp; <?= $data_fmt ?>
  </p>
</div>

<?php if (!empty($post['imagem'])): ?>
<img src="<?= htmlspecialchars($post['imagem']) ?>" alt="<?= htmlspecialchars($post['titulo']) ?>" style="width:100%;max-height:440px;object-fit:cover">
<?php endif; ?>

<div class="article-body">
  <?= $post['corpo'] ?>
</div>

<div style="max-width:720px;margin:0 auto;padding:0 2rem 4rem;border-top:1px solid var(--border);padding-top:2rem">
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
    <a href="/publicacoes/" class="btn btn-outline btn-sm">&larr; Voltar às publicações</a>
    <a href="/publicacoes/?categoria=<?= urlencode($post['categoria'] ?? '') ?>" class="btn btn-outline btn-sm">Mais <?= htmlspecialchars($post['categoria'] ?? '') ?></a>
  </div>
</div>

<footer>
  <div class="footer-inner">
    <div><div class="footer-logo-name">Jacqueline Alves</div><div class="footer-logo-tag">escritora · criadora · especialista em marketing</div><p>Um espaço para textos autorais, trajetória profissional e projetos criativos.</p></div>
    <div><h4>Navegação</h4><ul><li><a href="/sobre-mim.html">Sobre mim</a></li><li><a href="/curriculo.html">Currículo</a></li><li><a href="/projetos.html">Projetos</a></li><li><a href="/publicacoes/">Publicações</a></li></ul></div>
    <div><h4>Newsletters</h4><ul><li><a href="https://orbitandonomarketing.substack.com/" target="_blank">Orbitando no Marketing</a></li><li><a href="https://depoiseuteconto.substack.com" target="_blank">Depois Eu Te Conto</a></li><li><a href="https://marriedtothemusic.substack.com" target="_blank">Married to the Music</a></li></ul></div>
    <div><h4>Fale comigo</h4><ul><li><a href="/contato.html">Contato</a></li><li><a href="https://www.linkedin.com/in/jacquelinealvesoliveira" target="_blank">LinkedIn</a></li></ul></div>
  </div>
  <div class="footer-bottom"><p>© 2026 Jacqueline Alves. Todos os direitos reservados.</p><p>escritora · criadora · especialista em marketing</p></div>
</footer>
<script>function toggleNav(){document.getElementById('navLinks').classList.toggle('open');}</script>
</body></html>
