JQ(document).ready(function(){    
  JQ('.button-cancel').click(function (){            
        var controles = ['input', 'select', 'textarea'];
        JQ(controles).each(function(index, val){
          JQ(val).removeClass('required');
          JQ(val).removeAttr("required");
        });
        Joomla.submitbutton('smsenviosreferidos.cancel');          
  });
  
  //Aumentar el credito a los gerentes (referidos)
  JQ(document).on("change", ".masCredGerente", function(){
    var bolsaCreditos = accounting.unformat(JQ("#bolsaCreditos").val());    
    //Verifica que no este en 0 la bolsa para aumentar el credito
    if(bolsaCreditos<=0){
      JQ(this).val("");
      alert("No puede aumentar creditos la bolsa esta vacia");
      return false;
    }

    var idGerente = accounting.unformat(JQ(this).attr("idGte"));
    var creditoActual = accounting.unformat(JQ("#creditoIdGte_"+idGerente).val());
    var creditoAumentar = accounting.unformat(JQ(this).val());
    var totalCredito = creditoActual+(creditoAumentar);

    //Verifica que el credito de la bolsa no sea menor al que estan solicitando
    if(bolsaCreditos<creditoAumentar){
      JQ(this).val("");
      alert("No puede aumentar mas creditos de lo que contiene la bolsa");
      return false;
    }

    //Verificar que no pueda restar creditos mas creditos de los que tiene
    // var creditosRestar = creditoActual+(creditoAumentar);
    console.log(totalCredito);    
    if(totalCredito<0){
      JQ(this).val("");
      alert("No puede reducir mas creditos de los que tiene en actuales");
      return false; 
    }          

        
      //>>>>Salvar en db
      JQ("#cont_loading").show();      
      //Metodo que se implemento para pasar los parametros extras      
      var metodo = "salvarCreditos";
      var path = JQ('#path').val()+metodo;      
      //Obtener parametros     
      var params = {totalCredito:totalCredito, tipoProceso:0, usuarioId:idGerente, creditoRestar:creditoAumentar};

      ajaxPostData(path, params, function(data){
        // console.log(data);          
        if(data.success==true){
          console.log("ok");
          //Aumentar despues de salvar en db
          JQ("#creditoIdGte_"+idGerente).val(parseInt(totalCredito));
          JQ("#aumCreditoIdGte_"+idGerente).val("");   
          JQ("#bolsaCreditos").val(data.creditosReales);
          //Saber si esta inactivo para agregarlo al arreglo
          // var activoGte = accounting.unformat(JQ("#activoCreditoIdGte_"+idGerente).val());
          // if(activoGte==0 && totalCredito>0){            
          //   console.log("Agregar al arreglo # gte: "+idGerente+" aumenta:"+totalCredito);
          // }
        }else{
          alert("No fue posible salvar el credito");
        }        
        JQ("#cont_loading").hide();
      });
  });

  //Aumentar el credito a los agentes (promociones)
  JQ(document).on("change", ".masCredAsesor", function(){
    var bolsaCreditos = accounting.unformat(JQ("#bolsaCreditos").val());    
    if(bolsaCreditos<=0){
      JQ(this).val("");
      alert("No puede aumentar creditos la bolsa esta vacia");
      return false;
    }

    var idAsesor = accounting.unformat(JQ(this).attr("idAsesor"));
    var creditoActual = accounting.unformat(JQ("#creditoIdAsesor_"+idAsesor).val());
    var creditoAumentar = accounting.unformat(JQ(this).val());
    var totalCredito = creditoActual+(creditoAumentar);

    //Verifica que el credito de la bolsa no sea menor al que estan solicitando
    if(bolsaCreditos<creditoAumentar){
      JQ(this).val("");
      alert("No puede aumentar mas creditos de lo que contiene la bolsa");
      return false;
    }

    //Verificar que no pueda restar creditos mas creditos de los que tiene    
    console.log(totalCredito);    
    if(totalCredito<0){
      JQ(this).val("");
      alert("No puede reducir mas creditos de los que tiene en actuales");
      return false; 
    }  
    //return false;
        
      //>>>>Salvar en db
      JQ("#cont_loading").show();      
      //Metodo que se implemento para pasar los parametros extras      
      var metodo = "salvarCreditos";
      var path = JQ('#path').val()+metodo;      
      //Obtener parametros       
      var params = {totalCredito:totalCredito, tipoProceso:1, usuarioId:idAsesor, creditoRestar:creditoAumentar};

      ajaxPostData(path, params, function(data){
        // console.log(data);          
        if(data.success==true){
          console.log("ok");
          //Aumentar despues de salvar en db
          JQ("#creditoIdAsesor_"+idAsesor).val(parseInt(totalCredito));
          JQ("#aumCreditoIdAsesor_"+idAsesor).val("");          
          JQ("#bolsaCreditos").val(data.creditosReales);
        }else{
          alert("No fue posible salvar el credito");
        }        
        JQ("#cont_loading").hide();
      });
  });

  //Aumentar el credito a direccion (referidos y promociones)
  JQ(document).on("change", ".masCredDireccion", function(){
    var bolsaCreditos = accounting.unformat(JQ("#bolsaCreditos").val());    
    if(bolsaCreditos<=0){
      JQ(this).val("");
      alert("No puede aumentar creditos la bolsa esta vacia");
      return false;
    }
    var idDireccion = accounting.unformat(JQ(this).attr("idDireccion"));
    var creditoActual = accounting.unformat(JQ("#creditoIdDir_"+idDireccion).val());
    var creditoAumentar = accounting.unformat(JQ(this).val());
    var totalCredito = creditoActual+(creditoAumentar);
    //Verifica que el credito de la bolsa no sea menor al que estan solicitando
    if(bolsaCreditos<creditoAumentar){
      JQ(this).val("");
      alert("No puede aumentar mas creditos de lo que contiene la bolsa");
      return false;
    }
    //Verificar que no pueda restar creditos mas creditos de los que tiene    
    console.log(totalCredito);    
    if(totalCredito<0){
      JQ(this).val("");
      alert("No puede reducir mas creditos de los que tiene en actuales");
      return false; 
    }  
    // return false;
      //>>>>Salvar en db
      JQ("#cont_loading").show();      
      //Metodo que se implemento para pasar los parametros extras      
      var metodo = "salvarCreditos";
      var path = JQ('#path').val()+metodo;      
      //Obtener parametros       
      var params = {totalCredito:totalCredito, tipoProceso:2, usuarioId:idDireccion, creditoRestar:creditoAumentar};
      ajaxPostData(path, params, function(data){
        console.log(data);          
        if(data.success==true){
          console.log("ok");
          //Aumentar despues de salvar en db
          JQ("#creditoIdDir_"+idDireccion).val(parseInt(totalCredito));
          JQ("#aumCreditoIdDir_"+idDireccion).val("");          
          JQ("#bolsaCreditos").val(data.creditosReales);
        }else{
          alert("No fue posible salvar el credito");
        }        
        JQ("#cont_loading").hide();
      });
  });
  //Aumentar automatico y bolsa de credito
  JQ(document).on("change", ".masCredBolsaAutomatico", function(){
    var idCA = accounting.unformat(JQ(this).attr("idCA"));
    var creditoActual = 0;    
    var creditoAumentar = accounting.unformat(JQ(this).val());

    //bolsa
    if(idCA==1){
      creditoActual = accounting.unformat(JQ("#bolsaCreditos").val());
      var tipoProceso = 0;

      var totalCreditoTmp = creditoActual+(creditoAumentar);
      // console.log(totalCreditoTmp);    
      if(totalCreditoTmp<0){
        JQ(this).val("");
        alert("No puede reducir mas creditos de los que tiene en actuales");
        return false; 
      }
    }
    //Automatico
    if(idCA==2){
      var bolsaCreditos = accounting.unformat(JQ("#bolsaCreditos").val());    
      if(bolsaCreditos<=0){
        JQ(this).val("");
        alert("No puede aumentar creditos la bolsa esta vacia");
        return false;
      }

      //Verifica que el credito de la bolsa no sea menor al que estan solicitando
      if(bolsaCreditos<creditoAumentar){
        JQ(this).val("");
        alert("No puede aumentar mas creditos de lo que contiene la bolsa");
        return false;
      }

      creditoActual = accounting.unformat(JQ("#automaticos").val());
      var tipoProceso = 1;

      var totalCreditoTmp = creditoActual+(creditoAumentar);
      console.log(totalCreditoTmp);    
      if(totalCreditoTmp<0){
        JQ(this).val("");
        alert("No puede reducir mas creditos de los que tiene en actuales");
        return false; 
      }
    }
    
    var totalCredito = creditoActual+(creditoAumentar);
    // return false;
      
      //>>>>Salvar en db
      JQ("#cont_loading").show();        
      //Metodo que se implemento para pasar los parametros extras      
      var metodo = "salvarCreditosBolsaAutomaticos";
      var path = JQ('#path').val()+metodo;      
      //Obtener parametros       
      var params = {totalCredito:totalCredito, tipoProceso:tipoProceso, idCredito:idCA, creditoRestar:creditoAumentar};

      ajaxPostData(path, params, function(data){
        // console.log(data);          
        if(data.success==true){
          console.log("ok");
          //Aumentar despues de salvar en db    
          if(idCA==1){
            JQ("#bolsaCreditos").val(totalCredito);
            JQ("#aumentarBolsaCreditos").val("");            
          }
          if(idCA==2){
            JQ("#automaticos").val(totalCredito);  
            JQ("#aumentarAutomaticos").val("");
            JQ("#bolsaCreditos").val(data.creditosReales);
          }
        }else{
          alert("No fue posible salvar el credito");
        }        
        JQ("#cont_loading").hide();  
      });    
  });   
  //Activar o desactivar 
  // JQ('input[name=\"activo_sms\"]').click(function() {
    JQ(document).on("click", ".activo_sms", function(){  
        var v = JQ(this).val();
        var id = JQ(this).attr("id");
        var value = (v == 1) ? '0' : '1';                      
        if(id=="activo_automatico"){
          var tipoProceso = 1; //Para la tabla creditos 
          var idAct = 2;
        }else{
          var tipoProceso = 2; //Para la tabla creditos usuarios
          var idAct = accounting.unformat(JQ(this).attr("idActivo"));
        }
        console.log("--------");
        console.log(tipoProceso);
        // console.log(value);
        // console.log(id);
        console.log(idAct);
        //Actualizar estatus activo/inactivo        
        JQ("#cont_loading").show();
        //Metodo que se implemento para pasar los parametros extras      
        var metodo = "actualizarActivoCredito";
        var path = JQ('#path').val()+metodo;      
        //Obtener parametros       
        var params = {tipoProceso:tipoProceso, valor:value, idCredito:idAct};
        ajaxPostData(path, params, function(data){
          console.log(data);
          if(data.success==true){
            JQ('#'+id).val(value);
            //Quitar o agregar id del arreglo
            // console.log("Quitar del arreglo: "+idAct);
          }else{
            JQ('#'+id).prop('checked', false); 
            alert("No fue posible actualizar el estatus");
          }
          JQ("#cont_loading").hide();
        });            
    });  
    
 });
 

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