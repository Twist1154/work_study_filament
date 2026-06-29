<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CPUT Work Study Timesheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .header {
            width: 100%;
            margin-bottom: 10px;
        }
        .logo {
            height: 55px;
        }
        .title {
            font-size: 13px;
            font-weight: bold;
            text-align: left;
            margin-top: 5px;
        }
        .important-box {
            border: 1px solid #999;
            background-color: #f0f0f0;
            padding: 8px;
            margin-bottom: 12px;
        }
        .important-title {
            font-weight: bold;
            margin-bottom: 4px;
        }
        .meta-table, .timesheet-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .meta-table td {
            border: 1px solid #111;
            padding: 5px;
            vertical-align: top;
        }
        .meta-label {
            font-size: 8px;
            font-weight: bold;
            color: #333;
            display: block;
            text-transform: uppercase;
        }
        .meta-value {
            font-size: 12px;
            font-weight: bold;
        }
        .timesheet-table th, .timesheet-table td {
            border: 1px solid #111;
            padding: 3px 5px;
            text-align: center;
        }
        .timesheet-table th {
            background-color: #e5e7eb;
            font-weight: bold;
            font-size: 9px;
        }
        .week-header {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .totals-table td {
            border: 1px solid #111;
            padding: 4px 10px;
            font-weight: bold;
        }
        .signatures-section {
            width: 100%;
            margin-top: 15px;
        }
        .signatures-section td {
            padding-bottom: 12px;
            vertical-align: bottom;
        }
        .office-use-box {
            border: 1px solid #111;
            background-color: #d1d5db;
            padding: 8px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<!-- Header Logo & Title -->
<table class="header">
    <tr>
        <td style="width: 50%;">
            <!-- Dynamic Local Asset Path resolved in PDF compilation -->
            <img class="logo" src="https://www.cput.ac.za/images/logo.png" alt="CPUT Logo">
        </td>
        <td style="width: 50%; text-align: right; vertical-align: bottom;">
            <div class="title">WORK STUDY PROGRAMME: TIME SHEET & CLAIM FORM</div>
        </td>
    </tr>
</table>

<!-- Important Notice Box -->
<div class="important-box">
    <div class="important-title">VERY IMPORTANT</div>
    1. Fraudulent information may lead you to be disqualified from the program or even dismissal from CPUT.<br>
    2. Completed forms to be submitted to the Co-ordinator in the department concerned.<br>
    3. Please ensure that the forms are correctly completed.
</div>

<!-- Student & Department Metadata Grid -->
<table class="meta-table">
    <tr>
        <td style="width: 40%;">
            <span class="meta-label">Staff No:</span>
            <span class="meta-value">{{ $student->staff_number ?? '' }}&nbsp;</span>
        </td>
        <td style="width: 60%;" colspan="2">
            <span class="meta-label">Department in which you are working:</span>
            <span class="meta-value">{{ $claim->appointment->department->department_name ?? 'IT CENTRE' }}</span>
        </td>
    </tr>
    <tr>
        <td>
            <span class="meta-label">Student No:</span>
            <span class="meta-value">{{ $student->student_number }}</span>
        </td>
        <td style="width: 30%;">
            <span class="meta-label">Bursary:</span>
            <span class="meta-value">{{ $student->nsfas_funded ? 'YES (NSFAS)' : 'NO' }}</span>
        </td>
        <td style="width: 30%;">
            <span class="meta-label">Which Bursary:</span>
            <span class="meta-value">&nbsp;</span>
        </td>
    </tr>
    <tr>
        <td>
            <span class="meta-label">Initials, Surname & First Names</span>
            <span class="meta-value">{{ $student->surname }}, {{ $student->first_names }}</span>
        </td>
        <td colspan="2">
            <span class="meta-label">Tax Ref No:</span>
            <span class="meta-value">{{ $student->id_passport_number }}</span>
        </td>
    </tr>
</table>

<!-- Weekly Shifts Log Table -->
<table class="timesheet-table">
    <thead>
    <tr>
        <th style="width: 15%;">Date</th>
        <th style="width: 10%;">Time In</th>
        <th style="width: 10%;">Time Out</th>
        <th style="width: 35%;">Description of Duties</th>
        <th style="width: 10%;">Hours Worked</th>
        <th style="width: 10%;">Student Init</th>
        <th style="width: 10%;">Superv Init</th>
    </tr>
    </thead>
    <tbody>
    @for ($w = 1; $w <= 5; $w++)
        <tr>
            <td colspan="7" class="week-header">WEEK {{ $w }}</td>
        </tr>
        @if (empty($weeks[$w]))
            <tr>
                <td colspan="3">&nbsp;</td>
                <td class="text-left" style="color: #999;">No shifts logged during Week {{ $w }}</td>
                <td colspan="3">&nbsp;</td>
            </tr>
        @else
            @foreach ($weeks[$w] as $log)
                <tr>
                    <td>{{ $log->clock_in_at->format('d/m/y') }}</td>
                    <td>{{ $log->clock_in_at->format('H:i') }}</td>
                    <td>{{ $log->clock_out_at ? $log->clock_out_at->format('H:i') : 'N/A' }}</td>
                    <td class="text-left">IT Centre Lab Assistant</td>
                    <td>{{ $log->hours_worked }}</td>
                    <td>A</td>
                    <td>L</td>
                </tr>
            @endforeach
        @endif
    @endfor
    </tbody>
</table>

<!-- Calculated Totals Table -->
<table class="totals-table">
    <tr>
        <td style="width: 70%; text-align: right; background-color: #f9fafb;">TOTAL HOURS FOR THE PERIOD</td>
        <td style="width: 30%; text-align: center; font-size: 13px;">{{ $claim->hours_worked }}</td>
    </tr>
    <tr>
        <td style="text-align: right; background-color: #f9fafb;">RATE OF PAY PER HOUR</td>
        <td style="text-align: center; font-size: 13px;">R {{ number_format($rate, 2) }}</td>
    </tr>
    <tr>
        <td style="text-align: right; background-color: #f9fafb; font-size: 12px; color: #1d4ed8;">TOTAL PAYMENT FOR THE PERIOD</td>
        <td style="text-align: center; font-size: 14px; color: #1d4ed8;">R {{ number_format($claim->amount_claimed, 2) }}</td>
    </tr>
</table>

<!-- Signature Fields -->
<table class="signatures-section">
    <tr>
        <td style="width: 50%;">
            STUDENT'S SIGNATURE: ____________________________________
        </td>
        <td style="width: 50%; text-align: right;">
            CONTACT NO. OF SUPERVISOR: 083 507 6920
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding-top: 15px;">
            SIGNATURE: HOD: _________________________________________
        </td>
    </tr>
</table>

<!-- Office Use Box -->
<div class="office-use-box">
    <div style="font-weight: bold; margin-bottom: 8px; text-transform: uppercase;">For Office Use Only</div>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none; padding: 0;">
                CHECKED: ________________________________________
            </td>
            <td style="width: 50%; border: none; text-align: right; padding: 0;">
                DATE: ___________________________
            </td>
        </tr>
    </table>
</div>

</body>
</html>
