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
<!--

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/locales-all.js"></script>
 -->
 <script type='text/javascript' src='js/es.js'></script> 

</head>

<body>
<div class="container">
        <br />
        <h1 class="text-center text-primary"><u>Calendario vacaciones</u></h1>
        <br />

        <div id="calendar"></div>

    </div>
  

    <script>
$(document).ready(function () {
            "use strict"

            var eventos=[];

$.ajax({
    headers: {
        left: 'prev,next,listMonth',
        center: 'title',
        right: 'month,listMonth'
    },
   url: "/full-calender",
   type: "GET",
   datatype: "JSON",
   async: "false",
   
}).done(function(r){
    eventos=r;
})


$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

var calendar = $('#calendar').fullCalendar({
    dayClick: function(date, jsEvent, view) {

var start=date.format();
alert('Clicked on: ' + date.format());
    var title = prompt('Titulo del evento:');

        if (title) {


            var end = start;

            $.ajax({
             
    
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
//alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

//alert('Current view: ' + view.name);

// change the day's background color just for fun
//$(this).css('background-color', 'red');

},
    editable: true,
  
    events: '/full-calender',
    
    selectable: true,
    selectHelper: true,
    select: function(start, end, allDay) {
    
    },
    editable: true,
    eventResize: function(event, delta) {
        var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
        var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
        var title = event.title;
        var id = event.id;
        alert(id);

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


        var start = moment(event.start).format('YYYY-MM-DD HH:mm:00');
        //verificar por que no coge la fecha final

var end= moment(event.end).format('YYYY-MM-DD HH:mm:00');
      end=start;
        var title = event.title;
        var id = event.id;
        alert(id);
        alert(title);
        alert(start);
        alert(end);
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

    eventClick: function(data) {
        if (confirm("Are you sure you want to remove it?")) {
            var id = data.id;
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
    </script>

</body>

</html>