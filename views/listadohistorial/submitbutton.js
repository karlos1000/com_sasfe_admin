JQ(document).ready(function(){                                                     
    
    JQ("#estatus_sel").change(function(){
        var path = 'index.php?option=com_sasfe&task=departamento.cambioEstatusDpt';
        var valSel = JQ("#estatus_sel").val();
        var id_gral = JQ("#estatus_sel").attr("id_gral");
        var id_dpt = JQ("#estatus_sel").attr("id_dpt");
        
        console.log(valSel);
        console.log(JQ("#estatus_sel").attr("id_gral"));
        console.log(JQ("#estatus_sel").attr("id_dpt"));
        
        if(valSel!==''){                                    
            JQ.ajax({                 
                type: 'POST',
                url: path,                               
                data:{idEstatus: valSel, id_dpt: id_dpt, id_gral: id_gral },                                                          
                beforeSend: function() {                    
                    JQ('.loading_estatus').show();
                    JQ('#estatusMsgHist').show();                    
                },
                complete: function(){                                       
                   JQ('.loading_estatus').attr('style','display:none !important');
                }, 
                success: function(html){                               
                    var result = JQ(html).find('response').html();                                
                    console.log('R: ' +result);
                    if(result=='1'){
                        JQ("#msgHist").show();                                              
                    }                    
                }
            });
        }
        //Ocultar selector de cambio de estatus
        JQ("#estatus_sel").prop( "disabled", true );
        
        
    });
                       
 });