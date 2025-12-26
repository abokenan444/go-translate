# Smart Deployment Script
# Zips only necessary files from the inner application structure

$ErrorActionPreference = "Stop"
$Source = "go-translate-main"
$Dest = "deploy_package.zip"
$RemoteHost = "145.14.158.101"
$RemoteUser = "root"
$RemotePass = "Shatha-Yasser-1992"
$RemotePath = "/var/www/cultural-translate-platform"

Write-Host "Preparing deployment package..." -ForegroundColor Cyan

# 1. Create Temp Directory
$TempDir = "temp_deploy"
if (Test-Path $TempDir) { Remove-Item -Path $TempDir -Recurse -Force }
New-Item -ItemType Directory -Force -Path $TempDir | Out-Null

# 2. Copy Essential Folders
$Folders = @("app", "bootstrap", "config", "database", "lang", "public", "resources", "routes", "packages")
foreach ($f in $Folders) {
    $srcPath = "$Source\$f"
    if (Test-Path $srcPath) {
        Write-Host "Copying $f..."
        Copy-Item -Path $srcPath -Destination "$TempDir\$f" -Recurse -Force
    }
}

# 3. Copy Essential Files
$Files = @("composer.json", "composer.lock", "package.json", "vite.config.js", "artisan", "server.php")
foreach ($f in $Files) {
    $srcPath = "$Source\$f"
    if (Test-Path $srcPath) {
        Copy-Item -Path $srcPath -Destination "$TempDir\$f" -Force
    }
}

# 4. Zip Package
Write-Host "Zipping files (using tar)..." -ForegroundColor Cyan
if (Test-Path $Dest) { Remove-Item $Dest -Force }
try {
    # Use tar for better performance and locking handling
    # -a: auto-detect format (zip)
    # -c: create
    # -f: file
    # -C: change directory
    tar -a -c -f $Dest -C $TempDir .
    if ($LASTEXITCODE -ne 0) { throw "tar failed with exit code $LASTEXITCODE" }
} catch {
    Write-Error "Zipping failed: $_"
    exit 1
}

# 5. Cleanup Temp
Remove-Item -Path $TempDir -Recurse -Force

# 6. Upload
Write-Host "Uploading to $RemoteHost..." -ForegroundColor Cyan
# Using pscp
echo y | pscp -pw $RemotePass $Dest ${RemoteUser}@${RemoteHost}:${RemotePath}/deploy_package.zip
echo y | pscp -pw $RemotePass deploy_remote.sh ${RemoteUser}@${RemoteHost}:${RemotePath}/deploy_remote.sh

# 7. Execute Remote
Write-Host "Executing remote deployment..." -ForegroundColor Cyan
echo y | plink -batch -pw $RemotePass ${RemoteUser}@${RemoteHost} "chmod +x ${RemotePath}/deploy_remote.sh && ${RemotePath}/deploy_remote.sh"

Write-Host "Deployment Complete!" -ForegroundColor Green
