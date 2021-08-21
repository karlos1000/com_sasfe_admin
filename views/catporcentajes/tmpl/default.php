<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');

include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';                                                      
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';            
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolCalendar' . DIRECTORY_SEPARATOR . 'koolcalendar.php';                   
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'ext'. DIRECTORY_SEPARATOR .'datasources'. DIRECTORY_SEPARATOR .'MySQLiDataSource.php';

JViewLegacy::loadHelper('sasfehp');
$gridAses = SasfehpHelper::ObtCatalogoPorcentajes(1);
$gridProsp = SasfehpHelper::ObtCatalogoPorcentajes(2);

?> 
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&task=catporcentajes');?>" method="post" id="adminForm" name="adminForm">         
    <br/>
    <div>
        <label><strong>Porcentajes para Asesor</strong></label>
    </div>
    <br/>
    <?php     
        echo $koolajax->Render();
        echo $gridAses->Render();   
    ?>          
    <br/><br/><br/>    
    <div>
        <label><strong>Porcentajes para Prospectadores</strong></label>
    </div>
    <br/>
    <?php     
        echo $koolajax->Render();
        echo $gridProsp->Render();   
    ?>          
    <input type="hidden" name="task" value="catporcentajes" />                    
    <?php echo JHtml::_('form.token'); ?>                
</form>


<?php 
//SIN PREVENTA
//Apartado
$idPorcentajeASPApar = (isset($this->asesorSinPrev[0]->idPorcentaje))?$this->asesorSinPrev[0]->idPorcentaje:'';
$nombreASPApar = (isset($this->asesorSinPrev[0]->nombre))?$this->asesorSinPrev[0]->nombre:'';
$pctUnoASPApar = (isset($this->asesorSinPrev[0]->pctUno))?$this->asesorSinPrev[0]->pctUno:'';
$pctDosASPApar = (isset($this->asesorSinPrev[0]->pctDos))?$this->asesorSinPrev[0]->pctDos:'';

//Escritura
$idPorcentajeASPEsc = (isset($this->asesorSinPrev[1]->idPorcentaje))?$this->asesorSinPrev[1]->idPorcentaje:'';
$nombreASPEsc = (isset($this->asesorSinPrev[1]->nombre))?$this->asesorSinPrev[1]->nombre:'';
$pctUnoASPEsc = (isset($this->asesorSinPrev[1]->pctUno))?$this->asesorSinPrev[1]->pctUno:'';
$pctDosASPEsc = (isset($this->asesorSinPrev[1]->pctDos))?$this->asesorSinPrev[1]->pctDos:'';

//Liquidacion
$idPorcentajeASPLiq = (isset($this->asesorSinPrev[2]->idPorcentaje))?$this->asesorSinPrev[2]->idPorcentaje:'';
$nombreASPLiq = (isset($this->asesorSinPrev[2]->nombre))?$this->asesorSinPrev[2]->nombre:'';
$pctUnoASPLiq = (isset($this->asesorSinPrev[2]->pctUno))?$this->asesorSinPrev[2]->pctUno:'';
$pctDosASPLiq = (isset($this->asesorSinPrev[2]->pctDos))?$this->asesorSinPrev[2]->pctDos:'';

// ----- CON PREVENTA ----
$idPorcentajeACPApar = (isset($this->asesorConPrev[0]->idPorcentaje))?$this->asesorConPrev[0]->idPorcentaje:'';
$nombreACPApar = (isset($this->asesorConPrev[0]->nombre))?$this->asesorConPrev[0]->nombre:'';
$pctUnoACPApar = (isset($this->asesorConPrev[0]->pctUno))?$this->asesorConPrev[0]->pctUno:'';
$pctDosACPApar = (isset($this->asesorConPrev[0]->pctDos))?$this->asesorConPrev[0]->pctDos:'';

//Escritura
$idPorcentajeACPEsc = (isset($this->asesorConPrev[1]->idPorcentaje))?$this->asesorConPrev[1]->idPorcentaje:'';
$nombreACPEsc = (isset($this->asesorConPrev[1]->nombre))?$this->asesorConPrev[1]->nombre:'';
$pctUnoACPEsc = (isset($this->asesorConPrev[1]->pctUno))?$this->asesorConPrev[1]->pctUno:'';
$pctDosACPEsc = (isset($this->asesorConPrev[1]->pctDos))?$this->asesorConPrev[1]->pctDos:'';

//Liquidacion
$idPorcentajeACPLiq = (isset($this->asesorConPrev[2]->idPorcentaje))?$this->asesorConPrev[2]->idPorcentaje:'';
$nombreACPLiq = (isset($this->asesorConPrev[2]->nombre))?$this->asesorConPrev[2]->nombre:'';
$pctUnoACPLiq = (isset($this->asesorConPrev[2]->pctUno))?$this->asesorConPrev[2]->pctUno:'';
$pctDosACPLiq = (isset($this->asesorConPrev[2]->pctDos))?$this->asesorConPrev[2]->pctDos:'';


// ----- SIN PREVENTA PARA LOS PROSPECTADORES ----
$idPorcentajePSApar = (isset($this->prospSinPrev[0]->idPorcentaje))?$this->prospSinPrev[0]->idPorcentaje:'';
$nombrePSApar = (isset($this->prospSinPrev[0]->nombre))?$this->prospSinPrev[0]->nombre:'';
$pctUnoPSApar = (isset($this->prospSinPrev[0]->pctUno))?$this->prospSinPrev[0]->pctUno:'';
$pctDosPSApar = (isset($this->prospSinPrev[0]->pctDos))?$this->prospSinPrev[0]->pctDos:'';

//Escritura
$idPorcentajePSEsc = (isset($this->prospSinPrev[1]->idPorcentaje))?$this->prospSinPrev[1]->idPorcentaje:'';
$nombrePSEsc = (isset($this->prospSinPrev[1]->nombre))?$this->prospSinPrev[1]->nombre:'';
$pctUnoPSEsc = (isset($this->prospSinPrev[1]->pctUno))?$this->prospSinPrev[1]->pctUno:'';
$pctDosPSEsc = (isset($this->prospSinPrev[1]->pctDos))?$this->prospSinPrev[1]->pctDos:'';

//Con preventa
$idPorcentajePCApar = (isset($this->prospConPrev[0]->idPorcentaje))?$this->prospConPrev[0]->idPorcentaje:'';
$nombrePCApar = (isset($this->prospConPrev[0]->nombre))?$this->prospConPrev[0]->nombre:'';
$pctUnoPCApar = (isset($this->prospConPrev[0]->pctUno))?$this->prospConPrev[0]->pctUno:'';
$pctDosPCApar = (isset($this->prospConPrev[0]->pctDos))?$this->prospConPrev[0]->pctDos:'';

//Escritura
$idPorcentajePEsc = (isset($this->prospConPrev[1]->idPorcentaje))?$this->prospConPrev[1]->idPorcentaje:'';
$nombrePCEsc = (isset($this->prospConPrev[1]->nombre))?$this->prospConPrev[1]->nombre:'';
$pctUnoPCEsc = (isset($this->prospConPrev[1]->pctUno))?$this->prospConPrev[1]->pctUno:'';
$pctDosPCEsc = (isset($this->prospConPrev[1]->pctDos))?$this->prospConPrev[1]->pctDos:'';

//echo '<pre>';
//print_r($this->data);
//echo '</pre>';

?>
<?php /*
<form class="form-validate" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=catporcentajes'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">    
    <div class="notesUlEsphabit">        
    </div>        
    
    <div style="">
        <fieldset class="adminform">        
            <legend>Asesor sin preventa</legend>   
            
            <div><label><strong>Apartado</strong></label></div> 
            <div>
                <label for="pctUnoASPApar">Porcentaje 1: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctUnoASPApar" id="pctUnoASPApar" value="<?php echo $pctUnoASPApar; ?>" style="width:100px;" class="required"/>                        
            </div>                        
            <div>
                <label for="pctDosASPApar">Porcentaje 2: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctDosASPApar" id="pctDosASPApar" value="<?php echo $pctDosASPApar; ?>" style="width:100px;" class="required"/>                        
            </div>                                    
            
            <div><label><strong>Escritura</strong></label></div> 
            <div>
                <label for="pctUnoASPEsc">Porcentaje 1: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctUnoASPEsc" id="pctUnoASPEsc" value="<?php echo $pctUnoASPEsc; ?>" style="width:100px;" class="required"/>                        
            </div>                        
            <div>
                <label for="pctDosASPEsc">Porcentaje 2: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctDosASPEsc" id="pctDosASPEsc" value="<?php echo $pctDosASPEsc; ?>" style="width:100px;" class="required"/>                        
            </div>                                    
        
            <div><label><strong>Liquidaci&oacute;n</strong></label></div> 
            <div>
                <label for="pctUnoASPLiq">Porcentaje 1: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctUnoASPLiq" id="pctUnoASPLiq" value="<?php echo $pctUnoASPLiq; ?>" style="width:100px;" class="required"/>                        
            </div>                        
            <div>
                <label for="pctDosASPLiq">Porcentaje 2: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctDosASPLiq" id="pctDosASPLiq" value="<?php echo $pctDosASPLiq; ?>" style="width:100px;" class="required"/>                        
            </div>                                    
        </fieldset>
        
        <fieldset class="adminform">        
            <legend>Asesor con preventa</legend>
            
            <div><label><strong>Apartado</strong></label></div> 
            <div>
                <label for="pctUnoACPApar">Porcentaje 1: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctUnoACPApar" id="pctUnoACPApar" value="<?php echo $pctUnoACPApar; ?>" style="width:100px;" class="required"/>                        
            </div>                        
            <div>
                <label for="pctDosACPApar">Porcentaje 2: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctDosACPApar" id="pctDosACPApar" value="<?php echo $pctDosACPApar; ?>" style="width:100px;" class="required"/>                        
            </div>                                    
            
            <div><label><strong>Escritura</strong></label></div>
            <div>
                <label for="pctUnoACPEsc">Porcentaje 1: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctUnoACPEsc" id="pctUnoACPEsc" value="<?php echo $pctUnoACPEsc; ?>" style="width:100px;" class="required"/>                        
            </div>                        
            <div>
                <label for="pctDosACPEsc">Porcentaje 2: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctDosACPEsc" id="pctDosACPEsc" value="<?php echo $pctDosACPEsc; ?>" style="width:100px;" class="required"/>                        
            </div>                                    
        
            <div><label><strong>Liquidaci&oacute;n</strong></label></div>
            <div>
                <label for="pctUnoACPLiq">Porcentaje 1: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctUnoACPLiq" id="pctUnoACPLiq" value="<?php echo $pctUnoACPLiq; ?>" style="width:100px;" class="required"/>                        
            </div>                        
            <div>
                <label for="pctDosACPLiq">Porcentaje 2: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctDosACPLiq" id="pctDosACPLiq" value="<?php echo $pctDosACPLiq; ?>" style="width:100px;" class="required"/>                        
            </div>                                    
        </fieldset>
        
        
        <fieldset class="adminform">        
            <legend>Prospectador sin preventa</legend>
            
            <div><label><strong>Apartado</strong></label></div> 
            <div>
                <label for="pctUnoPSApar">Porcentaje 1: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctUnoPSApar" id="pctUnoPSApar" value="<?php echo $pctUnoPSApar; ?>" style="width:100px;" class="required"/>                        
            </div>                        
            <div>
                <label for="pctDosPSApar">Porcentaje 2: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctDosPSApar" id="pctDosPSApar" value="<?php echo $pctDosPSApar; ?>" style="width:100px;" class="required"/>                        
            </div>     
            
            <div><label><strong>Escritura</strong></label></div> 
            <div>
                <label for="pctUnoPSEsc">Porcentaje 1: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctUnoPSEsc" id="pctUnoPSEsc" value="<?php echo $pctUnoPSEsc; ?>" style="width:100px;" class="required"/>                        
            </div>                        
            <div>
                <label for="pctDosPSEsc">Porcentaje 2: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctDosPSEsc" id="pctDosPSEsc" value="<?php echo $pctDosPSEsc; ?>" style="width:100px;" class="required"/>                        
            </div>              
        </fieldset>
        
        <fieldset class="adminform">        
            <legend>Prospectador con preventa</legend>
            
            <div><label><strong>Apartado</strong></label></div> 
            <div>
                <label for="pctUnoPCApar">Porcentaje 1: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctUnoPCApar" id="pctUnoPCApar" value="<?php echo $pctUnoPCApar; ?>" style="width:100px;" class="required"/>                        
            </div>                        
            <div>
                <label for="pctDosPCApar">Porcentaje 2: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctDosPCApar" id="pctDosPCApar" value="<?php echo $pctDosPCApar; ?>" style="width:100px;" class="required"/>                        
            </div>     
            
            <div><label><strong>Escritura</strong></label></div> 
            <div>
                <label for="pctUnoPCEsc">Porcentaje 1: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctUnoPCEsc" id="pctUnoPCEsc" value="<?php echo $pctUnoPCEsc; ?>" style="width:100px;" class="required"/>                        
            </div>                        
            <div>
                <label for="pctDosPCEsc">Porcentaje 2: <span class="star">&nbsp;*</span></label>
                <input type="text" name="pctDosPCEsc" id="pctDosPCEsc" value="<?php echo $pctDosPCEsc; ?>" style="width:100px;" class="required"/>                        
            </div>              
        </fieldset>
        
    </div> 
        
        <input type="hidden" name="task" value="catporcentajes.edit" />
        <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />                    
        <!--sin preventa asesor-->
        <input type="hidden" name="idPorcentajeASPApar" value="<?php echo $idPorcentajeASPApar; ?>" />
        <input type="hidden" name="idPorcentajeASPEsc" value="<?php echo $idPorcentajeASPEsc; ?>" />
        <input type="hidden" name="idPorcentajeASPLiq" value="<?php echo $idPorcentajeASPLiq; ?>" />
        <!--con preventa asesor-->
        <input type="hidden" name="idPorcentajeACPApar" value="<?php echo $idPorcentajeACPApar; ?>" />
        <input type="hidden" name="idPorcentajeACPEsc" value="<?php echo $idPorcentajeACPEsc; ?>" />
        <input type="hidden" name="idPorcentajeACPLiq" value="<?php echo $idPorcentajeACPLiq; ?>" />
        <!--sin preventa prospectado-->
        <input type="hidden" name="idPorcentajePSApar" value="<?php echo $idPorcentajePSApar; ?>" />
        <input type="hidden" name="idPorcentajePSEsc" value="<?php echo $idPorcentajePSEsc; ?>" />
        <!--con preventa prospectado-->
        <input type="hidden" name="idPorcentajePCApar" value="<?php echo $idPorcentajePCApar; ?>" />
        <input type="hidden" name="idPorcentajePEsc" value="<?php echo $idPorcentajePEsc; ?>" />
        
        <?php echo JHtml::_('form.token'); ?>    
    
</form>
 
*/ ?>
