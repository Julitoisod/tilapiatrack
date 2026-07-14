@echo off
REM ============================================================
REM  TilapiaTrack background workers for XAMPP / Windows
REM ------------------------------------------------------------
REM  Feeding reminders only fire when BOTH of these run:
REM    1. schedule:work  -> dispatches notifications:send-feeding every minute
REM    2. queue:work     -> processes the queued database/broadcast notifications
REM
REM  Double-click this file to start both workers in their own
REM  windows. Keep the windows open while the app is in use.
REM
REM  For unattended / production use, register this script (or the
REM  two commands below) with Windows Task Scheduler set to run
REM  "At startup" with "Run whether user is logged on or not".
REM ============================================================

cd /d "%~dp0"
set "PHP=C:\xampp\php\php.exe"
if not exist "%PHP%" set "PHP=php"

start "TilapiaTrack Scheduler" "%PHP%" artisan schedule:work
start "TilapiaTrack Queue Worker" "%PHP%" artisan queue:work --tries=3 --sleep=3

echo.
echo TilapiaTrack scheduler and queue worker started in separate windows.
echo Close those windows to stop the workers.
