@include('layouts._partials.messages')
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Citas') }}
    </h2>

    {{-- FullCalendar --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.min.js"></script>

    <style>
      body {
        background-color: #ffffff !important;
        color: #333;
      }

      /* Estilo general del calendario */
      .fc {
        background-color: #fff;
        border-radius: 8px;
      }

      .fc-scrollgrid,
      .fc-scrollgrid-section,
      .fc-scrollgrid-sync-table {
        border-color: #b0b0b0 !important;
      }

      .fc-daygrid-day-frame {
        border-color: #b0b0b0 !important;
      }

      .fc-daygrid-day-number {
        color: #333 !important;
        font-weight: 500;
      }

      .fc-day-today {
        background-color: rgba(17, 140, 255, 0.23) !important;
      }

      /* Eventos por sala */
      .fc-event.Sala-1 {
        background-color: rgba(91, 101, 255, 0.59) !important;
        border-color: rgba(35, 31, 255, 0.7) !important;
        color: white !important;
      }

      .fc-event.Sala-2 {
        background-color: #18ffcde3 !important;
        border-color: #33ffc2ff !important;
        color: white !important;
      }

      .fc-event:hover {
        cursor: pointer !important;
      }

      .fc-button {
        background-color: #fff !important;
        color: #333 !important;
        border: 1px solid #b0b0b0 !important;
        border-radius: 6px !important;
        padding: 5px 10px !important;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      }

      .fc-button:hover {
        background-color: #e0f0ff !important;
        color: #000 !important;
        border-color: #7bb0ffff !important;
      }

      .fc-button-primary:not(:disabled).fc-button-active,
      .fc-button-primary:not(:disabled):active {
        background-color: #cde8ff !important;
        color: #000 !important;
        border-color: #7bb0ffff !important;
      }

      /* Iconos de flechas */
      .fc-icon {
        color: #333 !important;
        font-size: 1rem;
      }

      /* Título y encabezados de días */
      .fc-toolbar-title {
        color: #222222ff !important;
        font-weight: 600 !important;
      }

      .fc-col-header-cell-cushion {
        color: #444 !important;
        font-weight: 600;
      }
    </style>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Asegúrate de que $events esté disponible y sea un JSON válido
        const events = @json($events); 
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'es',
          buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Lista'
          },
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          events: events,
          allDaySlot: false,
          slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
          },
          eventClassNames: function(arg) {
            if (arg.event.extendedProps.room === 'Consultorio 1') return ['Sala-1'];
            if (arg.event.extendedProps.room === 'Consultorio 2') return ['Sala-2'];
            return [];
          },
          eventContent: function(arg) {
            if (arg.view.type !== 'dayGridMonth') {
              const start = arg.event.start;
              const end = arg.event.end;
              const doctor = arg.event.extendedProps.doctor || '';
              const formatHour = (date) => date ? date.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
              }) : '';
              return {
                html: `
                <div style="font-size: 0.7rem; font-family: sans-serif; color: #000000ff;">
                  <b>${arg.event.title}</b> 
                  ${formatHour(start)} - ${formatHour(end)}<br>
                  Doctor: ${doctor}
                </div>`
              };
            }
          },
          eventDidMount: function(info) {
            if (info.view.type === 'dayGridMonth') {
              const start = info.event.start;
              const end = info.event.end;
              const room = info.event.extendedProps.room || '';
              const doctor = info.event.extendedProps.doctor || '';
              const formatHour = (date) => date ? date.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
              }) : '';
              info.el.innerHTML = `
                <div style="font-size: 0.75rem; font-family: sans-serif; color: #000000ff;">
                  <b>${info.event.title}</b><br>
                  ${room}<br>
                  Horario: ${formatHour(start)} - ${formatHour(end)}<br>
                  Doctor: ${doctor}<br>
                  <b>Creado por: ${info.event.extendedProps.creator_name}</b>
                </div>`;
            }
          },
          eventClick: function(info) {
            const eventId = info.event.id;
            window.location.href = `/events/${eventId}`;
          }
        });

        calendar.render();
      });
    </script>

  </x-slot>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="flex justify-end p-5">
          <a href="{{ route('events.create') }}" class="botton1">{{ __('Crear Cita') }}</a>
        </div>
        <div id="calendar" class="p-4 text-gray-800 font-sans"></div>
      </div>
    </div>
  </div>
</x-app-layout>