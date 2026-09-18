<?php
// ── Listagem pública de publicações ──────────────────────────────────────────
$posts_dir = __DIR__ . '/../api/posts/';
$per_page  = 9;
$page      = max(1, (int)($_GET['pagina'] ?? 1));
$cat_filter = $_GET['categoria'] ?? '';

$all_posts = [];
if (is_dir($posts_dir)) {
    foreach (glob($posts_dir . '*.json') as $file) {
        $post = json_decode(file_get_contents($file), true);
        if (!$post || ($post['status'] ?? '') !== 'publicado') continue;
        if ($cat_filter && $post['categoria'] !== $cat_filter) continue;
        $all_posts[] = $post;
    }
}
usort($all_posts, fn($a,$b) => strcmp($b['data'] ?? '', $a['data'] ?? ''));

$total      = count($all_posts);
$total_pages = max(1, ceil($total / $per_page));
$page       = min($page, $total_pages);
$posts      = array_slice($all_posts, ($page - 1) * $per_page, $per_page);

$cats = ['Contos','Poemas','Fanfics','Ensaios','Diário de leitura'];
$cat_icons = ['Contos'=>'📖','Poemas'=>'✍️','Fanfics'=>'🌸','Ensaios'=>'💬','Diário de leitura'=>'📚'];

function excerpt($html, $len=160) {
    $text = strip_tags($html);
    return mb_strlen($text) > $len ? mb_substr($text, 0, $len) . '...' : $text;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Publicações | Jacqueline Alves</title>
<meta name="description" content="Textos autorais de Jacqueline Alves: contos, poemas, fanfics, ensaios e diário de leitura.">
<link rel="canonical" href="https://jacquelinealves.com.br/publicacoes/">
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
  <span class="hero-tag">Publicações</span>
  <h1>Textos autorais</h1>
  <p style="color:var(--muted);max-width:520px;margin:.75rem auto 0">Contos, poemas, fanfics, ensaios e anotações de leitura. Por Jacqueline Alves.</p>
</div>

<section class="section">
  <div class="section-inner">

    <!-- Filtro por categoria -->
    <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:2.5rem;justify-content:center">
      <a href="/publicacoes/" style="display:inline-block;font-size:.82rem;font-weight:600;padding:.35rem .9rem;border-radius:999px;border:1.5px solid var(--<?= $cat_filter ? 'border' : 'accent' ?>);color:var(--<?= $cat_filter ? 'muted' : 'accent' ?>);text-decoration:none;background:<?= $cat_filter ? 'transparent' : 'rgba(188,143,143,.1)' ?>">Todos</a>
      <?php foreach ($cats as $c): ?>
      <a href="/publicacoes/?categoria=<?= urlencode($c) ?>"
         style="display:inline-block;font-size:.82rem;font-weight:600;padding:.35rem .9rem;border-radius:999px;border:1.5px solid var(--<?= $cat_filter===$c ? 'accent' : 'border' ?>);color:var(--<?= $cat_filter===$c ? 'accent' : 'muted' ?>);text-decoration:none;background:<?= $cat_filter===$c ? 'rgba(188,143,143,.1)' : 'transparent' ?>;transition:all .15s">
        <?= htmlspecialchars($cat_icons[$c] . ' ' . $c) ?>
      </a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($posts)): ?>
    <div style="text-align:center;padding:4rem 0">
      <p style="font-size:1.1rem;color:var(--muted)">Nenhuma publicação encontrada<?= $cat_filter ? " em \"$cat_filter\"" : '' ?>.</p>
    </div>
    <?php else: ?>
    <div class="grid-3">
      <?php foreach ($posts as $post): ?>
      <div class="post-card">
        <?php if (!empty($post['imagem'])): ?>
        <img class="post-card-img" src="<?= htmlspecialchars($post['imagem']) ?>" alt="<?= htmlspecialchars($post['titulo']) ?>">
        <?php else: ?>
        <div class="post-card-img-placeholder"><?= $cat_icons[$post['categoria']] ?? '✨' ?></div>
        <?php endif; ?>
        <div class="post-card-body">
          <div class="post-card-meta">
            <span class="tag tag-accent" style="font-size:.68rem"><?= htmlspecialchars($post['categoria'] ?? '') ?></span>
            <span><?= date('d/m/Y', strtotime($post['data'] ?? '')) ?></span>
          </div>
          <h3><a href="/publicacoes/post.php?slug=<?= urlencode($post['slug']) ?>"><?= htmlspecialchars($post['titulo']) ?></a></h3>
          <p class="post-card-excerpt"><?= htmlspecialchars(excerpt($post['corpo'] ?? '')) ?></p>
          <a href="/publicacoes/post.php?slug=<?= urlencode($post['slug']) ?>" class="card-link">Ler o texto</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
    <div class="pagination">
      <?php if ($page > 1): ?>
        <a href="?pagina=<?= $page-1 ?><?= $cat_filter ? '&categoria='.urlencode($cat_filter) : '' ?>">&lsaquo;</a>
      <?php endif; ?>
      <?php for ($i=1; $i<=$total_pages; $i++): ?>
        <?php if ($i===$page): ?>
          <span class="current"><?= $i ?></span>
        <?php else: ?>
          <a href="?pagina=<?= $i ?><?= $cat_filter ? '&categoria='.urlencode($cat_filter) : '' ?>"><?= $i ?></a>
        <?php endif; ?>
      <?php endfor; ?>
      <?php if ($page < $total_pages): ?>
        <a href="?pagina=<?= $page+1 ?><?= $cat_filter ? '&categoria='.urlencode($cat_filter) : '' ?>">&rsaquo;</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

  </div>
</section>

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
