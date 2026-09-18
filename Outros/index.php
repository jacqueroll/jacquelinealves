<?php
require_once __DIR__ . '/../api/auth.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    if (do_login($senha)) {
        header('Location: /admin/dashboard.php');
        exit;
    }
    $erro = 'Senha incorreta.';
}
if (is_logged_in()) { header('Location: /admin/dashboard.php'); exit; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Jacqueline Alves</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,700;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',sans-serif;background:#FFF0F5;min-height:100vh;display:flex;align-items:center;justify-content:center}
.login-card{background:#FFFAFA;border:1px solid #CDC1C5;border-radius:16px;padding:2.5rem;width:100%;max-width:380px;box-shadow:0 4px 24px rgba(188,143,143,.1)}
.login-logo{text-align:center;margin-bottom:2rem}
.login-logo-j{width:52px;height:52px;border-radius:50%;background:#BC8F8F;display:flex;align-items:center;justify-content:center;font-family:'Lora',serif;font-style:italic;font-size:1.5rem;font-weight:700;color:#fff;margin:0 auto .75rem}
.login-logo h1{font-family:'Lora',serif;font-size:1.2rem;font-weight:700;color:#1C1C1C}
.login-logo p{font-size:.78rem;color:#8B8386}
label{display:block;font-size:.82rem;font-weight:600;color:#1C1C1C;margin-bottom:.4rem}
input[type="password"]{width:100%;padding:.8rem 1rem;border:1.5px solid #CDC1C5;border-radius:8px;font-size:.95rem;font-family:'Inter',sans-serif;outline:none;background:#fff;margin-bottom:1.1rem}
input[type="password"]:focus{border-color:#BC8F8F}
button{width:100%;background:#BC8F8F;color:#fff;border:none;border-radius:8px;padding:.8rem;font-size:.95rem;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:background .15s}
button:hover{background:#a07070}
.erro{background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;padding:.75rem 1rem;border-radius:8px;font-size:.85rem;margin-bottom:1rem}
a.back{display:block;text-align:center;margin-top:1.25rem;font-size:.82rem;color:#8B8386;text-decoration:none}
a.back:hover{color:#BC8F8F}
</style>
</head>
<body>
<div class="login-card">
  <div class="login-logo">
    <div class="login-logo-j">J</div>
    <h1>Painel Admin</h1>
    <p>Jacqueline Alves · Publicações</p>
  </div>
  <?php if ($erro): ?><div class="erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
  <form method="POST" action="/admin/index.php">
    <label for="senha">Senha</label>
    <input type="password" id="senha" name="senha" placeholder="••••••••••" autofocus required>
    <button type="submit">Entrar</button>
  </form>
  <a class="back" href="/">Voltar ao site</a>
</div>
</body>
</html>
