$(function () {
    
    var calendario = $("#calendario");
    $(".content").append(calendario);

    var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: 'UTC',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    locale: 'es',
    events: [
        {id: 1, title: "Tour Centro", start:"2024-02-13T10:30:00", extendedProps:  {nombreGuia: "Juan", numeroParticipantes: "10/20"}},
        {id: 2, title: "Tour Castillo", start:"2024-02-13T10:30:00", extendedProps:  {nombreGuia: "Pepe", numeroParticipantes: "12/20"}}],
    editable: true,
    dayMaxEvents: true, // when too many events in a day, show the popover
    // events: '/api/demo-feeds/events.json?overload-day'
    eventContent: function(arg) {
        // Create a new element to display the event details
        var el = document.createElement('div');
        el.innerHTML = arg.event.title + ' ' + arg.event.start.toISOString().slice(11,16)+ '<br>' +
                    //    arg.event.start.toISOString().slice(11,16) + '<br>' +
                       (arg.event.extendedProps.nombreGuia || '') + ' ' +
                       (arg.event.extendedProps.numeroParticipantes || '');
        return { html: el.outerHTML };
    },
    height: 'auto'
  });



  calendar.render();

  function cargaTours() {
    $.ajax({
        url: "/api/tours",
        method: "GET",
        dataType: "json",
        success: function (data) {
            console.log(data);
            var eventos = [];
            for (var i = 0; i < data.length; i++) {
                var tour = data[i];
                var evento = {
                    id: tour.id,
                    title: tour.nombre,
                    start: tour.fecha
                };
                eventos.push(evento);
            }
            calendar.addEventSource(eventos);
        },
        error: function (error) {
            console.error(error);
        }
    });
  }




})