document.addEventListener('DOMContentLoaded', function() {
    let formulario = document.querySelector("form");
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {    
        initialView: 'dayGridMonth',
        locale:"es",
        hiddenDays: [ 0, 6 ],
        slotMinTime:"08:00:00",
        slotMaxTime: "19:00:00",
        eventColor: '#6EB5FF',

        /*eventColor: function(info){
          info.event.id + '#6EB5FF'

        },*/
      


        headerToolbar: {
            left:'prev,next today',
            center:'title',
            right:'dayGridMonth,timeGridWeek,listWeek',
        },

        slotLabelFormat:{
            hour: '2-digit',
            minute: '2-digit',
            hour12: true,
            meridiem: 'short',
        },

        ////NOS ABRE EL MODAL PARA REGISTRAR///

        dateClick:function(info){
          $("#evento").modal("show");
          
        },
         dayClick: function(info) {
            // Llenar automáticamente el campo de fecha en el formulario
            document.getElementById('end').value = info.dateStr;
        },
        
        eventClick: function(info) {
          $('#Editar'+info.event.id).modal('show'); // abre el modal
          ///IMPORTANTE AQUI TENEMOS UN MODAL CONECTADO CON LA VISTA PARA PODER TRAER LA INFORMACÓN POR ID SE LE DEBE POONER "info.event.id"//
        },

        ////NOS DIRA CUANTOS EVENTOS PODEMOS APILAR EN LA VISTA PRINCIPAL DEL CALENDARIO////
        dayMaxEventRows: true,
        views: {
          timeGrid: {
            dayMaxEventRows: 3
          }
        },
        
        initialView: 'dayGridMonth',
        selectable: true,
        events:'/reservation/view/',

        
      
      });
      calendar.render();

});