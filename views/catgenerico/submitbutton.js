JQ(document).ready(function(){                                                     
    JQ('input[name=\"dato_cat_default\"]').click(function() {
        var v = JQ(this).val();
        var value = (v == 1) ? '0' : '1';
        JQ('#dato_cat_default').val(value);
    });
    JQ('input[name=\"dato_cat_activo\"]').click(function() {
        var v = JQ(this).val();
        var value = (v == 1) ? '0' : '1';
        JQ('#dato_cat_activo').val(value);
    });

    //Para las opciones de seleccionar gerentes de ventas o prospeccion
    JQ('#opc_gerente_ventas').click(function() {
        JQ("#cont_gerente_ventas").show();
        JQ("#cont_gerente_prospeccion").hide();
        JQ(".opcSelGte").removeAttr("required");

        JQ("#usuarioIdGteVenta").addClass("required");
        JQ("#usuarioIdGteProspeccion").removeClass("required");
        JQ("#usuarioIdGteVenta").val("");
        JQ("#usuarioIdGteProspeccion").val("");
    });    
    JQ('#opc_gerente_prospeccion').click(function() {
        JQ("#cont_gerente_prospeccion").show();
        JQ("#cont_gerente_ventas").hide();
        JQ(".opcSelGte").removeAttr("required");

        JQ("#usuarioIdGteProspeccion").addClass("required");
        JQ("#usuarioIdGteVenta").removeClass("required");
        JQ("#usuarioIdGteVenta").val("");
        JQ("#usuarioIdGteProspeccion").val("");
    });

    JQ('.opcSelGte').change(function() {
        JQ("#usuario_gte_id").val(JQ(this).val());
    });
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
                  var controles = ['input','select'];
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
