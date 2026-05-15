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
    <div class="w-full max-w-full rounded-xl border border-gray-200 bg-white shadow-theme-md dark:border-gray-800 dark:bg-gray-900">
        <div class="p-4 md:p-6 xl:p-9">
            <div id="calendar" class="custom-calendar"></div>
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

                    return {
                        html: `
                        <div class="event-fc-color flex fc-event-main ${colorClass} p-1 rounded-sm w-full">
                            <div class="fc-daygrid-event-dot hidden"></div>
                            <div class="fc-event-time hidden">${eventInfo.timeText}</div>
                            <div class="fc-event-title font-medium truncate">${eventInfo.event.title}</div>
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
.fc .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 600 !important; color: var(--color-gray-800); }
.dark .fc .fc-toolbar-title { color: var(--color-white); }
.fc-theme-standard .fc-scrollgrid { border-color: var(--color-gray-200); }
.dark .fc-theme-standard .fc-scrollgrid { border-color: var(--color-gray-800); }
.fc .fc-button-primary { background-color: var(--color-brand-500) !important; border-color: var(--color-brand-500) !important; }
.fc .fc-button-primary:hover { background-color: var(--color-brand-600) !important; }
.fc-bg-primary { background-color: var(--color-brand-50) !important; color: var(--color-brand-600) !important; border-left: 3px solid var(--color-brand-500); }
.fc-bg-success { background-color: var(--color-success-50) !important; color: var(--color-success-600) !important; border-left: 3px solid var(--color-success-500); }
.fc-bg-warning { background-color: var(--color-warning-50) !important; color: var(--color-warning-600) !important; border-left: 3px solid var(--color-warning-500); }
.fc-bg-error { background-color: var(--color-error-50) !important; color: var(--color-error-600) !important; border-left: 3px solid var(--color-error-500); }
</style>
@endsection
