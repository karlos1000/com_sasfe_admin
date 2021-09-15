JQ(document).ready(function(){
  //Imp. 18/03/21
   setTimeout(function(){
    JQ("#nombreGteJoomlaVentas").removeClass("sel_sololectura");
     if(Number(JQ("#racIdGteVentas").val())>0){
       JQ("#nombreGteJoomlaVentas").val(JQ("#racIdGteVentas").val());
       JQ("#nombreGteJoomlaVentas").addClass("sel_sololectura");
       JQ("#nombreGteJoomlaVentas").trigger("click");
     }
   },500);

        //Reemplazar evento onclick
        JQ('#toolbar-delete a').attr('onclick','').unbind('click');
        JQ('#toolbar-delete a').click(function(){
            if (document.adminForm.boxchecked.value==0){
                alert('Please first make a selection from the list');
            }else{
                var r=confirm('No es posible eliminar el elemento, al hacerlo se borrara los deparatamentos vinculados, ¿Realmente estás seguro?');
                if (r==true){
                    Joomla.submitbutton('catfraccionamientos.delete');
                }else{
                    return false;
                }
            }
         });

    JQ('#btn_exportar').click(function (){
        if(JQ("#adminForm").valid()){
            Joomla.submitbutton('reportes.reporteGral');
        }
        // if(JQ("#fracc").val()!=''){
        //     Joomla.submitbutton('reportes.reporteGral');
        // }
    });

    JQ('.button-cancel').click(function (){
          var controles = ['input', 'select', 'textarea'];
          JQ(controles).each(function(index, val){
            JQ(val).removeClass('required');
            JQ(val).removeAttr("required");
          });
          Joomla.submitbutton('reportes.cancel');
    });

    // Imp. 10/09/21, Carlos, Cargar el selector de agentes de venta por id de gerentes de ventas
    // Obtener valor de gerente de ventas y agregar selector de asesores
    JQ('#usuarioIdGteVenta').change(function (){
      //Obtener valor del gerente de ventas
      let idGteVenta = JQ(this).val();
      console.log(idGteVenta);
      // return false;

      if(idGteVenta>0){
        //obtener los datos para crear opciones
          let colAsesoresXGteTmp = JSON.parse(arrColAsesoresXGte);
          console.log(colAsesoresXGteTmp[idGteVenta]);
          let option = '';
          option += '<option value="">--Todos--</option>';
          JQ.each(colAsesoresXGteTmp[idGteVenta], function (ii, elem) {
            option += '<option value="'+elem.idDato+'">'+elem.nombre+'</option>';
          });
          JQ("#asig_agtventas").html(option);
      }else{
        JQ("#asig_agtventas").html('<option value="0">--Todos--</option>');
      }
    });

    //exportar el reporte de los prospectos
    JQ('#btn_exportarProsp').click(function (){
        JQ("#usuarioIdGteVenta").addClass("required");
        JQ("#fracc").removeClass("required");
        JQ("#fracc").removeAttr("required");
        JQ("#usuarioIdGteVenta3").removeClass("required");
        JQ("#usuarioIdGteVenta3").removeAttr("required");

        if(JQ("#adminForm").valid()){
          alertify.success("Generando reporte, esto puede tardar algunos minutos, esper&eacute; por favor.");
          // Joomla.submitbutton('reportes.reporteProspecto');
          JQ("#fracc").addClass("required");

          // Imp. 08/09/21 Carlos, Cargar en pantalla el resultado del reporte de productividad
          let params = {
            filter_fechaDel: JQ('#filter_fechaDel').val(),
            filter_fechaAl: JQ('#filter_fechaAl').val(),
            usuarioIdGteVenta: JQ('#usuarioIdGteVenta').val(),
            asig_agtventas: JQ('#asig_agtventas').val(),
            asig_fuente: JQ('#asig_fuente').val(),
          };
          console.log(params);
          // return false;

          JQ("#btn_exportarProsp").attr("disabled", "disabled");
          fAjax("reporteProspectoPantalla", params, function(data){
            JQ("#btn_exportarProsp").removeAttr("disabled");
            // console.log(data);
            if(data.result){
              document.getElementById('btnVerRptProductividad').click();
              mostrarRptProductividad(params.asig_fuente, data);
            }else{
              alert(data.msg);
            }
          });
        }
    });

    //exportar el reporte de los prospectos
    JQ('#btnPdfRptProductividad').click(function (){
      JQ("#fracc").removeClass("required");
      JQ("#fracc").removeAttr("required");
      document.getElementById('btnVerRptProductividad').click();
      Joomla.submitbutton('reportes.reporteProspecto');
    });

    // Imp. 08/09/21, Carlos
    JQ("#btnVerRptProductividad").click(function(){
      JQ('#popup_rpt_productividad').css('position','fixed');
    });
    // Imp. 09/09/21, Carlos
    JQ("#btnVerDetalleEvento").click(function(){
      JQ('#popup_detalle_evento').css('position','fixed');
    });

    //Fecha del
    JQ("#filter_fechaDel").datepicker({
          showOn: "button",
          buttonImage: JQ("#rutaCalendario").val(),
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          // maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",
          defaultDate: JQ("#filter_fechaDel").val(),
          onSelect: function(dateText, inst){
            JQ("#filter_fechaDel").val(JQ(this).val());
          }
    });
    //Fecha al
    JQ("#filter_fechaAl").datepicker({
          showOn: "button",
          buttonImage: JQ("#rutaCalendario").val(),
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          //maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",
          defaultDate: JQ("#filter_fechaAl").val(),
          onSelect: function(dateText, inst){
            JQ("#filter_fechaAl").val(JQ(this).val());
          }
    });

    //Para el campo de
    JQ('input[name=\"asig_fuente\"]').click(function() {
        var v = JQ(this).val();
        var value = (v == 1) ? '0' : '1';
        JQ('#asig_fuente').val(value);
    });


    //Imp. 30/09/20
    //Inicio de reporte de contactos por fuente
    //Fecha del
    JQ("#filter_fechaDelCF").datepicker({
          showOn: "button",
          buttonImage: JQ("#rutaCalendario").val(),
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          // maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",
          defaultDate: JQ("#filter_fechaDelCF").val(),
          onSelect: function(dateText, inst){
            JQ("#filter_fechaDelCF").val(JQ(this).val());
          }
    });
    //Fecha al
    JQ("#filter_fechaAlCF").datepicker({
          showOn: "button",
          buttonImage: JQ("#rutaCalendario").val(),
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          //maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",
          defaultDate: JQ("#filter_fechaAlCF").val(),
          onSelect: function(dateText, inst){
            JQ("#filter_fechaAlCF").val(JQ(this).val());
          }
    });

    // Imp. 14/09/21, Carlos, Cargar el selector de agentes de venta por id de gerentes de ventas
    // Obtener valor de gerente de ventas y agregar selector de asesores
    JQ('#usuarioIdGteVenta3').change(function (){
      //Obtener valor del gerente de ventas
      let idGteVenta = JQ(this).val();
      console.log(idGteVenta);
      // return false;

      if(idGteVenta>0){
        //obtener los datos para crear opciones
          let colAsesoresXGteTmp = JSON.parse(arrColAsesoresXGte);
          console.log(colAsesoresXGteTmp[idGteVenta]);
          let option = '';
          option += '<option value="">--Todos--</option>';
          JQ.each(colAsesoresXGteTmp[idGteVenta], function (ii, elem) {
            option += '<option value="'+elem.usuarioIdJoomla+'">'+elem.nombre+'</option>';
          });
          JQ("#asig_agtventas3").html(option);
      }else{
        JQ("#asig_agtventas3").html('<option value="0">--Todos--</option>');
      }
    });

    JQ('#btn_exportarContactosFuente').click(function (){
        JQ("#fracc").removeClass("required");
        JQ("#fracc").removeAttr("required");
        JQ("#usuarioIdGteVenta3").addClass("required");
        JQ("#usuarioIdGteVenta").removeAttr("required");
        JQ("#usuarioIdGteVenta").removeClass("required");

        if(JQ("#adminForm").valid()){
            Joomla.submitbutton('reportes.contactosFuente');
            JQ("#fracc").addClass("required");
        }
    });
    //Fin de reporte de contactos por fuente

    //Inicio de reporte de acciones de los contactos
    //Fecha del
    JQ("#filter_fechaDelAC").datepicker({
          showOn: "button",
          buttonImage: JQ("#rutaCalendario").val(),
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          // maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",
          defaultDate: JQ("#filter_fechaDelAC").val(),
          onSelect: function(dateText, inst){
            JQ("#filter_fechaDelAC").val(JQ(this).val());
          }
    });
    //Fecha al
    JQ("#filter_fechaAlAC").datepicker({
          showOn: "button",
          buttonImage: JQ("#rutaCalendario").val(),
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          //maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",
          defaultDate: JQ("#filter_fechaAlAC").val(),
          onSelect: function(dateText, inst){
            JQ("#filter_fechaAlAC").val(JQ(this).val());
          }
    });
    JQ('#btn_exportarAccionesContactos').click(function (){
        JQ("#fracc").removeClass("required");
        JQ("#fracc").removeAttr("required");
        JQ("#asig_agtventas").removeClass("required");
        JQ("#asig_agtventas").removeAttr("required");
        JQ("#nombreGteJoomlaVentas").addClass("required");
        JQ("#usuarioIdGteVenta").removeAttr("required");
        JQ("#usuarioIdGteVenta").removeClass("required");

        if(JQ("#adminForm").valid()){
            Joomla.submitbutton('reportes.detalleAccionesContactos');
            JQ("#fracc").addClass("required");
        }
    });

    // Obtener valor de gerente de ventas y agregar selector de asesores
    JQ('#nombreGteJoomlaVentas').click(function (){
    // JQ('#nombreGteJoomlaVentas').change(function (){
      //Obtener valor del gerente de ventas
      let idGteVenta = JQ(this).val();

      if(idGteVenta>0){
        //obtener los datos para crear opciones
          let colAsesoresXGteTmp = JSON.parse(arrColAsesoresXGte);
          // console.log(colAsesoresXGteTmp[idGteVenta]);
          let option = '';
          // option += '<option value="">--Seleccionar--</option>';
          option += '<option value="">--Todos--</option>';
          JQ.each(colAsesoresXGteTmp[idGteVenta], function (ii, elem) {
            option += '<option value="'+elem.usuarioIdJoomla+'">'+elem.nombre+'</option>';
          });
          JQ("#asig_agtventas2").html(option);
      }else{
        JQ("#asig_agtventas2").html('<option value="0">--Todos--</option>');
      }
    });



 });


// Imp. 09/09/21
function mostrarRptProductividad(fuente, data){
  // Imp. 14/09/21, Carlos Obtener el nombre del gerente y agente
  let nombreGte = JQ("#usuarioIdGteVenta option:selected").text();
  let nombreAgte = JQ("#asig_agtventas option:selected").text();
  let htmlGteAgte = "";
  if(nombreGte!=""){
    htmlGteAgte = `Gerente: ${nombreGte} `;
  }
  // if(nombreAgte!="--Todos--"){
  //   htmlGteAgte += ` - Agente: ${nombreAgte} `;
  // }

  let html = `
    <div>
        <h4>${htmlGteAgte}</h4>
        <h5>Del ${data.fechaDel} Al ${data.fechaAl}, D&iacute;as del periodo ${data.difDias} a ${data.fechaActual}</h5>
    </div>
  `;

  if(parseInt(fuente)>0){ //Por fuente
    console.log(data);
    html += `
      <table cellspacing="0" cellpadding="2" border="1">
        <tr>
          <td width="139" rowspan="2"></td>
          <td width="248" colspan="4">Prospectos</td>
          <td width="186" colspan="3">Ventas</td>
          <td width="372" colspan="6">Seguimientos</td>
        </tr>
        <tr>
          <td width="62"># Prospectos</td>
          <td width="62"># de Prospectos x d&iacute;a</td>
          <td width="62"># de Prospectos rechazados</td>
          <td width="62">% de Prospectos rechazados</td>
          <td width="62"># de prospectos convertidos</td>
          <td width="62">% de conversion</td>
          <td width="62">Velocidad de conversion d&iacute;as</td>
          <td width="62"># de eventos programados</td>
          <td width="62"># de eventos cumplidos</td>
          <td width="62">% de eventos cumplidos</td>
          <td width="62"># de eventos no cumplidos</td>
          <td width="62">% de eventos no cumplidos</td>
          <td width="62">Prom de eventos diarios</td>
        </tr>
      `;
      JQ.each(data.colDatosReporte, function (ii, ee) {
        // console.log(e);
        JQ.each(ee, function (i, e) { //Setea el nombre del agente de ventas
          html += `<tr>
                    <td colspan="13"><br/><b>${e.nombreAgenteV}</b></td>
                  </tr>
          `;
          return false;
        });
        JQ.each(ee, function (i, e) {
          html += `
              <tr style="text-align:right;">
                <td width="139" style="text-align:left;">${e.tipoCaptado}</td>
                <td width="62">${e.prospAdquiridos}</td>
                <td width="62">${e.prospectosxdia}</td>
                <td width="62">${e.prospNoprocedido}</td>
                <td width="62">${e.ptcRechazados} %</td>
                <td width="62">${e.prospConvertidos}</td>
                <td width="62">${e.ptcConversion} %</td>
                <td width="62">${e.velocidadConversionDias}</td>
                <td width="62">${e.eventosProgramados}</td>
                <td width="62"><a href="javascript:void(0);" onclick="verDetalleEventos(1, '${data.fechaDel}', '${data.fechaAl}', '${e.idsDatosProspectos}');">${e.eventosCumplidos}</a></td>
                <td width="62">${e.ptcEventosCumplidos} %</td>
                <td width="62"><a href="javascript:void(0);" onclick="verDetalleEventos(2, '${data.fechaDel}', '${data.fechaAl}', '${e.idsDatosProspectos}');">${e.eventosNoCumplidos}</a></td>
                <td width="62">${e.ptcEventosNoCumplidos} %</td>
                <td width="62">${e.eventosxdia}</td>
              </tr>
          `;
        });
      });
      html += `</table>`;
  }else{
    html += `
      <table cellspacing="0" cellpadding="2" border="1">
          <tr>
            <td width="" rowspan="2"></td>
            <td width="248" colspan="4">Prospectos</td>
            <td width="186" colspan="3">Ventas</td>
            <td width="372" colspan="6">Seguimientos</td>
          </tr>
          <tr>
            <td width="62"># Prospectos</td>
            <td width="62"># de Prospectos x d&iacute;a</td>
            <td width="62"># de Prospectos rechazados</td>
            <td width="62">% de Prospectos rechazados</td>
            <td width="62"># de prospectos convertidos</td>
            <td width="62">% de conversion</td>
            <td width="62">Velocidad de conversion d&iacute;as</td>
            <td width="62"># de eventos programados</td>
            <td width="62"># de eventos cumplidos</td>
            <td width="62">% de eventos cumplidos</td>
            <td width="62"># de eventos no cumplidos</td>
            <td width="62">% de eventos no cumplidos</td>
            <td width="62">Prom de eventos diarios</td>
          </tr>`;
          JQ.each(data.colDatosReporte, function (i, e) {
            // console.log(e);
            html += `
              <tr style="text-align:right;">
                  <td width="" style="text-align:left;">${e.nombreAgenteV}</td>
                  <td width="62" style="font-size:12px;">${e.prospAdquiridos}</td>
                  <td width="62" style="font-size:12px;">${e.prospectosxdia}</td>
                  <td width="62" style="font-size:12px;">${e.prospNoprocedido}</td>
                  <td width="62" style="font-size:12px;">${e.ptcRechazados}%</td>
                  <td width="62" style="font-size:12px;">${e.prospConvertidos}</td>
                  <td width="62" style="font-size:12px;">${e.ptcConversion}%</td>
                  <td width="62" style="font-size:12px;">${e.velocidadConversionDias}</td>
                  <td width="62" style="font-size:12px;">${e.eventosProgramados}</td>
                  <td width="62" style="font-size:12px;"><a href="javascript:void(0);" onclick="verDetalleEventos(1, '${data.fechaDel}', '${data.fechaAl}', '${e.idsDatosProspectos}');">${e.eventosCumplidos}</a></td>
                  <td width="62" style="font-size:12px;">${e.ptcEventosCumplidos} %</td>
                  <td width="62" style="font-size:12px;"><a href="javascript:void(0);" onclick="verDetalleEventos(2, '${data.fechaDel}', '${data.fechaAl}', '${e.idsDatosProspectos}');">${e.eventosNoCumplidos}</a></td>
                  <td width="62" style="font-size:12px;">${e.ptcEventosNoCumplidos} %</td>
                  <td width="62" style="font-size:12px;">${e.eventosxdia}</td>
                </tr>
            `;
          });
    html += `</table>`;
  }

  JQ("#cont_tabla_productividad").html(html);
}

// Imp. 09/09/21
function verDetalleEventos(tipoEvento, fechaDel, fechaAl, idsDatosProspectos){
  let params = {
    tipoEvento:tipoEvento,
    fechaDel: fechaDel,
    fechaAl: fechaAl,
    idsDatosProspectos: idsDatosProspectos,
  };
  // console.log(params);

  fAjax("detalleEvento", params, function(data){
    // console.log(data);
    if(data.result){
      document.getElementById('btnVerDetalleEvento').click();
      mostrarDetalleEvento(data.colDetalles);
    }else{
      alert(data.msg);
    }
  });
}

// Imp. 09/09/21
function mostrarDetalleEvento(data){
  /*<table cellspacing="0" cellpadding="2" border="0">
        <tr>
          <td  style="text-align:left;">Del ${data.fechaDel} Al ${data.fechaAl}, D&iacute;as del periodo ${data.difDias}</td>
          <td  style="text-align:right;">Fecha: ${data.fechaActual}</td>
        </tr>
    </table>*/
  let html = '';
  html = `
      <table cellspacing="0" cellpadding="2" border="1">
        <tr>
          <td>Prospecto</td>
          <td>RFC</td>
          <td>Tipo Evento</td>
          <td>Fecha</td>
          <td>Comentario</td>
        </tr>`;
          JQ.each(data, function (i, e) {
            // console.log(e);
            html += `
                <tr>
                  <td>${e.prospecto}</td>
                  <td>${e.RFC}</td>
                  <td>${e.tipoEvento}</td>
                  <td>${e.fechaHora}</td>
                  <td>${e.comentario}</td>
                </tr>
            `;
          });
  html += `</table>`;

  JQ("#cont_tabla_detalle_evento").html(html);
}


//Imp. 08/09/21, Carlos Metodo que se implemento para pasar los parametros por ajax
function fAjax(ctr, data, response){
  var path = JQ('#path').val()+ctr;
  // var loading = JQ('#loading_img').val();
  JQ.ajax({
    type: 'POST',
    url: path,
    data: data,
    beforeSend: function(){
      // JQ('#transmitter').html(loading);
    },
    success: function(html){
      response(html);
    }
  });
}