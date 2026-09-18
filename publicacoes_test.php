<?php
// Arquivo de diagnóstico - apagar depois de testar
echo "<h1>PHP está funcionando!</h1>";
echo "<p>Versão: " . phpversion() . "</p>";
echo "<p>Pasta: " . __DIR__ . "</p>";
$posts_dir = __DIR__ . '/api/posts/';
echo "<p>Pasta posts existe: " . (is_dir($posts_dir) ? 'SIM' : 'NÃO') . "</p>";
