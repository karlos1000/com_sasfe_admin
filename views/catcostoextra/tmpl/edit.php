<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');

$idDatoCE = (isset($this->data[0]->idDatoCE))?$this->data[0]->idDatoCE:'';
$catalogoId = (isset($this->data[0]->catalogoId))?$this->data[0]->catalogoId:'';
$idFracc = (isset($this->data[0]->fraccionamientoId))?$this->data[0]->fraccionamientoId:'';
$nombre = (isset($this->data[0]->nombre))?$this->data[0]->nombre:'';
$costo = (isset($this->data[0]->costo))?$this->data[0]->costo:'';
$activo = (isset($this->data[0]->activo))?$this->data[0]->activo:'';
	
//echo '<pre>';
//print_r($this->data);
//print_r($this->Fracc);
//echo '</pre>';


?>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=catcostoextra'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">    
    <div class="notesUlEsphabit">        
    </div>        
    
    <div style="">
        <fieldset class="adminform">                                    
            <div class="control-group">
                <div class="control-label">
                <label for="dato_cat_fracc">Fraccionamiento: <span class="star">&nbsp;*</span></label>                
                </div>
                <div class="controls">
                <select id="idFracc" name="dato_cat_fracc" class="required">                  
                    <option value="">--Seleccione--</option>
                    <?php
                    foreach ($this->Fracc as $item) {
                        if ($idFracc == $item->idFraccionamiento)
                            $sel = 'selected';
                        else
                            $sel = '';
                        echo '<option ' . $sel . ' value="' . $item->idFraccionamiento . '">' . $item->nombre . '</option>';                        
                    }
                    ?>                    
                </select>               
            </div> 
            </div> 
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
                <label for="dato_cat_costo">Costo: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                <input type="text" name="dato_cat_costo" id="dato_cat_costo" value="<?php echo $costo; ?>" class="required" style="width:150px;"/>                        
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
        </fieldset>
    </div> 
        
        <input type="hidden" name="task" value="catcostoextra.edit" />
        <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />                        
        <input type="hidden" name="dato_cat_catId" value="<?php echo $this->idCat; ?>" />                        
        
        <?php echo JHtml::_('form.token'); ?>    
    
</form>
