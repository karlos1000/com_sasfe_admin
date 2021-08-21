JQ(document).ready(function(){   
	JQ('.button-delete').attr('onclick','').unbind('click');                                          
	JQ('.button-delete').click(function(){
	   if (document.adminForm.boxchecked.value==0){
           alert('Please first make a selection from the list');
       }else{
       	   	var r = confirm("Esta seguro de borrar los registros seleccionados");
			if(r==true) {				
				// Joomla.submitbutton('prospectos.borrarProsRepetidos');
			}else{
				return false;
			}
       }  
	});
   
/*
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
        	//setLoading($(form).attr('id'));
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
	      yearRange: "-100:-0",
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
        	//setLoading($(form).attr('id'));
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
        	//setLoading($(form).attr('id'));
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
        	//setLoading($(form).attr('id'));
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
			JQ('#arrRepetidoId').val(JQ(this).attr("idPros")); //agregar ids seleccionados
			JQ('#rep_usuario').val(parseInt(JQ(this).attr("idAgteVenta"))); //setear el idDato del catalogo de datos
			JQ('#rep_usuario').attr('disabled', true);
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
      this.form.submit();      
    });    
    JQ("input#filter_montocto1").keyup(function(e){    	
    	JQ(this).val(format(JQ(this).val()));    	
    });
    JQ("input#filter_montocto2").keyup(function(e){    	
    	JQ(this).val(format(JQ(this).val()));
    });

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
        	//setLoading($(form).attr('id'));
        	form.submit();
    	}
    });
*/

 });