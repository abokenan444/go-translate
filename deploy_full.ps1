# Deployment Script for Windows
# Usage: .\deploy_full.ps1

$ErrorActionPreference = "Stop"

$RemoteHost = "145.14.158.101"
$RemoteUser = "root"
$RemotePass = "Shatha-Yasser-1992"
$RemotePath = "/tmp"

Write-Host "Starting Deployment Process..." -ForegroundColor Green

# 1. Create Zip Package
Write-Host "Creating deployment package..."
$exclude = @("vendor", "node_modules", ".git", ".env", "storage", "deploy_package.zip")
Compress-Archive -Path * -DestinationPath deploy_package.zip -Update

# 2. Upload Package
Write-Host "Uploading package to $RemoteHost..."
# Using pscp (Putty SCP) - assumes it's in PATH
echo y | pscp -pw $RemotePass deploy_package.zip ${RemoteUser}@${RemoteHost}:${RemotePath}/deploy_package.zip
echo y | pscp -pw $RemotePass deploy_remote.sh ${RemoteUser}@${RemoteHost}:${RemotePath}/deploy_remote.sh

# 3. Execute Remote Script
Write-Host "Executing remote deployment..."
# Using plink (Putty Link) - assumes it's in PATH
echo y | plink -batch -pw $RemotePass ${RemoteUser}@${RemoteHost} "chmod +x ${RemotePath}/deploy_remote.sh && ${RemotePath}/deploy_remote.sh"

Write-Host "Deployment Finished!" -ForegroundColor Green
