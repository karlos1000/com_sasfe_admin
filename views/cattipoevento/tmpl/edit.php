<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');

$idFracc = (isset($this->data[0]->idTipoEvento))?$this->data[0]->idTipoEvento:'';
$tipoEvento = (isset($this->data[0]->tipoEvento))?$this->data[0]->tipoEvento:'';
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
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=cattipoevento'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">    
    <div class="notesUlEsphabit">        
    </div>
    
    <div style="">
        <fieldset class="adminform">
            <div class="control-group">
                <div class="control-label">
                    <label for="tipoevento_nombre">Nombre: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                    <input type="text" name="tipoevento_nombre" id="tipoevento_nombre" value="<?php echo $tipoEvento; ?>" required style="width:150px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="tipoevento_activo">Activo:</label>
                </div>
                <div class="controls">
                    <input type="checkbox" name="tipoevento_activo" id="tipoevento_activo" value="<?php echo $activo; ?>" <?php echo ($activo==1)?'checked':''; ?>  />
                </div>
            </div>
        </fieldset>
    </div> 
        
        <input type="hidden" name="task" value="cattipoevento.edit" />
        <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />                        
        <?php echo JHtml::_('form.token'); ?>    
    
</form>
