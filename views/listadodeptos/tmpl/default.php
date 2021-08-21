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

$ordering	= $this->escape($this->state->get('list.ordering'));
$direction	= $this->escape($this->state->get('list.direction'));


function regresarNodo($idDpt, $arr){    
    $params = array();
    
//    echo '<pre>';
//        print_r($arr);
//    echo '</pre>';
    
    foreach ($arr as $i => $item){
//        echo 'DatoGral: ' .$item->idDatoGeneral .'<br/>';
        
        if($item->esHistorico==0 && $item->idDepartamento==$idDpt){                        
            
            $params = (object)array("numero"=>$item->numero, "fechaCierre"=>$item->fechaCierre, "fechaApartado"=>$item->fechaApartado, "nombre"=>$item->nombreC, "tipoCreditoId"=>$item->tipoCreditoId,
                                    "estatus"=>$item->estatus, "esHistorico"=>$item->esHistorico, "idEstatus"=>$item->idEstatus, "idDatoGeneral"=>$item->idDatoGeneral, "counter"=>$item->counter, "tipoCredito"=>$item->tipoCredito, "fechaDTU"=>$item->fechaDTU,
                                    "idAsesor"=>$item->idAsesor, "nAsesor"=>$item->nAsesor, "idGerenteVentas"=>$item->idGerenteVentas, "nGteVentas"=>$item->nGteVentas, "idPropectador"=>$item->idPropectador, "nProspectador"=>$item->nProspectador);
            
            break;
        }elseif($item->esHistorico==1 && $item->idDepartamento==$idDpt){
            $params = (object)array("numero"=>$item->numero, "fechaCierre"=>$item->fechaCierre, "fechaApartado"=>$item->fechaApartado, "nombre"=>$item->nombreC, "tipoCreditoId"=>$item->tipoCreditoId,
                                    "estatus"=>$item->estatus, "esHistorico"=>$item->esHistorico, "idEstatus"=>$item->idEstatus, "idDatoGeneral"=>$item->idDatoGeneral, "counter"=>$item->counter, "tipoCredito"=>$item->tipoCredito, "fechaDTU"=>$item->fechaDTU,
                                    "idAsesor"=>$item->idAsesor, "nAsesor"=>$item->nAsesor, "idGerenteVentas"=>$item->idGerenteVentas, "nGteVentas"=>$item->nGteVentas, "idPropectador"=>$item->idPropectador, "nProspectador"=>$item->nProspectador);

        }        
    }
    return $params;
}

?>
<div class="notesUlMozi">      
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=listadodeptos&idFracc='.$this->idFracc); ?>" method="post" name="adminForm" id="adminForm">
   
    <!-- <div class="left">
        <label for="filter_search">
                <?php echo 'No. Dpto:'; ?>
        </label>
        <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('Filtro'); ?>" />

        <button type="submit">
                <?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
        <button type="button" onclick="document.id('filter_search').value='';this.form.submit();">
                <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
    </div> -->
    <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('No. Dpto.');?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('No. Dpto.'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('No. Dpto.'); ?>" />
            </div>
            <div class="btn-group pull-left">
                    <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>
                    <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
            </div>
    </div>
    
    <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups)){ ?>
    <br/>
    <div class="filter-select fltrt">
        <button type="button" class="btn btn-small button-new" onclick="Joomla.submitbutton('reportes.descargaSembradoDesdeListadoDptos');">
            </span>Descargar sembrado
        </button>
        <input type="hidden" name="hid_idFracc" value="<?php echo $this->idFracc;?>">
    </div>
    <br/>
    <?php } ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="80" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Depto', 'numero', $direction, $ordering); ?>                    
                </th>
                <!-- <th width="100" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Fecha DTU', 'fechaDTU', $direction, $ordering); ?>  
                </th> -->
                <th width="80" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Asesor', 'idAsesor', $direction, $ordering); ?>                    
                </th>
                <th width="80" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Gte.Ventas', 'idGerenteVentas', $direction, $ordering); ?>                    
                </th>
                <th width="80" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Prospectador', 'idPropectador', $direction, $ordering); ?>
                </th>
                <th width="100" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Fecha Cierre', 'fechaCierre', $direction, $ordering); ?>  
                </th>
                <th width="100" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Fecha Apartado', 'fechaApartado', $direction, $ordering); ?>  
                </th>                                
                <th width="100" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Cliente', 'nombre', $direction, $ordering); ?>  
                </th>                                
                <th width="100" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Tipo de cr&eacute;dito', 'tipoCreditoId', $direction, $ordering); ?>  
                </th>
                <th width="100" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Estatus', 'estatus', $direction, $ordering); ?>  
                </th>
                <th width="100" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Historico', 'esHistorico', $direction, $ordering); ?>  
                </th>
            </tr>
        </thead>
        <tbody>                        
            <?php foreach ($this->items as $i => $item): ?>
                    <?php                      
                            //echo 'El dato general es: ' .$item->idDatoGeneral;
                            //echo '<br/>';
                          // echo "<pre>".print_r($this->dpts)."</pre>";
                          $itemInt =  regresarNodo($item->idDepartamento, $this->dpts);
                          
//                            echo '<pre>';
//                            print_r($itemInt);
//                            echo '</pre>';
                    ?>    
                        <tr class="row<?php echo $i % 2; ?>">                                                                                                                                                                                
                                    <td class="center">
                                       <?php if($itemInt->idDatoGeneral>0){                                                                
                                                if($itemInt->esHistorico==1){ ?>
                                                    <?php if($this->permiso==true){ ?>                                        
                                                    <a href="<?php echo JRoute::_('index.php?option=com_sasfe&view=departamento&depto_id=' . $item->idDepartamento.'&idFracc='.$this->idFracc.'&idDatoGral=0'); ?>">
                                                        <?php echo $item->numero; ?>
                                                    </a>
                                                    <?php }else{ echo $item->numero; } ?>
                                        
                                             <?php }else{ ?>
                                                   <?php if($this->permiso==true){ ?> 
                                                    <a href="<?php echo JRoute::_('index.php?option=com_sasfe&view=departamento&depto_id=' . $item->idDepartamento.'&idFracc='.$this->idFracc.'&idDatoGral='.$itemInt->idDatoGeneral); ?>">
                                                        <?php echo $item->numero; ?>
                                                    </a>
                                                    <?php }else{ echo $item->numero; } ?> 
                                        
                                             <?php } ?>  
                                       <?php }else{ ?>
                                            <?php if($this->permiso==true){ ?>
                                            <a href="<?php echo JRoute::_('index.php?option=com_sasfe&view=departamento&depto_id=' . $item->idDepartamento.'&idFracc='.$this->idFracc.'&idDatoGral=0'); ?>">
                                                 <?php echo $itemInt->numero; ?>
                                            </a>
                                            <?php }else{ echo $item->numero; } ?>
                                        
                                       <?php }?> 
                                    </td>
                                    <!-- <td class="center">
                                        <?php 
                                            if($itemInt->esHistorico==0){
                                                echo $itemInt->fechaDTU;
                                            }
                                        ?>                        
                                    </td> -->
                                    <td class="center">
                                        <?php
                                            if($itemInt->esHistorico==0){ 
                                            echo $itemInt->nAsesor;
                                            }   
                                        ?>                        
                                    </td>
                                    <td class="center">
                                        <?php
                                            if($itemInt->esHistorico==0){ 
                                            echo $itemInt->nGteVentas;
                                            }
                                        ?>                        
                                    </td>
                                    <td class="center">
                                        <?php 
                                            if($itemInt->esHistorico==0){
                                            echo $itemInt->nProspectador;
                                            }
                                        ?>                        
                                    </td>                                        
                                    <td class="center">
                                        <?php 
                                            if($itemInt->esHistorico==0){
                                            echo $itemInt->fechaCierre;
                                            }
                                        ?>                        
                                    </td>                                        
                                    <td class="center">
                                        <?php 
                                            if($itemInt->esHistorico==0){
                                            echo $itemInt->fechaApartado;
                                            }
                                        ?>                        
                                    </td>                                                            
                                    <td class="center">
                                        <?php 
                                            if($itemInt->esHistorico==0){
                                            echo $itemInt->nombre;
                                            }
                                        ?>
                                    </td>                                                            
                                    <td class="center">
                                        <?php 
                                            if($itemInt->esHistorico==0){
                                            echo $itemInt->tipoCredito;
                                            }
                                        ?>                       
                                    </td>
                                    <td class="center">
                                        <?php                         
                                            if($itemInt->esHistorico==1){
                                                echo 'Disponible';  
                                            }elseif($itemInt->idEstatus!=''){                                                
                                                echo $itemInt->estatus;
                                            }else{
                                                echo 'Disponible';  
                                            }                                                      
                                        ?>
                                    </td>
                                    <td class="center">
                                        <?php 
                                            if($itemInt->counter>0){                                                
                                                $idDpt = $item->idDepartamento;
                                                $idFracc = $this->idFracc;                                                
                                        ?>
                                              <div>
                                                <?php if($this->permiso==true){ ?>
                                                  <a href="<?php echo JRoute::_('index.php?option=com_sasfe&view=listadohistorial&depto_id=' . $item->idDepartamento.'&idFracc='.$this->idFracc); ?>">
                                                   Ver
                                                  </a>
                                                <?php }else{ echo 'ver'; } ?>
                                              </div>   
                                        <?php    }                         
                                        ?>                      
                                    </td>                                                                                                               
                            </tr>                                                                           
            <?php endforeach; ?>
                         
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
            </tr>
        </tfoot>        			
    </table>
    <div>        
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />        
        <input type="hidden" name="filter_order" value="<?php echo $ordering; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $direction; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
