JQ(document).ready(function(){
  //Opcion gerente de ventas o prospeccion
  if(typeof JQ('.opcGteSel').val() != 'undefined'){
    JQ(".opcGteSel").change(function (){
      idSel = JQ(this).attr("id");
      valSel = JQ(this).val();
      if(valSel!=""){
        var selectores = ['#nombreGteJoomlaVentas', '#nombreGteJoomlaProspeccion'];
        JQ(selectores).each(function(index, val){
          JQ(val).removeClass('required');
          JQ(val).removeAttr("required");
        });
        JQ("input[name=idGte]").val(valSel); //id del agente
        JQ("input[name=idUsrJoomla]").val("");
        JQ("input[name=opcUsuario]").val("");
        switch (idSel) {
            case "nombreGteJoomlaVentas":
                JQ("#nombreGteJoomlaVentas").addClass("required");
                JQ("#nombreGteJoomlaProspeccion").val("");
                JQ("input[name=opcGerente]").val("gteventas"); //opcion del agente
                selUProspectador = '<select id="usuarioProspectador" name="usuarioProspectador"><option value="">-Selecciona-</option></select>';
                JQ("#usuarioProspectador").html(selUProspectador);
                showLoading("#usuarioAgenteVentas"); //mostrar loading
                params = {ctr:1, val:valSel, contenedor: '#cont_usuarioAgenteVentas'};
                ajaxKoolObtUsuariosXGte(params);
                showLoading("#usuarioProspectador"); //mostrar loading
                params2 = {ctr:2, val:valSel, contenedor: '#cont_usuarioProspectador'};
                ajaxKoolObtUsuariosXGte(params2);
                break;
            case "nombreGteJoomlaProspeccion":
                JQ("#nombreGteJoomlaProspeccion").addClass("required");
                JQ("#nombreGteJoomlaVentas").val("");
                JQ("input[name=opcGerente]").val("gteprospeccion"); //opcion del agente
                // setTimeout(function(){
                  // showLoading("#usuarioAgenteVentas"); //mostrar loading
                  // params1 = {ctr:1, val:valSel, contenedor: '#cont_usuarioAgenteVentas'};
                  // ajaxKoolObtUsuariosXGte(params1);
                // }, 2000);
                showLoading("#usuarioProspectador"); //mostrar loading
                params2 = {ctr:2, val:valSel, contenedor: '#cont_usuarioProspectador'};
                ajaxKoolObtUsuariosXGte(params2);
                break;
        }
      }else{
        JQ("#nombreGteJoomlaVentas").addClass("required");
        JQ("#nombreGteJoomlaProspeccion").addClass("required");
        JQ("input[name=idGte]").val(""); //limpiar id del agente
        JQ("input[name=opcGerente]").val(""); //limpiar opcion del agente
      }
    });
    JQ(document).on("change","#usuarioAgenteVentas",function(){
      JQ("#usuarioAgenteVentas").addClass("required");
      JQ("#usuarioProspectador").removeClass("required");
      JQ("#usuarioProspectador").removeAttr("required");
      JQ("input[name=idUsrJoomla]").val("");
      JQ("input[name=opcUsuario]").val("");
      JQ("#usuarioProspectador").val("");
      JQ("input[name=idUsrJoomla]").val(JQ(this).val());
      JQ("input[name=opcUsuario]").val("agenteventas");
    });
    JQ(document).on("change","#usuarioProspectador",function(){
      JQ("#usuarioProspectador").addClass("required");
      JQ("#usuarioAgenteVentas").removeClass("required");
      JQ("#usuarioAgenteVentas").removeAttr("required");
      JQ("input[name=idUsrJoomla]").val("");
      JQ("input[name=opcUsuario]").val("");
      JQ("#usuarioAgenteVentas").val("");
      JQ("input[name=idUsrJoomla]").val(JQ(this).val());
      JQ("input[name=opcUsuario]").val("prospectador");
    });
  }
  //Verificar si puede guardar un prospecto
  if(typeof JQ('#verBtnGuardar').val() != 'undefined'){
    verBtnGuardar = parseInt(JQ('#verBtnGuardar').val());
    if(verBtnGuardar==0){
      JQ(".button-apply").hide();
      JQ(".button-save").hide();
      JQ(".button-save-new").hide();
    }
  }


  //No mostrar los botones para guardar porque ya se tiene asignado de forma permanente el departamento/casa
  // if(typeof JQ('#verBtnGuardarAsignadoDpto').val() != 'undefined'){
  //   verBtnGuardarAsigDpto = parseInt(JQ('#verBtnGuardarAsignadoDpto').val());
  //   if(verBtnGuardarAsigDpto==1){
  //     JQ(".button-apply").hide();
  //     JQ(".button-save").hide();
  //     JQ(".button-save-new").hide();
  //   }
  // }


    // JQ('input[name=\"fracc_activo\"]').click(function() {
    //     var v = JQ(this).val();
    //     var value = (v == 1) ? '0' : '1';
    //     JQ('#fracc_activo').val(value);
    // });

    // //Abrir calendario
    // JQ("#fechaNac").click(function (){
    //     jQuery('#fechaNac').datepicker("show");
    // });
    jQuery("#contFechaNac").datepicker({
      showOn: "button",
      buttonImage: JQ("#rutaCalendario").val(),
      buttonImageOnly: true,
      buttonText: "Select date",
      changeMonth: true,
      changeYear: true,
      maxDate: "0Y",
      minDate: "-100Y",
      yearRange: "-100:-0",
      defaultDate: JQ("#fechaNac").val(),
      onSelect: function(dateText, inst){
        JQ("#fechaNac").val(JQ(this).val());
      }
     });

     jQuery("#contPuntosHasta").datepicker({
      showOn: "button",
      buttonImage: JQ("#rutaCalendario").val(),
      buttonImageOnly: true,
      buttonText: "Select date",
      changeMonth: true,
      changeYear: true,
      // maxDate: "0Y",
      minDate: "0Y",
      //minDate: "-100Y",
      yearRange: "-100:+4",
      defaultDate: JQ("#puntosHasta").val(),
      onSelect: function(dateText, inst){
        JQ("#puntosHasta").val(JQ(this).val());
      }
     });


    //Comprobar rfc del prospectador
    /*JQ("#RFC").change(function (){
        if(JQ("#NSS").val()!=""){
          JQ("#NSS").change();
        }
    });*/
    // JQ("#NSS").change(function (){
    JQ("#RFC").change(function (){
       var loading = JQ('#loading_img').val(); //obtener imagen del loading
       JQ("#rfc_duplicado").val(0);
       // JQ("#alrduplicado").html("");
       // JQ("#alrduplicado").hide();
       JQ("#alrduplicado").remove();
       console.log(JQ('#RFC').val());

       rfc = JQ('#RFC').val();
       dig = rfc.substr(-20,10);
       console.log(dig);
       var res = rfc.split(dig);
       console.log(res[1]);
       //obt gerente
       var idGte = JQ('#idGte').val();
      //  console.log(idGte);
      //  return false;
       if(dig.length >= 10){
        params = {
            tarea: "comprobarRFC",
            //rfc: JQ('#RFC').val(),
            rfc: dig,
            idGte:idGte
        };
        //addInfo = JQ(this).parent().find(".addInfo"); //Obtener el div padre y buscar la clase addInfo
        //addInfo.html(loading); //Agregar loading

        ajaxGlobal(params, function(data){
            console.log(data);
            //addInfo.html(""); //Agregar loading
            if(data.result==true){
              var msg = JQ("#msg_duplicado").val();
              // msg = 'El prospecto ya existe en la base de datos, se enviará a revisión y de confirmarse la duplicidad se dará de baja.';
              //addInfo.html('<div class="alert alert-info text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>'); //Agregar mensaje

              JQ(".span12").append('<div id="alrduplicado"></div>'); //Agregar div en la botonera
              JQ("#alrduplicado").html('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>'); //Agregar mensaje
              JQ("#rfc_duplicado").val(1); //Ya existe en db aplicar regla
              JQ("#alrduplicado").show();
              JQ("#gtvId_rfc").val(data.gteigual.parametro);
              alert(msg);
            }else{
              JQ("#rfc_duplicado").val(0); //Ya existe en db aplicar regla
              JQ("#gtvId_rfc").val(0);
            }
        });
      }
    });

    //>>>Inicio de script relacionados al grid de eventos y comentario
    //Cambiar segun el tipo seleccionado
    JQ("#filter_tipo").change(function (){
        JQ("#formgrid_evcom").submit();
    });
    //Para agregar el comentario
    JQ(".selProspectoComEdit").click(function(){
      JQ('#popup_agregrarcomentario').css('position','fixed');
      JQ('#form_agregar_comentario')[0].reset();
    });
    JQ("#form_agregar_comentario").validate({
        submitHandler: function(form) {
          showLoading("#btn_agregrarcomentario"); //mostrar loading
          form.submit();
      }
    });
    JQ(".selProspecto").click(function(){
      JQ('#popup_agregarevento').css('position','fixed');
      JQ('#form_agregar_evento')[0].reset();
      JQ("#ev_optrecordatorio").val(0);
      JQ('#cont_ev_tiempo').hide();
      JQ('#ev_tiempo').removeClass("required");
    });
    JQ("#form_agregar_evento").validate({
        submitHandler: function(form) {
          showLoading("#btn_agregarevento"); //mostrar loading
          form.submit();
        }
    });
    JQ('input[name=\"ev_optrecordatorio\"]').click(function() {
        var v = JQ(this).val();
        var value = (v == 1) ? '0' : '1';
        JQ('#ev_optrecordatorio').val(value);
        JQ('#ev_tiempo').val("");

        if(parseInt(value)==1){
          JQ('#cont_ev_tiempo').show();
          JQ('#ev_tiempo').addClass("required");
        }else{
          JQ('#cont_ev_tiempo').hide();
          JQ('#ev_tiempo').removeClass("required");
        }
    });
    //Fecha
    jQuery("#ev_fecha_linea").datepicker({
        showOn: "button",
        buttonImage: JQ("#rutaCalendario").val(),
        buttonImageOnly: true,
        buttonText: "Select date",
        changeMonth: true,
        changeYear: true,
        //maxDate: "0Y",
        minDate: "0Y",
        //yearRange: "-100:-0",
        yearRange: "-100:+1",
        onSelect: function(dateText, inst){
          JQ("#ev_fecha").val(JQ(this).val());
        }
    });
    //horas
    JQ('input#ev_hora').timepicker({
      //timeFormat: 'h:mm p',
      timeFormat: 'H:mm',
        interval: 15,
        minTime: '7',
        maxTime: '11:45pm',
        defaultTime: '11',
        startTime: '07:00',
        dynamic: false,
        dropdown: true,
        scrollbar: false
    });
    //>>>Fin de script relacionados al grid de eventos y comentario

    //>>Inicio Apartar propiedad
    JQ("#selAsigCasa").click(function(){
      JQ('#popup_asignarcasa').css('position','fixed');
      JQ('#form_asignarcasa')[0].reset();
      JQ('#edit_cont_dptos a span').html("");
      JQ("#asigcasa_fraccionamiento").val("");
    });
    JQ("#form_asignarcasa").validate({
        submitHandler: function(form) {
          if(JQ("#asigcasa_casadpto").val()!=""){
            showLoading("#btn_asignarcasa"); //mostrar loading
            form.submit();
          }
      }
    });
    jQuery("#asigcasa_casadpto").customselect();
    JQ("#asigcasa_fraccionamiento").change(function(){
        var loading = JQ('#loading_img').val(); //obtener imagen del loading
        params = {
            tarea: "obtenerDepartamentosDisponibles",
            idFracc: JQ(this).val(),
            idProspectador: JQ("#check_un").val(),
            idGteV: JQ("input[name=idGte]").val(),
        };
console.log(params);
        addInfo = JQ("#edit_cont_dptos_loading").parent().find(".addInfo"); //Obtener el div padre y buscar la clase addInfo
        addInfo.html(loading); //Agregar loading
        html = "";

        ajaxGlobal(params, function(data){
            console.log(data);
            addInfo.html(""); //Agregar loading
            if(data.result==true){
               html += '<select name="asigcasa_casadpto" id="asigcasa_casadpto" class="required custom-select">';
                  html += '<option value="">--Seleccionar--</option>';
                  JQ(data.arrDptos).each(function(index, val){
                      html += '<option value="'+val.idDepartamento+'">'+val.numero+'</option>';
                  });
                html += '</select>';
            }else{
               html += '<select name="asigcasa_casadpto" id="asigcasa_casadpto" class="required custom-select">';
                html += '<option value="">--Seleccionar--</option>';
               html += '</select>';
            }
            html += '<div id="edit_cont_dptos_loading"><div class="addInfo" style="display:inline-block;"></div></div>';
            JQ("#edit_cont_dptos").html(html);
            html = "";
            jQuery("#asigcasa_casadpto").customselect();
        });
    });
    //>>Fin Apartar propiedad

    //Liberar casa desde el boton en la edicion de prospecto
    JQ("#selLiberarCasa").click(function(){
      var r = confirm("¿Realmente desea liberar la casa?");
      if (r == true) {
        Joomla.submitbutton('prospecto.liberarcasa');
      }
    });
    //Fin liberar casa desde el boton en la edicion de prospecto

    //>>Inicio no proceder
    JQ("#selNoProcede").click(function(){
      JQ('#popup_noprocede').css('position','fixed');
      JQ('#form_noprocede')[0].reset();
    });
    JQ("#form_noprocede").validate({
        submitHandler: function(form) {
          //setLoading($(form).attr('id'));
          showLoading("#btn_noprocede"); //mostrar loading
          form.submit();
      }
    });
    //>>Fin no proceder
    JQ(".idSelResumen").click(function(){
      JQ('#popup_resumenprospecto').css('position','fixed');
      idProsSel = JQ(this).attr("idPros");
      JQ("#contResumenProspecto").html(JQ("#idResumen_"+idProsSel).clone());
      JQ("#contResumenProspecto #idResumen_"+idProsSel).show();
    });

    //>>> Inicio Validar el prospecto (Esta script solo esta disponible para los gerentes ya que los agentes no ven el apartado de repetidos)
    JQ("#form_rep_prospecto_repetido").validate({
          submitHandler: function(form) {
            showLoading("#btn_validar_prospecto_repetido"); //mostrar loading
            form.submit();
        }
    });
    //Marcar como valido desde un enlace
    JQ(".selValidarProspectoRepetidoLink").click(function(){
      JQ('#popup_validar_prospecto_repetido').css('position','fixed');
      JQ('#arrRepetidoId').val(""); //limpiar hidden
      JQ("#rep_usuario").val("");
      JQ("#rep_comentario").val("");

      JQ('#arrRepetidoId').val(JQ(this).attr("idPros")); //agregar ids seleccionados
      JQ('#addHiddenUser').html('');
    });
    //>>> Fin validar el prospecto

    //>>>
    //>>>Poner formato a los montos cuando escribimos
    //>>>
    JQ('input.fmonto').blur(function(){
        jQuery('input.fmonto').formatCurrency();
    });
    //Convertir el texto ingresado al texto en mayuscula
    JQ('.mayuscula').keyup(function() {
      JQ(this).val(JQ(this).val().toUpperCase());
    });
    //Para la fecha de nacimiento
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
     if(dd<10){ dd='0'+dd }
     if(mm<10){ mm='0'+mm }
     today = yyyy+'-'+mm+'-'+dd;
    // document.getElementById("fechaNac").setAttribute("max", today);
    //fin para la fecha de nacimiento


    // Imp. 11/10/21, Carlos => Inicio expediente digital
    JQ(".selLinkAbrir").click(function(){
      let id = JQ(this).attr("id");
      showLoading2("#"+id); //mostrar loading
      JQ(this).attr("href", "javascript:void(0);");
      let idProspecto = accounting.unformat(JQ(this).attr("idProspecto"));
      let idDatoGeneral = accounting.unformat(JQ(this).attr("idDatoGeneral"));
      let tipoEnlace = accounting.unformat(JQ(this).attr("tipoEnlace"));

      // Buscar si tiene enlace
      let params = {
            idProspecto:idProspecto,
            idDatoGeneral:idDatoGeneral,
            tipoEnlace:tipoEnlace,
          };
          // console.log(params);
          // return false;
        fAjax("buscarenlacedigital", params, function(data){
          console.log(data);
          hideLoading("#"+id);
          let enlace = "";
          if(data.result==true){
            switch(tipoEnlace) {
            case 1: enlace = checkNulo(data.datosEnlace.linkGeneral); break;
                case 2: enlace = checkNulo(data.datosEnlace.linkContrato); break;
              case 3: enlace = checkNulo(data.datosEnlace.linkEscrituras); break;
              case 4: enlace = checkNulo(data.datosEnlace.linkEntregas); break;
          }

          if(enlace!=""){
            window.open(enlace, '_blank');
          }else{
            alertify.error("No existe enlace para abrir");
          }
          }else{
            alertify.error("No existe enlace para abrir");
          }
        });
    });

    JQ(".selLink").click(function(){
      JQ('#popup_links').css('position','fixed');
      JQ("#enlaceDigital").val("");
      let idProspecto = accounting.unformat(JQ(this).attr("idProspecto"));
      let idDatoGeneral = accounting.unformat(JQ(this).attr("idDatoGeneral"));
      let tipoEnlace = accounting.unformat(JQ(this).attr("tipoEnlace"));

      JQ("#hIdProspecto").val(idProspecto);
      JQ("#hIdDatoGeneral").val(idDatoGeneral);
      JQ("#hTipoEnlace").val(tipoEnlace);
      // console.log(idProspecto, idDatoGeneral, tipoEnlace);
      // return false;

      // Buscar si tiene enlace
      let params = {
            idProspecto:idProspecto,
            idDatoGeneral:idDatoGeneral,
            tipoEnlace:tipoEnlace,
          };
          // console.log(params);
          // return false;
      fAjax("buscarenlacedigital", params, function(data){
        // console.log(data);
        let enlace = "";
        let idEnlace = 0;
        if(data.result==true){
          idEnlace = data.datosEnlace.idEnlace;
          switch(tipoEnlace) {
          case 1: enlace = data.datosEnlace.linkGeneral; break;
              case 2: enlace = data.datosEnlace.linkContrato; break;
            case 3: enlace = data.datosEnlace.linkEscrituras; break;
            case 4: enlace = data.datosEnlace.linkEntregas; break;
        }
        }
        JQ("#enlaceDigital").val(enlace);
        JQ("#idEnlace").val(idEnlace);
      });
    });

    JQ("#form_agregarenlace").validate({
        submitHandler: function(form) {
          showLoading2("#btn_aceptar_enlace"); //mostrar loading

          let params = {
            idEnlace:JQ("#idEnlace").val(),
            idProspecto:JQ("#hIdProspecto").val(),
            idDatoGeneral:JQ("#hIdDatoGeneral").val(),
            tipoEnlace:JQ("#hTipoEnlace").val(),
            link:JQ("#enlaceDigital").val(),
            vistaInterna:1 //Solo aplica cuando se actualiza desde la edicion de (prospectos o CRM)
          };
          // console.log(params);
          // return false;

          fAjax("agregarenlace", params, function(data){
            console.log(data);
            jQuery('#popup_links').modal('hide');

            hideLoading("#btn_aceptar_enlace");
            if(data.result){
              alertify.success("El registro fue actualizado");

              if(typeof(data.datosEnlace.linkGeneral) != "undefined"){
                JQ("#expGeneral").val(checkNulo(data.datosEnlace.linkGeneral));
              }
            }else{
              alertify.error("No fue posible editar en registro");
            }
          });
      }
    });
    // Fin expediente digital

});

Joomla.submitbutton = function(task)
{
        if (task == '')
        {
                return false;
        }
        else
        {
                var isValid=true;
                var action = task.split('.');

                if (action[1] != 'cancel' && action[1] != 'close' && action[1] != 'cancelYEscritorio' && action[1] != 'cancelYListarEventos' && action[1] != 'cancelDos')
                {
                        var forms = $$('form.form-validate');
                        for (var i=0;i<forms.length;i++)
                        {
                                if (!document.formvalidator.isValid(forms[i]))
                                {
                                        isValid = false;
                                        break;
                                }
                        }
                }else{
                  var controles = ['input', 'select', 'textarea'];
                  JQ(controles).each(function(index, val){
                    JQ(val).removeClass('required');
                    JQ(val).removeAttr("required");
                  });
                }

                if (isValid)
                {
                        Joomla.submitform(task);
                        return true;
                }
                else
                {
                        return false;
                }
        }
}


//metodo ajax para obtener datos desde la db
function ajaxGlobal(params, response){
    var path = JQ('#path').val()+params.tarea;
    error = {result: 'error'};
    console.log(path);

    JQ.ajax({
        type: 'POST',
        url: path,
        data: params,
        beforeSend: function(){
            // JQ('#loadingRFC').html(loading);
        },
        success: function (html) {
            var result = JQ(html).find('response').html();
            var obj = JSON.parse(result);
            response(obj);
        },
        error: function () {
          response(error);
        }
    });
}

//Metodo para mostrar loading al presionar sobre el boton enviar de formulario
function showLoading(target){
  var loading = JQ('#loading_img').val(); //obtener imagen del loading
  // addInfo = JQ(target).parent().find(".addInfo"); //Obtener el div padre y buscar la clase addInfo
  // addInfo.html(loading); //Agregar loading
  addInfo = JQ(target).parent();
  addInfo.html('<div class="addInfo" style="display:inline-block;">'+loading+'</div>'); //Agregar loading
  JQ(target).hide();
}

//Metodo para mostrar loading al presionar sobre el boton enviar de formulario
function showLoading2(target){
  var loading = JQ('#loading_img').val(); //obtener imagen del loading
  addInfo = JQ(target).parent();
  addInfo.append('<div class="addInfo" style="display:inline-block;">'+loading+'</div>'); //Agregar loading
  JQ(target).hide();
}

//Metodo para ocultar loading al presionar sobre el boton enviar de formulario
function hideLoading(target){
  JQ(".addInfo").remove();
  JQ(target).show();
}

//comprobar si es nulo o vacio una cadena
function checkNulo(cadena){
    cadena = JQ.trim(cadena);
    if(cadena==null || cadena==""){
        return "";
    }else{
        return cadena;
    }
}

/**
  * Metodos ajax con koolphp
*/
function ajaxKoolObtUsuariosXGte(params){
    console.log(params);
    switch (params.ctr) {
      case 1: var html = koolajax.callback(obtAgtVentasSu(params.val)); break;
      case 2: var html = koolajax.callback(obtProspectadorSu(params.val)); break;
    }
    console.log(html);
    setTimeout(function(){
      JQ(params.contenedor).empty();
      JQ(params.contenedor).html(html);
    }, 700);
}

//Imp. 07/10/21, Carlos Metodo que se implemento para pasar los parametros por ajax
function fAjax(ctr, data, response){
  var path = JQ('#pathExpDig').val()+ctr;
  // console.log(path);
  // return false;
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