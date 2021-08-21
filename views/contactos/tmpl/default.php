<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');

include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolTabs' . DIRECTORY_SEPARATOR . 'kooltabs.php';
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolCalendar' . DIRECTORY_SEPARATOR . 'koolcalendar.php';
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'ext'. DIRECTORY_SEPARATOR .'datasources'. DIRECTORY_SEPARATOR .'MySQLiDataSource.php';
$base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
$calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';

$ordering	= $this->escape($this->state->get('list.ordering'));
$direction	= $this->escape($this->state->get('list.direction'));
$opcionTipoCreditos = $this->state->get('filter.opcionTipoCreditos');
$opcionEstatusProspecto = $this->state->get('filter.opcionEstatusProspecto');
$opcionEstatus = $this->state->get('filter.opcionEstatus');
$opcionFuentes = $this->state->get('filter.opcionFuentes');
$opcionGerentes = $this->state->get('filter.opcionGerentes');
$opcionAsesores = $this->state->get('filter.opcionAsesores');

$timeZone = SasfehpHelper::obtDateTimeZone();
$colAccionesContacto = SasfehpHelper::colAccionesContacto(); //coleccion de acciones
$colUsrGtesVentas = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11); //Obtener gerente de ventas
$colEstatusContacto = SasfehpHelper::colEstatusContacto(); //Obtener la coleccion de estatus
// $colAsesores = SasfehpHelper::obtUsuariosJoomlaPorGrupo(18); //Obtener agentes de venta de la tabla de usuarios
$colMotivosRechazo = SasfehpHelper::obtDatosMotivos(); //Obtener motivos de rechazo

// echo "<pre>";
// print_r($this->items);
// print_r($colAccionesContacto);
// print_r($this->ColAsesores);
// print_r($colUsrGtesVentas);
// print_r($colAsesores);
// print_r($colMotivosRechazo);
// echo "</pre>";
// exit();

// print_r($this->groups);

/**
 * Functiones para recargar via ajax usando el ajax de koolgrid
*/
$gteId = 0;
obtAgtVentasSu(trim($gteId));
$koolajax->enableFunction("obtAgtVentasSu");
echo $koolajax->Render();

function obtAgtVentasSu($gteId){
    return SasfehpHelper::obtColUsuarioXGte(1, $gteId);
}
?>
<style type="text/css">
    .table-striped tbody > tr:nth-child(odd) > td, .table-striped tbody > tr:nth-child(odd) > th{
        background-color: initial;
    }
    .colorAzul{background-color:#2068B9 !important; color:#fff; }
    .colorAzul a, .colorAzul a:hover, .colorAzul a:focus { color:#fff; }
    .colorAmarillo{background-color:#EDE30E !important; color:#000; }
    .colorAmarillo a, .colorAmarillo a:hover, .colorAmarillo a:focus { color:#000; }
    .colorRojo{background-color:#E41010 !important; color:#fff; }
    .colorRojo a, .colorRojo a:hover, .colorRojo a:focus { color:#fff; }
    .colorVerde{background-color:#39C513 !important; color:#000; }
    .colorVerde a, .colorVerde a:hover, .colorVerde a:focus { color:#000; }
</style>
<div class="notesUlMozi">
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos'); ?>" method="post" name="adminForm" id="adminForm">

    <div id="filter-bar" class="btn-toolbar">
        <!-- <div class="filter-search btn-group pull-left">
            <label for="filter_search" class="element-invisible"><?php echo JText::_('Nombre|Apellidos');?></label>
            <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('Nombre|Apellidos'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Nombre|Apellidos'); ?>" style="width:170px;" />
        </div> -->

        <div class="fila_filtro">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible"><?php echo JText::_('Nombre(s)');?></label>
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('Nombre(s)'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Nombre(s)'); ?>" style="width:170px;" />
            </div>
            <div class="filter-search btn-group pull-left">
                <label for="filter_apellidos" class="element-invisible"><?php echo JText::_('Apellido(s)');?></label>
                <input type="text" name="filter_apellidos" id="filter_apellidos" placeholder="<?php echo JText::_('Apellido(s)'); ?>" value="<?php echo $this->escape($this->state->get('filter.apellidos')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Apellido(s)'); ?>" style="width:170px;" />
            </div>
            <div class="filter-search btn-group pull-left">
                <label for="filter_tel" class="element-invisible"><?php echo JText::_('Tel&eacute;fono');?></label>
                <input type="text" name="filter_tel" id="filter_tel" placeholder="<?php echo JText::_('Tel&eacute;fono'); ?>" value="<?php echo $this->escape($this->state->get('filter.telefono')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Tel&eacute;fono'); ?>" style="width:100px;" />
            </div>
            <div class="filter-search btn-group pull-left">
                <label for="filter_email" class="element-invisible"><?php echo JText::_('Email');?></label>
                <input type="text" name="filter_email" id="filter_email" placeholder="<?php echo JText::_('Email'); ?>" value="<?php echo $this->escape($this->state->get('filter.email')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Email'); ?>" style="width:120px;" />
            </div>
        </div>
        <div class="fila_filtro">
            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("20", $this->groups)){ ?>
            <div class="filter-search btn-group pull-left">
                <label for="filter_gerentes" class="element-invisible"><?php echo JText::_('Gerentes');?></label>
                <select name="filter_gerentes" id="filter_gerentes" class="hasTooltip" title="<?php echo JHtml::tooltipText('Gerentes'); ?>" style="width:170px;">
                    <?php echo JHtml::_('select.options', JHtml::_('modules.opcionGerentesCont'), 'value', 'text', $opcionGerentes, true);?>
                </select>
            </div>
            <?php } ?>
            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("20", $this->groups)){ ?>
            <div class="filter-search btn-group pull-left">
                <label for="filter_asesores" class="element-invisible"><?php echo JText::_('Asesores');?></label>
                <select name="filter_asesores" id="filter_asesores" class="hasTooltip" title="<?php echo JHtml::tooltipText('Asesores'); ?>" style="width:170px;">
                    <?php echo JHtml::_('select.options', JHtml::_('modules.opcionAsesoresCont'), 'value', 'text', $opcionAsesores, true);?>
                </select>
            </div>
            <?php } ?>
    	    <div class="filter-search btn-group pull-left">
                <label for="filter_fuentes" class="element-invisible"><?php echo JText::_('Fuentes');?></label>
                <select name="filter_fuentes" id="filter_fuentes" class="hasTooltip" title="<?php echo JHtml::tooltipText('Fuentes'); ?>" style="width:170px;">
                    <?php echo JHtml::_('select.options', JHtml::_('modules.opcionFuentesContacto'), 'value', 'text', $opcionFuentes, true);?>
                </select>
            </div>
            <div class="filter-search btn-group pull-left">
                <label for="filter_estatus" class="element-invisible"><?php echo JText::_('Estatus');?></label>
                <select name="filter_estatus" id="filter_estatus" class="hasTooltip" title="<?php echo JHtml::tooltipText('Estatus'); ?>" style="width:170px;">
                    <?php echo JHtml::_('select.options', JHtml::_('modules.opcionEstatusContacto'), 'value', 'text', $opcionEstatus, true);?>
                </select>
            </div>

            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>
                <button type="button" id="limpiarFiltros" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" ><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
            </div>
        </div>
    </div>

    <div class="filter-select fltrt">
        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("17", $this->groups) || in_array("20", $this->groups) ){ ?>
        <button type="button" data-toggle="modal" data-target="#popup_asignar_gerente" class="btn btn-small button-new selAsignarGerente">
            </span>Reasignar Gerente
        </button>
        <?php } ?>
        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("20", $this->groups) ){ ?>
        <button type="button" data-toggle="modal" data-target="#popup_asignar" class="btn btn-small button-new selAsignar">
            </span>Reasignar Agente
        </button>
        <?php } ?>
        <button type="button" data-toggle="modal" data-target="#popup_descartar" class="btn btn-small button-new selDescartar">
            </span>Descartar
        </button>
    </div>
    <br/>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'ID', 'idDatoContacto', $direction, $ordering); ?>
                </th>
                <th width="20" class="nowrap center">
                    <?php echo JText::_('Sel'); ?>
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Nombre', 'nombre', $direction, $ordering); ?>
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Telefono', 'telefono', $direction, $ordering); ?>
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Email', 'email', $direction, $ordering); ?>
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Estatus', 'estatusId', $direction, $ordering); ?>
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'F. Alta', 'fechaAlta', $direction, $ordering); ?>
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'F. Contacto', 'fechaContacto', $direction, $ordering); ?>
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'F. Ult. Act.', 'fechaActualizacion', $direction, $ordering); ?>
                </th>
                <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("17", $this->groups) || in_array("20", $this->groups)){ ?>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Gerencia', 'gteVentasId', $direction, $ordering); ?>
                </th>
                <?php } ?>
                <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("20", $this->groups)){ ?>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Agente', 'agtVentasId', $direction, $ordering); ?>
                </th>
                <?php } ?>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Fuente', 'fuente', $direction, $ordering); ?>
                </th>
                <th class="nowrap center">
                    C
                </th>
                <th class="nowrap center">
                    <?php echo "Acciones"; ?>
                </th>
                <!-- ?php if(in_array("20", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("10", $this->groups) || in_array("8", $this->groups)){ ?>
                <th class="nowrap center">
                    ?php echo JHtml::_('grid.sort', 'Estatus', 'agtVentasId2', $direction, $ordering); ?>
                </th>
                ?php } ?> -->
                 <!-- ?php if(in_array("20", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("8", $this->groups) || in_array("10", $this->groups)){ ?>
                <th class="nowrap center">
                    ?php echo "Acciones"; ?>
                </th>
                ?php } ?> -->
            </tr>
        </thead>
        <tbody>
            <!-- <?php echo "------: ".count($this->items); ?> -->
            <!-- ?php exit(); ?> -->

            <?php foreach ($this->items as $i => $item): ?>
                <?php
                    $link = JRoute::_('index.php?option=com_sasfe&view=contacto&layout=editsu&id=' . $item->idDatoContacto);
                    // $link = JRoute::_('index.php?option=com_sasfe&view=contacto&layout=edit&id=' . $item->idDatoContacto);
                    // $linksl = JRoute::_('index.php?option=com_sasfe&view=contacto&layout=sololectura&id=' . $item->idDatoContacto. '&opc=1');
                    // $links2 = JRoute::_('index.php?option=com_sasfe&view=historialprospecto&id=' . $item->idDatoContacto);
                    // Obtener el historial de contactos por su id
                    // $colHistPros = SasfehpHelper::obtHistorialProspecto($item->idDatoContacto);

                    $colorEstatus = "";
                    if($item->estatusId!=""){
                        $colorEstatus = SasfehpHelper::colorEstatusContacto($item->estatusId);
                    }
                ?>
                <!-- style="<?php echo $colortr;?>" -->
                <tr class="<?php echo $colorEstatus; ?>  row<?php echo $i % 2; ?>" >
                    <td>
                        <?php echo $item->idDatoContacto; ?>
                    </td>
                    <td>
                        <?php echo JHtml::_('grid.id', $i, $item->idDatoContacto); ?>
                    </td>
                    <td class="center">
                        <?php echo $item->nombre." ".$item->aPaterno." ".$item->aMaterno; ?>
                    </td>
                    <td class="center">
                        <?php echo $item->telefono; ?>
                    </td>
                    <td class="center">
                        <?php echo $item->email; ?>
                    </td>
                    <td class="center">
                        <?php
                            if($item->estatusId!=""){
                                $colorEstatus = SasfehpHelper::colorEstatusContacto($item->estatusId);
                                switch ($item->estatusId) {
                                    case 1: echo '<span style="'.$colorEstatus.'">Asignado</span>'; break;
                                    case 2: echo '<span style="'.$colorEstatus.'">Seguimiento</span>'; break;
                                    case 3: echo '<span style="'.$colorEstatus.'">Contactado</span>'; break;
                                    case 4: echo '<span style="'.$colorEstatus.'">Descartado</span>'; break;
                                    case 5: echo '<span style="'.$colorEstatus.'">Prospecto</span>'; break;
                                    case 6: echo '<span style="'.$colorEstatus.'">Reasignado</span>'; break;
                                }
                            }
                        ?>
                    </td>
                    <td class="center">
                        <?php echo SasfehpHelper::conversionFechaF2($item->fechaAlta); ?>
                    </td>
                    <td class="center">
                        <?php if($item->fechaContacto!=""){
                            echo SasfehpHelper::conversionFechaF2($item->fechaContacto);
                        } ?>
                    </td>
                    <td class="center">
                        <?php echo SasfehpHelper::conversionFechaF2($item->fechaActualizacion); ?>
                    </td>
                    <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("17", $this->groups) || in_array("20", $this->groups)){ ?>
                    <td class="center">
                        <?php
                            if($item->gteVentasId!=""){
                                $datosUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($item->gteVentasId);
                                echo $datosUsrJoomla->name;
                            }
                        ?>
                    </td>
                    <?php } ?>
                    <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("20", $this->groups)){ ?>
                    <td class="center">
                        <?php
                            if($item->agtVentasId!=""){
                                $datosUsrJoomla2 = SasfehpHelper::obtInfoUsuariosJoomla($item->agtVentasId);
                                if($datosUsrJoomla2!=""){
                                    echo $datosUsrJoomla2->name;
                                }
                            }
                        ?>
                    </td>
                    <?php } ?>
                    <td class="center">
                        <?php
                        if($item->fuente!="" && is_numeric($item->fuente)){
                           $respTC = SasfehpHelper::obtTipoCaptado($item->fuente);
                           echo $respTC->tipoCaptado;
                        }
                        // echo $item->fuente;
                        ?>
                    </td>
                    <td class="center">
                        <?php
                            echo SasfehpHelper::contarAccionesContacto($item->idDatoContacto);
                        ?>
                    </td>
                    <td class="center">
                        <?php $agtVentasIdNA = ($item->agtVentasId!="" && $item->agtVentasId!=0)?$item->agtVentasId:0; ?>
                        <?php $gteVentasIdNA = ($item->gteVentasId!="" && $item->gteVentasId!=0)?$item->gteVentasId:0; ?>
                        <a href="#" data-toggle="modal" data-target="#popup_agregaraccion" idCont="<?php echo $item->idDatoContacto;?>" idAgt="<?php echo $agtVentasIdNA;?>" class="selAgregarAccion">+Acci&oacute;n</a>
                        | <a href="<?php echo $link; ?>">Ver</a>
                        | <a href="#" data-toggle="modal" data-target="#popup_cambiarestatus" idCont="<?php echo $item->idDatoContacto;?>" class="selCambiarEstatus">Estatus</a>

                        <input type="hidden" id="idGteVentas_<?php echo $item->idDatoContacto;?>" value="<?php echo $gteVentasIdNA;?>">
                        <input type="hidden" id="idAgtVentas_<?php echo $item->idDatoContacto;?>" value="<?php echo $agtVentasIdNA;?>">
                    </td>

                    <!--
                    <?php if(in_array("20", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("10", $this->groups) || in_array("8", $this->groups)){ ?>
                    <td class="center">
                        <?php
                            if($item->departamentoId!="" && $item->fechaDptoAsignado==""){
                                $asigText = "Apartado provisional";
                            }
                            elseif($item->departamentoId!="" && $item->fechaDptoAsignado!=""){
                                $asigText = "Apartado definitivo";
                            }
                            else{
                                $asigText = ($item->agtVentasId!="") ?"Agt. Asignado" :"Por Asignar Agt.";
                            }
                            echo $asigText;
                        ?>
                    </td>
                    <?php } ?>
                    <td class="center">
                        <?php
                            if($item->agtVentasId!=""){
                                $datosUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($item->agtVentasId);
                                echo $datosUsrJoomla->name;
                            }
                        ?>
                    </td>
                    <?php if(in_array("20", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups)){ ?>
                    <td class="center">
                        <?php if(in_array("20", $this->groups) || in_array("11", $this->groups)){ ?>
                            <a href="<?php echo $linksl; ?>">Ver detalle</a>
                            <?php if(in_array("11", $this->groups) && ($item->departamentoId=="" && $item->fechaDptoAsignado=="") ){ ?>
                                  | <a href="#" data-toggle="modal" data-target="#popup_asignar" idPros="<?php echo $item->idDatoContacto;?>" class="selAsigAgteVentasLink">Re/Asignar</a>
                            <?php } ?>
                            <?php if(in_array("20", $this->groups)){ ?>
                                  <a href="#" data-toggle="modal" data-target="#popup_asignargteventas" idPros="<?php echo $item->idDatoContacto;?>" class="selAsigGteVentasLink">Re/Asignar</a>
                            <?php } ?>
                        <?php }else{ ?>
                            <?php if($item->fechaDptoAsignado!=""){ ?>
                                <a href="<?php echo $link; ?>">Editar</a>
                            <?php }else{ ?>
                                <?php if(in_array("17", $this->groups)){ ?>
                                <a href="<?php echo $linksl; ?>">Ver detalle</a>
                                <?php }else{ ?>
                                <a href="<?php echo $link; ?>">Editar</a>
                                | <a href="#" data-toggle="modal" data-target="#popup_agregarevento" class="selProspecto" idPros="<?php echo $item->idDatoContacto;?>">Evento</a>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        <?php if(count($colHistPros)>0){ ?>
                            |
                                <a href="<?php echo $links2; ?>">Historial</a>
                        <?php } ?>
                    </td>
                    <?php } ?>
                    <?php if(in_array("8", $this->groups) || in_array("10", $this->groups)){ ?>
                    <td class="center">
                        <?php if($item->departamentoId!="" && $item->fechaDptoAsignado!=""){ ?>
                            <a href="<?php echo $linksl; ?>">Ver detalle</a> |
                        <?php }else{ ?>
                            <a href="index.php?option=com_sasfe&view=contactos&layout=editsu&id=<?php echo $item->idDatoContacto; ?>">Editar</a> |
                        <?php } ?>
                        <a href="#" data-toggle="modal" data-target="#popup_agregarevento" class="selProspecto" idPros="<?php echo $item->idDatoContacto;?>">Evento</a>
                        <?php if(count($colHistPros)>0){ ?>
                            | <a href="<?php echo $links2; ?>">Historial</a>
                        <?php } ?>
                        <?php if($item->departamentoId=="" && $item->fechaDptoAsignado==""){ ?>
                              | <a href="#" data-toggle="modal" data-target="#popup_asignar" idPros="<?php echo $item->idDatoContacto;?>" class="selAsigAgteVentasLink">Re/Asignar</a>
                        <?php } ?>
                    </td>
                    <?php } ?> -->
                </tr>
            <?php endforeach; ?>

        </tbody>
        <tfoot>
            <!-- <tr>
                <?php if(in_array("20", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups)){ ?>
                <td colspan="17"><?php echo $this->pagination->getListFooter(); ?></td>
                <?php }else{ ?>
                <td colspan="16"><?php echo $this->pagination->getListFooter(); ?></td>
                <?php } ?>
            </tr> -->
            <tr>
                <td colspan="14"><?php echo $this->pagination->getListFooter(); ?></td>
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
    </div>
    <input type="hidden" id="loading_img" value="<?php echo $this->imgLoading; ?>" />
</form>


<!-- Modal agregar accion -->
<div class="modal fade" id="popup_agregaraccion" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Nueva acci&oacute;n</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregaraccion" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=contacto.agregarAccion'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="agaccion_fecha">Fecha:</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="agaccion_fecha" id="agaccion_fecha" value="<?php echo $timeZone->fechaF2; ?>" class="hasTooltip" style="width:80px;" readonly/>
                    </div>
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="agaccion_accion">Acci&oacute;n:</label>
                    </div>
                    <div class="controls">
                        <select name="agaccion_accion" id="agaccion_accion" class="required">
                            <option value="">--Seleccionar--</option>
                            <?php
                            foreach ($colAccionesContacto as $item) {
                                echo '<option value="' . $item->idAccion . '">' . $item->nombre . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="agaccion_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="agaccion_comentario" id="agaccion_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_agregaraccion">Aceptar</button>
                </div>

                <input type="hidden" name="arrIdCont" id="arrIdCont" value="" />
                <input type="hidden" name="idAgtVentasNA" id="idAgtVentasNA" value="0" />
            </form>
          </div>
      </div>
    </div>
</div>

<!-- Modal gerente de ventas (asignar a otros agentes de venta) -->
<div class="modal fade" id="popup_asignar" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Asignar agente(s) de venta(s) a contacto(s)</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_asignarcontactosasesor" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=contacto.asignarContactoAsesor'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="asig_agtventas">Agente Venta:</label>
                    </div>
                    <div class="controls">
                        <select name="asig_agtventas" id="asig_agtventas" class="required">
                            <!-- <option value="">--Seleccionar--</option>
                            ?php
                            foreach ($this->ColAsesores as $itemAse) {
                                echo '<option value="' . $itemAse->idDato . '">' . $itemAse->nombre . '</option>';
                            }
                            ?> -->
                        </select>
                    </div>
                </div>
                <!-- <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="asig_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="asig_comentario" id="asig_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div> -->

                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_asignarContactoAsesor">Aceptar</button>
                </div>

                <input type="hidden" name="arrIdsContactos" id="arrIdsContactos" value="" />
                <input type="hidden" name="arrIdsAsesores" id="arrIdsAsesores" value="" />
            </form>
          </div>
      </div>
    </div>
</div>

<!-- Modal gerente de ventas (asignar a nuevo gerente y nuevo asesor) -->
<div class="modal fade" id="popup_asignar_gerente" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <!-- <h4 class="modal-title" id="title_asignar_gerente">Asignar gerente y agente venta a contacto(s)</h4> -->
            <h4 class="modal-title" id="title_asignar_gerente">Asignar gerente ventas a contacto(s)</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_asignarcontactosgerente" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=contacto.asignarContactoGerente'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="nombreGteJoomlaVentas">Gerente Ventas:</label>
                    </div>
                    <div class="controls">
                        <select id="nombreGteJoomlaVentas" name="nombreGteJoomlaVentas" class="opcGteSel required">
                            <option value="">--Seleccione--</option>
                            <?php
                            foreach ($colUsrGtesVentas as $itemGteV) {
                                echo '<option value="' . $itemGteV->id . '">' . $itemGteV->name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="usuarioAgenteVentas">Agente Ventas:</label>
                    </div>
                    <div class="controls" id="cont_usuarioAgenteVentas">
                        <select name="usuarioAgenteVentas" id="usuarioAgenteVentas" class="required">
                            <option value="">--Seleccionar--</option>
                        </select>
                    </div>
                </div> -->
                <!--
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="asig_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="asig_comentario" id="asig_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div> -->

                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_asignarContactoGteAsesor">Aceptar</button>
                </div>

                <input type="hidden" name="arrIdsContactos2" id="arrIdsContactos2" value="" />
                <input type="hidden" name="arrIdsGerentes2" id="arrIdsGerentes2" value="" />
                <input type="hidden" name="arrIdsAsesores2" id="arrIdsAsesores2" value="" />
            </form>
          </div>
      </div>
    </div>
</div>

<!-- Modal comentario descartar-->
<div class="modal fade" id="popup_descartar" role="dialog" style="width:500px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comentario para descartar contacto(s)</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregar_comentario_descartar" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=contacto.descartar'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="motivorechazo">Motivo rechazo:</label>
                    </div>
                    <div class="controls">
                        <select name="motivorechazo" id="motivorechazo" class="required">
                            <option value="">--Seleccionar--</option>
                            <?php
                            foreach ($colMotivosRechazo as $item) {
                                echo '<option value="' . $item->idMotivo . '">' . $item->titulo . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_comentariodescartar">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="ev_comentariodescartar" id="ev_comentariodescartar" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_descartar">Agregar comentario</button>
                </div>

                <input type="hidden" name="arrIdsContactos3" id="arrIdsContactos3" value="" />
            </form>
          </div>
      </div>
    </div>
</div>

<!-- Modal cambiar estatus -->
<div class="modal fade" id="popup_cambiarestatus" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Cambiar estatus</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_cambiarestatus" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=contacto.cambiarEstatusVC'); ?>" method="post">
                <!-- <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="agaccion_fecha">Fecha:</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="agaccion_fecha" id="agaccion_fecha" value="<?php echo $timeZone->fechaF2; ?>" class="hasTooltip" style="width:80px;" readonly/>
                    </div>
                </div> -->
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="estatusContactoId">Estatus:</label>
                    </div>
                    <div class="controls">
                        <select id="estatusContactoId" name="estatusContactoId" class="required">
                            <option value="">--Seleccione--</option>
                            <?php
                            foreach ($colEstatusContacto as $item) {
                                if ($item->idEstatus!=6 && $item->idEstatus!=5){
                                    echo '<option value="' . $item->idEstatus . '">' . $item->nombre . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="cont_motivocomentario_descartado" style="display:none;">
                    <div class="control-group ctrgr_popup">
                        <div class="control-label">
                            <label for="opc_motivo_descartado">Opc. Motivo descarte:</label>
                        </div>
                        <div class="controls">
                            <select name="opc_motivo_descartado" id="opc_motivo_descartado">
                                <option value="">--Seleccionar--</option>
                                <?php
                                foreach ($colMotivosRechazo as $item) {
                                    echo '<option value="' . $item->idMotivo . '">' . $item->titulo . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group ctrgr_popup">
                        <div class="control-label">
                            <label for="motivo_descartado">Motivo de descarte:</label>
                        </div>
                        <div class="controls">
                            <textarea name="motivo_descartado" id="motivo_descartado" class="form-control" style="min-width:60%;"></textarea>
                        </div>
                    </div>
                    <div class="control-group ctrgr_popup" id="cont_comentario_descartado">
                        <div class="control-label">
                            <label for="comentario_descartado">Comentario:</label>
                        </div>
                        <div class="controls">
                            <textarea name="comentario_descartado" id="comentario_descartado" class="form-control" style="min-width:60%;"></textarea>
                        </div>
                    </div>
                </div>

                <!-- <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="agaccion_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="agaccion_comentario" id="agaccion_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div> -->

                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_cambiarestatus">Aceptar</button>
                </div>

                <input type="hidden" name="arrIdsContactos4" id="arrIdsContactos4" value="" />
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
              <h4 class="modal-title">Evento para contacto</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregar_evento" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=contacto.addevento'); ?>" method="post">
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
              <h4 class="modal-title">Comentario para prospecto</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregar_comentario" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=contacto.addcomentario'); ?>" method="post">
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
            <h4 class="modal-title">Asignar gerente(s) de venta(s) a prospecto(s)</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_asignargteventas" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=contacto.asignarprospectoagteventas'); ?>" method="post">
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


 <!-- Modal proteger prospecto (solo getentes de venta) -->
<div class="modal fade" id="popup_protegerpros" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Proteger prospecto(s)</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_protegerpros" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=prospecto.protegerpros'); ?>" method="post">
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
 <!-- Modal seleccionar fechas para descargar contactos en un excel -->
<div class="modal fade" id="popup_descargaprospectos" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Descargar prospectos</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_descargaprospectos" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=reportes.descargaProspecto'); ?>" method="post">
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
//11 = gerente de ventas, 20 = redes, //17 = prospectadores
if(in_array("11", $this->groups) || in_array("20", $this->groups) || in_array("17", $this->groups)){
    $hidd_grp = 'porasignar';
}
//18 = agentes de ventas
if(in_array("18", $this->groups)){
    $hidd_grp = 'asignados';
}
echo '<input type="hidden" id="hidd_checkestatus" value="'.$checkFilEstatus.'" style="display:none;" />';
echo '<input type="hidden" id="hidd_grp" value="'.$hidd_grp.'" style="display:none;" />';


// Obtener col asesores por id de gerente
$arrColAsesoresXGte = [];
foreach ($colUsrGtesVentas as $key=>$elemGte) {
    $colAsesorTmp = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($elemGte->id);
    foreach ($colAsesorTmp as $key2=>$elemAgte) {
        // unset($colAsesorTmp[$key2]->idDato);
        unset($colAsesorTmp[$key2]->catalogoId);
        unset($colAsesorTmp[$key2]->pordefault);
        unset($colAsesorTmp[$key2]->activo);
        unset($colAsesorTmp[$key2]->usuarioIdGteJoomla);
    }
    $arrColAsesoresXGte[$elemGte->id] = $colAsesorTmp;
}
// echo "<pre>";
// print_r($arrColAsesoresXGte);
// echo "</pre>";

//Imp. 06/10/2020
// Obtener col motivos rechazo por id
$arrColMotivosRechazo = [];
foreach ($colMotivosRechazo as $key=>$elemMR) {
    $arrColMotivosRechazo[$elemMR->idMotivo] = array("idMotivo"=>$elemMR->idMotivo, "texto"=>$elemMR->texto);
}
?>

<script>
var arrColAsesoresXGte = [];
JQ(document).ready(function(){
  arrColAsesoresXGte = '<?php echo json_encode($arrColAsesoresXGte); ?>';
});

//Imp. 06/10/2020
var arrColMotivosRechazo = [];
JQ(document).ready(function(){
  arrColMotivosRechazo = '<?php echo json_encode($arrColMotivosRechazo); ?>';
});
</script>