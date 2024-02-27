$(function () {

    var colores = [];

  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: 'UTC',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    locale: 'es',
    events: cargaTours(),
    // events: [
    //     {id: 1, title: "Tour Centro", start:"2024-02-13T10:30:00", extendedProps:  {nombreGuia: "Juan", numeroParticipantes: "10/20"}},
    //     {id: 2, title: "Tour Castillo", start:"2024-02-13T10:30:00", extendedProps:  {nombreGuia: "Pepe", numeroParticipantes: "12/20"}}],
    editable: true,
    dayMaxEvents: true,
    eventContent: function (arg) {
      var el = $('<div></div>');
      // var bcolor = el.css("background-color", getRandomColor());
      console.log(arg.event.backgroundColor);
      console.log(arg);
    //   el.on("click", editaTour());
    //   el.on("change", editaTour());
      // el.css("background-color", getRandomColor());
      el.css("width", "100%");
      // console.log(el.css("background-color"));
      // if (el.css("background-color")) {
        
      // }
      
      el.css("background-color", arg.event.backgroundColor);
      var color = el.css("background-color");
      var numColor = color.match(/\d+/g).map(Number); 
      var suma = numColor.reduce((a, b) => a + b, 0);
      if (suma>=375) {
        el.css("color", "black");
      } else{
        el.css("color", "white");
      }
      console.log(suma);
      el.css("text-align", "center");
      el.css("border-radius", "5px");
      el.html(arg.event.title + ' ' + arg.event.start.toISOString().slice(11, 16) + '<br>' +
        (arg.event.extendedProps.nombreGuia || '') + ' ' +
        (arg.event.extendedProps.numeroParticipantes || ''));
      return {
        html: el.prop('outerHTML')
      };
    },
    // height: 'auto'
  });

  //   calendar.render();

  function cargaTours() {
    var eventos = [];
    $.ajax({
      url: "/api/tour/all/guia",
      method: "GET",
      dataType: "json",
      success: function (data) {
        console.log(data);
        // var eventos = [];
        for (var i = 0; i < data.length; i++) {
          var tour = data[i];
          console.log(tour.ruta.nombre);
          var fechaHora = new Date(tour.fechaHora.date);
          console.log(fechaHora);
          // colores[tour.usuario.id]="";
          console.log(colores[tour.usuario.id]);
          console.log($.inArray(tour.usuario.id, colores[tour.usuario.id]));
          if (colores[tour.usuario.id]=== undefined) {
            colores[tour.usuario.id] = getRandomColor();
            console.log("dentro del if");
          }

          console.log(tour.usuario.id);
          console.log(colores[tour.usuario.id]);
          var evento = {
            id: tour.id,
            title: tour.ruta.nombre,
            start: fechaHora.toISOString().split('.')[0],
            extendedProps: {
              nombreGuia: tour.usuario.nombre,
              numeroParticipantes: tour.ruta.aforo + "/" + tour.ruta.aforo
            },
            color: colores[tour.usuario.id]
          };

          console.log(evento.color);
          eventos.push(evento);
        }
        calendar.addEventSource(eventos);
        // console.log(calendar);
        console.log(eventos);
      },
      error: function (error) {
        console.error(error);
      }
    });

    return eventos;
  }

  //   cargaTours();

  calendar.render();

  function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }

})