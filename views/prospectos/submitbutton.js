JQ(document).ready(function(){
	console.log(JQ("#estatusId").val());
	/*
	if(JQ("#estatusId").val() == 2){
		document.getElementById("filter_estatus").selectedIndex=2;
		JQ("#filter_estatus option[value=]").hide();
		JQ("#filter_estatus option[value=0]").hide();
		JQ("#filter_estatus option[value=2]").hide();
	}else if(JQ("#estatusId").val() == 1){
		document.getElementById("filter_estatus").Index=3;
	}
	*/
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

    // Reemplazar evento onclick
   //  JQ('#toolbar-delete a').attr('onclick','').unbind('click');
   //  JQ('#toolbar-delete a').click(function(){
   //     if (document.adminForm.boxchecked.value==0){
   //         alert('Please first make a selection from the list');
   //     }else{
   //     	   	var r = confirm("Esta seguro de borrar los registros seleccionados");
			// if(r==true) {
			// 	JQ("#borrar_prosp").val(idPros);
			// 	Joomla.submitbutton('prospectos.borrarProsRepetidos');
			// }
   //         // var r=confirm('No es posible eliminar el elemento, al hacerlo se borrara los deparatamentos vinculados, ¿Realmente estás seguro?');
   //         // if (r==true){
   //         //     Joomla.submitbutton('catfraccionamientos.delete');
   //         // }else{
   //         //     return false;
   //         // }
   //     }
   //  });

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

	//Para la asignacion
	JQ(".selAsignar").click(function(){
		JQ('#popup_asignar').css('position','fixed');
		JQ('#arrIdPros').val(""); //limpiar hidden
		JQ("#asig_agtventas").val("");

		if (document.adminForm.boxchecked.value==0){
           alert('Please first make a selection from the list');
           return false;
	    }else{
	        var searchIDs = JQ('input:checked').map(function(){
	      		return JQ(this).val();
		    });
		    JQ('#arrIdPros').val(searchIDs.get().join(",")); //agregar ids seleccionados
		}
	});
	JQ("#form_asignarprospecto").validate({
        submitHandler: function(form) {
        	showLoading("#btn_asignar"); //mostrar loading
        	form.submit();
    	}
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

		// var searchIDs = JQ('input:checked').map(function(){
  // 			return JQ(this).val();
	 //    });
	 //    if(searchIDs.get().length>0){
	 //    	JQ('#arrRepetidoId').val(searchIDs.get().join(",")); //agregar ids gte ventas seleccionados
	 //    }else{
		//    alert('Please first make a selection from the list');
  //          return false;
	 //   	}

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
      JQ("#filter_montocto1").val("");
      JQ("#filter_montocto2").val("");
      JQ("#filter_puntoshasta").val("");
      JQ("#filter_tipocto").val("");
      JQ("#filter_estatus").val("0");
      JQ("#filter_apellidos").val("");
      JQ("#filter_rfc").val("");
      JQ("#filter_cel").val("");
      JQ("#filter_gerentes").val(""); //Imp. 23/08/21, Carlos
      this.form.submit();
    });
    JQ("input#filter_montocto1").keyup(function(e){
    	JQ(this).val(format(JQ(this).val()));
    });
    JQ("input#filter_montocto2").keyup(function(e){
    	JQ(this).val(format(JQ(this).val()));
    });

 //    // Try it yourself clicky demo:
	// var $demoValue = JQ('#filter_montocto1'),
	//     $demoResult = JQ('#filter_montocto1');

	// // $demoValue.bind('keydown keyup keypress focus blur paste change', function() {
	// $demoValue.bind('keydown keyup keypress paste change', function() {
 // 	    result = accounting.formatMoney(
 // 	    	$demoValue.val(),
 // 	    	"",
 // 	    	2,
 // 	    );
 // 	    // console.log(result);
 // 	    setTimeout(function(){ $demoResult.val(result); }, 1000);
	// });


	//Proteger un prospecto
	// setTimeout(function(){
		// JQ('input:checkbox').addClass("checkPorVencer");
		// JQ(document).find('input:checkbox').addClass('checkPorVencer');
	// }, 500);
	JQ('input:checkbox').click(function(){
		textfechaAsig = JQ.trim(JQ(this).parent().parent().find("td.fechaAsig").html());
		console.log(textfechaAsig);
		if(textfechaAsig==""){
			JQ("#idPorVencer").val(1);
		}
	});
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

	// Imp. 23/08/21, Carlos, Obt. los agentes dependiendo de la seleccion de los gerentes
	if(typeof JQ('#filter_gerentes').val() != 'undefined'){
	    JQ("#filter_gerentes").change(function (){
	    	let valSel = parseInt(JQ(this).val());
	    	let html = "";
	    	JQ("#filter_asesores").html("");
	    	let arrTest = [];
	    	console.log(valSel);

	      	// console.log(arrJsonAsesores);

	      	html += `<option value="" selected="selected">Gerentes (Todos)</option>`;
	      	JQ.each(arrJsonAsesores, function(i,v) {
			    if(parseInt(v.usuarioIdGteJoomla)==valSel){
	      			html += `<option value="${v.usuarioIdJoomla}">${v.nombre}</option>`;
	      			// arrTest[i] = v;
	      		}
			});
			// console.log(arrTest);
			JQ("#filter_asesores").html(html);
			JQ("#filter_asesores").val("");
	  	});
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