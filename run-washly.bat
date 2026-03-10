@echo off
setlocal

REM ---------------------------------------------
REM Washly quick run script (XAMPP start manually)
REM Usage:
REM   run-washly.bat        -> run web + reverb
REM   run-washly.bat build  -> run npm build first, then web + reverb
REM ---------------------------------------------

cd /d "%~dp0"

if exist "C:\xampp\php\php.exe" (
  set "PHP_CMD=C:\xampp\php\php.exe"
) else (
  set "PHP_CMD=php"
)

where "%PHP_CMD%" >nul 2>&1
if errorlevel 1 (
  echo [ERROR] PHP not found. Install PHP or update PHP path in this script.
  pause
  exit /b 1
)

if /I "%~1"=="build" (
  echo [INFO] Running npm build...
  call npm run build
  if errorlevel 1 (
    echo [ERROR] npm run build failed.
    pause
    exit /b 1
  )
)

echo [INFO] Starting Laravel app server on 0.0.0.0:8000
start "Washly - Laravel" cmd /k "cd /d "%~dp0" && "%PHP_CMD%" artisan serve --host=0.0.0.0 --port=8000"

echo [INFO] Starting Reverb server on 0.0.0.0:8080
start "Washly - Reverb" cmd /k "cd /d "%~dp0" && "%PHP_CMD%" artisan reverb:start"

echo.
echo [DONE] Services started.
echo - Web:   http://localhost:8000
echo - LAN:   http://<YOUR_IP>:8000
echo.
echo Close each opened terminal window to stop services.

endlocal
