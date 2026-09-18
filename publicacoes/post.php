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

<div class="article-header">
  <div style="margin-bottom:.75rem">
    <a href="/publicacoes/?categoria=<?= urlencode($post['categoria'] ?? '') ?>" class="tag tag-accent" style="text-decoration:none"><?= $icon . ' ' . htmlspecialchars($post['categoria'] ?? '') ?></a>
  </div>
  <h1><?= htmlspecialchars($post['titulo']) ?></h1>
  <p class="meta" style="margin-top:.75rem">
    Por <a href="/sobre-mim" style="color:var(--accent)">Jacqueline Alves</a>
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

<footer style="background:#aa6062;border-top:none">
  <div class="footer-inner">
    <div>
      <div style="display:flex;align-items:center;gap:.65rem;margin-bottom:.65rem">
        <img src="/logo0-jacqueline-alves.png" alt="Jacqueline Alves" width="36" height="36" style="flex-shrink:0;border-radius:7px">
        <div>
          <div class="footer-logo-name" style="color:#FFFAFA;opacity:.88">Jacqueline Alves</div>
          <div class="footer-logo-tag" style="color:rgba(255,250,250,.7)">escritora · criadora · especialista em marketing</div>
        </div>
      </div>
      <p style="color:rgba(255,250,250,.8)">Um espaço para textos autorais, trajetória profissional e projetos criativos.</p>
    </div>
    <div>
      <h4 style="color:rgba(255,250,250,.6)">Navegação</h4>
      <ul>
        <li><a href="/sobre-mim" style="color:#FFFAFA;opacity:.85">Sobre mim</a></li>
        <li><a href="/curriculo" style="color:#FFFAFA;opacity:.85">Currículo</a></li>
        <li><a href="/projetos" style="color:#FFFAFA;opacity:.85">Projetos</a></li>
        <li><a href="/publicacoes/" style="color:#FFFAFA;opacity:.85">Publicações</a></li>
      </ul>
    </div>
    <div>
      <h4 style="color:rgba(255,250,250,.6)">Newsletters</h4>
      <ul>
        <li><a href="https://orbitandonomarketing.com.br/insights/" target="_blank" style="color:#FFFAFA;opacity:.85">Orbitando no Marketing</a></li>
        <li><a href="https://depoiseuteconto.substack.com" target="_blank" style="color:#FFFAFA;opacity:.85">Depois Eu Te Conto</a></li>
        <li><a href="https://marriedtothemusic.substack.com" target="_blank" style="color:#FFFAFA;opacity:.85">Married to the Music</a></li>
      </ul>
    </div>
    <div>
      <h4 style="color:rgba(255,250,250,.6)">Fale comigo</h4>
      <ul>
        <li><a href="/contato" style="color:#FFFAFA;opacity:.85">Contato</a></li>
        <li><a href="https://www.linkedin.com/in/jacquelinealvesoliveira" target="_blank" style="color:#FFFAFA;opacity:.85">LinkedIn</a></li>
        <li><a href="https://orbitandonomarketing.com.br" target="_blank" style="color:#FFFAFA;opacity:.85">Soluções profissionais</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom" style="border-top:1px solid rgba(255,250,250,.2)">
    <p style="color:rgba(255,250,250,.6)">© 2026 Jacqueline Alves. Todos os direitos reservados.</p>
    <p style="color:rgba(255,250,250,.6)">escritora · criadora · especialista em marketing</p>
  </div>
</footer>
<script>function toggleNav(){document.getElementById('navLinks').classList.toggle('open');}</script>
</body></html>
