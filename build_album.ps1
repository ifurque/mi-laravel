# build_album.ps1
# Crea un directorio 'album' con los archivos copiados en el orden pedido por el usuario.

$root = Split-Path -Parent $MyInvocation.MyCommand.Path
$albumDir = Join-Path $root "album"
if (-not (Test-Path $albumDir)) { New-Item -Path $albumDir -ItemType Directory | Out-Null }

$index = 1
function CopyIndexed($path) {
    if (Test-Path $path) {
        $padded = '{0:D3}' -f $global:index
        $dest = Join-Path $albumDir ("$padded" + "_" + (Split-Path $path -Leaf))
        Copy-Item -Path $path -Destination $dest -Force
        Write-Output "[$global:index] Copied $path -> $dest"
        $global:index++
    } else {
        Write-Warning "File not found: $path"
    }
}

# Rutas base
$public = Join-Path $root "public"
$ffc = Join-Path $public "foto feria de ciencias"
$images = Join-Path $public "images"
$resources = Join-Path $root "resources"

$files = @()

# 1) Primero video.mp4 (raíz si existe, si no busca en public)
$videoRoot = Join-Path $root "video.mp4"
if (Test-Path $videoRoot) { $files += $videoRoot } elseif (Test-Path (Join-Path $ffc "video.mp4")) { $files += (Join-Path $ffc "video.mp4") }

# 2) Foto de la parte posterior de la tarjeta de inicio -> public/images/ep33.jpg
$files += (Join-Path $images "ep33.jpg")

# 3) Fotos 1 a 50, reemplazando la 16 por plantando.jpeg
for ($i=1; $i -le 50; $i++) {
    if ($i -eq 16) {
        $files += (Join-Path $ffc "plantando.jpeg")
    } else {
        $files += (Join-Path $ffc ("{0}.jpg" -f $i))
    }
}

# 4) rabanito1 y rabanito2
$files += (Join-Path $ffc "rabanito1.jpeg")
$files += (Join-Path $ffc "rabanito2.jpeg")

# 5) videoaguadeajo.mp4
$files += (Join-Path $ffc "videoaguadeajo.mp4")

# 6) qr1 a qr25
for ($q=1; $q -le 25; $q++) {
    $files += (Join-Path $ffc ("qr{0}.jpeg" -f $q))
}

# 7) n1 a n10 (prefiere public/foto feria de ciencias, sino resources)
for ($n=1; $n -le 10; $n++) {
    $candidate1 = Join-Path $ffc ("n{0}.jpeg" -f $n)
    $candidate2 = Join-Path $resources ("n{0}.jpeg" -f $n)
    if (Test-Path $candidate1) { $files += $candidate1 } elseif (Test-Path $candidate2) { $files += $candidate2 } else { $files += $candidate1 }
}

# Ejecutar copias
foreach ($f in $files) { CopyIndexed $f }

Write-Output "Done. Total files copied: $($index - 1)"