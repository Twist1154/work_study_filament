<x-filament-panels::page>

    @push('styles')
        @vite('resources/css/clock-in-out.css')
    @endpush

    <div class="dwt-grid">

        {{-- ── Left: Action Card ── --}}
        <div class="dwt-card">
            <h2 class="dwt-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--fi-primary-500,#8b5cf6)"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l3.5 2"/></svg>
                Work Tracker
            </h2>

            @if(!$activeAppointment)
                <div class="dwt-alert">
                    <span class="dwt-alert-icon">⚠</span>
                    <span>No active work-study appointment found. Contact your supervisor to get assigned.</span>
                </div>
            @else
                {{-- Clock face --}}
                <div class="dwt-clock-face">
                    <span class="dwt-clock-label">Current Time</span>
                    <div class="dwt-clock-time" id="dwt-live-clock">—</div>
                    <div class="dwt-clock-date" id="dwt-live-date"></div>
                </div>

                {{-- Status --}}
                <div class="dwt-status-row">
                    <span class="dwt-status-label">Shift Status</span>
                    @if($currentActiveLog)
                        <span class="dwt-badge dwt-badge-online">
                            <span class="dwt-pulse"></span> Clocked In
                        </span>
                    @else
                        <span class="dwt-badge dwt-badge-offline">● Offline</span>
                    @endif
                </div>

                @if($currentActiveLog)
                    <div class="dwt-clock-in-info">
                        <strong>Started:</strong> {{ $currentActiveLog->clock_in_at->format('d M Y @ H:i') }}
                    </div>
                    <button wire:click="clockOut" class="dwt-btn dwt-btn-clockout">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Clock Out of Shift
                    </button>
                @else
                    <button wire:click="clockIn" class="dwt-btn dwt-btn-clockin">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Clock In to Shift
                    </button>
                @endif
            @endif
        </div>

        {{-- ── Right: Shift Logs ── --}}
        <div class="dwt-card">
            <h2 class="dwt-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--fi-primary-500,#8b5cf6)"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Recent Shifts
                <span style="margin-left:auto;font-size:.7rem;font-weight:500;color:var(--fi-color-gray-400,#9ca3af);">Last 10</span>
            </h2>

            @if(empty($recentLogs))
                <div class="dwt-empty">
                    <span class="dwt-empty-icon">📋</span>
                    No shifts logged yet this cycle.
                </div>
            @else
                <div class="dwt-table-wrap">
                    <table class="dwt-table">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th style="text-align:right">Hours</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentLogs as $log)
                            <tr>
                                <td class="dwt-td-date">{{ $log->clock_in_at->format('d M Y') }}</td>
                                <td><span class="dwt-time-chip">{{ $log->clock_in_at->format('H:i') }}</span></td>
                                <td>
                                    @if($log->clock_out_at)
                                        <span class="dwt-time-chip">{{ $log->clock_out_at->format('H:i') }}</span>
                                    @else
                                        <span class="dwt-time-chip na">Active</span>
                                    @endif
                                </td>
                                <td class="dwt-td-hours">
                                    <span class="dwt-hours-pill">{{ $log->hours_worked }} h</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
        @vite('resources/js/clock-in-out.js')
    @endpush

</x-filament-panels::page>
