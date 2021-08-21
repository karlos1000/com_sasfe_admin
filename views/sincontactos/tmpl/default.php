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
$colUsrGtesVentas = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11); //Obtener gerente de ventas de la tabla de usuarios
$colAsesores = SasfehpHelper::obtUsuariosJoomlaPorGrupo(18); //Obtener agentes de venta de la tabla de usuarios
$colFracc = SasfehpHelper::obtTodosFraccionamientos(); //Obtener los fraccionamientos
$colAsesoresActivos = SasfehpHelper::obtColAsesoresActivosIds();
//Obtener sincronizaciones del dia
$colSincronizacionesDelDia = SasfehpHelper::obtSincronizacionesDelDia($timeZone->fecha);
$arrIdsAsesores = SasfehpHelper::obtDatosConfiguracionPorId(2, $timeZone->fecha);
$arrIdsAsesoresDB = (isset($arrIdsAsesores->valor) && $arrIdsAsesores->valor!="")?explode(",", $arrIdsAsesores->valor):array();
$strIdsAsesoresDB = (isset($arrIdsAsesores->valor) && $arrIdsAsesores->valor!="")?$arrIdsAsesores->valor:"";

// $agentes = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas(307);
// echo "<pre>";
// print_r($colSincronizacionesDelDia);
// print_r($colUsrGtesVentas);
// print_r($colAsesores);
// print_r($agentes);
// print_r($colFracc);
// print_r($arrIdsAsesoresDB);
// echo "</pre>";
// foreach ($colUsrGtesVentas as $elemGteV) {
//     $agenteId = 0;
//     echo $elemGteV->id." - ";
//     $gerenteId = $elemGteV->id;
//     $agentes = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($gerenteId);
//     //echo "<pre>";print_r($agentes);echo "</pre>";
            
//             $asignar = false;
    
//             $arrAgentesIds = array();
//             foreach($agentes as $agente){
//                 $arrAgentesIds[] = $agente->usuarioIdJoomla;
//             }
//     if(count($agentes) > 0){
//         while($agenteId == 0){
//             foreach($agentes as $agente){
//                 $ultimoAgenteId = obtenerUltimoAgenteId($gerenteId); // obtener el ultimo id agente

//                 // $ultimoAgenteId = (in_array($ultimoAgenteId, $arrAgentesIds))?$ultimoAgenteId:0;
//                 $ultimoAgenteId = (in_array($ultimoAgenteId, $arrAgentesIds))?$ultimoAgenteId:0;

//                 if($agente->usuarioIdJoomla == $ultimoAgenteId || $ultimoAgenteId == 0){
//                     $asignar = true;
//                 }
//                 echo $ultimoAgenteId." ".$agente->usuarioIdJoomla." ".$asignar."<br>";
            
//                 if($agente->usuarioIdJoomla != $ultimoAgenteId && $asignar){
//                     $agenteId = $agente->usuarioIdJoomla;
//                     $asignar = false;
//                     echo "Guardar agente id: ".$agenteId."<br>";
//                     guardarUltimoAgenteId($gerenteId, $agenteId);
//                 }
//             }
//         }
//     }
// }


        function obtenerUltimoAgenteId($gerenteId){
            $ultimoAgenteId = SasfehpHelper::obtUltimoAsesorSinc($gerenteId);
            return $ultimoAgenteId;
        }

        function guardarUltimoAgenteId($gerenteId, $agenteId){
            // $_SESSION["ultimoAgenteId".$gerenteId] = $agenteId;
            $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
            $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora
    
            $resp = SasfehpHelper::salvarIdAsesorSinc($gerenteId, $agenteId, $fechaHora);
            // if($resp){
            //     echo "El asesor se salvo correctamente";
            // }
        }

?>
<style type="text/css">
    table.table.table-striped {
        font-size: 12px;
    }
    .table th, .table td {
        padding: 3px;
    }
    .usuariosGtVentas{
        width: 60%;
    }
    .contactoDuplicado td{
        background: #f16b6b !important;
    }
    .contVerDuplicado{
        cursor: pointer;
    }
</style>
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
        width: 40%;
        display: inline-block;
    }
    #bootstrap-duallistbox-nonselected-list_asesores, #bootstrap-duallistbox-selected-list_asesores{
        height: 150px !important;
    }
</style>
<form class="form-validate form-horizontal sin-cont" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=sincontactos'); ?>" method="post" name="adminForm" id="adminForm">

    <div class="alert alert-warning" id="cont_msg_sincontactos" style="display:none;">
      <div><b>La solicitud tardo demasiado en responder, intentar nuevamente.</b></div>
    </div>

    <div class="control-group">
        <!-- <div class="control-label">
            <label for="opc_gerente">Seleccionar Gerente</label>
        </div> -->
        <div class="controls">
            <fieldset id="jform_opc_gerente" class="btn-group btn-group-yesno radio">
                <input type="radio">
                <label class="btn" id="opc_gerente_ventas">Gerente</label>
                <input type="radio">
                <label class="btn" id="opc_asesores_ventas">Asesores</label>
            </fieldset>
        </div>
    </div>

    <fieldset class="adminform">
        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("20", $this->groups)){ ?>

            <!-- gerentes de ventas -->
            <div class="control-group cont_gerentes" id="cont_gerente_ventas" style="display:none">
                <div class="control-label">
                    <label for="usuarioIdGteVenta">Gerentes Venta:</label>
                </div>
                <div class="controls">
                    <select id="usuarioIdGteVenta" name="usuarioIdGteVenta" class="usuariosGtVentas" multiple size="10">
                        <!-- <option value="">--Seleccione--</option> -->
                        <?php
                        foreach ($colUsrGtesVentas as $itemUVenta) {
                            if($itemUVenta->id != 319 && $itemUVenta->id != 611){
                                echo '<option selected value="' . $itemUVenta->id . '">' . $itemUVenta->name . '</option>';
                            }
                            // if ($gteVentasId == $itemUVenta->id)
                            //     $sel = 'selected';
                            // else
                            //     $sel = '';
                            //     echo '<option '.$sel.' value="' . $itemUVenta->id . '">' . $itemUVenta->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="control-group" id="cont_agente_ventas" style="display:none">
                <div class="control-label">
                    <label for="asesores">Asesores:</label>
                </div>
                <?php //echo "<pre>";print_r($colAsesoresActivos);echo "</pre>"; 
                $arrIds = array();
                foreach($colAsesoresActivos as $asesor){
                    $arrIds[] = $asesor["usuarioIdJoomla"];
                }
                ?>
                <select id="asesores" name="asesores" class="duallb" multiple="multiple">
                    <?php
                        foreach ($colAsesores as $item) {
                            if(in_array($item->id, $arrIdsAsesoresDB)){
                              $sel = 'selected';
                            }else{
                              $sel = '';
                            }
                            if(in_array($item->id, $arrIds)){
                                echo '<option '.$sel.' value="' . $item->id . '">' . $item->name . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>
            <input type="hidden" id="idsAsesores" name="idsAsesores" value="<?php echo $strIdsAsesoresDB; ?>" />

            <div class="cont_btn_sincontactos">
                <button type="button" onclick="sincronizarContactos();" class="btn btn-small button-apply btn-success">Iniciar sincronizaci&oacute;n</button>
            </div>
            <hr><br>


            <h4>Se localizar&oacute;n <span id="totalContactos">0</span> registros desde la &uacute;ltima sincronizaci&oacute;n</h4>
            <table class="table table-striped header-fixed" style="width:90% !important">
                <thead>
                    <tr>
                        <th>Sel</th>
                        <th>Nombre</th>
                        <th>Tel&eacute;fono</th>
                        <th>Email</th>
                        <th>Desarrollo</th>
                        <th>Gerente</th>
                        <th>Asesor</th>
                        <th>--</th>
                    </tr>
                </thead>
                <tbody id="cont_contactos">
                </tbody>
            </table>
            <div class="cont_btn_salvarasignacion" style="display:none;">
                <!-- <button onclick="Joomla.submitbutton('sincontactos.salvarAsignaciones');" class="btn btn-small button-apply btn-success">Salvar Asignaciones</button> -->
                <button type="button" onclick="salvarAsignaciones();" class="btn btn-small button-apply btn-success">Salvar Asignaciones</button>
            </div>
            <hr><br>


            <h4>Sincronizaciones del d&iacute;a</h4>
            <table class="table table-striped header-fixed" style="width:90% !important">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tel&eacute;fono</th>
                        <th>Email</th>
                        <th>Desarrollo</th>
                        <th>Gerente</th>
                        <th>Asesor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($colSincronizacionesDelDia as $elem) { ?>
                        <tr>
                            <td><?php echo $elem->nombre." ".$elem->aPaterno." ".$elem->aMaterno; ?></td>
                            <td><?php echo $elem->telefono; ?></td>
                            <td><?php echo $elem->email; ?></td>
                            <td>
                                <?php
                                    foreach ($colFracc as $elemFra) {
                                        if($elem->desarrolloId == $elemFra->idFraccionamiento){
                                            echo $elemFra->nombre;
                                            continue;
                                        }
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    foreach ($colUsrGtesVentas as $elemGteV) {
                                        if($elem->gteVentasId == $elemGteV->id){
                                            echo $elemGteV->name;
                                            continue;
                                        }
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    foreach ($colAsesores as $elemAsesorV) {
                                        if($elem->agtVentasId == $elemAsesorV->id){
                                            echo $elemAsesorV->name;
                                            continue;
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br>
        <?php } ?>
    </fieldset>
    </div>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="idsCheck" id="idsCheck"/>
        <input type="hidden" name="idUserJoomla" id="idUserJoomla" value="<?php echo $this->user->id;?>" />
        <?php echo JHtml::_('form.token'); ?>
        <!-- <input type="hidden" id="rutaCalendario" value="<?php echo JURI::root().'media/com_sasfe/images/calendar.gif'; ?>" /> -->
        <!-- <input type="hidden" name="usuarioIdGteVenta" value="?php echo $gteVentasId; ?>" /> -->
        <input type="hidden" id="path" value="<?php echo $this->pathUrl;?>" />
        <!-- <input type="hidden" id="idsUsuariosGtes" value="?php echo implode(',', $arrIdsInacGerentes); ?>" /> -->

        <!-- Options desarrollos -->
        <?php
        $htmlOptionsFracc = '';
        foreach ($colFracc as $elemFra) {
            $htmlOptionsFracc .= '<option value="'.$elemFra->idFraccionamiento.'">'.$elemFra->nombre.'</option>';
        }

        $htmlOptionsGer = '';
        $arrAgentesPorGerente = array();
        foreach($colUsrGtesVentas as $elemGer){
            $htmlOptionsGer .= '<option value="'.$elemGer->id.'">'.$elemGer->name.'</option>';
            $agentes = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($elemGer->id);
            // echo "<pre>";print_r($agentes);echo "</pre>";
            $arrAgentesPorGerente[] = (object)array("idGerente"=>$elemGer->id, "agentes"=>$agentes);
        }
        ?>

        <input type="hidden" name="htmlOptionsFracc" id="htmlOptionsFracc" value="<?php echo htmlentities($htmlOptionsFracc) ?>">
        <input type="hidden" name="htmlOptionsGer" id="htmlOptionsGer" value="<?php echo htmlentities($htmlOptionsGer) ?>">

        <?php
        foreach($arrAgentesPorGerente as $elemAgentesPorGerente){
            $htmlOptionsAgente = '';
            // echo "<pre>";print_r($elemAgentesPorGerente->agentes);echo "</pre>";
            foreach($elemAgentesPorGerente->agentes as $elemAgente){
                $htmlOptionsAgente .= '<option value="'.$elemAgente->usuarioIdJoomla.'">'.$elemAgente->nombre.'</option>';
            }
            ?>
            <input type="hidden" name="htmlOptionsAgente<?php echo $elemAgentesPorGerente->idGerente ?>" id="htmlOptionsAgente<?php echo $elemAgentesPorGerente->idGerente ?>" value="<?php echo htmlentities($htmlOptionsAgente); ?>">
            <?php
        }
        ?>
    </div>

</form>


<div style="background-color: #FFF;width: 100%;height: 100%;position: fixed;top: 0;left: 0;opacity: .5;z-index: 9999; display:none;" id="cont_loading">
<span style="display: block;text-align: center;line-height: 30px;font-weight: bold;background: #8eff92;font-size: medium;position: relative;top: 90px;">ESPERANDO PROCESO <img src="<?php echo JURI::root().'media/com_sasfe/images/loading_transparent.gif'; ?>" style="width:22px;" /></span>
</div>


<!-- Modal -->
<div id="modalDup" class="modal fade pop-cont" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content formato">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detalle contacto</h4>
      </div>
      <div class="modal-body">
        <!-- <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="">Label:</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="" id="" value="" readonly="">
                    </div>
                    <div class="col-md-3 text-right">
                        <label for="">Label</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="" id="" value="" readonly="">
                    </div>
                </div>
            </div>
            <div class="col-md-1"></div>

        </div> -->

        <table>
            <tr>
                <td><label for="">Nombre:</label></td>
                <td><input type="text" name="dupnombre" id="dupnombre" value="" readonly=""></td>
                <td><label for="">Gerencia:</label></td>
                <td><input type="text" name="dupgerencia" id="dupgerencia" value="" readonly=""></td>
            </tr>

            <tr>
                <td><label for="">Email:</label></td>
                <td><input type="text" name="dupemail" id="dupemail" value="" readonly=""></td>
                <td><label for="">Asesor:</label></td>
                <td><input type="text" name="dupasesor" id="dupasesor" value="" readonly=""></td>
            </tr>

            <tr>
                <td><label for="">Tel&eacute;fono:</label></td>
                <td><input type="text" name="duptelefono" id="duptelefono" value="" readonly=""></td>
                <td><label for="">Estatus:</label></td>
                <td><input type="text" name="dupestatus" id="dupestatus" value="" readonly=""></td>
            </tr>

            <tr>
                <td><label for="">F. Alta:</label></td>
                <td><input type="text" name="dupfalta" id="dupfalta" value="" readonly=""></td>
                <td><label for="">F. Contacto:</label></td>
                <td><input type="text" name="dupfcontacto" id="dupfcontacto" value="" readonly=""></td>
            </tr>

            <tr>
                <td><label for="">F. Ult Cambio:</label></td>
                <td><input type="text" name="dupfcambio" id="dupfcambio" value="" readonly=""></td>
                <td></td>
                <td></td>
            </tr>

            <!-- <tr>
                <td><label for="">F. Alta:</label></td>
                <td><input type="text" name="" id="" value="" readonly=""></td>
                <td><label for="">F. Contacto:</label></td>
                <td><input type="text" name="" id="" value="" readonly=""></td>
            </tr> -->
        </table>

        <div id="divAcciones"></div>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</div>