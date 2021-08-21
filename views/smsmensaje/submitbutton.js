JQ(document).ready(function(){                                                     
    JQ('input[name=\"mensaje_activo\"]').click(function() {
        var v = JQ(this).val();
        var value = (v == 1) ? '0' : '1';
        JQ('#mensaje_activo').val(value);
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
                  var controles = ['input', 'textarea'];
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
