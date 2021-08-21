<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');

$idMensaje = (isset($this->data[0]->idMensaje))?$this->data[0]->idMensaje:'';
$titulo = (isset($this->data[0]->titulo))?$this->data[0]->titulo:'';
$texto = (isset($this->data[0]->texto))?$this->data[0]->texto:'';
$activo = (isset($this->data[0]->activo))?$this->data[0]->activo:'';

// echo '<pre>';
// print_r($this->data);
// echo '</pre>';

?>
<style>
.defaultKCD{ position: absolute;margin: 0px;padding: 0px;} label{ width: 170px; } 
.adminform label{
    min-width: 170px;
    padding: 0 5px 0 0; 
}    
</style>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=smspromocion'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">    
    <div class="notesUlEsphabit">        
    </div>
    
    <div style="">
        <fieldset class="adminform">
            <div class="control-group">
                <div class="control-label">
                    <label for="mensaje_titulo">Titulo: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                    <input type="text" id="mensaje_titulo" name="mensaje_titulo" value="<?php echo $titulo; ?>" required>                    
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="promocion_texto">Promoci&oacute;n: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                    <textarea name="promocion_texto" id="promocion_texto" cols="30" rows="6" required style="width:300px;"><?php echo $texto; ?></textarea>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="promocion_activo">Activo:</label>
                </div>
                <div class="controls">
                    <input type="checkbox" name="promocion_activo" id="promocion_activo" value="<?php echo $activo; ?>" <?php echo ($activo==1)?'checked':''; ?>  />
                </div>
            </div>
        </fieldset>
    </div> 
        
        <input type="hidden" name="task" value="smspromocion.edit" />
        <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />                        
        <?php echo JHtml::_('form.token'); ?>    
    
</form>
