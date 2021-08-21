JQ(document).ready(function(){    
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
    JQ("#limpiarFiltros").click(function(){
      console.log("Entre");
      JQ("#filter_fechaDel").val(JQ("#hidFechaDel").val());
      JQ("#filter_fechaAl").val(JQ("#hidFechaAl").val());
      JQ("#filter_tipoenvio").val("");
      JQ("#filter_agtev").val("");
      this.form.submit();      
    });  

    // JQ(".selIdHidEv").click(function(){
    //   JQ("#idHidEvento").val(0);
    //   JQ("#idHidEvento").val(JQ(this).attr("idHidEv"));
    //   Joomla.submitbutton('listareventos.descargarEventoOutlook');
    // });
    
    //Tootip para la columna comentario 
    JQ('[data-toggle="tooltip"]').tooltip();
 });

//obtener la fecha actual con formato (d/m/Y)
function getCurrentDate(ctr){
    var d = new Date();

    var month = d.getMonth()+1;
    var day = d.getDate();

  	var horas= (d.getHours()>=10) ?d.getHours() :'0'+d.getHours();
  	var minutos = (d.getMinutes()>=10) ?d.getMinutes() :'0'+d.getMinutes();
  	var segundos = d.getSeconds()

    var dateCurrent = ((''+day).length<2 ? '0' : '') + day +'/'+ ((''+month).length<2 ? '0' : '') + month + '/' + d.getFullYear();
    var tiempo = horas + ":" + minutos;// + ":" + segundos;

    if(ctr==1){
    	return dateCurrent;
    }
    if(ctr==2){
    	return dateCurrent+" "+tiempo;
    }   
}

//Metodo para mostrar loading al presionar sobre el boton enviar de formulario
function showLoading(target){
  var loading = JQ('#loading_img').val(); //obtener imagen del loading   
  addInfo = JQ(target).parent();
  addInfo.html('<div class="addInfo" style="display:inline-block;">'+loading+'</div>'); //Agregar loading
  JQ(target).hide();
}

//exportar todos los Eventos dependiendo del tipo de persona
// function exportEventos(){
//     Joomla.submitbutton('listareventos.Export');    
// }