<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');

$ordering	= $this->escape($this->state->get('list.ordering'));
$direction	= $this->escape($this->state->get('list.direction'));
$opcionTipoCreditos = $this->state->get('filter.opcionTipoCreditos');
$opcionEstatusProspecto = $this->state->get('filter.opcionEstatusProspecto');
$opcionEstatus = $this->state->get('filter.opcionEstatus');
$opcionGerentes = $this->state->get('filter.opcionGerentes');
$opcionAsesores = $this->state->get('filter.opcionAsesores');
$timeZone = SasfehpHelper::obtDateTimeZone();
$modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

// echo "<pre>";
// print_r($this->items);
// echo "</pre>";

?>
<div class="notesUlMozi">
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=expdigitales'); ?>" method="post" name="adminForm" id="adminForm">

    <div id="filter-bar" class="btn-toolbar">
        <!-- <div class="fila_filtro">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible"><?php echo JText::_('Nombre(s)');?></label>
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('Nombre(s)'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Nombre(s)'); ?>" style="width:170px;" />
            </div>
            <div class="filter-search btn-group pull-left">
                <label for="filter_apellidos" class="element-invisible"><?php echo JText::_('Apellido(s)');?></label>
                <input type="text" name="filter_apellidos" id="filter_apellidos" placeholder="<?php echo JText::_('Apellido(s)'); ?>" value="<?php echo $this->escape($this->state->get('filter.apellidos')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Apellido(s)'); ?>" style="width:170px;" />
            </div>
            <div class="filter-search btn-group pull-left">
                <label for="filter_email" class="element-invisible"><?php echo JText::_('Email');?></label>
                <input type="text" name="filter_email" id="filter_email" placeholder="<?php echo JText::_('Email'); ?>" value="<?php echo $this->escape($this->state->get('filter.email')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Email'); ?>" style="width:170px;" />
            </div>
        </div> -->

        <div class="fila_filtro">
            <!-- <div class="filter-search btn-group pull-left">
                <label for="filter_estatus" class="element-invisible"><?php echo JText::_('Estatus');?></label>
                <select name="filter_estatus" id="filter_estatus" class="hasTooltip" title="<?php echo JHtml::tooltipText('Estatus'); ?>" style="width:170px;">
                    <?php echo JHtml::_('select.options', JHtml::_('modules.opcionEstatusExpDig'), 'value', 'text', $opcionEstatus, true);?>
                </select>
            </div>
            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("20", $this->groups)){ ?>
                <div class="filter-search btn-group pull-left">
                    <label for="filter_gerentes" class="element-invisible"><?php echo JText::_('Gerentes');?></label>
                    <select name="filter_gerentes" id="filter_gerentes" class="hasTooltip" title="<?php echo JHtml::tooltipText('Gerentes'); ?>" style="width:170px;">
                        <?php echo JHtml::_('select.options', JHtml::_('modules.opcionGerentesProspectos'), 'value', 'text', $opcionGerentes, true);?>
                    </select>
                </div>
            <?php } ?>
            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("20", $this->groups) || in_array("11", $this->groups)){ ?>
                <div class="filter-search btn-group pull-left">
                    <label for="filter_asesores" class="element-invisible"><?php echo JText::_('Gerentes');?></label>
                    <select name="filter_asesores" id="filter_asesores" class="hasTooltip" title="<?php echo JHtml::tooltipText('Agentes V.'); ?>" style="width:170px;">
                        <?php echo JHtml::_('select.options', JHtml::_('modules.opcionAsesoresProspectos'), 'value', 'text', $opcionAsesores, true);?>
                    </select>
                </div>
            <?php } ?>


            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>
                <button type="button" id="limpiarFiltros" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" ><i class="icon-remove"></i></button>
            </div> -->
            <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                <?php echo $this->pagination->getLimitBox(); ?>
            </div>
        </div>
    </div>
    <!-- <br/><br/><br/> -->

    <table class="table table-striped">
        <thead>
            <tr>
                <!-- <th width="5" class="nowrap center">
                    <?php echo "idDatoProspecto"; ?>
                </th> -->
                <!-- <th width="20" class="nowrap center">
                    <?php echo JText::_('Sel'); ?>
                </th> -->
                <!-- <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Fecha Alta', 'fechaAlta', $direction, $ordering); ?>
                </th> -->
                <th class="nowrap center">
                    <!-- <?php echo JHtml::_('grid.sort', 'Nombre', 'nombre', $direction, $ordering); ?> -->
                    <?php echo "Nombre"; ?>
                </th>
                <th class="nowrap center">
                    <!-- <?php echo JHtml::_('grid.sort', 'Email', 'email', $direction, $ordering); ?> -->
                    <?php echo "Email"; ?>
                </th>
                <?php //if(in_array("19", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("10", $this->groups) || in_array("8", $this->groups)){ ?>
                <th class="nowrap center">
                    <!-- <?php echo JHtml::_('grid.sort', 'Estatus', 'agtVentasId2', $direction, $ordering); ?> -->
                    <?php echo "Estatus"; ?>
                </th>
                <?php //} ?>
                <th class="nowrap center">
                    <!-- <?php echo JHtml::_('grid.sort', 'Gerente', 'gteVentasId', $direction, $ordering); ?> -->
                    <?php echo "Gerente"; ?>
                </th>
                <th class="nowrap center">
                    <!-- <?php echo JHtml::_('grid.sort', 'Agente', 'agtVentasId', $direction, $ordering); ?> -->
                    <?php echo "Agente"; ?>
                </th>
                <th class="nowrap center">
                    <?php echo "Generales"; ?>
                </th>
                <th class="nowrap center">
                    <?php echo "Contrato P."; ?>
                </th>
                <th class="nowrap center">
                    <?php echo "Escrituras"; ?>
                </th>
                <th class="nowrap center">
                    <?php echo "Entregas"; ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php //echo "------: ".count($this->items); ?>
            <?php foreach ($this->items as $i => $item): ?>
                <?php
                    // $link = JRoute::_('index.php?option=com_sasfe&view=expdigital&layout=edit&id=' . $item->idDatoProspecto);
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <!-- <td>
                        <?php echo $item->idDatoProspecto; ?>
                    </td> -->
                    <!-- <td>
                        <?php echo JHtml::_('grid.id', $i, $item->idDatoProspecto); ?>
                    </td>
                    <td class="center">
                        <?php echo SasfehpHelper::conversionFechaF2($item->fechaAlta); ?>
                    </td> -->
                    <td class="center">
                        <?php echo $item->nombre." ".$item->aPaterno." ".$item->aManterno; ?>
                    </td>
                    <td class="center">
                        <?php echo $item->email; ?>
                    </td>
                    <!-- gerentes de prospeccion y gerentes ventas -->
                    <!-- <?php if(in_array("19", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("10", $this->groups) || in_array("8", $this->groups)){ ?> -->
                    <td class="center">
                        <?php
                            // echo $item->estatus;
                            echo $item->estatusNombre;
                            /*if($item->departamentoId!="" && $item->fechaDptoAsignado==""){
                                $asigText = "Apartado provisional";
                            }
                            elseif($item->departamentoId!="" && $item->fechaDptoAsignado!=""){
                                $asigText = "Apartado definitivo";
                            }
                            else{
                                $asigText = ($item->agtVentasId!="") ?"Agt. Asignado" :"Por Asignar Agt.";
                            }
                            echo $asigText;*/
                        ?>
                    </td>
                    <!-- <?php } ?> -->
                    <td class="center">
                        <?php
                            if($item->gteVentasId!=""){
                                $datosGteUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($item->gteVentasId);
                                echo isset($datosGteUsrJoomla->name) ?$datosGteUsrJoomla->name :"";
                            }
                            if($item->gteProspeccionId!=""){
                                $datosGteUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($item->gteProspeccionId);
                                echo isset($datosGteUsrJoomla->name) ?$datosGteUsrJoomla->name :"";
                            }
                        ?>
                    </td>
                    <td class="center">
                        <?php
                            if($item->agtVentasId!=""){
                                $datosUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($item->agtVentasId);
                                // print_r($datosUsrJoomla);
                                echo isset($datosUsrJoomla->name) ?$datosUsrJoomla->name :"";
                            }
                        ?>
                    </td>
                    <td class="center">
                        <a href="javascript:void(0);"
                            idProspecto="<?php echo $item->idDatoProspecto;?>"
                            idDatoGeneral="<?php echo $item->idDatoGeneral;?>"
                            tipoEnlace="1"
                            class="selLinkAbrir"
                            id="lgeneral_<?php echo $i;?>">
                            <img src="<?php echo JURI::root().'media/com_sasfe/images/btn_enlace.png'; ?>" style="width:25px;"></a>
                        <a href="#" data-toggle="modal" data-target="#popup_links"
                            idProspecto="<?php echo $item->idDatoProspecto;?>"
                            idDatoGeneral="<?php echo $item->idDatoGeneral;?>"
                            tipoEnlace="1"
                            class="selLink">
                            <img src="<?php echo JURI::root().'media/com_sasfe/images/btn_editar.png'; ?>" style="width:25px;"></a>
                    </td>
                    <td class="center">
                        <?php if($item->consulta==1){ ?>
                            <a href="javascript:void(0);"
                                idProspecto="<?php echo $item->idDatoProspecto;?>"
                                idDatoGeneral="<?php echo $item->idDatoGeneral;?>"
                                tipoEnlace="2"
                                class="selLinkAbrir"
                                id="lcontrato_<?php echo $i;?>">
                                <img src="<?php echo JURI::root().'media/com_sasfe/images/btn_enlace.png'; ?>" style="width:25px;"></a>
                            <a href="#" data-toggle="modal" data-target="#popup_links"
                                idProspecto="<?php echo $item->idDatoProspecto;?>"
                                idDatoGeneral="<?php echo $item->idDatoGeneral;?>"
                                tipoEnlace="2"
                                class="selLink">
                                <img src="<?php echo JURI::root().'media/com_sasfe/images/btn_editar.png'; ?>" style="width:25px;"></a>
                        <?php } ?>
                    </td>
                    <td class="center">
                        <?php if($item->consulta==1){ ?>
                            <a href="javascript:void(0);"
                                idProspecto="<?php echo $item->idDatoProspecto;?>"
                                idDatoGeneral="<?php echo $item->idDatoGeneral;?>"
                                tipoEnlace="3"
                                class="selLinkAbrir"
                                id="lescritura_<?php echo $i;?>">
                                <img src="<?php echo JURI::root().'media/com_sasfe/images/btn_enlace.png'; ?>" style="width:25px;"></a>
                            <a href="#" data-toggle="modal" data-target="#popup_links"
                                idProspecto="<?php echo $item->idDatoProspecto;?>"
                                idDatoGeneral="<?php echo $item->idDatoGeneral;?>"
                                tipoEnlace="3"
                                class="selLink">
                                <img src="<?php echo JURI::root().'media/com_sasfe/images/btn_editar.png'; ?>" style="width:25px;"></a>
                        <?php } ?>
                    </td>
                    <td class="center">
                        <?php if($item->consulta==1){ ?>
                            <a href="javascript:void(0);"
                                idProspecto="<?php echo $item->idDatoProspecto;?>"
                                idDatoGeneral="<?php echo $item->idDatoGeneral;?>"
                                tipoEnlace="4"
                                class="selLinkAbrir"
                                id="lentrega_<?php echo $i;?>">
                                <img src="<?php echo JURI::root().'media/com_sasfe/images/btn_enlace.png'; ?>" style="width:25px;"></a>
                            <a href="#" data-toggle="modal" data-target="#popup_links"
                                idProspecto="<?php echo $item->idDatoProspecto;?>"
                                idDatoGeneral="<?php echo $item->idDatoGeneral;?>"
                                tipoEnlace="4"
                                class="selLink">
                                <img src="<?php echo JURI::root().'media/com_sasfe/images/btn_editar.png'; ?>" style="width:25px;"></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
        <tfoot>
            <tr>
                <?php if(in_array("19", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups)){ ?>
                <td colspan="17"><?php echo $this->pagination->getListFooter(); ?></td>
                <?php }else{ ?>
                <td colspan="16"><?php echo $this->pagination->getListFooter(); ?></td>
                <?php } ?>
            </tr>
        </tfoot>
    </table>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $ordering; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $direction; ?>" />
        <?php echo JHtml::_('form.token'); ?>

        <input type="hidden" id="rutaCalendario" value="<?php echo JURI::root().'media/com_sasfe/images/calendar.gif'; ?>" />
        <input type="hidden" id="idPorVencer" value="0" />
        <?php if(in_array("18", $this->groups)){ ?>
        <input type="hidden" name="vista_eventos" value="0" />
        <?php }elseif(in_array("11", $this->groups)){ ?>
        <input type="hidden" name="vista_eventos" value="1" />
        <?php } ?>

        <!-- En caso de ser gerentes -->
        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("20", $this->groups)){
                $f_gerentes_id = (isset($_POST["filter_gerentes"]))?$_POST["filter_gerentes"]:0;
                $f_asesores_id = (isset($_POST["filter_asesores"]))?$_POST["filter_asesores"]:0;
         ?>
            <input type="hidden" name="f_gerentes_id" id="f_gerentes_id" value="<?php echo $f_gerentes_id; ?>" />
            <input type="hidden" name="f_asesores_id" id="f_asesores_id" value="<?php echo $f_asesores_id; ?>" />
        <?php } ?>
    </div>

    <input type="hidden" id="loading_img" value="<?php echo $this->imgLoading; ?>" />
    <input type="hidden" id="path" value="index.php?option=com_sasfe&task=expdigitales." />
</form>

<!-- Modal enlaces link -->
<div class="modal fade" id="popup_links" role="dialog" style="width:700px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Agregar o editar enlace</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregarenlace" class="form-horizontal">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="enlace">Enlace:</label>
                    </div>
                    <div class="controls">
                        <textarea name="enlaceDigital" id="enlaceDigital" class="form-control url required" style="min-width:90%;min-height:70px;"></textarea>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_aceptar_enlace">Aceptar</button>
                </div>


                <!--<input type="hidden" name="hIdPC" id="hIdPC" value="" />-->
                <input type="hidden" name="hIdProspecto" id="hIdProspecto" value="0" />
                <input type="hidden" name="hIdDatoGeneral" id="hIdDatoGeneral" value="0" />
                <input type="hidden" name="hTipoEnlace" id="hTipoEnlace" value="0" />
                <input type="hidden" name="idEnlace" id="idEnlace" value="0" />

                <input type="hidden" name="hConsulta" id="hConsulta" value="-1" />
            </form>
          </div>
      </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="popup_agregarevento" role="dialog" style="width:500px;height:600px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Evento para expdigital</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregar_evento" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=expdigitales&task=expdigital.addevento'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_tipoevento">Evento:</label>
                    </div>
                    <div class="controls">
                        <select name="ev_tipoevento" id="ev_tipoevento" class="required">
                            <option value="">--Seleccionar--</option>
                            <?php
                            foreach ($this->arrTipoEventos as $itemTE) {
                                echo '<option value="' . $itemTE->idTipoEvento . '">' . $itemTE->tipoEvento . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>



                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_fecha">Fecha y hora:</label>
                    </div>
                    <div class="controls">
                        <!-- <input type="text" name="ev_fecha" id="ev_fecha" class="required" style="width:100px;" readonly/> -->
                        <div id="ev_fecha_linea" style="display:inline-block;"></div>
                        <input type="hidden" name="ev_fecha" id="ev_fecha" value="<?php echo $timeZone->fechaF2; ?>" />
                        <div style="display:inline-block;"><input class="timepicker timepicker-with-dropdown text-center" name="ev_hora" id="ev_hora" value="7:00" style="width:50px;cursor:pointer;" readonly></div>
                    </div>
                </div>

                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_optrecordatorio">Recordatorio:</label>
                    </div>
                    <div class="controls">
                        <input type="checkbox" name="ev_optrecordatorio" id="ev_optrecordatorio" class="" value="0">
                    </div>
                </div>
                <div class="control-group ctrgr_popup" style="display:none;" id="cont_ev_tiempo">
                    <div class="control-label">
                        <label for="ev_tiempo">Tiempo:</label>
                    </div>
                    <div class="controls">
                        <select name="ev_tiempo" id="ev_tiempo" class="">
                            <option value="">--Selecciona--</option>
                            <?php
                            foreach ($this->arrTiempoRecordatorios as $itemTR) {
                                echo '<option value="' . $itemTR->idTiempoRecordatorio . '">' . $itemTR->texto . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="ev_comentario" id="ev_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_agregarevento">Agregar evento</button>
                </div>

                <input type="hidden" name="ev_idPros" id="ev_idPros" value="0" />
                <input type="hidden" name="edit_evpros" value="0" />
            </form>
          </div>
      </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="popup_comentario" role="dialog" style="width:500px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comentario para expdigital</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregar_comentario" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=expdigitales&task=expdigital.addcomentario'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="ev_comentario" id="ev_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-small button-new btn-success">Agregar comentario</button>

                <input type="hidden" name="com_idPros" id="com_idPros" value="0" />
            </form>
          </div>
      </div>
    </div>
</div>

 <!-- Modal gerente prospeccion -->
<div class="modal fade" id="popup_asignargteventas" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Asignar gerente(s) de venta(s) a expdigital(s)</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_asignargteventas" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=expdigitales&task=expdigital.asignarprospectoagteventas'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="asiggtev_gteventas">Gerente Venta:</label>
                    </div>
                    <div class="controls">
                        <select name="asiggtev_gteventas" id="asiggtev_gteventas" class="required">
                            <option value="">--Seleccionar--</option>
                            <?php
                            foreach ($this->ColGteVentas as $itemGteV) {
                                echo '<option value="' . $itemGteV->idDato . '">' . $itemGteV->nombre . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="asiggtev_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="asiggtev_comentario" id="asiggtev_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>

                <div>
                <button type="submit" class="btn btn-small button-new btn-success" id="btn_asignargteventas">Aceptar</button>
                </div>
                <input type="hidden" name="arrIdProsGteV" id="arrIdProsGteV" value="" />
            </form>
          </div>
      </div>
    </div>
</div>

<!-- Modal proteger expdigital (solo getentes de venta) -->
<div class="modal fade" id="popup_protegerpros" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Proteger expdigital(s)</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_protegerpros" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=expdigitales&task=expdigital.protegerpros'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="prot_tiempo">Tiempo protecci&oacute;n:</label>
                    </div>
                    <div class="controls">
                        <select name="prot_tiempo" id="prot_tiempo" class="required">
                            <option value="">--Seleccionar--</option>
                            <option value="1">1 semana</option>
                            <option value="2">15 días</option>
                            <option value="3">1 mes</option>
                        </select>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_protegerpros">Aceptar</button>
                </div>

                <input type="hidden" name="arrIdProsProt" id="arrIdProsProt" value="" />
            </form>
          </div>
      </div>
    </div>
</div>

 <!-- Modal seleccionar fechas para descargar expdigitales en un excel -->
<div class="modal fade" id="popup_descargaprospectos" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Descargar expdigitales</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_descargaprospectos" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=expdigitales&task=reportes.descargaProspecto'); ?>" method="post">
                <div class="control-group ctrgr_popup" style="margin-top:-30px;">
                    <b>Seleccionar alg&uacute;n rango de fechas</b>
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="descargaProsp_del">Del:</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="descargaProsp_del" id="descargaProsp_del" value="<?php echo SasfehpHelper::diasPrevPos(7, $timeZone->fecha, "prev"); ?>" class="hasTooltip required" style="width:80px;" readonly/>
                    </div>
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="descargaProsp_hasta">Hasta:</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="descargaProsp_hasta" id="descargaProsp_hasta" value="<?php echo $timeZone->fechaF2;?>" class="hasTooltip required" style="width:80px;" readonly />
                    </div>
                </div>
                <div id="cont_btn_descargaprospectos">
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_descargaprospectos">Aceptar</button>
                </div>
            </form>
          </div>
      </div>
    </div>
</div>

<?php
//Verificar si tiene el filtro de estatus esta vacio o no
$checkFilEstatus = ($opcionEstatus=="") ?0 :1;
$hidd_grp = '';
$this->groups = JAccess::getGroupsByUser($this->user->id, true);
//8 = super usuario, //10 = direccion
if(in_array("8", $this->groups) || in_array("10", $this->groups)){
    $hidd_grp = 'todos';
}
//11 = gerente de ventas, 19 = gerente prospeccion, //17 = prospectadores
if(in_array("11", $this->groups) || in_array("19", $this->groups) || in_array("17", $this->groups)){
    $hidd_grp = 'porasignar';
}
//18 = agentes de ventas
if(in_array("18", $this->groups)){
    $hidd_grp = 'asignados';
}
echo '<input type="hidden" id="hidd_checkestatus" value="'.$checkFilEstatus.'" style="display:none;" />';
echo '<input type="hidden" id="hidd_grp" value="'.$hidd_grp.'" style="display:none;" />';


// Imp. 23/08/21, Carlos, Impresion de los Agentes de Ventas
$datosArrAsesores = array();
foreach ($this->ColAsesores as $elem) {
    $datosArrAsesores[$elem->usuarioIdJoomla] = array("nombre"=>$elem->nombre, "usuarioIdJoomla"=>$elem->usuarioIdJoomla, "usuarioIdGteJoomla"=>$elem->usuarioIdGteJoomla);
}

// echo "<pre>";
// print_r($this->ColAsesores);
// print_r($_POST);
// echo "</pre>";
?>

<script>
    var arrJsonAsesores = <?php echo json_encode($datosArrAsesores); ?>;
</script>