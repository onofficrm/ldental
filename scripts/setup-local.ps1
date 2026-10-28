# 그누보드 로컬 실행 (PHP 내장 서버)
$Root = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
$Web = Join-Path $Root "public_html"
$DbConfig = Join-Path $Web "data\dbconfig.php"
$Sample = Join-Path $Web "data\dbconfig.php.sample"

if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "PHP가 PATH에 없습니다. Laragon/XAMPP 설치 후 php.exe PATH를 추가하거나:"
    Write-Host "  winget install PHP.PHP"
    exit 1
}

if (-not (Test-Path $DbConfig)) {
    if (-not (Test-Path $Sample)) {
        Write-Error "dbconfig.php.sample 이 없습니다."
        exit 1
    }
    Copy-Item $Sample $DbConfig
    Write-Host "data\dbconfig.php 를 생성했습니다. DB 비밀번호·TOKEN 키를 편집한 뒤 다시 실행하세요."
    notepad $DbConfig
    exit 0
}

Push-Location $Web
Write-Host "http://127.0.0.1:8080/  (종료: Ctrl+C)"
php -S 127.0.0.1:8080
Pop-Location
