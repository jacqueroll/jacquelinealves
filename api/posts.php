<?php
require_once __DIR__ . '/auth.php';
require_login();

header('Content-Type: application/json');

$posts_dir = __DIR__ . '/posts/';
if (!is_dir($posts_dir)) mkdir($posts_dir, 0755, true);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// ── Listar ────────────────────────────────────────────────────────────────────
if ($method === 'GET' && $action === 'list') {
    $posts = [];
    foreach (glob($posts_dir . '*.json') as $f) {
        $p = json_decode(file_get_contents($f), true);
        if ($p) $posts[] = ['slug'=>$p['slug'],'titulo'=>$p['titulo'],'categoria'=>$p['categoria'],'status'=>$p['status'],'data'=>$p['data']];
    }
    usort($posts, fn($a,$b) => strcmp($b['data'],$a['data']));
    echo json_encode(['ok'=>true,'posts'=>$posts]);
    exit;
}

// ── Ler um ────────────────────────────────────────────────────────────────────
if ($method === 'GET' && $action === 'get') {
    $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'] ?? ''));
    $file = $posts_dir . $slug . '.json';
    if (!$slug || !file_exists($file)) { http_response_code(404); echo json_encode(['ok'=>false,'msg'=>'Não encontrado']); exit; }
    echo json_encode(['ok'=>true,'post'=>json_decode(file_get_contents($file),true)]);
    exit;
}

// ── Salvar / publicar ─────────────────────────────────────────────────────────
if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) { http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Dados inválidos']); exit; }

    $titulo    = trim($body['titulo'] ?? '');
    $corpo     = $body['corpo'] ?? '';
    $categoria = $body['categoria'] ?? 'Contos';
    $status    = in_array($body['status'] ?? '', ['publicado','rascunho']) ? $body['status'] : 'rascunho';
    $imagem    = $body['imagem'] ?? '';
    $old_slug  = $body['old_slug'] ?? '';

    if (!$titulo) { http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Título obrigatório']); exit; }

    // Gerar slug
    $slug = mb_strtolower($titulo, 'UTF-8');
    $slug = preg_replace('/[áàãâä]/u','a',$slug);
    $slug = preg_replace('/[éèêë]/u','e',$slug);
    $slug = preg_replace('/[íìîï]/u','i',$slug);
    $slug = preg_replace('/[óòõôö]/u','o',$slug);
    $slug = preg_replace('/[úùûü]/u','u',$slug);
    $slug = preg_replace('/[ç]/u','c',$slug);
    $slug = preg_replace('/[^a-z0-9]+/','-',$slug);
    $slug = trim($slug,'-');

    // Evitar colisão de slug (exceto ao editar o mesmo post)
    $base_slug = $slug; $i = 1;
    while (file_exists($posts_dir.$slug.'.json') && $slug !== $old_slug) {
        $slug = $base_slug.'-'.$i++;
    }

    // Se slug mudou, remover arquivo antigo
    if ($old_slug && $old_slug !== $slug && file_exists($posts_dir.$old_slug.'.json')) {
        unlink($posts_dir.$old_slug.'.json');
    }

    $data_pub = $body['data'] ?? date('Y-m-d');

    $post = compact('slug','titulo','corpo','categoria','status','imagem','data_pub');
    $post['data'] = $data_pub;
    file_put_contents($posts_dir.$slug.'.json', json_encode($post, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    echo json_encode(['ok'=>true,'slug'=>$slug]);
    exit;
}

// ── Deletar ───────────────────────────────────────────────────────────────────
if ($method === 'DELETE') {
    $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'] ?? ''));
    $file = $posts_dir . $slug . '.json';
    if ($slug && file_exists($file)) { unlink($file); echo json_encode(['ok'=>true]); }
    else { http_response_code(404); echo json_encode(['ok'=>false,'msg'=>'Não encontrado']); }
    exit;
}

http_response_code(405);
echo json_encode(['ok'=>false,'msg'=>'Método não suportado']);
