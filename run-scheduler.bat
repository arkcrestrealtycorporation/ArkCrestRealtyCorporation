@echo off
cd /d C:\Users\USER\Herd\system_project
php artisan schedule:run >> storage\logs\scheduler.log 2>&1