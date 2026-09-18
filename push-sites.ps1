# push-sites.ps1
# Roda git add . + commit + push nos dois repositórios de uma vez.
# Uso: clique duplo no arquivo, ou rode no PowerShell.

$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm"

$repos = @(
    @{ path = "D:/Documentos/Site/Repositório/jacquelinealves";      name = "jacquelinealves" },
    @{ path = "D:/Documentos/Site/Repositório/orbitandonomarketing"; name = "orbitandonomarketing" }
)

foreach ($repo in $repos) {
    Write-Host "`n>>> $($repo.name)" -ForegroundColor Cyan

    git -C $repo.path config --global --add safe.directory $repo.path 2>$null

    $status = git -C $repo.path status --porcelain
    if (-not $status) {
        Write-Host "Nada para commitar." -ForegroundColor Yellow
        continue
    }

    git -C $repo.path add -A
    git -C $repo.path commit -m "Update $($repo.name) - $timestamp"
    git -C $repo.path push

    if ($LASTEXITCODE -eq 0) {
        Write-Host "Push ok." -ForegroundColor Green
    } else {
        Write-Host "Erro no push." -ForegroundColor Red
    }
}

Write-Host "`nConcluido. Pressione Enter para fechar."
$null = Read-Host
