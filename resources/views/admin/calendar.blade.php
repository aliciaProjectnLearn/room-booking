@extends('admin.layouts.app')

@section('title', 'Jadwal Ruangan')

@section('content')
<div x-data="{
    isModalOpen: false, 
    modalMode: 'add', 
    eventTitle: '', 
    eventRoomId: '',
    eventParticipants: '',
    eventStartDate: '', 
    eventEndDate: '',
    selectedEventId: null,
    rooms: {{ Js::from($rooms) }},
    colors: ['Primary', 'Success', 'Warning', 'Danger'],
    
    get selectedRoom() {
        return this.rooms.find(r => r.id == this.eventRoomId) || null;
    },

    get eventLevel() {
        if (!this.selectedRoom) return 'Primary';
        // Assign a color based on room id to make it consistent
        const index = this.rooms.findIndex(r => r.id == this.eventRoomId);
        return this.colors[index % this.colors.length];
    },
    
    openModal(mode, start, end, title = '', roomId = '', participants = '', id = null) {
        this.modalMode = mode;
        // Format for datetime-local: YYYY-MM-DDTHH:mm
        this.eventStartDate = start ? start.substring(0, 16) : '';
        this.eventEndDate = end ? end.substring(0, 16) : this.eventStartDate;
        
        this.eventTitle = title;
        this.eventRoomId = roomId;
        this.eventParticipants = participants;
        this.selectedEventId = id;
        this.isModalOpen = true;
    },
    
    closeModal() {
        this.isModalOpen = false;
        this.eventTitle = '';
        this.eventRoomId = '';
        this.eventParticipants = '';
        this.eventStartDate = '';
        this.eventEndDate = '';
        this.selectedEventId = null;
    },
    
    saveEvent() {
        if (!this.eventTitle.trim()) {
            Swal.fire({icon: 'error', title: 'Oops...', text: 'Judul kegiatan tidak boleh kosong!'});
            return;
        }
        if (!this.eventRoomId) {
            Swal.fire({icon: 'error', title: 'Oops...', text: 'Silakan pilih ruangan!'});
            return;
        }
        if (this.selectedRoom && this.eventParticipants > this.selectedRoom.capacity) {
            Swal.fire({icon: 'error', title: 'Oops...', text: 'Jumlah peserta melebihi kapasitas ruangan (' + this.selectedRoom.capacity + ')!'});
            return;
        }
        if (!this.eventStartDate || !this.eventEndDate) {
            Swal.fire({icon: 'error', title: 'Oops...', text: 'Waktu mulai dan selesai harus diisi!'});
            return;
        }
        
        let formData = {
            title: this.eventTitle,
            room_id: this.eventRoomId,
            participants: this.eventParticipants,
            start: this.eventStartDate,
            end: this.eventEndDate,
        };

        let token = document.querySelector('meta[name=csrf-token]').getAttribute('content');

        fetch('{{ route('admin.calendar.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error || data.errors) {
                let msg = data.error || Object.values(data.errors).join('\n');
                Swal.fire({icon: 'error', title: 'Gagal', text: msg});
            } else {
                Swal.fire({icon: 'success', title: 'Berhasil', text: data.success || 'Booking berhasil diajukan!'});
                
                // Trigger Calendar event
                const detail = {
                    id: data.booking.id,
                    title: formData.title,
                    start: formData.start,
                    end: formData.end,
                    level: this.eventLevel,
                    roomId: formData.room_id,
                    participants: formData.participants,
                    mode: this.modalMode
                };
                window.dispatchEvent(new CustomEvent('save-calendar-event', { detail }));
                this.closeModal();
            }
        })
        .catch(error => {
            Swal.fire({icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.'});
        });
    },

    deleteEvent() {
        if(this.selectedEventId) {
            window.dispatchEvent(new CustomEvent('delete-calendar-event', { detail: { id: this.selectedEventId } }));
        }
        this.closeModal();
    }
}" 
@open-calendar-modal.window="openModal($event.detail.mode, $event.detail.start, $event.detail.end, $event.detail.title, $event.detail.roomId, $event.detail.participants, $event.detail.id)">

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white">
            Jadwal Ruangan
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium hover:text-brand-500" href="{{ route('admin.dashboard') }}">Dashboard /</a>
                </li>
                <li class="font-medium text-brand-500">Jadwal Ruangan</li>
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
            <h5 class="mb-2 font-semibold text-gray-800 text-theme-xl dark:text-white/90 lg:text-2xl" x-text="modalMode === 'add' ? 'Buat Booking Baru' : 'Detail Booking'"></h5>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pilih ruangan dan jadwalkan kegiatan.
            </p>

            <div class="mt-8 space-y-6">
                <!-- Judul Event -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Judul Kegiatan
                    </label>
                    <input x-model="eventTitle" :disabled="modalMode === 'edit'" type="text" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800" placeholder="Contoh: Rapat Koordinasi" />
                </div>

                <!-- Ruangan & Peserta -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Ruangan
                        </label>
                        <select x-model="eventRoomId" :disabled="modalMode === 'edit'" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">-- Pilih Ruangan --</option>
                            <template x-for="room in rooms" :key="room.id">
                                <option :value="room.id" x-text="room.name + ' (Kapasitas: ' + room.capacity + ')'"></option>
                            </template>
                        </select>
                        <p class="mt-1 text-xs text-gray-500" x-show="selectedRoom">
                            Warna event: <span class="font-medium" x-text="eventLevel" :class="{
                                'text-blue-500': eventLevel === 'Primary',
                                'text-green-500': eventLevel === 'Success',
                                'text-yellow-500': eventLevel === 'Warning',
                                'text-red-500': eventLevel === 'Danger'
                            }"></span>
                        </p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Jumlah Peserta
                        </label>
                        <input x-model="eventParticipants" :disabled="modalMode === 'edit'" type="number" min="1" :max="selectedRoom ? selectedRoom.capacity : ''" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" placeholder="0" />
                        <p class="mt-1 text-xs text-error-500" x-show="selectedRoom && eventParticipants > selectedRoom.capacity">
                            Melebihi kapasitas maksimal (<span x-text="selectedRoom.capacity"></span>)
                        </p>
                    </div>
                </div>

                <!-- Mulai & Selesai -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Waktu Mulai</label>
                        <input x-model="eventStartDate" :disabled="modalMode === 'edit'" type="datetime-local" :min="new Date().toISOString().slice(0, 16)" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Waktu Selesai</label>
                        <input x-model="eventEndDate" :disabled="modalMode === 'edit'" type="datetime-local" :min="eventStartDate || new Date().toISOString().slice(0, 16)" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-center gap-3 mt-8 sm:justify-end">
                <button @click="closeModal()" class="flex w-full sm:w-auto justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <span x-text="modalMode === 'edit' ? 'Tutup' : 'Batal'"></span>
                </button>
                <button x-show="modalMode === 'add'" @click="saveEvent()" class="flex w-full sm:w-auto justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    <span>Buat Booking</span>
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
                        <div class="flex flex-col fc-event-main ${colorClass} px-2.5 py-1.5 rounded-lg w-full transition-all duration-200 hover:shadow-md hover:scale-[1.02] border border-transparent hover:border-gray-200/50 cursor-pointer text-xs">
                            <div class="flex items-center mb-1">
                                <div class="w-2 h-2 rounded-full mr-2 flex-shrink-0 ${dotColorClass}"></div>
                                <div class="font-semibold tracking-wide truncate">${eventInfo.event.title}</div>
                            </div>
                            <div class="opacity-80">${eventInfo.timeText}</div>
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
                    editable: false,
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: true,
                    eventContent: renderEventContent,
                    events: '{{ route("admin.calendar.events") }}',

                    select: function(info) {
                        // When selecting on dayGridMonth, fullcalendar might just provide dates.
                        // We can provide a time to make it datetime-local friendly.
                        let start = info.startStr.includes('T') ? info.startStr : info.startStr + 'T08:00';
                        let end = info.endStr.includes('T') ? info.endStr : info.startStr + 'T09:00';

                        window.dispatchEvent(new CustomEvent('open-calendar-modal', {
                            detail: {
                                mode: 'add',
                                start: start,
                                end: end,
                                roomId: '',
                                participants: ''
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
                                level: info.event.extendedProps.calendar || 'Primary',
                                roomId: info.event.extendedProps.roomId || '',
                                participants: info.event.extendedProps.participants || ''
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
                            allDay: false, // Since it's specific time
                            extendedProps: { 
                                calendar: detail.level,
                                roomId: detail.roomId,
                                participants: detail.participants
                            }
                        });
                    } else {
                        let event = calendar.getEventById(detail.id);
                        if(event) {
                            event.setProp('title', detail.title);
                            event.setDates(detail.start, detail.end, { allDay: false });
                            event.setExtendedProp('calendar', detail.level);
                            event.setExtendedProp('roomId', detail.roomId);
                            event.setExtendedProp('participants', detail.participants);
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
