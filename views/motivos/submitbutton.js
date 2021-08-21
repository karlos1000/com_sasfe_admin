JQ(document).ready(function(){
        //Reemplazar evento onclick
        JQ('#toolbar-delete a').attr('onclick','').unbind('click');
        JQ('#toolbar-delete a').click(function(){
            if (document.adminForm.boxchecked.value==0){
                alert('Please first make a selection from the list');
            }else{
                var r=confirm('¿Realmente está seguro de querer eliminar el registro?');
                if (r==true){
                    Joomla.submitbutton('motivos.delete');
                }else{
                    return false;
                }
            }
         });

 });