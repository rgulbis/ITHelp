<div>
    @php use Illuminate\Support\Str; @endphp

    <div class="p-6 space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between gap-4 print:hidden">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Ticket Calendar</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Visual overview of all tickets by creation date.</p>
            </div>
            <button onclick="printCalendarOnly()"
                    class="inline-flex items-center rounded-lg bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 px-4 py-2 text-white shadow-sm transition-colors">
                Print Calendar
            </button>
        </div>

        {{-- Print Title --}}
        <div class="print:block hidden mb-6">
            <h1 class="text-2xl font-bold text-black text-center mb-2">Ticket Calendar - {{ now()->timezone('Europe/Riga')->format('F Y') }}</h1>
            <p class="text-center text-gray-600 text-sm">Generated on {{ now()->timezone('Europe/Riga')->format('d.m.Y H:i') }}</p>
        </div>

        {{-- Calendar --}}
        <div id="print-container" class="rounded-xl border-2 border-gray-900 dark:border-gray-300 bg-white dark:bg-gray-900 p-4 shadow-none print:shadow-none print:border-2 print:border-black print:p-2 max-w-full print:max-w-[95vw]">
            <div id="calendar"
                 class="dark:text-gray-100 
                        [&_.fc-toolbar]:bg-white dark:[&_.fc-toolbar]:bg-gray-900 
                        [&_.fc-toolbar-title]:text-gray-900 dark:[&_.fc-toolbar-title]:text-white 
                        [&_.fc-button]:bg-white dark:[&_.fc-button]:bg-gray-800 
                        [&_.fc-button]:text-gray-900 dark:[&_.fc-button]:text-gray-200 
                        [&_.fc-button]:border-gray-300 dark:[&_.fc-button]:border-gray-600 
                        [&_.fc-button]:hover:bg-gray-100 dark:[&_.fc-button]:hover:bg-gray-700 
                        print:!bg-white print:!text-black">
            </div>
        </div>

        {{-- Stats (Screen Only) --}}
        <div class="grid gap-4 lg:grid-cols-2 print:hidden mt-6">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm dark:shadow-gray-800/50">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Monthly Ticket Count</h2>
                <div class="space-y-3">
                    @forelse($ticketCounts as $month => $count)
                        <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-800/50 p-3 border border-gray-200/50 dark:border-gray-600/50">
                            <span class="text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</span>
                            <span class="rounded-full bg-blue-100 dark:bg-blue-900/30 px-3 py-1 text-sm font-semibold text-blue-700 dark:text-blue-300 border border-blue-200/50 dark:border-blue-800/50">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">No ticket data available yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm dark:shadow-gray-800/50">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Open Tickets</h2>
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @foreach($tickets->whereIn('status', ['Open', 'In Progress'])->take(6) as $ticket)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-all duration-200">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $ticket->title }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ Str::limit($ticket->description ?? '', 60) }}</p>
                                </div>
                                <span class="text-xs font-semibold uppercase tracking-wide px-2 py-1 rounded-full
                                    {{ $ticket->status === 'Open' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/50' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border border-amber-200/50 dark:border-amber-800/50' }}">
                                    {{ $ticket->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

<style>
@media print {
    @page {
        size: landscape;
        margin: 0.1in;
    }

    body * { visibility: hidden; }
    #print-container, #print-container * { visibility: visible; }

    #print-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100vw !important;
        height: 100vh !important;
        margin: 0 !important;
        padding: 5px !important;
        box-sizing: border-box;
        background: white !important;
        border-color: black !important;
    }

    #calendar {
        width: 100% !important;
        height: 100% !important;
        max-width: 100vw !important;
        overflow: visible !important;
        color: black !important;
        background: white !important;
    }

    .fc {
        font-size: 7.5px !important;
        width: 100% !important;
        max-width: 100% !important;
        overflow: visible !important;
        color: black !important;
        background: white !important;
    }

    .fc-scrollgrid,
    .fc-scrollgrid table {
        width: 100% !important;
        table-layout: fixed !important;
    }

    .fc-daygrid-day-frame {
        height: 45px !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    #calendar {
        max-width: 100%;
        overflow-x: visible !important;
        padding-right: 0 !important;
    }
}
</style>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        function printCalendarOnly() {
            const calendar = window.calendar;

            calendar.setOption('initialView', 'dayGridMonth');
            calendar.setOption('contentHeight', 'auto');
            calendar.setOption('aspectRatio', 1.2);
            calendar.setOption('fixedWeekCount', false);
            
            calendar.render();

            setTimeout(() => window.print(), 100);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            window.calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
                events: @json($this->getEvents()),
                eventDisplay: 'block',
                timeZone: 'Europe/Riga',
                eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                slotLabelFormat: [{ hour: '2-digit', minute: '2-digit', hour12: false, omitZeroMinute: false }],
                dayHeaderFormat: { weekday: 'short' },
                firstDay: 1,
                headerToolbarClassNames: 'print:font-bold',
                dayHeaderClassNames: 'print:font-bold',
                eventDidMount: function(info) {
                    const priority = info.event.extendedProps.priority;
                    let color = '#10b981';
                    if (priority === 'High') color = '#ef4444';
                    else if (priority === 'Medium') color = '#f59e0b';
                    
                    info.el.style.borderLeft = `4px solid ${color}`;
                    info.el.style.backgroundColor = color + '20';
                    info.el.style.borderRadius = '4px';
                    info.el.style.color = '#111827';
                    info.el.style.fontWeight = '500';
                    info.el.classList.add('print:font-bold');
                    
                    // Dark mode event text color adjustment
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        info.el.style.color = '#f9fafb';
                    }
                },
                eventClick: function(info) {
                    if (info.event.url) window.open(info.event.url, '_blank');
                },
                height: 'auto',
                aspectRatio: 1.8,
                dayMaxEvents: 3,
                fixedWeekCount: false
            });
            window.calendar.render();
        });
    </script>
    @endpush
</div>