JQ(document).ready(function(){
    Number.prototype.formatMoney = function(c, d, t){
        var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
   };

    if(JQ('#dg_fApart').val()=='0'){
        JQ('#fApartado').attr('readonly', true);
        JQ("#fApartado_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#dg_fCierre').val()=='0'){
        JQ('#fCierre').attr('readonly', true);
        JQ("#fCierre_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#dg_fInsc').val()=='0'){
        JQ('#fInsc').attr('readonly', true);
        JQ("#fInsc_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#dg_fEstatus').val()=='0'){
        JQ('#fEstatus').attr('readonly', true);
        JQ("#fEstatus_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#dg_fEntrega').val()=='0'){
        JQ('#fEntrega').attr('readonly', true);
        JQ("#fEntrega_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#dg_reprog').val()=='0'){
        JQ('#fReprog').attr('readonly', true);
        JQ("#fReprog_dateopener").removeClass("kcdDateOpener");
    }

    if(JQ('#nom_ases_fPagoApar').val()=='0'){
        JQ('#fPagoApar').attr('readonly', true);
        JQ("#fPagoApar_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#nom_ases_fDescomision').val()=='0'){
        JQ('#fDescomision').attr('readonly', true);
        JQ("#fDescomision_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#nom_ases_fPagoEsc').val()=='0'){
        JQ('#fPagoEsc').attr('readonly', true);
        JQ("#fPagoEsc_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#nom_ases_fPagoLiq').val()=='0'){
        JQ('#fPagoLiq').attr('readonly', true);
        JQ("#fPagoLiq_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#nom_pros_fPagoApar').val()=='0'){
        JQ('#fPagoAparPros').attr('readonly', true);
        JQ("#fPagoAparPros_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#nom_pros_fDescomision').val()=='0'){
        JQ('#fDescomisionPros').attr('readonly', true);
        JQ("#fDescomisionPros_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#nom_pros_fPagoEsc').val()=='0'){
        JQ('#fPagoEscPros').attr('readonly', true);
        JQ("#fPagoEscPros_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#dg_fdtu').val()=='0'){
        JQ('#fdtu').attr('readonly', true);
        JQ("#fdtu_dateopener").removeClass("kcdDateOpener");
    }
    if(JQ('#dg_fNac').val()=='0'){
        JQ('#fNacimiento').attr('readonly', true);
        JQ("#fNacimiento_dateopener").removeClass("kcdDateOpener");
    }
    //JQ("#fCierre").addClass('required');

    //JQ('#fCierre').attr('readonly', true);
    JQ("#motivo_cancel_dg").attr('disabled', true);
    if(JQ("#oculto_nombre_Ref").val()=='84'){
       JQ("#campoNombreRef").show();
    }

    //var cantidad = 300000.00;
    //console.log(cantidad.formatMoney(2));

    ctoMasSub();
    difTotal();
    totalDepositos();

    apartEscLiqNomPros();
    JQ("#fPagoAparPros").change(function(){
        apartEscLiqNomPros();
    });
    JQ("#fPagoEscPros").change(function(){
        apartEscLiqNomPros();
    });
    JQ("#fDescomisionPros").change(function(){
        apartEscLiqNomPros();
    });

    apartEscLiqNomAsesor();
    /*
    JQ('input[name=\"preventa_nom\"]').click(function() {
        var v = JQ(this).val();
        var value = (v == 1) ? '0' : '1';

        if(value=='0'){
            JQ('#dropDownAsesorCP').hide();
            JQ('#dropDownAsesorSP').show();
        }
        if(value=='1'){
            JQ('#dropDownAsesorCP').show();
            JQ('#dropDownAsesorSP').hide();
        }

        JQ('#preventa_nom').val(value);
        apartEscLiqNomAsesor();
    });
    */

    JQ("#fPagoApar").change(function(){
        apartEscLiqNomAsesor();
    });
    JQ("#fPagoEsc").change(function(){
        apartEscLiqNomAsesor();
    });
    JQ("#fPagoLiq").change(function(){
        apartEscLiqNomAsesor();
    });
    JQ("#fDescomision").change(function(){
        apartEscLiqNomAsesor();
    });
    /*
    JQ('input[name=\"pros_preventa_nom\"]').click(function() {
        var v = JQ(this).val();
        var value = (v == 1) ? '0' : '1';

        if(value=='0'){
            JQ('#dropDownProspCP').hide();
            JQ('#dropDownProspSP').show();
        }
        if(value=='1'){
            JQ('#dropDownProspSP').hide();
            JQ('#dropDownProspCP').show();
        }

        JQ('#pros_preventa_nom').val(value);
        apartEscLiqNomPros();
    });
    */

    //Estatus old
    // setTimeout(function(){
        var estatus_dg_old = JQ("#estatus_dg").val();
        console.log(estatus_dg_old);
    // }, 1000);
    //Habiliar selector motivo de cancelacion en caso de obtener el 88
    JQ("#estatus_dg").change(function(){
        JQ("#cont_motivo_liberar_dpto").hide();
        JQ("#motivo_liberar_dpto").removeClass('required');
        if(JQ(this).val()==88){
            JQ("#avisoHistorial").show();
            JQ("#motivo_cancel_dg").attr('disabled', false);
            JQ("#motivo_cancel_dg").addClass('required');
            //Mostrar liberar asesor
            JQ("#cont_motivo_liberar_dpto").show();
            JQ("#motivo_liberar_dpto").addClass('required');
            JQ("#cont_motivo_liberar_dpto label").html("Descripci&oacute;n de cancelaci&oacute;n:");
        }
        else if(JQ(this).val()==402){
            JQ("#cont_motivo_liberar_dpto").show();
            JQ("#motivo_liberar_dpto").addClass('required');
            JQ("#cont_motivo_liberar_dpto label").html("Motivo regresar al asesor:");
            JQ("#avisoHistorial").hide();
            JQ("#motivo_cancel_dg").attr('disabled', true);
            JQ("#motivo_cancel_dg").val('');
            JQ("#motivo_cancel_dg").removeClass('required');
        }
        else if(JQ(this).val()==400){
            var celularCliente = "+52"+JQ("#celularCliente").val();
            var msgBody = SMSEsphabit[1];
            var msgAlert = "CAMBIO A APARTADO DEFINITIVO\n"+"Se cambio a estatus apartado definitivo, se enviara el siguiente mensaje al "+celularCliente+"\n\n"+msgBody;
            if(JQ("#celularCliente").val()!=""){
                var r = confirm(msgAlert);
                if(r==true){
                    console.log("continuar");
                }else{
                    JQ("#estatus_dg").val(estatus_dg_old);
                    return false;
                }
            }
        }
        else if(JQ(this).val()==94){
            var celularCliente = "+52"+JQ("#celularCliente").val();
            var msgBody = SMSEsphabit[2];
            var msgAlert = "CAMBIO A AVISO DE RETENCION\n"+"Se cambio a estatus aviso de retencion, se enviara el siguiente mensaje al "+celularCliente+"\n\n"+msgBody;
            if(JQ("#celularCliente").val()!=""){
                var r = confirm(msgAlert);
                if(r==true){
                    console.log("continuar");
                }else{
                    JQ("#estatus_dg").val(estatus_dg_old);
                    return false;
                }
            }
        }
        else if(JQ(this).val()==87){
            var celularCliente = "+52"+JQ("#celularCliente").val();
            var msgBody = SMSEsphabit[3];
            var msgAlert = "CAMBIO A ESCRITURADO\n"+"Se cambio a estatus escriturado, se enviara el siguiente mensaje al "+celularCliente+"\n\n"+msgBody;
            if(JQ("#celularCliente").val()!=""){
                var r = confirm(msgAlert);
                if(r==true){
                    console.log("continuar");
                }else{
                    JQ("#estatus_dg").val(estatus_dg_old);
                    return false;
                }
            }
        }
        else{
            JQ("#avisoHistorial").hide();
            JQ("#motivo_cancel_dg").attr('disabled', true);
            JQ("#motivo_cancel_dg").val('');
            JQ("#motivo_cancel_dg").removeClass('required');
        }
    });

    //Enviar los datos capturados al historial
    JQ("#motivo_cancel_dg").change(function(){
        //Joomla.submitbutton('departamento.salvarDatoHistorial');
    });

    //Abrir campo cuando el estatus sea referido
    JQ("#prospectador_dg").change(function(){
        if(JQ(this).val()==85){
            JQ("#campoNombreRef").show();
        }else{
           JQ("#campoNombreRef").hide();
           JQ("#nombreRef_dg").val('');
        }
    });

//    //Escuchar cuando cambia los acabados
//    JQ("#acabadosDptGrid_mt_nr_acabadosDptGrid_mt_c2_input").change(function(){
//       console.log('');
//    });


    //Cuando escuche un cambio en alguna de sus opciones
    JQ("#porcentajesAses_cl").change(function(){
        apartEscLiqNomAsesor();
    });
    JQ("#porcentajesAsesCP_cl").change(function(){
        apartEscLiqNomAsesor();
    });

    JQ("#porcentajesProsp_cl").change(function(){
        apartEscLiqNomPros();
    });
    JQ("#porcentajesProspCP_cl").change(function(){
        apartEscLiqNomPros();
    });


    //Toma todos los depositos seleccionados para crear el pdf de Acuenta
    JQ('#btnPdfAcuenta').click(function (){

        var idcollDpt = [];
        JQ('input:checkbox[class="selectCheckDpt"]').each(function(){
             if(JQ(this).is(':checked')) {
                idcollDpt.push(this.id);
            }
        });
        console.log(idcollDpt.join());
        JQ("#idsDptsPdf").val(idcollDpt.join());

        if(idcollDpt.length>=1){
            JQ("#checkBotonesPdf").val('1');
            Joomla.submitbutton('generarpdfs.pdfDepositos');
        }
    });

    //Toma el deposito seleccionado para crear el pdf de apartados
    JQ('#btnPdfApartados').click(function (){

        var idcollDpt = [];
        JQ('input:checkbox[class="selectCheckDpt"]').each(function(){
             if(JQ(this).is(':checked')) {
                idcollDpt.push(this.id);
            }
        });
        console.log(idcollDpt.join());
        JQ("#idsDptsPdf").val(idcollDpt.join());

        if(idcollDpt.length==1){
            JQ("#checkBotonesPdf").val('0');
            Joomla.submitbutton('generarpdfs.pdfDepositos');
        }
    });

    //Para realizar el pdf de la liquidacion
    JQ('#btnPdfLiq').click(function (){
        JQ(".selectCheckDpt").attr('checked', true);

        var idcollDpt = [];
        JQ('input:checkbox[class="selectCheckDpt"]').each(function(){
             if(JQ(this).is(':checked')) {
                idcollDpt.push(this.id);
            }
        });
        console.log(idcollDpt.join());
        JQ("#idsDptsPdf").val(idcollDpt.join());

        if(idcollDpt.length>=1){
            JQ("#checkBotonesPdf").val('1');
            Joomla.submitbutton('generarpdfs.pdfLiquidacion');
        }

    });

    //>>>
    //>>>Poner formato a los montos cuando escribimos
    //>>>
    JQ('input.fmonto').blur(function(){
        jQuery('input.fmonto').formatCurrency();
    });

    //>>>
    //>>>Setear readonly a las fechas
    //>>>
    JQ('#fApartado').attr('readonly', true);
    JQ('#fCierre').attr('readonly', true);
    JQ('#fInsc').attr('readonly', true);
    JQ('#fEstatus').attr('readonly', true);
    JQ('#fEntrega').attr('readonly', true);
    JQ('#fReprog').attr('readonly', true);
    JQ('#fPagoApar').attr('readonly', true);
    JQ('#fDescomision').attr('readonly', true);
    JQ('#fPagoEsc').attr('readonly', true);
    JQ('#fPagoLiq').attr('readonly', true);
    JQ('#fPagoAparPros').attr('readonly', true);
    JQ('#fDescomisionPros').attr('readonly', true);
    JQ('#fPagoEscPros').attr('readonly', true);
    JQ('#fdtu').attr('readonly', true);
    JQ('#fNacimiento').attr('readonly', true);


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
            vistaInterna:2 //Solo aplica cuando se actualiza desde la edicion de (prospectos o CRM)
          };
          console.log(params);
          // return false;

          tipoEnlace = accounting.unformat( params.tipoEnlace );
          fAjax("agregarenlace", params, function(data){
            console.log(data);
            jQuery('#popup_links').modal('hide');

            hideLoading("#btn_aceptar_enlace");
            if(data.result){
                alertify.success("El registro fue actualizado");

                if(typeof(data.datosEnlace.idEnlace) != "undefined"){
                    switch(tipoEnlace) {
                        case 1: JQ("#expGeneral").val(checkNulo(data.datosEnlace.linkGeneral)); break;
                        case 2: JQ("#expContrato").val(checkNulo(data.datosEnlace.linkContrato)); break;
                        case 3: JQ("#expEscrituras").val(checkNulo(data.datosEnlace.linkEscrituras)); break;
                        case 4: JQ("#expEntrega").val(checkNulo(data.datosEnlace.linkEntregas)); break;
                    }
                }
            }else{
              alertify.error("No fue posible editar en registro");
            }
          });
      }
    });
    // Fin expediente digital

});
//>>>
//>>>Evento de fecha cierre
//>>>
function event_fechaentrega(){
    var fechaEntrega = fEntrega.get_value();
    var resFEntrega = fechaEntrega.split("/");
    if(resFEntrega.length==3){
        console.log(SMSEsphabit[4]);
        var celularCliente = "+52"+JQ("#celularCliente").val();
        var msgBody = SMSEsphabit[4];
        var msgAlert = "CAMBIO A ENTREGA DE VIVIENDA\n"+"Se cambio a entrega de vivienda, se enviara el siguiente mensaje al "+celularCliente+"\n\n"+msgBody;
        if(JQ("#celularCliente").val()!=""){
            var r = confirm(msgAlert);
            if(r==true){
                console.log("continuar");
            }else{
                JQ("#fEntrega").val("");
                return false;
            }
        }
    }
}

/**
 * Suma de credito infonavit + subsidio federal
 */
function ctoMasSub(){
    var cInF = JQ('#cInfonavit_dc').val();
    var subF = JQ('#subFed_dc').val();
    //var cInF= limpiarCantidad(JQ('#cInfonavit_dc').val());
    //var subF= limpiarCantidad(JQ('#subFed_dc').val());
    //console.log(cInF);

    //var cInfo = JQ('#cInfonavitHid').val();
    //var subFe = JQ('#sFederalHid').val();


    var sum = (accounting.unformat(cInF) + accounting.unformat(subF));
    //JQ("#cdtoSub_dc").val("$ "+sum.toFixed(2));
    JQ("#cdtoSub_dc").val(accounting.formatMoney(sum));
	totalVivienda();
    diferencia();
}
/**
 * Diferencia entre el valor de la vivienda - suma del (creditoInf+subsidioF)
 */
function diferencia(){
    var ctoMasSub = JQ('#cdtoSub_dc').val();
    var valorViv = JQ('#totalViv_dc').val();
    var dif = (accounting.unformat(valorViv) - accounting.unformat(ctoMasSub));
    var seguros_dc = accounting.unformat(JQ('#seguros_dc_resta').val());
    var res = dif-seguros_dc;
    JQ("#diferencia_dc").val(accounting.formatMoney(res));
    difTotal();
}
/**
 * Diferencia total entre gastos de escrituracion + ahorro voluntario
 */
function difTotal(){
    var gEsc = JQ('#gEscrituracion_dc').val();
    var ahorroVol = JQ('#ahorroVol_dc').val();
    var diferencia = JQ('#diferencia_dc').val();

    var difTotal = (accounting.unformat(gEsc) + accounting.unformat(ahorroVol) + accounting.unformat(diferencia) );
    JQ("#difTotal_dc").val(accounting.formatMoney(difTotal));
    difPendiente();
}
/**
 * Diferencia pendiente difTotal - total depositos
 */
function difPendiente(){
    var difTotal = JQ('#difTotal_dc').val();
    var totalDep = JQ('#totalDep_dc').val();
    var difPend = (accounting.unformat(difTotal) - accounting.unformat(totalDep));
    JQ("#difPend_dc").val(accounting.formatMoney(difPend));
    JQ("#difPend_Pag").val(accounting.formatMoney(difPend));

    //JQ("#difPend_dc").val(difPend.toFixed(2));
    //JQ("#difPend_Pag").val(difPend.toFixed(2));

}
/**
 * total de depositos
 */
function totalDepositos(){
    var depTotal = JQ('#totalDep_dc').val();
    var total = (accounting.unformat(depTotal));
    //JQ("#totalPagado_Pag").val(total.toFixed(2));
    JQ("#totalPagado_Pag").val(accounting.formatMoney(total));
}

/**
 * Total de la vivenda
 */
function totalVivienda(){
    var valorViv = JQ('#valorv_dc').val();
    var acabados_total = JQ('#acabados_dc').val();
    var servicios_total = JQ('#servicios_dc').val();
    var seguros_dc = accounting.unformat(JQ('#seguros_dc').val());
    //+ accounting.unformat(seguros_dc)
    var sum = accounting.unformat(acabados_total) + accounting.unformat(servicios_total) + accounting.unformat(valorViv);
    var sumaTVi = sum+seguros_dc;
    JQ("#totalViv_dc").val(accounting.formatMoney(sumaTVi));
    //Se copia la cantidad del campo seguros a seguros resta
    JQ("#seguros_dc_resta").val('$'+accounting.toFixed(seguros_dc,2));
}

/**
 * Formulas para vista nominas secci?n asesores
 */
function apartEscLiqNomAsesor(){
    //var espreventa = (JQ('#preventa_nom').val()!='1') ? '0' : '1';

    var comision =  accounting.unformat(JQ('#ases_comision_nom').val());
    var montoApar = 0;
    var montoEsc = 0;
    var montoLiq = 0;
    var total = 0;

     //obtener el id del porcetaje seleccionado
     pctAsesId = JQ("#porcentajesAses_cl").val();
     JQ("#idPctAsesor").val(pctAsesId);

     strPctAsesSPA = colPctAses[pctAsesId].split(",");
     valueApar = accounting.unformat(strPctAsesSPA[0]);
     valueEsc = accounting.unformat(strPctAsesSPA[1]);
     valueLiq = accounting.unformat(strPctAsesSPA[2]);
     valueMult = accounting.unformat(strPctAsesSPA[3]);

//     console.log(valueApar);
//     console.log(valueEsc);
//     console.log(valueLiq);
//     console.log(valueMult);

    if(comision>0){
            aparAsesor = (parseFloat(comision)*valueApar)*valueMult;
            JQ("#ases_apartado_nom").val(accounting.formatMoney(aparAsesor)); //apartado nominas asesor

            escAsesor = (parseFloat(comision)*valueEsc)*valueMult;
            JQ("#ases_escritura_nom").val(accounting.formatMoney(escAsesor));  //escritura nominas asesor

            liqAsesor = (parseFloat(comision)*valueLiq)*valueMult;
            JQ("#ases_liquidacion_nom").val(accounting.formatMoney(liqAsesor)); //liquidacion nominas asesor


        if(JQ('#fPagoApar').val()!=''){
            if(JQ('#fDescomision').val()!=''){
                montoApar = 0;
            }else{
                montoApar = accounting.unformat(aparAsesor);
            }
        }
        if(JQ('#fPagoEsc').val()!=''){
            montoEsc = accounting.unformat(escAsesor);
        }
        if(JQ('#fPagoLiq').val()!=''){
            montoLiq = accounting.unformat(liqAsesor);
        }

        total = (accounting.unformat(montoApar) + accounting.unformat(montoEsc) + accounting.unformat(montoLiq));

        JQ("#ases_total_nom").val(accounting.formatMoney(total));

   }else{
       var valor0 = 0;
       JQ("#ases_apartado_nom").val(accounting.formatMoney(valor0)); //apartado nominas asesor
       JQ("#ases_escritura_nom").val(accounting.formatMoney(valor0));  //escritura nominas asesor
       JQ("#ases_liquidacion_nom").val(accounting.formatMoney(valor0)); //liquidacion nominas asesor
       JQ("#ases_total_nom").val(accounting.formatMoney(valor0));
    }
    console.log(total);
}


/**
 * Formulas para vista nominas seccion de prospectatores
 */
function apartEscLiqNomPros(){
    //var espreventa = (JQ('#pros_preventa_nom').val()!='1') ? '0' : '1';

    var comision =  accounting.unformat(JQ('#pros_comision_nom').val());
    var montoApar = 0;
    var montoEsc = 0;
    var total = 0;

    //obtener el id del porcetaje seleccionado
     pctProspId = JQ("#porcentajesProsp_cl").val();
     JQ("#idPctProspectador").val(pctProspId);

     strPctProspSPA = colPctProsp[pctProspId].split(",");
     valueApar = accounting.unformat(strPctProspSPA[0]);
     valueEsc = accounting.unformat(strPctProspSPA[1]);
     valueMult = accounting.unformat(strPctProspSPA[2]);

     console.log(valueApar);
     console.log(valueEsc);
     console.log(valueMult);

    if(comision>0){
            aparPros = (parseFloat(comision)*valueApar)*valueMult;
            JQ("#pros_apartado_nom").val(accounting.formatMoney(aparPros));  //escritura nominas pros

            escPros = (parseFloat(comision)*valueEsc)*valueMult;
            JQ("#pros_escritura_nom").val(accounting.formatMoney(escPros));  //escritura nominas pros


        if(JQ('#fPagoAparPros').val()!=''){
            //montoApar = aparPros.toFixed(2);
            if(JQ('#fDescomisionPros').val()!=''){
                montoApar = 0;
            }else{
                montoApar = accounting.unformat(aparPros);
            }
        }
        if(JQ('#fPagoEscPros').val()!=''){
            //montoEsc = escPros.toFixed(2);
            montoEsc = accounting.unformat(escPros);
        }
        //total = (parseFloat(montoApar) + parseFloat(montoEsc));
        total = (accounting.unformat(montoApar) + accounting.unformat(montoEsc));
        //JQ("#pros_total_nom").val(total.toFixed(2));
        JQ("#pros_total_nom").val(accounting.formatMoney(total));
    }else{
        var valor0 = 0;
        JQ("#pros_apartado_nom").val(accounting.formatMoney(valor0));  //escritura nominas pros
        JQ("#pros_escritura_nom").val(accounting.formatMoney(valor0));  //escritura nominas pros
        JQ("#pros_total_nom").val(accounting.formatMoney(valor0));
    }

    //console.log(total);
}


/**
 * Actualiza campo (Total pagado) => seccion pagares
 */
function AjaxSumaTotalDepositos(){
    var path = 'index.php?option=com_sasfe&task=departamento.totalDepositos';
    var idCto = JQ('#id_DatoCto').val();
    JQ.ajax({
        type: 'POST',
        url: path,
        data:{idDatoCredito: idCto },
        success: function(html){
            var result = JQ(html).find('response').html();
            //JQ("#totalDep_dc").val(result);
            //JQ("#totalPagado_Pag").val(result);
            JQ("#totalDep_dc").val(accounting.formatMoney(result));
            JQ("#totalPagado_Pag").val(accounting.formatMoney(result));
            difPendiente();
        }
    });
}
function Handle_OnConfirmInsert(sender,args){
    AjaxSumaTotalDepositos();
}
function Handle_OnRowConfirmEdit(sender,args){
    AjaxSumaTotalDepositos();
}
function Handle_OnRowDelete(sender,args){
    AjaxSumaTotalDepositos();
}
/**
 * Suma total automatica de los pagares al insertar o actualizar el grid de pagares
 */
function AjaxSumaTotalPagares(){
    var path = 'index.php?option=com_sasfe&task=departamento.totalPagares';
    var idCto = JQ('#id_DatoCto').val();
    JQ.ajax({
        type: 'POST',
        url: path,
        data:{idDatoCredito: idCto },
        success: function(html){
            var result = JQ(html).find('response').html();
            var exp = result.split('|');
            //JQ("#suma_Pag").val(exp[0]);
            //JQ("#porPagar_Pag").val(exp[1]);

            JQ("#suma_Pag").val(accounting.formatMoney(exp[0]));
            JQ("#porPagar_Pag").val(accounting.formatMoney(exp[1]));

            console.log(result);
            console.log(exp);
        }
    });
}
function Handle_OnConfirmInsertPagares(sender,args){
    AjaxSumaTotalPagares();
}
function Handle_OnRowConfirmEditPagares(sender,args){
    AjaxSumaTotalPagares();
}
function Handle_OnRowDeletePagares(sender,args){
    AjaxSumaTotalPagares();
}


/**
 * Actualiza campo (Total de acabados y acabados) via ajax
 */
function AjaxSumaTotalAcabados(){
    var path = 'index.php?option=com_sasfe&task=departamento.totalAcabados';
    var idDatoGral= JQ('#id_DatoGral').val();
    JQ.ajax({
        type: 'POST',
        url: path,
        data:{idDatoGral: idDatoGral },
        success: function(html){
            var result = JQ(html).find('response').html();
            //JQ("#total_acabados").val(result);
            //JQ("#acabados_dc").val(result);
            JQ("#total_acabados").val(accounting.formatMoney(result));
            JQ("#acabados_dc").val(accounting.formatMoney(result));
            totalVivienda();
        }
    });
}
function OnRowConfirmEditAcabados(sender,args){
    AjaxSumaTotalAcabados();
}
function OnRowDeleteAcabados(sender,args){
    AjaxSumaTotalAcabados();
}
function OnConfirmInsertAcabados(sender,args){
    AjaxSumaTotalAcabados();
}


/**
 * Actualiza campo (Total de servicios) via ajax
 */
function AjaxSumaTotalServicios(){
    var path = 'index.php?option=com_sasfe&task=departamento.totalServicios';
    var idDatoGral= JQ('#id_DatoGral').val();
    JQ.ajax({
        type: 'POST',
        url: path,
        data:{idDatoGral: idDatoGral },
        success: function(html){
            var result = JQ(html).find('response').html();
            //JQ("#total_servicios").val(result);
            //JQ("#servicios_dc").val(result);
            JQ("#total_servicios").val(accounting.formatMoney(result));
            JQ("#servicios_dc").val(accounting.formatMoney(result));

            //console.log(result);
            totalVivienda();
        }
    });
}
function OnRowConfirmEditServicios(sender,args){
    AjaxSumaTotalServicios();
}
function OnRowDeleteServicios(sender,args){
    AjaxSumaTotalServicios();
}
function OnConfirmInsertServicios(sender,args){
    AjaxSumaTotalServicios();
}



Joomla.submitbutton = function(task)
{

    var action = task.split('.');
    if(action[1] != 'cancel' && action[1] != 'close')
    {
        var estatus = JQ("#estatus_dg").val();
        var motivo =  JQ("#motivo_cancel_dg").val();
        var historico =  JQ("#es_historico").val();
        if(motivo!=''){
            if(historico==0){
                if(estatus=='88'){
                    var r = confirm("El estatus esta en cancelado, en verdad desea continuar, si lo hace los datos se hirán a historial ");
                    if (r == true) {
                        Joomla.submitform('departamento.salvarDatoHistorial');
                        return true;
                    }else{
                        return false;
                    }
                }
           }else{
               Joomla.submitform('departamento.salvarDatoHistorial');
               return true;
           }
        }else{
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
            }

            if (isValid)
            {
                Joomla.submitform(task);
                return true;
            }else{
                return false;
            }
        }
    }else{
        if(action[1]=="cancel"){
          var controles = ['input', 'select'];
          JQ(controles).each(function(index, val){
            JQ(val).removeClass('required');
            JQ(val).removeAttr("required");
          });
        }
        Joomla.submitform(task);
    }

}

function limpiarCantidad(cantidad){
    var patron=",";
    cantidad=cantidad.replace(patron,'');

    return cantidad;
}

function jSelectCustomerlp(idDepartamento, fraccionamientoId, numero) {
     console.log(idDepartamento + ' - ' + fraccionamientoId + ' - ' + numero);
     //JQ("#jform_client").val(nameCustomer);
     //JQ("#idUser_hidden").val(idUserJoomla);
     SqueezeBox.close();
}

// Imp. 07/09/21, Carlos, Al cambiar fecha se selecciona la opcion DTU en SI
function dtuOnSelect(sender,args){
    JQ("#dtu_dg").val(1);
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