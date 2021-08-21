JQ(document).ready(function(){    
  //btn Cancel
  // JQ('#toolbar-cancel').attr('onclick','').unbind('click');
  // JQ(document).on("click", '#toolbar-cancel', function(evt){
  //     console.log("cancel");
  //     evt.preventDefault();
  //     return false;
  // });
  // JQ('#toolbar-delete a').click(function(){
  // JQ(document).on("click", ".button-cancel", function(){
  //   console.log("cancel");
  //   validarForm();
  // });
  //  //Comprobar si todo esta completo para mandar mensaje
  //   document.getElementById('adminForm').addEventListener('submit', function(evt){        
  //     console.log(this.id);
  //     var idsProspectos = JQ("#idsProspectos").val();
  //     // console.log(idsProspectos);
  //     if(idsProspectos==""){
  //         alert("Debe seleccionar al menos un usuario para enviar el mensaje");                    
  //         evt.preventDefault();
  //         // window.history.back();
  //     }
  //   });
  // JQ('.button-cancel').click(function (){  
  //   console.log("cancel");          
  //       var controles = ['input', 'select', 'textarea'];
  //       JQ(controles).each(function(index, val){
  //         JQ(val).removeClass('required');
  //         JQ(val).removeAttr("required");
  //       });
  //       Joomla.submitbutton('smsenviosreferidos.cancel');          
  // });

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

  //Para el listbox
  if(JQ(".duallb").length){
    var listaProspectos = JQ('select[name="prospectos"]').bootstrapDualListbox({        
        preserveSelectionOnMove: 'moved',
        moveOnSelect: false,        
    });
        
    //click move bootstrapDualListbox
    // JQ(document).on("click", ".move", function(){      
    //   console.log(JQ(".agentItems").val());      
    // });
                  
    JQ('#btn_buscarProsp').click(function (){        
        //Metodo que se implemento para pasar los parametros extras
        var metodo = "obtProspectosSMSSegunFiltro";
        var path = JQ('#path').val()+metodo;
        // var loading = JQ('#loading_img').val();  
        //Obtener parametros 
        var idAsesor = (JQ('#agtventas').val()!="")?JQ('#agtventas').val():""; 
        if(idAsesor==""){
          alert("Seleccionar un agente de ventas");
          return false;
        }
        JQ('#btn_buscarProsp').hide();
        JQ("#loading_btn_buscarProsp").show();        

        var idEstatus = (JQ('#estatus').val()!="")?JQ('#estatus').val():""; 
        var fechaDel = (JQ('#filter_fechaDel').val()!="")?JQ('#filter_fechaDel').val():""; 
        var fechaAl = (JQ('#filter_fechaAl').val()!="")?JQ('#filter_fechaAl').val():""; 
        var idFracc = (JQ('#idFracc').val()!="")?JQ('#idFracc').val():"";
        var params = {idAsesor:idAsesor, idEstatus:idEstatus, fechaDel:fechaDel, fechaAl:fechaAl, idFracc:idFracc};
        // console.log(params);

        ajaxPostData(path, params, function(data){
          // console.log(data);          
          if(data.success==true){
            var html = '';
            var option = '';
            JQ.each(data.datos, function(i,v){
              // console.log(v.idDatoCliente);              
              // console.log(v.cliente);
              // console.log(v.celular);
              // console.log("------");
              option += '<option value="'+v.idDatoCliente+'|'+v.celular+'|'+v.cliente+'|'+v.email+'">('+v.totalMsg+') '+v.cliente+ ' - '+v.celular+ '</option>';
            });

            html += '<div class="control-label">';        
              html += '<label for="prospectos">Prospectos:</label>';
            html += '</div>';
            html += '<select id="prospectos" name="prospectos" class="required duallb" multiple="multiple">';
              html += option;
            html += '</select>';
            JQ('#cont_lista_prospectos').html("");
            JQ('#cont_lista_prospectos').html(html);
            
            var listaProspectos = JQ('select[name="prospectos"]').bootstrapDualListbox({                      
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,            
            });
          }else{
            alert("No hay resultados");
          }
          JQ('#btn_buscarProsp').show();
          JQ("#loading_btn_buscarProsp").hide();
        });
    });
    
    //Obtener valores cada vez que cambia el listbox
    JQ(document).on("change", "#prospectos", function(){            
        // console.log(JQ(this).val());
        if(JQ(this).val()!=null){
            //total de usuarios a los que enviara el mensaje
            var arrUsuarios = JQ(this).val();
            var totalUsuarios = arrUsuarios.length;
            var credDisponibles = accounting.unformat(JQ("#credDisponibles").val());
            if(credDisponibles<totalUsuarios){
              JQ(".button-sendMessage").hide();              
              JQ("#cont_dinamico").html("No es posible enviar el mensaje, porque el n&uacute;mero de cr&eacute;ditos disponibles es menor a la cantidad de usuarios por recibir.");              
              jQuery('#modal_dinamico').modal('show');
            }else{
              JQ(".button-sendMessage").show();
            }            

            // JQ("#totalUsuariosEnviar").val(totalUsuarios);
            JQ("#idsProspectos").val(JQ(this).val());
        }else{
          JQ("#idsProspectos").val("");      
        }
    });
  }  

  
    //Al presionar la opción del mensaje se copiara automaticamente al textarea #mensaje                                 
    JQ("#preconf_msn").change(function () { 
      JQ("#mensaje").val(" ");
      var v = JQ("#preconf_msn").val();                        
      JQ("#mensaje").val(v);                   
      var idMsg = accounting.unformat(JQ('option:selected', this).attr('idMsg'));
      JQ("#preconfMsnId").val(idMsg);
    });  
  //Fin para el listbox
    
    //Setearel nombre del estatus
    JQ(document).on("change", "#estatus", function(){
      var idEstatus = JQ(this).val();
      if(idEstatus!=""){
        JQ("#nombreEstatus").val(JQ("#estatus option:selected").text());
      }else{
        JQ("#nombreEstatus").val("");
      }            
    });    
    

    //Si no tiene credito no mostrar boton de enviar mensaje
    var credDisponibles = accounting.unformat(JQ("#credDisponibles").val());    
    if(credDisponibles==0){
      JQ(".button-sendMessage").hide();      
    }

 });
 
// function validarForm(){
//   console.log("ok");
//   return false;
// }

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//>>>>>>>>>>>>>>>>>>>>METODOS GENERALES>>>>>>>>>>>>>>>>>>>>
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//metodo para abre llamadas ajax
function ajaxPostData(url, data, response){
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
      }
  });
}
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
                if (action[1] != 'cancel' && action[1] != 'close')
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