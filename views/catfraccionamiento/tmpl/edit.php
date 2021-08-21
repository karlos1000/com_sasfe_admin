<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');

$idFracc = (isset($this->data[0]->idFraccionamiento))?$this->data[0]->idFraccionamiento:'';
$nombre = (isset($this->data[0]->nombre))?$this->data[0]->nombre:'';
$imagen = (isset($this->data[0]->imagen))?$this->data[0]->imagen:'';
$activo = (isset($this->data[0]->activo))?$this->data[0]->activo:'';

//echo '<pre>';
//print_r($this->data);
//echo '</pre>';

?>
<style>
.defaultKCD{ position: absolute;margin: 0px;padding: 0px;} label{ width: 170px; } 
.adminform label{
    min-width: 170px;
    padding: 0 5px 0 0; 
}    
</style>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=catfraccionamiento'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">    
    <div class="notesUlEsphabit">        
    </div>        
    
    <div style="">
        <fieldset class="adminform">        
                        
            <div class="control-group">
                <div class="control-label">
                <label for="fracc_nombre">Fraccionamiento: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                    <input type="text" name="fracc_nombre" id="fracc_nombre" value="<?php echo $nombre; ?>" required style="width:150px;"/>
            </div>            
            </div>            
            <div class="control-group">
                <div class="control-label">
                <label for="fracc_imagen">Imagen:</label>                
                </div>
                <div class="controls">
                <?php if($imagen!=''){  ?>
                    <img src="<?php echo $this->imgPath.'/'.$imagen;?>" height="68" width="68"><br/>
                    <input type="file" name="fracc_imagen" size="10" value=""/>                    
                <?php }else{ ?>                
                    <input type="file" name="fracc_imagen" size="10" value=""/>
                <?php } ?>
            </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                <label for="fracc_activo">Activo:</label>                                
                </div>
                <div class="controls">    
                <input type="checkbox" name="fracc_activo" id="fracc_activo" value="<?php echo $activo; ?>" <?php echo ($activo==1)?'checked':''; ?>  />                                       
                </div>
            </div>                                                  
        </fieldset>
    </div> 
        
        <input type="hidden" name="task" value="catfraccionamiento.edit" />
        <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />                        
        <?php echo JHtml::_('form.token'); ?>    
    
</form>
