<!DOCTYPE html>
<html>

<head>
    <title>FULL CALENDAR</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script type='text/javascript' src='js/es.js'></script>

</head>

<body>

    <div class="container">
        <br />
        <h1 class="text-center text-primary"><u>FULL CALENDAR</u></h1>
        <br />

        <div id="calendar"></div>

    </div>

    <div class="container">
        <br />
        <h1 class="text-center text-primary"><u>FULL CALENDAR1</u></h1>
        <br />

        <div id="calendar1"></div>

    </div>

    <script>
          $(document).ready(function() {

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var calendar = $('#calendar').fullCalendar({
    editable: true,
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
    },
    events: '/full-calender',
    selectable: true,
    selectHelper: true,
    select: function(start, end, allDay) {
        var title = prompt('Titulo del evento:');

        if (title) {

            var start = $.fullCalendar.formatDate(start, 'Y-MM-DD HH:mm:ss');

            var end = $.fullCalendar.formatDate(end, 'Y-MM-DD HH:mm:ss');

            $.ajax({
                /*
                                    type:"ajax",
                   
                               url: '/full-calender/action',
                               type: 'POST',
                               data: {
                                   title: title,
                                   type: 'add'
                               },
                               success: function(data){
                                    alert('bien');
                               },
                               error: function(data){
                                   alert('mal');
                               },
                     */
                url: "/full-calender/action",
                type: "POST",
                data: {
                    title: title,
                    start: start,
                    end: end,
                    type: 'add'
                },
                success: function(data) {
                    calendar.fullCalendar('refetchEvents');
                    alert("Event Created Successfully");
                },

                error: function(data) {
                    alert('mal');
                }
            })
        }
    },
    editable: true,
    eventResize: function(event, delta) {
        var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
        var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
        var title = event.title;
        var id = event.id;
        $.ajax({
            url: "/full-calender/action",
            type: "POST",
            data: {
                title: title,
                start: start,
                end: end,
                id: id,
                type: 'update'
            },
            success: function(response) {
                calendar.fullCalendar('refetchEvents');
                alert("Event Updated Successfully");
            }
        })
    },
    eventDrop: function(event, delta) {
        var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
        var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
        var title = event.title;
        var id = event.id;
        $.ajax({
            url: "/full-calender/action",
            type: "POST",
            data: {
                title: title,
                start: start,
                end: end,
                id: id,
                type: 'update'
            },
            success: function(response) {
                calendar.fullCalendar('refetchEvents');
                alert("Event Updated Successfully");
            }
        })
    },

    eventClick: function(event) {
        if (confirm("Are you sure you want to remove it?")) {
            var id = event.id;
            $.ajax({
                url: "/full-calender/action",
                type: "POST",
                data: {
                    id: id,
                    type: "delete"
                },
                success: function(response) {
                    calendar.fullCalendar('refetchEvents');
                    alert("Event Deleted Successfully");
                }
            })
        }
    }
});

});
  $(document).ready(function() {
    $('#calendar1').fullCalendar({
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: '2023-01-01',
        buttonIcons: true,
        weekNumbers: false,
        editable: true,
        eventLimit: true,
        events: [
            {
                title: 'All Day Event',
                description: 'Lorem ipsum 1...',
                start: '2019-07-01',
                color: '#3A87AD',
                textColor: '#ffffff',
            }
        ],
        dayClick: function (date, jsEvent, view) {
            alert('Has hecho click en: '+ date.format());
        }, 
        eventClick: function (calEvent, jsEvent, view) {
            $('#event-title').text(calEvent.title);
            $('#event-description').html(calEvent.description);
            $('#modal-event').modal();
        },  
    });
});
    </script>

</body>

</html>