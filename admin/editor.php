<?php
require_once __DIR__ . '/../api/auth.php';
require_login();

$slug    = $_GET['slug'] ?? '';
$post    = null;
$is_edit = false;

if ($slug) {
    $file = __DIR__ . '/../api/posts/' . preg_replace('/[^a-z0-9\-]/', '', $slug) . '.json';
    if (file_exists($file)) { $post = json_decode(file_get_contents($file), true); $is_edit = true; }
}

$cats = ['Contos','Poemas','Fanfics','Ensaios','Diário de leitura'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $is_edit ? 'Editar' : 'Nova publicação' ?> | Admin Jacqueline Alves</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,600;0,700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<!-- Quill.js editor rico -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.2/quill.snow.min.css" rel="stylesheet">
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
    <h1><?= $is_edit ? 'Editar publicação' : 'Nova publicação' ?></h1>
    <div style="display:flex;gap:.75rem">
      <button onclick="salvar('rascunho')" class="btn-admin btn-outline-admin">Salvar rascunho</button>
      <button onclick="salvar('publicado')" class="btn-admin">Publicar</button>
    </div>
  </div>

  <div id="msg" style="display:none;margin-bottom:1rem"></div>

  <div class="editor-layout">
    <div class="editor-main">
      <div class="card-admin" style="margin-bottom:1.25rem">
        <label class="field-label">Título</label>
        <input type="text" id="titulo" placeholder="Título do texto" value="<?= htmlspecialchars($post['titulo'] ?? '') ?>" class="field-input">
      </div>

      <div class="card-admin">
        <label class="field-label">Corpo do texto</label>
        <div id="editor" style="min-height:420px;font-family:'Lora',serif;font-size:1rem;line-height:1.8"><?= $post['corpo'] ?? '' ?></div>
      </div>
    </div>

    <div class="editor-sidebar">
      <div class="card-admin" style="margin-bottom:1rem">
        <label class="field-label">Categoria</label>
        <select id="categoria" class="field-input">
          <?php foreach ($cats as $c): ?>
          <option value="<?= $c ?>" <?= ($post['categoria'] ?? 'Contos') === $c ? 'selected' : '' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="card-admin" style="margin-bottom:1rem">
        <label class="field-label">Data de publicação</label>
        <input type="date" id="data" value="<?= htmlspecialchars($post['data'] ?? date('Y-m-d')) ?>" class="field-input">
      </div>

      <div class="card-admin" style="margin-bottom:1rem">
        <label class="field-label">Imagem de capa</label>
        <?php if (!empty($post['imagem'])): ?>
        <img src="<?= htmlspecialchars($post['imagem']) ?>" style="width:100%;border-radius:8px;margin-bottom:.75rem;object-fit:cover;max-height:160px">
        <?php endif; ?>
        <input type="file" id="img_file" accept="image/*" style="font-size:.82rem;font-family:'Inter',sans-serif">
        <div id="img_preview" style="margin-top:.5rem"></div>
        <input type="hidden" id="imagem" value="<?= htmlspecialchars($post['imagem'] ?? '') ?>">
        <p style="font-size:.75rem;color:#8B8386;margin-top:.4rem">JPG, PNG, WebP — máximo 4MB</p>
      </div>

      <div class="card-admin" style="margin-bottom:1rem">
        <label class="field-label">Exportar conteúdo</label>
        <button onclick="exportarJSON()" class="btn-admin btn-outline-admin" style="width:100%;margin-bottom:.5rem">Exportar JSON</button>
        <button onclick="exportarHTML()" class="btn-admin btn-outline-admin" style="width:100%">Exportar HTML</button>
      </div>

      <?php if ($is_edit && ($post['status'] ?? '') === 'publicado'): ?>
      <div class="card-admin">
        <label class="field-label">Link público</label>
        <a href="/publicacoes/post.php?slug=<?= urlencode($post['slug']) ?>" target="_blank" style="font-size:.82rem;color:#BC8F8F;word-break:break-all">Ver publicação</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.2/quill.js"></script>
<script>
var quill = new Quill('#editor', {
  theme: 'snow',
  modules: {
    toolbar: [
      [{ header: [1,2,3,false] }],
      ['bold','italic','underline','strike'],
      ['blockquote','code-block'],
      [{ list:'ordered' },{ list:'bullet' }],
      [{ align:[] }],
      ['link','image'],
      ['clean']
    ]
  }
});

// Upload de imagem no editor (via botão da toolbar)
quill.getModule('toolbar').addHandler('image', function() {
  var input = document.createElement('input');
  input.setAttribute('type','file');
  input.setAttribute('accept','image/*');
  input.click();
  input.onchange = async function() {
    var file = this.files[0];
    if (!file) return;
    var fd = new FormData();
    fd.append('imagem', file);
    var r = await fetch('/api/upload.php', {method:'POST', body:fd});
    var d = await r.json();
    if (d.ok) {
      var range = quill.getSelection(true);
      quill.insertEmbed(range.index, 'image', d.url);
    } else { alert('Erro ao subir imagem: ' + (d.msg||'')); }
  };
});

// Upload da imagem de capa
document.getElementById('img_file').addEventListener('change', async function() {
  var file = this.files[0];
  if (!file) return;
  var fd = new FormData();
  fd.append('imagem', file);
  var r = await fetch('/api/upload.php', {method:'POST', body:fd});
  var d = await r.json();
  if (d.ok) {
    document.getElementById('imagem').value = d.url;
    document.getElementById('img_preview').innerHTML = '<img src="'+d.url+'" style="width:100%;border-radius:8px;margin-top:.5rem;max-height:160px;object-fit:cover">';
  } else { alert('Erro ao subir capa: '+(d.msg||'')); }
});

async function salvar(status) {
  var titulo = document.getElementById('titulo').value.trim();
  if (!titulo) { showMsg('O título é obrigatório.','erro'); return; }

  var payload = {
    titulo:    titulo,
    corpo:     quill.root.innerHTML,
    categoria: document.getElementById('categoria').value,
    status:    status,
    imagem:    document.getElementById('imagem').value,
    data:      document.getElementById('data').value,
    old_slug:  '<?= addslashes($post['slug'] ?? '') ?>'
  };

  var r = await fetch('/api/posts.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)});
  var d = await r.json();
  if (d.ok) {
    showMsg(status==='publicado' ? 'Publicado com sucesso!' : 'Rascunho salvo!', 'ok');
    if (d.slug !== '<?= addslashes($post['slug'] ?? '') ?>') {
      setTimeout(() => { window.location.href = '/admin/editor.php?slug=' + encodeURIComponent(d.slug); }, 800);
    }
  } else { showMsg('Erro: ' + (d.msg || 'Tente novamente.'), 'erro'); }
}

function showMsg(text, type) {
  var el = document.getElementById('msg');
  el.style.display = 'block';
  el.className = type === 'ok' ? 'msg-ok' : 'msg-erro';
  el.textContent = text;
  setTimeout(() => { el.style.display='none'; }, 3500);
}

function exportarJSON() {
  var data = {
    titulo: document.getElementById('titulo').value,
    corpo: quill.root.innerHTML,
    categoria: document.getElementById('categoria').value,
    data: document.getElementById('data').value,
    imagem: document.getElementById('imagem').value
  };
  var blob = new Blob([JSON.stringify(data,null,2)],{type:'application/json'});
  var a = document.createElement('a'); a.href = URL.createObjectURL(blob);
  a.download = (data.titulo || 'post') + '.json'; a.click();
}

function exportarHTML() {
  var html = '<h1>' + document.getElementById('titulo').value + '</h1>\n' + quill.root.innerHTML;
  var blob = new Blob([html],{type:'text/html'});
  var a = document.createElement('a'); a.href = URL.createObjectURL(blob);
  a.download = (document.getElementById('titulo').value || 'post') + '.html'; a.click();
}
</script>
</body>
</html>
