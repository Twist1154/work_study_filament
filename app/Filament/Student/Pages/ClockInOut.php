<?php

declare(strict_types=1);

namespace App\Filament\Student\Pages;

use App\Models\Appointment;
use App\Models\WorkLog;
use BackedEnum;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class ClockInOut extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clock';

    protected string $view = 'filament.student.pages.clock-in-out';

    // Holds state parameters for the active layout
    public ?Appointment $activeAppointment = null;
    public ?WorkLog $currentActiveLog = null;
    public array $recentLogs = [];

    public function mount(): void
    {
        $user = auth()->user();

        // 1. Fetch the student profile linked to this user
        $student = $user->student;

        if (!$student) {
            $this->activeAppointment = null;
            return;
        }

        // 2. Fetch the active workstudy appointment [1.2.3]
        $this->activeAppointment = Appointment::where('student_id', $student->student_id)
            ->where('status', 'active')
            ->first();

        if ($this->activeAppointment) {
            // 3. Check if the student is currently clocked in (has a log with no clock_out_at)
            $this->currentActiveLog = WorkLog::where('student_id', $student->student_id)
                ->where('appointment_id', $this->activeAppointment->appointment_id)
                ->whereNull('clock_out_at')
                ->first();

            // 4. Fetch the 10 most recent logs for listing
            $this->loadRecentLogs($student->student_id);
        }
    }

    public function clockIn(): void
    {
        if (!$this->activeAppointment) {
            return;
        }

        $student = auth()->user()->student;

        // Prevent double clocking in
        if ($this->currentActiveLog) {
            return;
        }

        // Create the active daily log record [1.1.2]
        $this->currentActiveLog = WorkLog::create([
            'student_id' => $student->student_id,
            'appointment_id' => $this->activeAppointment->appointment_id,
            'clock_in_at' => now(),
            'lunch_break_minutes' => 30, // Default lunch break deduction [1.2.3]
        ]);

        Notification::make()
            ->title('Clocked In Successfully')
            ->body('Shift started at ' . now()->format('H:i'))
            ->success()
            ->send();

        $this->loadRecentLogs($student->student_id);
    }

    public function clockOut(): void
    {
        if (!$this->currentActiveLog) {
            return;
}

        $student = auth()->user()->student;
        $clockInTime = $this->currentActiveLog->clock_in_at;
        $clockOutTime = now();

        // Calculate the working time difference in minutes
        $totalMinutes = $clockInTime->diffInMinutes($clockOutTime);
        $lunchBreak = $this->currentActiveLog->lunch_break_minutes;

        // Deduct lunch break only if the shift is greater than the break duration [1.2.3]
        $netMinutes = max(0, $totalMinutes - $lunchBreak);

        // Convert the net minutes to decimal hours (e.g., 7 hours 30 mins = 7.5 hours)
        $hoursWorked = round($netMinutes / 60, 2);

        // Update the log with clock out metrics [1.1.2]
        $this->currentActiveLog->update([
            'clock_out_at' => $clockOutTime,
            'hours_worked' => $hoursWorked,
        ]);

        Notification::make()
            ->title('Clocked Out Successfully')
            ->body(sprintf('Shift ended. Total hours worked: %s hrs (30-min break deducted).', $hoursWorked))
            ->success()
            ->send();

        $this->currentActiveLog = null;
        $this->loadRecentLogs($student->student_id);
    }

    private function loadRecentLogs(int $studentId): void
    {
        $this->recentLogs = WorkLog::where('student_id', $studentId)
            ->whereNotNull('clock_out_at')
            ->orderBy('clock_in_at', 'desc')
            ->limit(10)
            ->get()
            ->all();
    }
}
