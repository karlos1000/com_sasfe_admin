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


    //exportar el reporte de los prospectos
    JQ('#btn_exportarProsp').click(function (){
        JQ("#fracc").removeClass("required");
        JQ("#fracc").removeAttr("required");
        if(JQ("#adminForm").valid()){
            // Joomla.submitbutton('reportes.reporteProspecto');
            // Joomla.submitbutton('reportes.reporteProspectoPantalla');
            JQ("#fracc").addClass("required");

            // Imp. 08/09/21 Carlos, Cargar en pantalla el resultado del reporte de productividad
            let params = {
              filter_fechaDel: JQ('#filter_fechaDel').val(),
              filter_fechaAl: JQ('#filter_fechaAl').val(),
              usuarioIdGteVenta: JQ('#usuarioIdGteVenta').val(),
              asig_agtventas: JQ('#asig_agtventas').val(),
              asig_fuente: JQ('#asig_fuente').val(),
            };
            fAjax('reporteProspectoPantalla', params);
        }
    });

    // Imp. 08/09/21, Carlos
    JQ("#btnVerRptProductividad").click(function(){
      JQ('#popup_rpt_productividad').css('position','fixed');
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
    JQ('#btn_exportarContactosFuente').click(function (){
        JQ("#fracc").removeClass("required");
        JQ("#fracc").removeAttr("required");
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

        if(JQ("#adminForm").valid()){
            Joomla.submitbutton('reportes.detalleAccionesContactos');
            JQ("#fracc").addClass("required");
        }
    });

    // Obtener valor de gerente de ventas y agregar selector de asesores
    JQ('#nombreGteJoomlaVentas').click(function (){
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


//Imp. 08/09/21, Carlos Metodo que se implemento para pasar los parametros por ajax
function fAjax(ctr, data){
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
        var result = JQ(html).find('response').html();
        console.log(result);
        JQ('#transmitter').html(result);
        JQ("#btnVerRptProductividad").trigger("click");
        // JQ('ul#ul_transmitter li').click(function(){
        //     var css = JQ(this).attr('class');
        //     (css == 'list') ? JQ(this).removeClass('list') : JQ(this).addClass('list');
        // });
    }
  });
}