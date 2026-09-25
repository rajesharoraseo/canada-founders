$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$distFolder = Join-Path $projectRoot 'dist'
$stageFolder = Join-Path $distFolder '_tmp'

if (Test-Path $distFolder) {
    Remove-Item $distFolder -Recurse -Force
}

New-Item -ItemType Directory -Path $distFolder -Force | Out-Null
New-Item -ItemType Directory -Path $stageFolder -Force | Out-Null

$themeSource = Join-Path $projectRoot 'wp-content/themes/canadafounders'
$pluginSource = Join-Path $projectRoot 'wp-content/plugins/canadafounders-demo-import'

if (-not (Test-Path $themeSource)) {
    throw "Theme source folder not found: $themeSource"
}

if (-not (Test-Path $pluginSource)) {
    throw "Plugin source folder not found: $pluginSource"
}

Copy-Item -Path $themeSource -Destination (Join-Path $stageFolder 'canadafounders') -Recurse -Force
Copy-Item -Path $pluginSource -Destination (Join-Path $stageFolder 'canadafounders-demo-import') -Recurse -Force

Compress-Archive -Path (Join-Path $stageFolder 'canadafounders') -DestinationPath (Join-Path $distFolder 'canadafounders-theme.zip') -Force
Compress-Archive -Path (Join-Path $stageFolder 'canadafounders-demo-import') -DestinationPath (Join-Path $distFolder 'canadafounders-demo-import.zip') -Force

Write-Host "Created installable packages:"
Get-ChildItem $distFolder | Select-Object Name, FullName

Remove-Item $stageFolder -Recurse -Force
