<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
JHTML::_('behavior.formvalidation');
$timeZone = SasfehpHelper::obtDateTimeZone();
$colUsrGtesVentas = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11);
$colFracc = SasfehpHelper::obtTodosFraccionamientos();
$gteVentasId = "";
$usrActivo = 0;
if(in_array("11", $this->groups)){
    $gteVentasId = $this->user->id;
    //obtener los asesores por el id del gerente de ventas     
    $colAsesores = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($gteVentasId);
    //Obtener cuantos creditos tiene el gerente de ventas
    $colCreditos = SasfehpHelper::checkCreditoPorUsuarioIdSMS($gteVentasId);    
    $creditosDisp = 0;
    if(count($colCreditos)>0){
      $creditosDisp = $colCreditos->creditos;
      $usrActivo = $colCreditos->activo;
    }
    // echo "idGte: ".$gteVentasId.'<br/>';
    // echo "idGte: ".$gteVentasId.'<br/>';
}else{
    //Obtener todos los asesores ya que esta como super usuario o direccion
    $direccionId = $this->user->id;
    $colAsesores = SasfehpHelper::obtColElemPorIdCatalogo(3); //Agentes de venta
    $colCreditos = SasfehpHelper::checkCreditoPorUsuarioIdSMS($direccionId);
    $creditosDisp = 0;
    if(count($colCreditos)>0){
      $creditosDisp = $colCreditos->creditos;
      $usrActivo = $colCreditos->activo;
    }
    //Como es super usuario tiene 0 creditos
    // $creditosDisp = 0;   
}
// echo "<pre>";
// print_r($colAsesores);
// echo "</pre>";

$fechaDel = "";
$fechaAl = "";

//Obtener los mensajes preconfigurados
$colMensajes = SasfehpHelper::obtMensajesSMSPorTipoId(1);
// echo "<pre>";
// print_r($colMensajes);
// echo "</pre>";

//Col estatus
$colEstatus = array();
$colEstatus[] = (object)array("idEstatus"=>401, "nombre"=>"Apartado provisional");
$colEstatus[] = (object)array("idEstatus"=>400, "nombre"=>"Apartado definitivo");
$colEstatus[] = (object)array("idEstatus"=>87, "nombre"=>"Escriturado");
?>

<style type="text/css">
    .glyphicon {
        position: relative;
        top: 1px;
        display: inline-block;
        font-family: 'Glyphicons Halflings';
        -webkit-font-smoothing: antialiased;
        font-style: normal;
        font-weight: normal;
        line-height: 1;
        -moz-osx-font-smoothing: grayscale;
    }
    .glyphicon-arrow-right:before {
        content: "\E092";
    }
    .glyphicon-arrow-left:before {
        content: "\e091";
    }
    .btn-group>.btn:first-child:not(:last-child):not(.dropdown-toggle) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    .bootstrap-duallistbox-container .btn-group .btn {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    .btn-group>.btn:first-child {
        margin-left: 0;
    }
    .btn-default {
        color: #333;
        background-color: #fff;
        border-color: #ccc;
    }
    .box1, .box2{
        width: 23.3%;
        display: inline-block;
    }
    #bootstrap-duallistbox-nonselected-list_prospectos, #bootstrap-duallistbox-selected-list_prospectos{
        height: 200px !important;
        /*width: 230px !important;*/
        width: 300px !important;
    }
    #cont_lista_prospectos option {
        font-size: .81em;
    }
</style>

<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=smsenviosreferidos'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="alert alert-info">
      <strong>Nota:</strong>
      <div>1.- Seleccionar el agente de ventas y presionar sobre el bot&oacute;n de buscar, si encuentra coincidencias se listar&aacute;n en el listbox.</div>
      <div>2.- El estatus y el rango de fechas de captura son opcionales, si elige alguna presionar despues el bot&oacute;n de buscar.</div>
      <div>3.- Los clientes que no tienen un n&uacute;mero celular no se listar&aacute;n.</div>
    </div>
    <?php if($usrActivo==0){ $creditosDisp = 0; ?>
    <div class="alert alert-warning">      
      <div>Estas inactivo para enviar mensajes, para resolverlo contacta a direcci&oacute;n.</div>
    </div>
    <?php } ?>
    <div>        
        <fieldset class="adminform">                
        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups)){ ?>
        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups)){ ?><br/><br/><?php }?>
        <!-- <legend>Reporte productividad de prospectos</legend> -->
        <div class="text-right" style="margin-right:150px;padding-bottom:10px;"><label><b>Cr&eacute;ditos disponibles: <span><?php echo $creditosDisp;?></span></b></label></div>
        <div class="col50">
            <div class="control-group">    
                <div class="control-label">                            
                    <label for="agtventas">Agente Venta:</label>
                </div>
                <div class="controls">
                    <select name="agtventas" id="agtventas" class="required">
                        <!-- <option value="0">--Todos--</option> -->
                        <option value="">--Seleccionar--</option>
                        <?php
                        foreach ($colAsesores as $itemAse) {
                            echo '<option value="' . $itemAse->idDato . '">' . $itemAse->nombre . '</option>';                        
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">    
                <div class="control-label">                            
                    <label for="estatus">Estatus:</label>
                </div>
                <div class="controls">
                    <select name="estatus" id="estatus"> <!--class="required"-->
                        <option value="">--Seleccionar--</option>
                        <?php
                        foreach ($colEstatus as $itemEst) {
                            echo '<option value="' . $itemEst->idEstatus . '">' . $itemEst->nombre . '</option>';                        
                        }
                        ?>
                    </select>
                </div>
            </div> 
            <div class="control-group">
                <div class="control-label">
                    <label for="idFracc">Desarrollo: </label>
                </div>
                <div class="controls">
                    <select id="idFracc" name="idFracc">
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($colFracc as $itemFra) {
                            echo '<option value="' . $itemFra->idFraccionamiento . '">' . $itemFra->nombre . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>        
            <div class="control-group">                
                <div class="control-label" style="width:230px !important;">
                    <label for="filter_fechaDel" style="display: inline-block;">Fecha captura del: </label>
                    <input type="text" name="filter_fechaDel" id="filter_fechaDel" value="<?php echo $fechaDel; ?>" class="" style="width:80px;height:25px;" readonly/>        
                    <span class="separaFiltro"></span>
                </div>
                <div class="controls">
                    <label for="filter_fechaAl" style="display: inline-block;line-height:38px;">Al: </label>
                    <input type="text" name="filter_fechaAl" id="filter_fechaAl" value="<?php echo $fechaAl; ?>" class="" style="width:80px;height:25px;" readonly/>        
                    <span class="separaFiltro"></span>
                </div>            
            </div>
        </div>
        <div class="control-group col50">            
            <div class="control-label">
                <button type="button" id="btn_buscarProsp" style="width:100px;">Buscar</button>
            </div>
            <div id="loading_btn_buscarProsp" style="display:none;"><img src="<?php echo JURI::root().'media/com_sasfe/images/loading_transparent.gif'; ?>" width="32"></div>
        </div>  
        
        <!-- selector -->
        <hr>
        <br/>
        <div class="control-group" id="cont_lista_prospectos"><!--style="display:none;"-->
            <div class="control-label">
                <label for="prospectos">Prospectos:</label> 
            </div>
            <select id="prospectos" name="prospectos" class="required duallb" multiple="multiple"></select>
        </div>

        <div class="control-group">    
            <div class="control-label">                            
                <label for="preconf_msn">Mensajes:</label>
            </div>
            <div class="controls">
                <select name="preconf_msn" id="preconf_msn" class="required">
                    <option value="">--Seleccionar--</option>
                    <?php
                    foreach ($colMensajes as $itemMsg) {
                        echo '<option idMsg="'.$itemMsg->idMensaje.'"  value="' . $itemMsg->texto . '" >' . $itemMsg->titulo . '</option>';                        
                    }
                    ?>
                </select>
            </div>
            <div class="controls">
                <textarea name="mensaje" id="mensaje" cols="30" rows="6" required style="width:300px;" readonly></textarea>
            </div>
        </div>


        
        <?php } ?>
        </fieldset>
    </div>
    
    <div>        
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />        
        <?php echo JHtml::_('form.token'); ?>
        <input type="hidden" id="rutaCalendario" value="<?php echo JURI::root().'media/com_sasfe/images/calendar.gif'; ?>" />
        <input type="hidden" name="usuarioIdGteVenta" value="<?php echo $gteVentasId; ?>" />
        <input type="hidden" id="idsProspectos" name="idsProspectos" />
        <!-- <input type="hidden" id="totalUsuariosEnviar" value="0" /> -->
        <input type="hidden" id="nombreEstatus" name="nombreEstatus" value="" />
        <input type="hidden" id="path" value="<?php echo $this->pathUrl;?>" />        
        <input type="hidden" id="credDisponibles" value="<?php echo $creditosDisp;?>" />
        <input type="hidden" id="preconfMsnId" name="preconfMsnId" value="0" />
    </div>
    
    <!-- Modal dinamico -->    
    <div class="modal fade" id="modal_dinamico" role="dialog" style="width:400px;display:none;">
        <div class="modal-dialog">          
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Aviso</h4>
              </div>
              <div class="modal-body cont_form_popup" id="cont_dinamico">            
                OK OK
              </div>              
          </div>
        </div>
    </div>
</form>
