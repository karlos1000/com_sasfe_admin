<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');
JViewLegacy::loadHelper('sasfehp');                    

//obtener el usuario segun el tipo de catalogo
$colUsrGtesVentas = array();
$colUsrGtesProspeccion = array();
$usuarioIdJoomla = (isset($this->data[0]->usuarioIdJoomla))?$this->data[0]->usuarioIdJoomla:'';
//prospectadores
if($this->idCat==4){    
    $colUsuariosIdsJoomla = SasfehpHelper::obtUsuariosJoomlaPorGrupo(17); 
    $colUsrGtesVentas = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11); //gerente de ventas
    $colUsrGtesProspeccion = SasfehpHelper::obtUsuariosJoomlaPorGrupo(19); //gerente prospeccion
}
//agentes de venta
if($this->idCat==3){    
    $colUsuariosIdsJoomla = SasfehpHelper::obtUsuariosJoomlaPorGrupo(18); 
    $colUsrGtesVentas = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11); //gerente de ventas   
}
//gerentes de venta
if($this->idCat==1){
    $colUsuariosIdsJoomla = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11);
    $colFraccionamientos = SasfehpHelper::obtTodosFraccionamientos();
    $usuarioIdJoomlaFracc = ($usuarioIdJoomla !== '')?$usuarioIdJoomla:0;
    $fraccGer = SasfehpHelper::obtTodosFraccionamientosGerente($usuarioIdJoomlaFracc);
    $arrFraccGerIds = (isset($fraccGer->fraccionamientosId))?explode(",", $fraccGer->fraccionamientosId):array();
}
// echo "<pre>"; print_r($colUsuariosIdsJoomla); echo "</pre>";
// echo "<pre>"; print_r($colUsrGtesVentas); echo "</pre>";
// echo "<pre>"; print_r($colUsrGtesProspeccion); echo "</pre>";

$nombre = (isset($this->data[0]->nombre))?$this->data[0]->nombre:'';
$catalogoId = (isset($this->data[0]->catalogoId))?$this->data[0]->catalogoId:'';
$default = (isset($this->data[0]->pordefault))?$this->data[0]->pordefault:'';
$activo = (isset($this->data[0]->activo))?$this->data[0]->activo:'';
$usuarioIdGteJoomla = (isset($this->data[0]->usuarioIdGteJoomla))?$this->data[0]->usuarioIdGteJoomla:'';
//Saber a que grupo pertenece
$contGteVentas = false;
$contGteProspeccion = false;
if($usuarioIdGteJoomla!=""){    
    $grupos = JAccess::getGroupsByUser($usuarioIdGteJoomla, true);  //obtiene grupo/s por id de usuario 
    //grupo gerente de ventas
    if(in_array("11", $grupos)){
        $contGteVentas = true;
    }
    if(in_array("19", $grupos)){
        $contGteProspeccion = true;
    }
    // echo "<pre>"; print_r($grupos); echo "</pre>";
}

//echo '<pre>';
//print_r($this->data);
//echo '</pre>';
?>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=catgenerico'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">    
    <div class="notesUlEsphabit">        
    </div>        
    
    <div style="">
        <fieldset class="adminform">        
            <div class="control-group">
                <div class="control-label">
                <label for="dato_cat_nombre">Nombre: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                <input type="text" name="dato_cat_nombre" id="dato_cat_nombre" value="<?php echo $nombre; ?>" class="required" style="width:150px;"/>                        
            </div>                        
            </div>
            <div class="control-group">
                <div class="control-label">
                <label for="dato_cat_default">Default:</label>                                
                </div>
                <div class="controls">
                <input type="checkbox" name="dato_cat_default" id="dato_cat_default" value="<?php echo $default; ?>" <?php echo ($default==1)?'checked':''; ?>  />                                       
            </div>                                                  
            </div>                                                  
            <div class="control-group">
                <div class="control-label">
                <label for="dato_cat_activo">Activo:</label>                                
                </div>
                <div class="controls">
                <input type="checkbox" name="dato_cat_activo" id="dato_cat_activo" value="<?php echo $activo; ?>" <?php echo ($activo==1)?'checked':''; ?>  />                                       
                </div>
            </div>                                                  
            
            <?php if($this->idCat==4 || $this->idCat==3 || $this->idCat==1) { ?>
            <!-- id usuarios joomla -->
            <div class="control-group">
                <div class="control-label">
                    <label for="usuarioIdJoomla">Usuario Joomla:</label>                
                </div>
                <div class="controls">
                    <select id="usuarioIdJoomla" name="usuarioIdJoomla" class="required">                  
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($colUsuariosIdsJoomla as $itemUJoomla) {
                            if ($usuarioIdJoomla == $itemUJoomla->id)
                                $sel = 'selected';
                            else
                                $sel = '';
                            
                            echo '<option '.$sel.' value="' . $itemUJoomla->id . '">' . $itemUJoomla->name . '</option>';
                        }
                        ?>
                    </select>               
                </div>
            </div>
            <?php } ?>

            <?php if($this->idCat==1) { ?>
            <!-- id usuarios joomla -->
            <div class="control-group">
                <div class="control-label">
                    <label for="fraccEspecialitas">Fraccionamientos (especialista):</label>                
                </div>
                <div class="controls">
                    <select id="fraccEspecialitas" name="fraccEspecialitas[]" class="" multiple="">                  
                        <!-- <option value="">--Seleccione--</option> -->
                        <?php
                        foreach ($colFraccionamientos as $itemFracc) {
                            if ($usuarioIdJoomla == $itemFracc->idFraccionamiento)
                                $sel = 'selected';
                            else
                            if(isset($itemFracc->idFraccionamiento) && $itemFracc->idFraccionamiento != ""){
                                $sel = (in_array($itemFracc->idFraccionamiento, $arrFraccGerIds))?'selected':'';
                            }else{
                                $sel = '';
                            }
                            
                            echo '<option '.$sel.' value="' . $itemFracc->idFraccionamiento . '">' . $itemFracc->nombre . '</option>';
                        }
                        ?>
                    </select>               
                </div>
            </div>
            <?php } ?>

            <?php if($this->idCat==3) { ?>
            <!-- gerentes de ventas -->            
            <div class="control-group" id="cont_gerente_ventas">
                <div class="control-label">
                    <label for="usuarioIdGteVenta">Gerentes Venta:</label>
                </div>
                <div class="controls">
                    <!-- class="required" -->
                    <select id="usuarioIdGteVenta" class="opcSelGte required">
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($colUsrGtesVentas as $itemUVenta) {
                            if ($usuarioIdGteJoomla == $itemUVenta->id)
                                $sel = 'selected';
                            else
                                $sel = '';                            
                            
                            echo '<option '.$sel.' value="' . $itemUVenta->id . '">' . $itemUVenta->name . '</option>';
                        }
                        ?>
                    </select>               
                </div>
            </div>
            <?php } ?>


            <?php if($this->idCat==4) { ?>            
            <!-- active btn-success -->
            <div class="control-group">
                <div class="control-label">
                    <label for="opc_gerente">Seleccionar Gerente</label>
                </div>
                <div class="controls">
                    <fieldset id="jform_opc_gerente" class="btn-group btn-group-yesno radio">
                        <input type="radio">
                        <label class="btn" id="opc_gerente_ventas">Ventas</label>
                        <input type="radio">
                        <label class="btn" id="opc_gerente_prospeccion">Prospecci&oacute;n</label>
                    </fieldset>
                </div>
            </div>

            <!-- gerentes de ventas -->            
            <div class="control-group" id="cont_gerente_ventas" <?php echo ($contGteVentas==true) ?'' :'style="display:none;"';?> >
                <div class="control-label">
                    <label for="usuarioIdGteVenta">Gerentes Venta:</label>
                </div>
                <div class="controls">
                    <!-- class="required" -->
                    <select id="usuarioIdGteVenta" class="opcSelGte <?php echo ($contGteVentas==true) ?'required' :'';?> " >
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($colUsrGtesVentas as $itemUVenta) {
                            if ($usuarioIdGteJoomla == $itemUVenta->id)
                                $sel = 'selected';
                            else
                                $sel = '';                            
                            
                            echo '<option '.$sel.' value="' . $itemUVenta->id . '">' . $itemUVenta->name . '</option>';
                        }
                        ?>
                    </select>               
                </div>
            </div>
            <!-- gerentes de prospeccion -->
            <div class="control-group" id="cont_gerente_prospeccion" <?php echo ($contGteProspeccion==true) ?'' :'style="display:none;"';?>>
                <div class="control-label">
                    <label for="usuarioIdGteProspeccion">Gerentes Prospecci&oacute;n:</label>
                </div>
                <div class="controls">
                    <!-- class="required" -->
                    <select id="usuarioIdGteProspeccion" class="opcSelGte <?php echo ($contGteProspeccion==true) ?'required' :'';?> " >
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($colUsrGtesProspeccion as $itemUProspeccion) {
                            if ($usuarioIdGteJoomla == $itemUProspeccion->id)
                                $sel = 'selected';
                            else
                                $sel = '';    

                            echo '<option '.$sel.' value="' . $itemUProspeccion->id . '">' . $itemUProspeccion->name . '</option>';
                        }
                        ?>
                    </select>               
                </div>
            </div>
            <?php } ?>
            

        </fieldset>
    </div> 
        
        <input type="hidden" name="task" value="catgenerico.edit" />
        <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />                        
        <input type="hidden" name="dato_cat_catId" value="<?php echo $this->idCat; ?>" />

        <input type="hidden" name="usuario_gte_id" id="usuario_gte_id" value="<?php echo $usuarioIdGteJoomla;?>" />
        
        <?php echo JHtml::_('form.token'); ?>    
    
</form>
