# start-dev.ps1
# Script para subir o ambiente de desenvolvimento no Windows (PowerShell)
# Executar a partir da raiz do projeto

param(
    [switch]$Rebuild
)

$projectDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $projectDir

if ($Rebuild) {
    Write-Host "Reconstruindo imagens e subindo containers..." -ForegroundColor Cyan
    docker-compose up -d --build
} else {
    Write-Host "Subindo containers..." -ForegroundColor Cyan
    docker-compose up -d
}

# Espera até o container do MySQL ficar saudável
Write-Host "Aguardando MySQL ficar saudável (até 120s)..." -ForegroundColor Cyan
$maxWait = 120
$elapsed = 0
$healthy = $false

while ($elapsed -lt $maxWait) {
    $status = docker inspect --format='{{json .State.Health.Status}}' mysql-db 2>$null | tr -d '"'
    if ($status -eq 'healthy') {
        $healthy = $true
        break
    }
    Start-Sleep -Seconds 2
    $elapsed += 2
    Write-Host -NoNewline "."
}

if (-not $healthy) {
    Write-Host "MySQL não reportou status healthy após $maxWait segundos. Verifique logs: docker logs mysql-db" -ForegroundColor Yellow
} else {
    Write-Host "`nMySQL está saudável." -ForegroundColor Green

    # Importa o banco se ainda não existir as tabelas
    $check = docker exec -i mysql-db mysql -u root -proot -e "USE trab_integrador; SHOW TABLES;" 2>$null
    if (-not $check) {
        Write-Host "Importando database.sql..." -ForegroundColor Cyan
        docker exec -i mysql-db mysql -u root -proot < .\database.sql
        Write-Host "Importação concluída." -ForegroundColor Green
    } else {
        Write-Host "Banco já contém tabelas — pulando importação." -ForegroundColor Green
    }
}

Write-Host "Acesse a aplicação em: http://localhost:8080" -ForegroundColor Green
Write-Host "Execute scripts PHP dentro do container web com: docker exec -it php-apache bash" -ForegroundColor Gray
