var opcGteAsesor = 0;
JQ(document).ready(function(){
  if(JQ(".duallb").length){
    JQ('select[name="asesores"]').bootstrapDualListbox({
        // nonSelectedListLabel: 'No seleccionados',
        // selectedListLabel: 'Seleccionados',
        preserveSelectionOnMove: 'moved',
        moveOnSelect: false,
        //moveOnSelect: true,
        //infoText: true
    });
  }
  JQ('#asesores').change(function() {
      if(JQ(this).val()!=null){
          JQ("#idsAsesores").val(JQ(this).val());
      }else{
          JQ("#idsAsesores").val("");
      }
  });

  setTimeout(function(){
    JQ('#opc_gerente_ventas').trigger("click");
  },400);
  //Para las opciones de seleccionar gerentes de ventas o asesores
    JQ('#opc_gerente_ventas').click(function() {
        opcGteAsesor = 0;
        JQ("#cont_gerente_ventas").show();
        JQ("#cont_agente_ventas").hide();
        JQ("#usuarioIdGteVenta").addClass("required");
        JQ("#asesores").removeClass("required");
        // JQ("#idsAsesores").val("");
    });
    JQ('#opc_asesores_ventas').click(function() {
        opcGteAsesor = 1;
        JQ("#cont_agente_ventas").show();
        JQ("#cont_gerente_ventas").hide();
        JQ("#asesores").addClass("required");
        JQ("#usuarioIdGteVenta").removeClass("required");
    });
});


// Sincronizar desde google sheet
function sincronizarContactos(){
  if(opcGteAsesor==1){
    if(!JQ("#idsAsesores").val() != ""){
      alert("Debe seleccionar al menos un asesor.");
      return false;
    }
  }

  // console.log("sincronizar");
  JQ("#cont_msg_sincontactos").hide();
  JQ("#cont_loading").show();
  var metodo = "sincronizarContactos";
  var path = JQ('#path').val()+metodo;
  // var params = {idsGerencias:"1,3,5"};
  var arrIds = JQ("#usuarioIdGteVenta").val();
  ids= arrIds.join();
  // console.log(ids);
  // var params = {idsGerencias:ids};
  var params = {idsGerencias:ids, idsAsesores:JQ("#idsAsesores").val(), opcGteAsesor:opcGteAsesor};
  // console.log(params);
  // return false;

  ajaxPostData(path, params, function(data){
    console.log(data);
    let html = '';
    if(data.success==true){
      // JQ("#cont_contactos").html("");
      JQ("#totalContactos").html(data.total); //Total encontrados

      JQ.each(data.sheetData, function (i, v) {
        // console.log(i, v);
        var nombreC = v.nombre+' '+v.apaterno+' '+v.amaterno;

        let desarrollos = '';
        desarrollos +='<select id="idFracc_'+i+'" name="idFracc_'+i+'">';
        desarrollos +='    <option value="">--Seleccione--</option>';
        // desarrollos +='    <option value="1">Villas Los frutales II</option>';
        // desarrollos +='    <option value="2">Villas Manzanilla</option>';
        // desarrollos +='    <option value="3">Villas Arcoiris II</option>';
        desarrollos += JQ("#htmlOptionsFracc").val();
        desarrollos +='</select>';

        let selGerentes = '';
        selGerentes += '<select class="idGteVentas" id="idGteVentas_'+i+'" name="idGteVentas_'+i+'" onchange="recargaAgentesSincCont('+i+')">';
        selGerentes += '  <option value="">--Seleccione--</option>';
        // selGerentes += '  <option value="307">AMALFI BOTA LANDINI</option>';
        selGerentes += JQ("#htmlOptionsGer").val();
        selGerentes += '</select>';

        let selAsesores = '';
        selAsesores +='<select id="idAsesorVentas_'+i+'" name="idAsesorVentas_'+i+'">';
        selAsesores +='  <option value="">--Seleccione--</option>';
        // selAsesores +='  <option value="339">MARCOS SOLIS ALVARADO</option>';
        // selAsesores +='  <option value="341">MARIA AURORA MARICELA ROMERO GUTIERREZ</option>';
        selAsesores += JQ("#htmlOptionsAgente"+v.gerenteId).val();
        selAsesores +='</select>';
        // console.log("gerenteId: "+v.gerenteId);

        // Revisa duplicado
        let checked = (v.duplicado==0)?"checked":"";
        let contactoDuplicado = (v.duplicado==1)?"contactoDuplicado":"";
        html += '<tr class="'+contactoDuplicado+'">';
        html += '    <td><input type="checkbox" name="cid_'+i+'" id="id_'+i+'" class="selCheck" '+checked+'></td>';
        html += '    <td>';
              html += '<input type="text" name="nombre_'+i+'" value="'+nombreC+'" readonly />';
              html += '<input type="hidden" name="h_nombre_'+i+'" value="'+v.nombre+'" readonly />';
              html += '<input type="hidden" name="h_apaterno_'+i+'" value="'+v.apaterno+'" readonly />';
              html += '<input type="hidden" name="h_amaterno_'+i+'" value="'+v.amaterno+'" readonly />';
              html += '<input type="hidden" name="h_fuente_'+i+'" value="'+v.fuente+'" readonly />';
              html += '<input type="hidden" name="h_credito_'+i+'" value="'+v.tipocredito+'" readonly />';
        html += '    </td>';
        html += '    <td><input type="text" name="tel_'+i+'" value="'+v.telefono+'" readonly /></td>';
        html += '    <td><input type="text" name="email_'+i+'" value="'+v.email+'" readonly /></td>';
        html += '    <td>'+desarrollos+'</td>';
        html += '    <td>'+selGerentes+'</td>';
        html += '    <td>'+selAsesores+'</td>';
        html += '    <td>';
        if(v.duplicado==1){
          gerenteAnteriorNombre = (v.gerenteAnterior != null)?v.gerenteAnterior.name:'';
          asesorAnteriorNombre = (v.asesorAnterior != null)?v.asesorAnterior.name:'';
          html += '<input type="hidden" name="h_contactoDup_'+i+'" value="'+v.idDatoContacto+'" readonly />';
          html += '<input type="hidden" name="h_agtVentasIdDup_'+i+'" value="'+v.contactoDup.agtVentasId+'" readonly />';
          // console.log(v.contactoDup);
          html += '       <div class="contVerDuplicado">'
          +'<a class="btn btn-info btn-lg" data-toggle="modal" data-target="#modalDup"  onclick="verDuplicado('+v.idDatoContacto+', \''+v.estatusAnterior+'\', \''+gerenteAnteriorNombre+'\', \''+asesorAnteriorNombre+'\', \''+v.contactoDup.nombre+'\', \''+v.contactoDup.email+'\', \''+v.contactoDup.telefono+'\', \''+v.contactoDup.fechaAlta+'\', \''+v.contactoDup.fechaContacto+'\', \''+v.contactoDup.fechaActualizacion+'\', '+i+')"><img style="width:100%;" src="../media/com_sasfe/images/vermas.png"></a>'
          +''

          +'</div>';

          var htmlAcciones = '<h3>Acciones</h3><table class=&quot;table table-striped header-fixed&quot;>';
          htmlAcciones += '<thead>';
          htmlAcciones += '<tr>';
          htmlAcciones += '<th>Accion</th>';
          htmlAcciones += '<th>Comentario</th>';
          htmlAcciones += '<th>Fecha</th>';
          htmlAcciones += '</tr>';
          htmlAcciones += '</thead>';
         
          cont = 0;
          //Guardar html de las acciones en un hidden
          JQ.each(v.acciones, function (iAcc, accion) {
            cont++;
            var accionNombre = '';
            console.log(typeof accion.accionId);

            switch(parseInt(accion.accionId)){
              case 1: accionNombre = 'Llamar'; break;
              case 2: accionNombre = 'Whatsapp'; break;
              case 3: accionNombre = 'SMS'; break;
              case 4: accionNombre = 'Correo'; break;
              case 5: accionNombre = 'Cita'; break;
              case 6: accionNombre = 'Recontacto'; break;
              case 7: accionNombre = 'Reasignacion'; break;
            }

            htmlAcciones += '<tr>';
            htmlAcciones += '<td>'+accionNombre+'</td>';
            htmlAcciones += '<td>'+accion.comentario+'</td>';
            htmlAcciones += '<td>'+accion.fechaAlta+'</td>';
            htmlAcciones += '</tr>';

          });

          htmlAcciones += (cont == 0)?'<tr ><td colspan=&quot;3&quot;>No hay acciones registradas</td></tr>':'';
          
          htmlAcciones += '</table>';
          
          // var encodedStr = htmlAcciones.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
          //   return '&#'+i.charCodeAt(0)+';';
          // });

          html += '<input type="hidden" name="h_htmlAcciones_'+i+'" id="h_htmlAcciones_'+i+'" value="'+htmlAcciones+'" readonly />';

        }
        html += '     </td>';
        html += '</tr>';
        // JQ("#cont_contactos").append(html);

      });
    }
    JQ("#cont_contactos").html(html);
    JQ("#cont_loading").hide();
    JQ(".cont_btn_salvarasignacion").show();
    if(data.success==true){
      JQ.each(data.sheetData, function (i, v) {
        JQ("#idFracc_"+i).val(v.desarrolloId);
        JQ("#idGteVentas_"+i).val(v.gerenteId);
        JQ("#idAsesorVentas_"+i).val(v.agenteId);

        // console.log("desarrolloId: "+v.desarrolloId)
        
      });
    }
  });
}

// Ver duplicado
function verDuplicado(idDatoContacto,   estatusId,   gteVentasId,   agtVentasId,   nombre,   email,   telefono,   fechaAlta,   fechaContacto,   fechaActualizacion, i){
  console.log(idDatoContacto);

  console.log(nombre);
  fechaContacto = (fechaContacto == 'null')?'':fechaContacto;

  JQ("#dupnombre").val(nombre);
  JQ("#dupgerencia").val(gteVentasId);
  JQ("#dupemail").val(email);
  JQ("#dupasesor").val(agtVentasId);
  JQ("#duptelefono").val(telefono);
  JQ("#dupestatus").val(estatusId);
  JQ("#dupfalta").val(fechaAlta);
  JQ("#dupfcontacto").val(fechaContacto);
  JQ("#dupfcambio").val(fechaActualizacion);

  JQ("#divAcciones").html(JQ("#h_htmlAcciones_"+i).val()) ;
}

function recargaAgentesSincCont(i){
  let gerenteId = JQ("#idGteVentas_"+i).val();
  let selAsesores = '';
  selAsesores +='<select id="idAsesorVentas_'+i+'" name="idAsesorVentas_'+i+'">';
  selAsesores +='  <option value="">--Seleccione--</option>';
  // selAsesores +='  <option value="339">MARCOS SOLIS ALVARADO</option>';
  // selAsesores +='  <option value="341">MARIA AURORA MARICELA ROMERO GUTIERREZ</option>';
  selAsesores += JQ("#htmlOptionsAgente"+gerenteId).val();
  selAsesores +='</select>';

  // console.log("gerenteId: "+gerenteId);
  // console.log(JQ("#htmlOptionsAgente"+gerenteId).val());

  JQ("#idAsesorVentas_"+i).html(selAsesores);
}

// Salvar asignacion de contactos
function salvarAsignaciones(){
  JQ("#cont_loading").show();
  let idsCheck = [];
  JQ('input[type=checkbox]').each(function () {
    if (this.checked) {
      // console.log(JQ(this).attr("id"));
      var id = JQ(this).attr("id");
      var split = id.split("id_");
      idsCheck.push(split[1]);
    }
  });
  JQ("#idsCheck").val(idsCheck.join());

  setTimeout(function(){
    Joomla.submitbutton('sincontactos.salvarAsignaciones');
  }, 500);
}


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//>>>>>>>>>>>>>>>>>>>>METODOS GENERALES>>>>>>>>>>>>>>>>>>>>
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//metodo para abre llamadas ajax
function ajaxPostData(url, data, response){
  var compareDate = new Date();
  JQ.ajax({
      type: 'POST',
      dataType: 'json',
      data: data,
      url: url,
      beforeSend: function () {
        // JQ('#transmitter').html(loading);
      },
      complete: function () {
      },
      success: function (data) {
          //console.log(data);
          response(data);
      },
      error: function () {
        var data = {success:false};
        response(data);
      },
      // timeout: 5000,
      timeout: 60000,
  })
  .done(function() {
    // console.log(Math.abs(new Date() - compareDate));
  })
  .fail(function() {
    // console.log(Math.abs(new Date() - compareDate));
    JQ("#cont_msg_sincontactos").show();
  })
}
