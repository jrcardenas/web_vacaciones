<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}

        </h2>
    </x-slot>


    <script>
    $(document).ready(function() {




        var eventos = [];
        var idEmpleado = $('#idEmpleado').val();
        var idJefeEquipo = $('#idJefeEquipo').val();
        var nombre = $('#name').val();

        var $calendar = $("#calendar");



        /*
                    var options = { // Create an options object
          googleCalendarApiKey: 'key',
          events: {
            googleCalendarId: 'id'
          },
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'agendaDay,agendaWeek,month'
          },
          eventBackgroundColor: 'transparent',
          eventBorderColor: '#08c',
          eventTextColor: 'black',
          height: 'auto',
          defaultView: 'agendaWeek',
          allDaySlot: false,
        }
        //$calendar.fullCalendar(options);

        function recreateFC(screenWidth) { // This will destroy and recreate the FC taking into account the screen size
          if (screenWidth < 700) {
            options.header = {
              left: 'prev,next today',
              center: 'title',
              right: ''
            };
            options.defaultView = 'agendaDay';
          } else {
            options.header = {
              left: 'prev,next today',
              center: 'title',
              right: 'agendaDay,agendaWeek,month'
            };
            options.defaultView = 'agendaWeek';
          }
        }
        */

        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                //cabeceras para visualizar en el calendario


            }
        });
        $.ajax({

            url: "/full-calender",
            type: "GET",
            datatype: "JSON",
            async: "true",


        }).done(function(r) {
            eventos = r;
        })


        //creamos el calendario
        calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'agendaDay,agendaWeek,month,listYear'
            },
            //quitar la hora a la vista de los eventos
            displayEventTime: false,
            //evento al hacer click

            dayClick: function(date, jsEvent, view) {
                var tipoEvento = document.getElementsByName("evento");

                //generamos un bucle para poder seleccionar el check marcado
                for (i = 0; i < tipoEvento.length; i++) {
                    if (tipoEvento[i].checked) {
                        //var memory=memo[i].checked;
                        tipoEvento = tipoEvento[i].value;
                    }
                }
                var start = date.format();
                var end = date.format();
                var color;
                //alert('Clicked on: ' + date.format());

                if (tipoEvento == "vacas") {
                    color = "#999999"
                } else if (tipoEvento == "cumple") {
                    color = "#bae1ff"
                } else if (tipoEvento == "reuni") {
                    color = "#ffffba"
                } else {
                    color = "#ffdfba"

                }
                if (tipoEvento == 'vacas') {

                    $.ajax({

                        url: "/full-calender/action",
                        type: "POST",
                        data: {
                            title: 'Vacas '+ nombre,
                            start: start,
                            end: end,
                            idEmpleado: idEmpleado,
                            idJefeEquipo: idJefeEquipo,
                            tipoEvento: tipoEvento,
                            color: color,
                            type: 'add',
                        },
                        success: function(data) {
                            calendar.fullCalendar('refetchEvents');
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Vacaciones solicitadas',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        },

                        error: function(data) {
                            alert('mal');
                        }
                    })
                } else {

                
                Swal.fire({
                    title: 'Titulo del evento',
                    position: 'top-end',

                    html: `<input type="text" id="login" class="swal2-input" placeholder="Título">`,
                    confirmButtonText: 'Crear',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    preConfirm: () => {
                        var title = Swal.getPopup().querySelector('#login').value

                        return {
                            title: title
                        }
                    }
                }).then((result) => {



                    if (result.value.title) {
                        $.ajax({

                            url: "/full-calender/action",
                            type: "POST",
                            data: {
                                title: result.value.title,
                                start: start,
                                end: end,
                                idEmpleado: idEmpleado,
                                idJefeEquipo: idJefeEquipo,
                                tipoEvento: tipoEvento,
                                color: color,
                                type: 'add',
                            },
                            success: function(data) {
                                calendar.fullCalendar('refetchEvents');
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Evento creado',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            },

                            error: function(data) {
                                alert('mal');
                            }
                        })
                    }
                
                });
                }
                //Formato de colores del calendario

                //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

                //alert('Current view: ' + view.name);

                // change the day's background color just for fun
                //$(this).css('background-color', 'red');
            
            },
            
            events: '/full-calender',
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {},
            editable: true,

            //redimensionar eventos

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
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Evento actualizado',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                })
            },
            //mover eventos
            eventDrop: function(event, delta) {


                var start = moment(event.start).format('YYYY-MM-DD HH:mm:00');
                //verificar por que no coge la fecha final

                var end = moment(event.end).format('YYYY-MM-DD HH:mm:00');
                end = start;
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
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Evento actualizado',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                })
            },

            //eliminar eventos


            eventClick: function(data) {
                var id = data.id;
                var color = data.color;


                if (idJefeEquipo == idEmpleado && color == '#999999') {
                    Swal.fire({

                        position: 'top-end',

                        title: '¿Que quieres hacer con las vacaciones?',
                        showDenyButton: true,
                        showCancelButton: true,
                        cancelButtonText: 'Aplazar',
                        confirmButtonText: 'Aprobar',
                        denyButtonText: `Denegar`,
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Aprobadas',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            color = '#1b7340';
                            $.ajax({
                                url: "/full-calender/action",
                                type: "POST",
                                data: {
                                    id: id,
                                    type: "aprobar",
                                    color: color
                                },
                                success: function(response) {
                                    calendar.fullCalendar('refetchEvents');
                                }
                            })
                        } else if (result.isDenied) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Denegadas',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            color = '#ea2a18';
                            $.ajax({
                                url: "/full-calender/action",
                                type: "POST",
                                data: {
                                    id: id,
                                    type: "aprobar",
                                    color: color
                                },
                                success: function(response) {
                                    calendar.fullCalendar('refetchEvents');
                                }
                            })
                        } else if (result.dismiss) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'info',
                                title: 'Aplazadas',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $.ajax({
                                url: "/full-calender/action",
                                type: "POST",
                                data: {
                                    id: id,
                                    type: "aprobar",
                                    color: color
                                },
                                success: function(response) {
                                    calendar.fullCalendar('refetchEvents');
                                }
                            })
                        }
                    })

                } else {
                    var id = data.id;
                    var color = data.color;

                    if (idJefeEquipo == idEmpleado && color == '#1b7340' || color == '#ea2a18') {


                        Swal.fire({
                            icon: 'error',
                            position: 'top-end',
                            timer: 1500,
                            title: 'No puedes eliminar este evento',
                            showConfirmButton: false

                        })
                    } else {

                        Swal.fire({

                            position: 'top-end',

                            title: '¿Quieres eliminar el evento?',
                            showCancelButton: true,
                            confirmButtonText: 'Eliminar',
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                var id = data.id;
                                var color = data.color;
                                $.ajax({
                                    url: "/full-calender/action",
                                    type: "POST",
                                    data: {
                                        id: id,
                                        color: color,
                                        type: "delete"

                                    },
                                    success: function(response) {
                                        calendar.fullCalendar('refetchEvents');
                                    }
                                })
                            }

                        })
                    }
                }
            }

        });

    });
    </script>

</x-app-layout>