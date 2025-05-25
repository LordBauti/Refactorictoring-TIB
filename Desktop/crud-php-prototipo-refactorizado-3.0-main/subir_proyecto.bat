@echo off
echo ============================================
echo Subiendo archivos al repositorio de GitHub...
echo ============================================

:: Agrega todos los archivos (nuevos/modificados)
git add .

:: Crea el commit con mensaje personalizado
git commit -m "Archivos proporcionados por la catedra para refactorizar"

:: Sube al repositorio remoto
git push

echo ============================================
echo Cambios subidos exitosamente.
pause
