JQ(document).ready(function(){                                                   
    //Accion cuando se selecciona un nuevo fraccionamiento            
    JQ("#idFraccSel").change(function(){                        
        console.log(JQ(this).val());
        var idSelFracc = JQ(this).val();        
        JQ("#idFracc").val(idSelFracc);
        
        JQ('#idFraccSel').attr('disabled', 'disabled');        
                                
        var path = JQ('#path_url').val();        
        var idDpt = '&depto_id='+JQ("#depto_id").val();
        var idFracc = '&idFracc='+JQ("#idFracc").val();
        var idDatoGral = '&idDatoGral='+JQ("#idDatoGral").val();
        
        var url = path+idDpt+idFracc+idDatoGral;
        console.log(url);        
        location.href=url;
    });    
    
});

function reasigarFuct(idDpt){    
    JQ("#dptoNew").val(idDpt);    
    Joomla.submitform('seldepartamentos.reasignar');
    
    //console.log(JQ("#dptoNew").val());
}