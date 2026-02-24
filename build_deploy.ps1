$ErrorActionPreference = "Stop"

Write-Host "Starting Build Process for JAILED Shared Hosting..." -ForegroundColor Cyan

# Define Paths
$projectRoot = Get-Location
$distDir = "$projectRoot\dist"
$coreDir = "$distDir\laravel_core"
# NOTE: We use 'dist' itself as the public root container to flatten the structure for upload
# $publicUploadDir = "$distDir\public_upload" 

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
# We don't create a separate public_upload folder, simply copy public contents to dist root
# New-Item -ItemType Directory -Path $publicUploadDir -Force | Out-Null

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
    "$projectRoot\.env",       
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
        # Copy to laravel_core subfolder
        # Write-Host "   Copying: $($item.Name)"
        Copy-Item -Path $item.FullName -Destination $coreDir -Recurse -Force
    }
}

# 3. Prepare Public (Public content goes to DIST root)
Write-Host "3. Copying Public Assets to Root..." -ForegroundColor Yellow
Copy-Item -Path "$projectRoot\public\*" -Destination $distDir -Recurse -Force

# 4. Hard-Fix index.php
Write-Host "4. Updating index.php paths..." -ForegroundColor Yellow
$indexFile = "$distDir\index.php"

if (Test-Path $indexFile) {
    $content = Get-Content $indexFile -Raw

    # Path Fix: Remove '../' to point to ./laravel_core
    # FROM: __DIR__.'/../storage/...'
    # TO:   __DIR__.'/laravel_core/storage/...'
    $content = $content -replace "__DIR__\.'/../", "__DIR__.'/laravel_core/"

    # Public Binding Fix: Inject right after $app creation
    $bindingCode = "`$app = require_once __DIR__.'/laravel_core/bootstrap/app.php';"
    $bindingFix = "$bindingCode`n`n// HOSTING FIX: Bind public path to current directory`n`$app->bind('path.public', function() {`n    return __DIR__;`n});"
    
    # We replace the bootstrap require line with itself + the fix
    $content = $content.Replace($bindingCode, $bindingFix)

    Set-Content -Path $indexFile -Value $content
    Write-Host "   SUCCESS: index.php updated." -ForegroundColor Green
} else {
    Write-Error "   ERROR: index.php not found in dist root!"
}

# 5. Hard-Fix config/filesystems.php
Write-Host "5. Patching config/filesystems.php (No Symlinks)..." -ForegroundColor Yellow
$configFile = "$coreDir\config\filesystems.php"

if (Test-Path $configFile) {
    $configContent = Get-Content $configFile -Raw
    
    # Replace 'root' => storage_path('app/public'),
    # With    'root' => public_path('storage'),
    # Note: Regex allows for some whitespace variation
    $configContent = $configContent -replace "'root'\s*=>\s*storage_path\('app/public'\),", "'root' => public_path('storage'),"

    Set-Content -Path $configFile -Value $configContent
    Write-Host "   SUCCESS: filesystems.php patched." -ForegroundColor Green
} else {
    Write-Error "   ERROR: config/filesystems.php not found in core!"
}

# 6. Security (.htaccess)
Write-Host "6. Securing Core Directory..." -ForegroundColor Yellow
$htaccessPath = "$coreDir\.htaccess"
Set-Content -Path $htaccessPath -Value "Deny from all"
Write-Host "   SUCCESS: .htaccess created in laravel_core." -ForegroundColor Green

Write-Host "--------------------------------------------------------"
Write-Host "BUILD COMPLETE!" -ForegroundColor Green
Write-Host "--------------------------------------------------------"
Write-Host "Upload contents of '$distDir' directly to your server's public root (/kd239258)."
Write-Host "--------------------------------------------------------"
