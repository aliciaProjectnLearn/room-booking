@extends('admin.layouts.app')

@section('title', 'Calendar')

@section('content')
<div x-data="{
    isModalOpen: false, 
    modalMode: 'add', 
    eventTitle: '', 
    eventLevel: 'Primary', 
    eventStartDate: '', 
    eventEndDate: '',
    selectedEventId: null,
    
    openModal(mode, start, end, title = '', level = 'Primary', id = null) {
        this.modalMode = mode;
        this.eventStartDate = start.split('T')[0];
        if (end) {
            let endDate = new Date(end);
            endDate.setDate(endDate.getDate() - 1); // Fullcalendar end dates are exclusive, minus 1 day for UI
            this.eventEndDate = endDate.toISOString().split('T')[0];
        } else {
            this.eventEndDate = this.eventStartDate;
        }
        this.eventTitle = title;
        this.eventLevel = level;
        this.selectedEventId = id;
        this.isModalOpen = true;
    },
    
    closeModal() {
        this.isModalOpen = false;
        this.eventTitle = '';
        this.eventStartDate = '';
        this.eventEndDate = '';
        this.eventLevel = 'Primary';
        this.selectedEventId = null;
    },
    
    saveEvent() {
        if (!this.eventTitle.trim()) {
            alert('Judul event tidak boleh kosong!');
            return;
        }
        let endDate = new Date(this.eventEndDate);
        endDate.setDate(endDate.getDate() + 1); // Add 1 day for Fullcalendar exclusive end date
        
        const detail = {
            id: this.selectedEventId || Date.now().toString(),
            title: this.eventTitle,
            start: this.eventStartDate,
            end: endDate.toISOString().split('T')[0],
            level: this.eventLevel,
            mode: this.modalMode
        };
        window.dispatchEvent(new CustomEvent('save-calendar-event', { detail }));
        this.closeModal();
    },

    deleteEvent() {
        if(this.selectedEventId) {
            window.dispatchEvent(new CustomEvent('delete-calendar-event', { detail: { id: this.selectedEventId } }));
        }
        this.closeModal();
    }
}" 
@open-calendar-modal.window="openModal($event.detail.mode, $event.detail.start, $event.detail.end, $event.detail.title, $event.detail.level, $event.detail.id)">

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white">
            Calendar
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium hover:text-brand-500" href="{{ route('admin.dashboard') }}">Dashboard /</a>
                </li>
                <li class="font-medium text-brand-500">Calendar</li>
            </ol>
        </nav>
    </div>

    <!-- Calendar Card -->
    <div class="w-full max-w-full rounded-2xl border border-gray-100 bg-white/70 backdrop-blur-lg shadow-xl shadow-gray-200/50 dark:border-gray-800 dark:bg-gray-900/80">
        <div class="p-5 md:p-8 overflow-x-auto custom-scrollbar">
            <div id="calendar" class="custom-calendar font-sans min-w-[800px]"></div>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[999999] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/50 p-4 backdrop-blur-sm transition-opacity">
        <div @click.outside="closeModal()" class="relative w-full max-w-[700px] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11 shadow-theme-xl transform transition-all">
            <h5 class="mb-2 font-semibold text-gray-800 text-theme-xl dark:text-white/90 lg:text-2xl" x-text="modalMode === 'add' ? 'Add Event' : 'Edit Event'"></h5>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Jadwalkan atau edit event pada kalender.
            </p>

            <div class="mt-8">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Judul Event
                    </label>
                    <input x-model="eventTitle" type="text" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800" placeholder="Contoh: Rapat Koordinasi" />
                </div>

                <div class="mt-6">
                    <label class="block mb-4 text-sm font-medium text-gray-700 dark:text-gray-400">
                        Warna Event
                    </label>
                    <div class="flex flex-wrap items-center gap-4 sm:gap-5">
                        <template x-for="color in ['Primary', 'Success', 'Warning', 'Danger']">
                            <label class="flex items-center text-sm text-gray-700 dark:text-gray-400 cursor-pointer">
                                <input type="radio" :value="color" x-model="eventLevel" class="sr-only" />
                                <span class="flex items-center justify-center w-5 h-5 mr-2 border border-gray-300 rounded-full box dark:border-gray-700" :class="{'ring-2 ring-brand-500 ring-offset-1': eventLevel === color}">
                                    <span class="w-full h-full rounded-full" 
                                          :class="{
                                              'bg-brand-500': color === 'Primary',
                                              'bg-success-500': color === 'Success',
                                              'bg-warning-500': color === 'Warning',
                                              'bg-error-500': color === 'Danger'
                                          }"></span>
                                </span>
                                <span x-text="color"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Mulai</label>
                        <input x-model="eventStartDate" type="date" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Selesai</label>
                        <input x-model="eventEndDate" type="date" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-center gap-3 mt-8 sm:justify-end">
                <button @click="closeModal()" class="flex w-full sm:w-auto justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Batal
                </button>
                <button x-show="modalMode === 'edit'" @click="deleteEvent()" class="flex w-full sm:w-auto justify-center rounded-lg border border-error-500 bg-error-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-error-600">
                    Hapus
                </button>
                <button @click="saveEvent()" class="flex w-full sm:w-auto justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    <span x-text="modalMode === 'edit' ? 'Simpan Perubahan' : 'Tambah Event'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Calendar Script -->
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            if (window.FullCalendar) {
                var calendarEl = document.getElementById('calendar');
                
                var renderEventContent = function(eventInfo) {
                    let level = eventInfo.event.extendedProps.calendar || 'Primary';
                    let colorClass = `fc-bg-${level.toLowerCase()}`;
                    if(colorClass === 'fc-bg-danger') colorClass = 'fc-bg-error'; // handle mapping

                    let dotColorClass = 'bg-brand-500';
                    if(level === 'Success') dotColorClass = 'bg-success-500';
                    else if(level === 'Warning') dotColorClass = 'bg-warning-500';
                    else if(level === 'Danger') dotColorClass = 'bg-error-500';

                    return {
                        html: `
                        <div class="flex items-center fc-event-main ${colorClass} px-2.5 py-1.5 rounded-lg w-full transition-all duration-200 hover:shadow-md hover:scale-[1.02] border border-transparent hover:border-gray-200/50 cursor-pointer">
                            <div class="w-2 h-2 rounded-full mr-2 flex-shrink-0 ${dotColorClass}"></div>
                            <div class="fc-event-time hidden">${eventInfo.timeText}</div>
                            <div class="fc-event-title font-semibold text-xs tracking-wide truncate">${eventInfo.event.title}</div>
                        </div>
                        `
                    };
                };

                var calendar = new window.FullCalendar.Calendar(calendarEl, {
                    plugins: [ 
                        window.FullCalendar.dayGridPlugin, 
                        window.FullCalendar.timeGridPlugin, 
                        window.FullCalendar.listPlugin, 
                        window.FullCalendar.interactionPlugin 
                    ],
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    editable: true,
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: true,
                    eventContent: renderEventContent,
                    events: [
                        {
                            id: '1',
                            title: 'Meeting dengan Klien',
                            start: new Date().toISOString().split('T')[0],
                            extendedProps: { calendar: 'Primary' }
                        },
                        {
                            id: '2',
                            title: 'Review Bulanan',
                            start: new Date(new Date().setDate(new Date().getDate() + 2)).toISOString().split('T')[0],
                            extendedProps: { calendar: 'Warning' }
                        }
                    ],
                    select: function(info) {
                        window.dispatchEvent(new CustomEvent('open-calendar-modal', {
                            detail: {
                                mode: 'add',
                                start: info.startStr,
                                end: info.endStr
                            }
                        }));
                        calendar.unselect();
                    },
                    eventClick: function(info) {
                        window.dispatchEvent(new CustomEvent('open-calendar-modal', {
                            detail: {
                                mode: 'edit',
                                id: info.event.id,
                                title: info.event.title,
                                start: info.event.startStr,
                                end: info.event.endStr || info.event.startStr,
                                level: info.event.extendedProps.calendar || 'Primary'
                            }
                        }));
                    }
                });
                calendar.render();

                // Alpine.js event listeners to update FullCalendar
                window.addEventListener('save-calendar-event', (e) => {
                    let detail = e.detail;
                    if (detail.mode === 'add') {
                        calendar.addEvent({
                            id: detail.id,
                            title: detail.title,
                            start: detail.start,
                            end: detail.end,
                            allDay: true,
                            extendedProps: { calendar: detail.level }
                        });
                    } else {
                        let event = calendar.getEventById(detail.id);
                        if(event) {
                            event.setProp('title', detail.title);
                            event.setDates(detail.start, detail.end, { allDay: true });
                            event.setExtendedProp('calendar', detail.level);
                        }
                    }
                });

                window.addEventListener('delete-calendar-event', (e) => {
                    let event = calendar.getEventById(e.detail.id);
                    if(event) {
                        event.remove();
                    }
                });

            }
        }, 100);
    });
</script>

<style>
/* Modern Toolbar */
.fc .fc-toolbar-title { font-size: 1.5rem !important; font-weight: 700 !important; color: #1e293b; letter-spacing: -0.025em; }
.dark .fc .fc-toolbar-title { color: #f8fafc; }
.fc .fc-button-primary { background-color: #f1f5f9 !important; border-color: transparent !important; color: #475569 !important; border-radius: 0.75rem; font-weight: 600; text-transform: capitalize; padding: 0.5rem 1rem; transition: all 0.2s ease; box-shadow: none !important; }
.fc .fc-button-primary:hover { background-color: #e2e8f0 !important; color: #0f172a !important; transform: translateY(-1px); }
.fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background-color: #3b82f6 !important; color: white !important; border-color: transparent !important; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3) !important; }

/* Grid and Borders */
.fc-theme-standard th { border: none !important; padding: 1rem 0; text-transform: uppercase; font-size: 0.75rem; font-weight: 700; color: #64748b; }
.fc-theme-standard td { border-color: #e2e8f0 !important; }
.fc-theme-standard .fc-scrollgrid { border-color: #e2e8f0 !important; border: 1px solid #e2e8f0 !important; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 0 0 1px #e2e8f0; }
.dark .fc-theme-standard td { border-color: #475569 !important; }
.dark .fc-theme-standard .fc-scrollgrid { border-color: #475569 !important; border: 1px solid #475569 !important; box-shadow: 0 0 0 1px #475569; }

/* View Harness Fix for cut-off */
.fc-view-harness { padding: 2px; }

/* Days */
.fc .fc-daygrid-day-number { padding: 0.5rem !important; font-weight: 600; color: #475569; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; margin: 4px; transition: all 0.2s; }
.fc .fc-daygrid-day-number:hover { background-color: #f1f5f9; color: #0f172a; }
.fc .fc-day-today { background-color: #f8fafc !important; }
.fc .fc-day-today .fc-daygrid-day-number { background-color: #3b82f6 !important; color: white !important; box-shadow: 0 4px 10px -2px rgba(59, 130, 246, 0.5); }
.dark .fc .fc-daygrid-day-number { color: #cbd5e1; }
.dark .fc .fc-day-today { background-color: #1e293b !important; }

/* Events */
.fc-event { border: none !important; background: transparent !important; }
.fc-h-event { background: transparent !important; border: none !important; }
.fc-bg-primary { background-color: #eff6ff !important; color: #1d4ed8 !important; }
.fc-bg-success { background-color: #f0fdf4 !important; color: #15803d !important; }
.fc-bg-warning { background-color: #fefce8 !important; color: #a16207 !important; }
.fc-bg-error { background-color: #fef2f2 !important; color: #b91c1c !important; }
.dark .fc-bg-primary { background-color: rgba(59, 130, 246, 0.2) !important; color: #93c5fd !important; }
.dark .fc-bg-success { background-color: rgba(34, 197, 94, 0.2) !important; color: #86efac !important; }
.dark .fc-bg-warning { background-color: rgba(234, 179, 8, 0.2) !important; color: #fde047 !important; }
.dark .fc-bg-error { background-color: rgba(239, 68, 68, 0.2) !important; color: #fca5a5 !important; }
</style>
@endsection
