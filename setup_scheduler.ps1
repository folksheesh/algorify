# Setup Laravel Scheduler to run automatically on Windows startup
# This script creates a Windows Task Scheduler entry

$taskName = "Algorify Laravel Scheduler"
$workingDir = "c:\laragon\www\algorify"
$batchFile = "$workingDir\start_scheduler.bat"
$logFile = "$workingDir\storage\logs\scheduler.log"

Write-Host "Setting up Laravel Scheduler as Windows Task..." -ForegroundColor Cyan
Write-Host ""

# Check if task already exists
$existingTask = Get-ScheduledTask -TaskName $taskName -ErrorAction SilentlyContinue

if ($existingTask) {
    Write-Host "Task already exists. Removing old task..." -ForegroundColor Yellow
    Unregister-ScheduledTask -TaskName $taskName -Confirm:$false
}

# Create the scheduled task using current user (no admin required)
$action = New-ScheduledTaskAction -Execute "cmd.exe" -Argument "/c start /min `"$batchFile`"" -WorkingDirectory $workingDir

# Trigger on user logon
$trigger = New-ScheduledTaskTrigger -AtLogOn

# Run as current user
$principal = New-ScheduledTaskPrincipal -UserId $env:USERNAME -LogonType Interactive

# Settings: allow task to run indefinitely
$settings = New-ScheduledTaskSettingsSet `
    -AllowStartIfOnBatteries `
    -DontStopIfGoingOnBatteries `
    -StartWhenAvailable `
    -ExecutionTimeLimit (New-TimeSpan -Days 0)

# Register the task
try {
    Register-ScheduledTask `
        -TaskName $taskName `
        -Action $action `
        -Trigger $trigger `
        -Principal $principal `
        -Settings $settings `
        -Description "Automatically runs Laravel Scheduler for Algorify platform (DOKU payment check & auto-approval)" `
        -ErrorAction Stop
    
    Write-Host ""
    Write-Host "✓ Task Scheduler setup complete!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Task Name: $taskName" -ForegroundColor Cyan
    Write-Host "Working Directory: $workingDir" -ForegroundColor Cyan
    Write-Host "Batch File: $batchFile" -ForegroundColor Cyan
    Write-Host "Log File: $logFile" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "The Laravel scheduler will now start automatically when you log in to Windows." -ForegroundColor Green
    Write-Host ""
    Write-Host "To manage the task:" -ForegroundColor Yellow
    Write-Host "  - View: Task Scheduler Library" -ForegroundColor Gray
    Write-Host "  - Start now: Start-ScheduledTask -TaskName 'Algorify Laravel Scheduler'" -ForegroundColor Gray
    Write-Host "  - Stop: Stop-ScheduledTask -TaskName 'Algorify Laravel Scheduler'" -ForegroundColor Gray
    Write-Host "  - Remove: Unregister-ScheduledTask -TaskName 'Algorify Laravel Scheduler' -Confirm:`$false" -ForegroundColor Gray
    Write-Host ""
    Write-Host "Starting the task now..." -ForegroundColor Yellow
    Start-ScheduledTask -TaskName $taskName
    Start-Sleep -Seconds 2
    Write-Host "✓ Task started successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "The scheduler is now running in the background and will check for payments every minute." -ForegroundColor Green
} catch {
    Write-Host "✗ Error creating task: $_" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please run this script as Administrator or use Task Scheduler GUI:" -ForegroundColor Yellow
    Write-Host "1. Open Task Scheduler" -ForegroundColor Gray
    Write-Host "2. Create Basic Task..." -ForegroundColor Gray
    Write-Host "3. Name: Algorify Laravel Scheduler" -ForegroundColor Gray
    Write-Host "4. Trigger: When I log on" -ForegroundColor Gray
    Write-Host "5. Action: Start a program" -ForegroundColor Gray
    Write-Host "6. Program: cmd.exe" -ForegroundColor Gray
    Write-Host "7. Arguments: /c start /min `"$batchFile`"" -ForegroundColor Gray
    Write-Host "8. Start in: $workingDir" -ForegroundColor Gray
}

