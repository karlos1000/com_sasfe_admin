JQ(document).ready(function(){

	JQ("#btnSelFecha").click(function(){
		JQ('#popup_fechadtu').css('position','fixed');
	});

	//Fecha
    JQ("#fecha_dtu_linea").datepicker({
      	  showOn: "button",
	      buttonImage: JQ("#rutaCalendario").val(),
	      buttonImageOnly: true,
	      buttonText: "Select date",
	      changeMonth: true,
	      changeYear: true,
	      //maxDate: "0Y",
	      // minDate: "0Y",
	      yearRange: "-100:+1",
	      onSelect: function(dateText, inst){
	        JQ("#fecha_dtu").val(JQ(this).val());
	      }
	});


	JQ('#btnSeleccionarTodo').on('click', function(){
      JQ(".selDelMul").each(function(i,v) {
        JQ(this).val(1);
        JQ(this).prop('checked', true);
      });
      JQ("#btnSeleccionarTodo").hide();
      JQ("#btnDeseleccionarTodo").show();
  	});
	JQ('#btnDeseleccionarTodo').on('click', function(){
      JQ(".selDelMul").each(function(i,v) {
        JQ(this).val(0);
        JQ(this).prop('checked', false);
      });
      JQ("#btnSeleccionarTodo").show();
      JQ("#btnDeseleccionarTodo").hide();
	});


	//>>>Para la seleccion multiple
	var arrIdsTmp = [];
	var arrIdsFinal = [];
  	JQ('.selDelMul').on('change', function(){
      var v = JQ(this).val();
      var value = (v == 1)?0:1;
      JQ(this).val(value);
  	});

  	//Obtener aquellos por seleccionar
  	JQ('#btn_cambiarfechasdtu').on('click', function(){
      arrIdsTmp = [];
      arrIdsFinal = [];
      JQ('input:checkbox[class="selDelMul"]').each(function(){
          if(JQ(this).is(':checked')) {
              var idCheck = JQ(this).attr("idCheck");
              arrIdsTmp[idCheck] = idCheck;
          }
      });
      //Limpiar arreglo
      if(arrIdsTmp.length>0){
          JQ.each(arrIdsTmp, function(i,v){
            if(typeof v != "undefined"){
              arrIdsFinal.push(v);
            }
          });
      }

      //comprobar si existe algun elemento por borrar
      if(arrIdsTmp.length>0){
      	// console.log(arrIdsTmp);
      	// alertify.error("No fue posible borrar el(los) registro(s), intente después");
      	JQ(".modal .close").click()

        alertify.confirm("<strong>&#191Esta seguro que desea cambiar la fecha de (los) registro(s) seleccionado(s)?</strong>", function(){
        	JQ("#idsDatoGeneral").val(arrIdsFinal.join());
			JQ("#btn_cambiarfechasdtu_hid").click();
        },function(){
            JQ('input:checkbox[class="selDelMul"]').each(function(){
                JQ(this).prop("checked",false);
                JQ(this).val(0);
                JQ("#btnSeleccionarTodo").show();
                JQ("#btnDeseleccionarTodo").hide();
            });
        }).set({title:"Aviso"}, {labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
      }else{
      	JQ(".modal .close").click()
        alert("No hay registro(s) para cambiar la fecha DTU.");
      }
  	});

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
