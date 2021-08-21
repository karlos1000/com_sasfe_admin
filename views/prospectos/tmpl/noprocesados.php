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
$timeZone = SasfehpHelper::obtDateTimeZone();

// echo "<pre>";
// print_r($this->items);
// echo "</pre>";

// echo "ESSSSS; ".$this->layout.'<br/>';

?>
<div class="notesUlMozi">      
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&layout=noprocesados'); ?>" method="post" name="adminForm" id="adminForm">
    
    <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible"><?php echo JText::_('Nombre|Apellidos');?></label>
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('Nombre|Apellidos'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Nombre|Apellidos'); ?>" style="width:170px;" />
            </div>
            <div class="filter-search btn-group pull-left">
                <label for="filter_montocto1" class="element-invisible"><?php echo JText::_('$ Monto cto. 1');?></label>
                <?php $montocto1 = ($this->escape($this->state->get('filter.montocto1')) != "") ?$this->escape($this->state->get('filter.montocto1')) :""; ?>
                <input type="text" name="filter_montocto1" id="filter_montocto1" placeholder="<?php echo JText::_('$ Monto cto. 1'); ?>" value="<?php echo $montocto1; ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('$ Monto cto. 1'); ?>" style="width:72px;" />
            </div>
            <div class="filter-search btn-group pull-left">
                <?php $montocto2 = ($this->escape($this->state->get('filter.montocto2')) != "") ?$this->escape($this->state->get('filter.montocto2')) :""; ?>
                <label for="filter_montocto2" class="element-invisible"><?php echo JText::_('$ Monto cto. 2');?></label>
                <input type="text" name="filter_montocto2" id="filter_montocto2" placeholder="<?php echo JText::_('$ Monto cto. 2'); ?>" value="<?php echo $montocto2; ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('$ Monto cto. 2'); ?>" style="width:72px;" />
            </div>
            <div class="filter-search btn-group pull-left">                
                <label for="filter_puntoshasta" class="element-invisible"><?php echo JText::_('Puntos Hasta');?></label>
                <input type="text" name="filter_puntoshasta" id="filter_puntoshasta" placeholder="<?php echo JText::_('Puntos Hasta'); ?>" value="<?php echo $this->escape($this->state->get('filter.puntoshasta')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Puntos Hasta'); ?>" style="width:80px;"/>
            </div>
            <div class="filter-search btn-group pull-left">                
                <label for="filter_tipocto" class="element-invisible"><?php echo JText::_('Tipo Cr&eacute;dito');?></label>
                <select name="filter_tipocto" id="filter_tipocto" class="hasTooltip" title="<?php echo JHtml::tooltipText('Tipo Crédito'); ?>" style="width:170px;">
                    <option value=""><?php echo JText::_('-Tipo Cr&eacute;dito-');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('modules.opcionTipoCreditos'), 'value', 'text', $opcionTipoCreditos, true);?>
                </select>
            </div>            

            

            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>
                <button type="button" id="limpiarFiltros" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" ><i class="icon-remove"></i></button>
                <!-- <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button> -->                
            </div>
            <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
            </div>
    </div>
    <br/>   
    <div class="filter-select fltrt">
        <!-- Para los gerentes de venta -->
        <?php if(in_array("11", $this->groups)){ ?>
        <button type="button" data-toggle="modal" data-target="#popup_asignar" class="btn btn-small button-new selAsignar">
            </span>Re/Asignar
        </button>
        <button type="button" class="btn btn-small button-new" onclick="Joomla.submitbutton('prospectos.repetidos');">
            </span>Revisar repetidos
        </button>
        <button type="button" data-toggle="modal" data-target="#popup_protegerpros" class="btn btn-small button-new selProtegerPros">
            </span>Proteger
        </button>
        <button onclick="Joomla.submitbutton('prospecto.miseventos');" class="btn btn-small button-new">
            </span>Ver eventos
        </button>         
        <?php } ?>

        <!-- Para los gerentes de prospeccion -->
        <?php if(in_array("19", $this->groups)){ ?>
        <button type="button" data-toggle="modal" data-target="#popup_asignargteventas" class="btn btn-small button-new selAsignarGteVentas">
            </span>Re/Asignar
        </button>
        <button type="button" class="btn btn-small button-new" onclick="Joomla.submitbutton('prospectos.repetidos');">
            </span>Revisar repetidos
        </button>
        <?php } ?>
    </div>
    <br/>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'ID', 'idDatoProspecto', $direction, $ordering); ?>                    
                </th>
                <th width="20" class="nowrap center">
                    <?php echo JText::_('Sel'); ?>                    
                </th>			
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Fecha Alta', 'fechaAlta', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Nombre', 'nombre', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Celular', 'celular', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Monto Cr&eacute;dito', 'montoCredito', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Tipo Cr&eacute;dito', 'tipoCreditoId', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo "Vence el"; ?>
                </th>
                <th class="nowrap center">
                    <?php echo "T. protegido"; ?>
                </th>
                <th width="300" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Comentario', 'comentario', $direction, $ordering); ?>  
                </th>
                <?php if(in_array("19", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups)){ ?>
                <th class="nowrap center">
                    <?php echo "Estatus"; ?>
                </th>
                <?php } ?>                
                <th class="nowrap center">
                    <?php echo "Agente"; ?>
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Comentario no procesados', 'comentarioNoProcesados', $direction, $ordering); ?>  
                </th>
                <!-- <th class="nowrap center">
                    <?php echo "Acciones"; ?>
                </th> -->
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($this->items as $i => $item): ?>
                <?php 
                    $link = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=edit&id=' . $item->idDatoProspecto);
                    $linksl = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=sololectura&id=' . $item->idDatoProspecto. '&opc=1');
                    $links2 = JRoute::_('index.php?option=com_sasfe&view=historialprospecto&id=' . $item->idDatoProspecto);
                    // Obtener el historial de prospecto por su id
                    $colHistPros = SasfehpHelper::obtHistorialProspecto($item->idDatoProspecto);
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
                        <?php echo $item->idDatoProspecto; ?>
                    </td>
                    <td>
                        <?php echo JHtml::_('grid.id', $i, $item->idDatoProspecto); ?>
                    </td>
                    <td class="center">
                        <?php echo SasfehpHelper::conversionFechaF2($item->fechaAlta); ?>                        
                    </td>
                    <td class="center">                        
                        <?php echo $item->nombre." ".$item->aPaterno." ".$item->aManterno; ?>
                        <!-- <a href="<?php echo $link; ?>">
                            <?php echo $item->nombre." ".$item->aPaterno." ".$item->aManterno; ?>
                        </a> -->
                    </td>
                    <td class="center">
                        <?php echo $item->celular; ?>                        
                    </td>
                    <td class="center">
                        <?php echo "$ ". number_format($item->montoCredito,2); ?>
                    </td>                    
                    <td class="center">
                        <?php echo $item->tipoCredito; ?>                        
                    </td>
                    <td class="center fechaAsig">
                        <?php echo ($item->periodoAsignacion!="") ?SasfehpHelper::conversionFechaF2($item->periodoAsignacion) :""; ?>
                    </td>
                    <td class="center">
                        <?php 
                            if($item->idTiempoProteccion!=""){
                                switch ($item->idTiempoProteccion) {            
                                    case 1: echo "1 semana"; break; //Una semana
                                    case 2: echo "15 d&iacute;as"; break; //15 dias
                                    case 3: echo "1 mes"; break; //un mes
                                }
                            }
                        ?>
                    </td>
                    <td class="left">                            
                        <?php                             
                            $lngComentario = strlen($item->comentario);
                            if($lngComentario>=200){
                                echo substr($item->comentario, 0, 180).'...<a href="'.$link.'">Ver m&aacute;s</a>';
                            }else{
                                echo $item->comentario;
                            }                            
                        ?>
                    </td>
                    <!-- gerentes de prospeccion y gerentes ventas -->
                    <?php if(in_array("19", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups)){ ?>
                    <!-- style="font-size:11px;" -->
                    <td class="center">
                        <?php 
                            $asigText = ($item->agtVentasId!="") ?"Asignado" :"Por asignar";
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
                    <td class="left">                            
                        <?php                             
                            $lngComentarioNP = strlen($item->comentarioNoProcesados);
                            if($lngComentarioNP>=200){
                                echo substr($item->comentarioNoProcesados, 0, 180).'...';
                            }else{
                                echo $item->comentarioNoProcesados;
                            }                            
                        ?>
                    </td>
                    <!-- <td class="center">                        
                        <?php if(in_array("19", $this->groups) || in_array("11", $this->groups)){ ?>
                            <a href="<?php echo $linksl; ?>">Ver detalle</a> | 
                            <?php if(in_array("11", $this->groups)){ ?>
                                  <a href="#" data-toggle="modal" data-target="#popup_asignar" idPros="<?php echo $item->idDatoProspecto;?>" class="selAsigAgteVentasLink">Re/Asignar</a>  
                            <?php } ?>
                            <?php if(in_array("19", $this->groups)){ ?>
                                  <a href="#" data-toggle="modal" data-target="#popup_asignargteventas" idPros="<?php echo $item->idDatoProspecto;?>" class="selAsigGteVentasLink">Re/Asignar</a>
                            <?php } ?>
                        <?php }else{ ?>
                            <?php if($item->fechaDptoAsignado!=""){ ?>    
                                <a href="<?php echo $link; ?>">Ver</a>
                            <?php }else{ ?>
                                <?php if(in_array("17", $this->groups)){ ?>                                
                                <a href="<?php echo $linksl; ?>">Ver detalle</a>
                                <?php }else{ ?> 
                                <a href="<?php echo $link; ?>">Editar</a>
                                | <a href="#" data-toggle="modal" data-target="#popup_agregarevento" class="selProspecto" idPros="<?php echo $item->idDatoProspecto;?>">Evento</a>
                                <?php } ?>
                            <?php } ?>                            
                        <?php } ?>
                        <?php if(count($colHistPros)>0){ ?>
                            | 
                                <a href="<?php echo $links2; ?>">Historial</a>
                        <?php } ?>                        
                    </td> -->
                </tr>                
            <?php endforeach; ?>
                         
        </tbody>
        <tfoot>
            <tr>
                <?php if(in_array("19", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups)){ ?>
                <td colspan="13"><?php echo $this->pagination->getListFooter(); ?></td>    
                <?php }else{ ?>
                <td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
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
    </div> 
    
</form>