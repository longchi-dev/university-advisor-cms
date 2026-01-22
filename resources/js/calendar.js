import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');

    let calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        editable: true,
        events: '/api/events',
        dateClick: function (info) {
            $("#showReward").modal('show');
            console.log(info);
            // let title = prompt("Enter Event Title:");
            // if (title) {
            //     fetch('/api/events', {
            //         method: 'POST',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            //         },
            //         body: JSON.stringify({
            //             title: title,
            //             start: info.dateStr,
            //         }),
            //     })
            //         .then(response => response.json())
            //         .then(data => {
            //             if (data.success) {
            //                 calendar.addEvent(data.event); // Thêm sự kiện vào lịch
            //             }
            //         });
            // }
        }
    });

    calendar.render();
});
