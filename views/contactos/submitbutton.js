JQ(document).ready(function(){
	// console.log(JQ("#estatusId").val());
	//ocultar opcion prospecto (5) ya que en esta vista no tendra ninguna accion por realizar
	setTimeout(function(){
		let valOpc = 5;
		JQ("#filter_estatus option[value=" + valOpc + "]").hide();
	}, 400);

	JQ('.button-delete').attr('onclick','').unbind('click');
	JQ('.button-delete').click(function(){
	   if (document.adminForm.boxchecked.value==0){
           alert('Please first make a selection from the list');
       }else{
       	   	var r = confirm("Esta seguro de borrar los registros seleccionados");
			if(r==true) {
				Joomla.submitbutton('prospectos.borrarProsRepetidos');
			}else{
				return false;
			}
       }
	});

	// Agregar accion
	JQ(".selAgregarAccion").click(function(){
		JQ('#popup_agregaraccion').css('position','fixed');
		JQ('#arrIdCont').val(""); //limpiar hidden
		JQ("#agaccion_accion").val("");

		//Resetear input cheched
		JQ('input:checked').prop('checked', false);
		document.adminForm.boxchecked.value = 0;
	    JQ('#arrIdCont').val(JQ(this).attr("idCont")); //agregar id seleccionado
	    JQ('#idAgtVentasNA').val(JQ(this).attr("idAgt")); //agregar id agente de ventas
	});
	JQ("#form_agregaraccion").validate({
        submitHandler: function(form) {
        	showLoading("#btn_agregaraccion"); //mostrar loading
        	form.submit();
    	}
    });


	// Imp. 02/10/20
	var arrUnicoGtesVentas = function(col) {
		let uniqsIds = col.filter(function(item, index, array) {
		  return array.indexOf(item) === index;
		})
		return uniqsIds;
	}

    //Para la asignacion de agentes(asesor)
	JQ(".selAsignar").click(function(){
		JQ('#popup_asignar').css('position','fixed');
		JQ('#arrIdsContactos').val(""); //limpiar hidden
		JQ("#asig_agtventas").val("");

		if (document.adminForm.boxchecked.value==0){
           alert('Please first make a selection from the list');
           return false;
	    }else{
	        var searchIDs = JQ('input:checked').map(function(){
	      		return JQ(this).val();
		    });
		    JQ('#arrIdsContactos').val(searchIDs.get().join(",")); //agregar ids seleccionados

		    //Imp. 02/10/20
		    //Obtener los ids de los gerentes para validar si continua o no el proceso
		    let colIdsGtesVenta = [];
		    JQ.each(searchIDs.get(), function (ind, idContacto) {
		    	colIdsGtesVenta.push(JQ("#idGteVentas_"+idContacto).val());
	        });

		    // Vuelve unico el arreglo para validar si los contactos seleccionados tienen diferentes gerencias
	        colIdsGtesVenta = arrUnicoGtesVentas(colIdsGtesVenta);
	        // console.log(colIdsGtesVenta);
	        if(colIdsGtesVenta.length>1){
	        	alert("No puede reasignar los contactos(s) porque pertenecen a diferentes gerencias.");
	        	return false;
	        }

	        //Continua ya que la gerencia es la misma
	        let idGteVenta = colIdsGtesVenta[0];
	        // console.log(idGteVenta);
	        //obtener los datos para crear opciones
	        let colAsesoresXGteTmp = JSON.parse(arrColAsesoresXGte);
	        // console.log(colAsesoresXGteTmp[idGteVenta]);
	        let option = '';
	        option += '<option value="">--Seleccionar--</option>';
			JQ.each(colAsesoresXGteTmp[idGteVenta], function (ii, elem) {
		    	// console.log(elem.nombre, elem.usuarioIdJoomla);
		    	option += '<option value="'+elem.usuarioIdJoomla+'">'+elem.nombre+'</option>';
		    	// option += '<option value="'+elem.idDato+'">'+elem.nombre+'</option>';
	        });
	        JQ("#asig_agtventas").html(option);
	        // // <option value="40">MARCOS SOLIS ALVARADO</option>

	        //Obtener los ids de los agentes
	        let colIdsAgtesVenta = [];
	        JQ.each(searchIDs.get(), function (ind, idContacto2) {
		    	colIdsAgtesVenta.push(JQ("#idAgtVentas_"+idContacto2).val());
	        });
	        JQ('#arrIdsAsesores').val(colIdsAgtesVenta); //agregar ids gerentes seleccionados
	        console.log(colIdsAgtesVenta);
		}
	});
	JQ("#form_asignarcontactosasesor").validate({
        submitHandler: function(form) {
        	showLoading("#btn_asignarContactoAsesor"); //mostrar loading
        	form.submit();
    	}
    });

    //Para la asignacion de gerentes y asesor
    JQ(".selAsignarGerente").click(function(){
		JQ('#popup_asignar_gerente').css('position','fixed');
		JQ('#arrIdsContactos2').val(""); //limpiar hidden
		JQ("#nombreGteJoomlaVentas").val("");
		JQ("#asig_agtventas2").val("");
		JQ("#arrIdsGerentes2").val("");
		JQ("#arrIdsAsesores2").val("");

		if (document.adminForm.boxchecked.value==0){
           alert('Please first make a selection from the list');
           return false;
	    }else{
	        var searchIDs = JQ('input:checked').map(function(){
	      		return JQ(this).val();
		    });
		    JQ('#arrIdsContactos2').val(searchIDs.get().join(",")); //agregar ids seleccionados

		    //Imp. 09/10/20
		    //Obtener los ids de los gerentes
		    let colIdsGtesVenta = [];
		    JQ.each(searchIDs.get(), function (ind, idContacto) {
		    	colIdsGtesVenta.push(JQ("#idGteVentas_"+idContacto).val());
		    	// colIdsGtesVenta.push(idContacto+"|"+JQ("#idGteVentas_"+idContacto).val());
	        });
	        JQ('#arrIdsGerentes2').val(colIdsGtesVenta); //agregar ids gerentes seleccionados

	        //Obtener los ids de los agentes
	        let colIdsAgtesVenta = [];
	        JQ.each(searchIDs.get(), function (ind, idContacto2) {
		    	colIdsAgtesVenta.push(JQ("#idAgtVentas_"+idContacto2).val());
	        });
	        JQ('#arrIdsAsesores2').val(colIdsAgtesVenta); //agregar ids gerentes seleccionados

	        // console.log(searchIDs.get());
	        // console.log(colIdsGtesVenta);
	        // console.log(colIdsAgtesVenta);
		}
	});
	JQ("#form_asignarcontactosgerente").validate({
        submitHandler: function(form) {
        	showLoading("#btn_asignarContactoGteAsesor"); //mostrar loading
        	form.submit();
    	}
    });

    // Para descartar los contactos seleccionados
    JQ(".selDescartar").click(function(){
    	JQ('#popup_descartar').css('position','fixed');
    	JQ("#ev_comentariodescartar").val("");
    	JQ("#motivorechazo").val("");

		if (document.adminForm.boxchecked.value==0){
           alert('Please first make a selection from the list');
           return false;
	    }else{
	        var searchIDs = JQ('input:checked').map(function(){
	      		return JQ(this).val();
		    });
		    JQ('#arrIdsContactos3').val(searchIDs.get().join(",")); //agregar ids seleccionados
		}
    });
    JQ("#form_agregar_comentario_descartar").validate({
        submitHandler: function(form) {
        	showLoading("#btn_descartar"); //mostrar loading
        	form.submit();
        	console.log(form);
    	}
    });

    //Imp. 06/10/20
    //Obtener el texto del motivo seleccionado
    JQ("#motivorechazo").change(function(){
    	let id = JQ(this).val();

    	JQ("#ev_comentariodescartar").val("");
    	if(id!=""){
    		let colMotivosRechazo = JSON.parse(arrColMotivosRechazo);
    		JQ("#ev_comentariodescartar").val(colMotivosRechazo[id].texto);
    	}
    });


    // Imp. 01/10/20
    // cambiar estatus
	JQ(".selCambiarEstatus").click(function(){
		JQ('#popup_cambiarestatus').css('position','fixed');
		JQ('#arrIdsContactos4').val(""); //limpiar hidden
		JQ("#estatusContactoId").val("");

		//Resetear input cheched
		JQ('input:checked').prop('checked', false);
		document.adminForm.boxchecked.value = 0;
	    JQ('#arrIdsContactos4').val(JQ(this).attr("idCont")); //agregar id seleccionado
	    // JQ('#idAgtVentasNA').val(JQ(this).attr("idAgt")); //agregar id agente de ventas
	});
	JQ("#form_cambiarestatus").validate({
        submitHandler: function(form) {
        	showLoading("#btn_cambiarestatus"); //mostrar loading
        	form.submit();
    	}
    });


 	 //Obtener opciones
	  JQ("#estatusContactoId").change(function (){
	    let idEstatus = JQ(this).val();

	    JQ("#motivo_descartado").val("");
	    JQ("#comentario_descartado").val("");

	    // Opcion descartado
	    if(idEstatus==4){
	      JQ("#cont_motivocomentario_descartado").show();
	      JQ("#motivo_descartado").addClass("required");
	      JQ("#comentario_descartado").addClass("required");
	    }else{
	      JQ("#motivo_descartado").val("");
	      JQ("#motivo_descartado").removeClass("required");
	      JQ("#comentario_descartado").val("");
	      JQ("#comentario_descartado").removeClass("required");
	      JQ("#cont_motivocomentario_descartado").hide();
	    }

	    // Mandar a prospecto
	    JQ("#mandarAProspecto").val(0);
	    if(idEstatus==5){
	      var r = confirm("¿Realmente desea mandar a prospecto el contacto?");
	      if (r == true) {
	        // Joomla.submitbutton('prospecto.liberarcasa');
	        JQ("#mandarAProspecto").val(1);
	      }else{
	        JQ("#estatusContactoId").val("");
	        return false;
	      }
	    }
	  });

  	//Imp. 22/03/21
    //Obtener el texto del motivo seleccionado
    JQ("#opc_motivo_descartado").change(function(){
    	let id = JQ(this).val();

    	JQ("#motivo_descartado").val("");
    	if(id!=""){
    		let colMotivosRechazo = JSON.parse(arrColMotivosRechazo);
    		JQ("#motivo_descartado").val(colMotivosRechazo[id].texto);
    	}
    });



	//Para los eventos
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

	// JQ('#popup_agregarevento').css('display','none');
	JQ(".selProspecto").click(function(){
		JQ('#popup_agregarevento').css('position','fixed');

		JQ("#ev_idPros").val(0);
		JQ("#ev_idPros").val(JQ(this).attr("idPros"));

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

	//Fecha
    JQ("#ev_fecha_linea").datepicker({
      	  showOn: "button",
	      buttonImage: JQ("#rutaCalendario").val(),
	      buttonImageOnly: true,
	      buttonText: "Select date",
	      changeMonth: true,
	      changeYear: true,
	      //maxDate: "0Y",
	      minDate: "0Y",
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


    JQ(".selAsigAgteVentasLink").click(function(){
		JQ('#popup_asignar').css('position','fixed');
		JQ('#arrIdPros').val(""); //limpiar hidden
		JQ("#asig_agtventas").val("");
		//Resetear input cheched
		JQ('input:checked').prop('checked', false);
		document.adminForm.boxchecked.value = 0;
	    JQ('#arrIdPros').val(JQ(this).attr("idPros")); //agregar id seleccionado
	});

	//Para agregar el comentario
    JQ(".selProspectoCom").click(function(){
		JQ('#popup_comentario').css('position','fixed');
		JQ("#com_idPros").val(0);
		JQ("#com_idPros").val(JQ(this).attr("idPros"));
		JQ('#form_agregar_comentario')[0].reset();
	});
	JQ("#form_agregar_comentario").validate({
        submitHandler: function(form) {
        	//setLoading($(form).attr('id'));
        	form.submit();
    	}
    });


	//Para la asignacion de gerentes de venta (lo hace prospeccion)
	JQ(".selAsignarGteVentas").click(function(){
		JQ('#popup_asignargteventas').css('position','fixed');
		JQ('#arrIdProsGteV').val(""); //limpiar hidden
		JQ("#asiggtev_gteventas").val("");

		if (document.adminForm.boxchecked.value==0){
           alert('Please first make a selection from the list');
           return false;
	    }else{
	        var searchIDs = JQ('input:checked').map(function(){
	      		return JQ(this).val();
		    });
		    JQ('#arrIdProsGteV').val(searchIDs.get().join(",")); //agregar ids gte ventas seleccionados
		}
	});
	JQ(".selAsigGteVentasLink").click(function(){
		JQ('#popup_asignargteventas').css('position','fixed');
		JQ('#arrIdProsGteV').val(""); //limpiar hidden
		JQ("#asiggtev_gteventas").val("");

		//Resetear input cheched
		JQ('input:checked').prop('checked', false);
		document.adminForm.boxchecked.value = 0;
	    JQ('#arrIdProsGteV').val(JQ(this).attr("idPros")); //agregar id seleccionado
	});
	JQ("#form_asignargteventas").validate({
        submitHandler: function(form) {
        	showLoading("#btn_asignargteventas"); //mostrar loading
        	form.submit();
    	}
    });


	//Marcar como valido un prospectador siendo prospeccion
	JQ(".selValidarProspectoRepetido").click(function(){
		JQ('#popup_validar_prospecto_repetido').css('position','fixed');
		JQ('#arrRepetidoId').val(""); //limpiar hidden
		JQ("#rep_usuario").val("");
		JQ("#rep_comentario").val("");

		if (document.adminForm.boxchecked.value==0){
           alert('Please first make a selection from the list');
           return false;
	    }else{
	        var searchIDs = JQ('input:checked').map(function(){
	      		return JQ(this).val();
		    });
		    JQ('#arrRepetidoId').val(searchIDs.get().join(",")); //agregar ids gte ventas seleccionados
		}
	});
	JQ("#form_rep_prospecto_repetido").validate({
        submitHandler: function(form) {
        	showLoading("#btn_validar_prospecto_repetido"); //mostrar loading
        	form.submit();
    	}
    });
    JQ(".borrar_pros").click(function(){
    	idPros = parseInt(JQ(this).attr("idPros"));
    	JQ("#borrar_prosp").val(0);
    	var r = confirm("Esta seguro de borrar esta fila");
		if(r==true) {
			JQ("#borrar_prosp").val(idPros);
			Joomla.submitbutton('prospectos.borrarProsRepetidos');
		}
    });
    //Marcar como valido desde un enlace
    JQ(".selValidarProspectoRepetidoLink").click(function(){
		JQ('#popup_validar_prospecto_repetido').css('position','fixed');
		JQ('#arrRepetidoId').val(""); //limpiar hidden
		JQ("#rep_usuario").val("");
		JQ("#rep_comentario").val("");

		valIdAgteVenta = parseInt(JQ(this).attr("idAgteVenta"));
		if(valIdAgteVenta == 0){
			JQ('#arrRepetidoId').val(JQ(this).attr("idPros")); //agregar ids seleccionados
			JQ('#addHiddenUser').html('');
		}else{
			console.log(valIdAgteVenta);
			JQ('#arrRepetidoId').val(JQ(this).attr("idPros")); //agregar ids seleccionados
			JQ('#rep_usuario').val(parseInt(JQ(this).attr("idAgteVenta"))); //setear el idDato del catalogo de datos
			//JQ('#rep_usuario').attr('disabled', true);
			JQ('#addHiddenUser').html('<input type="hidden" name="rep_usuario" value="'+valIdAgteVenta+'" />');
		}
	});


	//Filtro puntos hasta
    JQ("#filter_puntoshasta").datepicker({
          showOn: "button",
          buttonImage: JQ("#rutaCalendario").val(),
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          // maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",
          defaultDate: JQ("#filter_puntoshasta").val(),
          onSelect: function(dateText, inst){
            JQ("#filter_puntoshasta").val(JQ(this).val());
          }
    });
    JQ("#limpiarFiltros").click(function(){
      JQ("#filter_search").val("");
      JQ("#filter_tel").val("");
      JQ("#filter_email").val("");
      JQ("#filter_estatus").val("0");
      JQ("#filter_apellidos").val("");
      JQ("#filter_fuentes").val("");
      JQ("#filter_gerentes").val("");
      JQ("#filter_asesores").val("");
      this.form.submit();
    });


	/*JQ('input:checkbox').click(function(){
		textfechaAsig = JQ.trim(JQ(this).parent().parent().find("td.fechaAsig").html());
		console.log(textfechaAsig);
		if(textfechaAsig==""){
			JQ("#idPorVencer").val(1);
		}
	});*/
	JQ(".selProtegerPros").click(function(){
		JQ('#popup_protegerpros').css('position','fixed');
		JQ('#arrIdProsProt').val(""); //limpiar hidden
		JQ("#prot_tiempo").val("");

		if (document.adminForm.boxchecked.value==0){
		   JQ("#idPorVencer").val(0);
           alert('Please first make a selection from the list');
           return false;
	    }else{
	    	if(parseInt(JQ("#idPorVencer").val()) == 0){
	    		var searchIDs = JQ('input:checked').map(function(){
		      		return JQ(this).val();
			    });
			    JQ('#arrIdProsProt').val(searchIDs.get().join(",")); //agregar ids de los prospectos
			    console.log(searchIDs.get().join(","));
	    	}else{
	    		alert('No es posible proteger el(los) prospecto(s) seleccionado(s) porque alguno no tiene aún el agente de ventas asociado.');
           		return false;
	    	}
		}
	});
	JQ("#form_protegerpros").validate({
        submitHandler: function(form) {
        	showLoading("#btn_protegerpros"); //mostrar loading
        	form.submit();
    	}
    });


	//Tootip para la columna comentario
    JQ('[data-toggle="tooltip"]').tooltip();
    //Inicio para descargar prospectos por rango de fechas
    JQ(".selDescargaProspectos").click(function(){
		JQ('#popup_descargaprospectos').css('position','fixed');
	});
    JQ("#form_descargaprospectos").validate({
        submitHandler: function(form) {
        	showLoading("#btn_descargaprospectos"); //mostrar loading
        	form.submit();
        	setTimeout(function(){
        		jQuery('#popup_descargaprospectos').modal('hide');
        		JQ("#cont_btn_descargaprospectos").html('<button type="submit" class="btn btn-small button-new btn-success" id="btn_descargaprospectos">Aceptar</button>');
        	}, 1000);
    	}
    });
    //Fecha del
    JQ("#descargaProsp_del").datepicker({
          showOn: "button",
          buttonImage: JQ("#rutaCalendario").val(),
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          // maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",
          defaultDate: JQ("#descargaProsp_del").val(),
          onSelect: function(dateText, inst){
            JQ("#descargaProsp_del").val(JQ(this).val());
          }
    });
    //Fecha Hasta
    JQ("#descargaProsp_hasta").datepicker({
          showOn: "button",
          buttonImage: JQ("#rutaCalendario").val(),
          buttonImageOnly: true,
          buttonText: "Select date",
          changeMonth: true,
          changeYear: true,
          // maxDate: "0Y",
          minDate: "-10Y",
          yearRange: "-10:+1",
          defaultDate: JQ("#descargaProsp_hasta").val(),
          onSelect: function(dateText, inst){
            JQ("#descargaProsp_hasta").val(JQ(this).val());
          }
    });
    //Fin para descargar prospectos por rango de fechas
    //Cambiar opcion del menu segun el grupo y dependiendo si el estatus esta vacio para ser aplicado
    if(JQ("#hidd_checkestatus").val() != null){
	 	hidd_checkestatus = parseInt(JQ("#hidd_checkestatus").val());
	 	hidd_grp = JQ("#hidd_grp").val();
	 	if(hidd_checkestatus==0){
	 	  //Si es prospectador cambia a por asignar
	 	  if(hidd_grp!=""){
	 	  	switch (hidd_grp) {
			    case 'todos': JQ("#filter_estatus").val(0); break;
			    case 'porasignar': JQ("#filter_estatus").val(2); break;
			    case 'asignados': JQ("#filter_estatus").val(1); break;
			}
	 	  }
	 	}
	}
 });


var format = function(num){
	var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
	if(str.indexOf(".") > 0) {
		parts = str.split(".");
		str = parts[0];
	}
	str = str.split("").reverse();
	for(var j = 0, len = str.length; j < len; j++) {
		if(str[j] != ",") {
			output.push(str[j]);
			if(i%3 == 0 && j < (len - 1)) {
				output.push(",");
			}
			i++;
		}
	}
	formatted = output.reverse().join("");
	return("$" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
};

//Metodo para mostrar loading al presionar sobre el boton enviar de formulario
function showLoading(target){
  var loading = JQ('#loading_img').val(); //obtener imagen del loading
  addInfo = JQ(target).parent();
  addInfo.html('<div class="addInfo" style="display:inline-block;">'+loading+'</div>'); //Agregar loading
  JQ(target).hide();
}

/**
  * Metodos ajax con koolphp
*/
function ajaxKoolObtUsuariosXGte(params){
	showLoading("#usuarioAgenteVentas"); //mostrar loading
    console.log(params);
    switch (params.ctr) {
      case 1: var html = koolajax.callback(obtAgtVentasSu(params.val)); break;
      // case 2: var html = koolajax.callback(obtProspectadorSu(params.val)); break;
    }
    // console.log(html);
    setTimeout(function(){
      JQ(params.contenedor).empty();
      JQ(params.contenedor).html(html);
    }, 700);
}

