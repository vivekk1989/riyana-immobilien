$ErrorActionPreference = "Stop"

Write-Host "Starting Build Process for Shared Hosting (Sister Folder Structure)..." -ForegroundColor Cyan

# Define Paths
$projectRoot = Get-Location
$distDir = "$projectRoot\dist"
$coreDir = "$distDir\laravel_core"
$publicUploadDir = "$distDir\public_upload"

# 0. Optimization & Preparation
Write-Host "0. Cleaning and Optimizing..." -ForegroundColor Yellow
php artisan optimize:clear

Write-Host "NOTE: This script copies your CURRENT 'vendor' directory." -ForegroundColor Magenta
Write-Host "For a production build, ensure you have run:" -ForegroundColor Magenta
Write-Host "   composer install --no-dev --optimize-autoloader" -ForegroundColor Magenta
Write-Host "If you haven't, stop this script (Ctrl+C), run that command, and try again." -ForegroundColor Magenta
Write-Host "Waiting 3 seconds..." -ForegroundColor Gray
Start-Sleep -Seconds 3

# 1. Clean and Create Build Directory
Write-Host "1. Preparing 'dist' directory..."
if (Test-Path $distDir) {
    Remove-Item $distDir -Recurse -Force
}
New-Item -ItemType Directory -Path $coreDir -Force | Out-Null
New-Item -ItemType Directory -Path $publicUploadDir -Force | Out-Null

# 2. Prepare Core (Private)
Write-Host "2. Copying Core Application Files..." -ForegroundColor Yellow

# Define excludes (Git, Node Modules, Tests, existing dist, etc.)
$exclude = @(
    "$projectRoot\.git",
    "$projectRoot\.github",
    "$projectRoot\node_modules",
    "$projectRoot\dist",
    "$projectRoot\public",
    "$projectRoot\tests",
    "$projectRoot\.env",       # Usually safer not to bundle .env for prod upload
    "$projectRoot\.env.example"
)

# Get all items in project root
$items = Get-ChildItem -Path $projectRoot

foreach ($item in $items) {
    # Check if item should be excluded
    $shouldExclude = $false
    foreach ($ex in $exclude) {
        if ($item.FullName -eq $ex) {
            $shouldExclude = $true
            break
        }
    }

    if (-not $shouldExclude) {
        Write-Host "   Copying: $($item.Name)"
        Copy-Item -Path $item.FullName -Destination $coreDir -Recurse -Force
    }
}

# 3. Prepare Public (Public)
Write-Host "3. Copying Public Assets..." -ForegroundColor Yellow
Copy-Item -Path "$projectRoot\public\*" -Destination $publicUploadDir -Recurse -Force

# 4. Update index.php Paths
Write-Host "4. Updating index.php paths..." -ForegroundColor Yellow
$indexFile = "$publicUploadDir\index.php"

if (Test-Path $indexFile) {
    $content = Get-Content $indexFile -Raw

    # Replace maintenance mode check path
    # FROM: __DIR__.'/../storage/framework/maintenance.php'
    # TO:   __DIR__.'/../laravel_core/storage/framework/maintenance.php'
    $content = $content -replace "__DIR__\.'/../storage/", "__DIR__.'/../laravel_core/storage/"

    # Replace autoload path
    # FROM: __DIR__.'/../vendor/autoload.php'
    # TO:   __DIR__.'/../laravel_core/vendor/autoload.php'
    $content = $content -replace "__DIR__\.'/../vendor/", "__DIR__.'/../laravel_core/vendor/"

    # Replace bootstrap path
    # FROM: __DIR__.'/../bootstrap/app.php'
    # TO:   __DIR__.'/../laravel_core/bootstrap/app.php'
    $content = $content -replace "__DIR__\.'/../bootstrap/", "__DIR__.'/../laravel_core/bootstrap/"

    Set-Content -Path $indexFile -Value $content
    Write-Host "   SUCCESS: index.php updated." -ForegroundColor Green
} else {
    Write-Error "   ERROR: index.php not found in public_upload!"
}

Write-Host "--------------------------------------------------------"
Write-Host "BUILD COMPLETE!" -ForegroundColor Green
Write-Host "--------------------------------------------------------"
Write-Host "Upload Instructions:"
Write-Host "1. Upload contents of '$coreDir' to '/laravel_core' on your FTP."
Write-Host "2. Upload contents of '$publicUploadDir' to '/kd239258' (your public root)."
Write-Host "--------------------------------------------------------"
