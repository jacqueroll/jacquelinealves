<?php
require_once __DIR__ . '/auth.php';
require_login();

header('Content-Type: application/json');

$upload_dir = __DIR__ . '/../assets/posts/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['imagem'])) {
    http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Nenhum arquivo enviado']); exit;
}

$file   = $_FILES['imagem'];
$ext    = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg','jpeg','png','gif','webp'];

if (!in_array($ext, $allowed)) {
    http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Tipo de arquivo não permitido']); exit;
}
if ($file['size'] > 4 * 1024 * 1024) {
    http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Arquivo muito grande (máximo 4MB)']); exit;
}

$name    = uniqid('img_', true) . '.' . $ext;
$dest    = $upload_dir . $name;
$url_pub = '/assets/posts/' . $name;

if (move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(['ok'=>true,'url'=>$url_pub]);
} else {
    http_response_code(500); echo json_encode(['ok'=>false,'msg'=>'Falha ao salvar o arquivo']);
}
