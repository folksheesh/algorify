@echo off
title Algorify Laravel Scheduler
echo Starting Laravel Scheduler for Algorify...
echo Checking DOKU payments and auto-approving pending transactions every minute
echo.
echo Press Ctrl+C to stop
echo.
cd /d C:\laragon\www\algorify
php artisan schedule:work
