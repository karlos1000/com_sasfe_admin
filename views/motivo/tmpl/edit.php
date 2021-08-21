<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');

$idMotivo = (isset($this->data[0]->idMotivo))?$this->data[0]->idMotivo:'';
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
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=motivo'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div class="notesUlEsphabit">
    </div>

    <div style="">
        <fieldset class="adminform">
            <div class="control-group">
                <div class="control-label">
                    <label for="titulo">Titulo: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                    <input type="text" id="titulo" name="titulo" value="<?php echo $titulo; ?>" required  style="width:300px !important;">
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="texto">Texto: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                    <textarea name="texto" id="texto" cols="30" rows="6" required style="width:300px;"><?php echo $texto; ?></textarea>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="activo">Activo:</label>
                </div>
                <div class="controls">
                    <input type="checkbox" name="activo" id="activo" value="<?php echo $activo; ?>" <?php echo ($activo==1)?'checked':''; ?>  />
                </div>
            </div>
        </fieldset>
    </div>

        <input type="hidden" name="task" value="motivo.edit" />
        <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />
        <?php echo JHtml::_('form.token'); ?>

</form>