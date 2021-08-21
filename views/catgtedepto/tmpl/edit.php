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
$colUsrGtesVentas = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11);
$colDeptos = SasfehpHelper::obtTodosDepartamentosArr();
// echo "<pre>"; print_r($colDeptos); echo "</pre>";
// echo "es: ".count($colDeptos).'<br/>';

$idGteDepto = (isset($this->data[0]->idGteDepto))?$this->data[0]->idGteDepto:'';
$gteVentasId = (isset($this->data[0]->gteVentasId))?$this->data[0]->gteVentasId:'';
$departamentosId = (isset($this->data[0]->departamentosId))?$this->data[0]->departamentosId:'';
$arrIdDeptosDB = explode(",", $departamentosId);

// echo '<pre>';
// print_r($this->data);
// print_r($arrIdDeptosDB);
// echo '</pre>';
?>
<style type="text/css">
.glyphicon {
    position: relative;
    top: 1px;
    display: inline-block;
    font-family: 'Glyphicons Halflings';
    -webkit-font-smoothing: antialiased;
    font-style: normal;
    font-weight: normal;
    line-height: 1;
    -moz-osx-font-smoothing: grayscale;
}
.glyphicon-arrow-right:before {
    content: "\E092";
}
.glyphicon-arrow-left:before {
    content: "\e091";
}
.btn-group>.btn:first-child:not(:last-child):not(.dropdown-toggle) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.bootstrap-duallistbox-container .btn-group .btn {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.btn-group>.btn:first-child {
    margin-left: 0;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.box1, .box2{
    width: 40%;
    display: inline-block;
}
#bootstrap-duallistbox-nonselected-list_departamentos, #bootstrap-duallistbox-selected-list_departamentos{
    height: 330px !important;    
}
</style>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=catgtedepto'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div class="notesUlEsphabit">        
    </div>
    
    <div style="">
        <fieldset class="adminform">
            <!-- gerentes de ventas -->            
            <div class="control-group" id="cont_gerente_ventas">
                <div class="control-label">
                    <label for="usuarioIdGteVenta">Gerentes Venta:</label>
                </div>
                <div class="controls">                    
                    <select id="usuarioIdGteVenta" name="usuarioIdGteVenta" class="required">
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($colUsrGtesVentas as $itemUVenta) {
                            if ($gteVentasId == $itemUVenta->id)
                                $sel = 'selected';
                            else
                                $sel = '';                            
                            
                            echo '<option '.$sel.' value="' . $itemUVenta->id . '">' . $itemUVenta->name . '</option>';
                        }
                        ?>
                    </select>               
                </div>
            </div>            
                
            <div class="control-group">
                <div class="control-label">
                    <label for="departamentos">Departamentos:</label> 
                </div>
                <select id="departamentos" name="departamentos" class="required duallb" multiple="multiple">
                    <?php
                        foreach ($colDeptos as $itemDepto) {
                            if(in_array($itemDepto->idDepartamento, $arrIdDeptosDB)){
                              $sel = 'selected';
                            }else{
                              $sel = '';
                            }
                            
                            echo '<option '.$sel.' value="' . $itemDepto->idDepartamento . '">' . $itemDepto->nfraccionamiento .' | '. $itemDepto->numero . '</option>';
                        }
                    ?>                    
                </select>
            </div>

        </fieldset>
    </div> 
        
    <input type="hidden" name="task" value="catgtedepto.edit" />
    <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />
    <input type="hidden" id="idsDeptos" name="idsDeptos" value="<?php echo $departamentosId;?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>
