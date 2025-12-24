# Скрипт для восстановления файлов из коммита main, исключая backend
$ErrorActionPreference = "Stop"

# Получаем текущую директорию
$scriptPath = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $scriptPath

# Устанавливаем переменные окружения для git
$env:GIT_DIR = Join-Path $scriptPath ".git"
$env:GIT_WORK_TREE = $scriptPath

# Коммит из main с файлами
$mainCommit = "a756ce73bc0bb9ced97058391bfe2a0402987641"

Write-Host "Восстанавливаем файлы из коммита $mainCommit (исключая backend)..."

# Получаем список всех файлов из коммита
$files = git ls-tree -r --name-only $mainCommit

# Фильтруем файлы, исключая backend
$filesToRestore = $files | Where-Object { $_ -notlike "backend/*" -and $_ -ne "backend" }

Write-Host "Найдено файлов для восстановления: $($filesToRestore.Count)"

# Восстанавливаем каждый файл
foreach ($file in $filesToRestore) {
    try {
        git show "$mainCommit`:$file" | Out-File -FilePath $file -Encoding UTF8 -Force
        Write-Host "Восстановлен: $file"
    } catch {
        Write-Warning "Не удалось восстановить: $file - $_"
    }
}

Write-Host "Файлы восстановлены!"

