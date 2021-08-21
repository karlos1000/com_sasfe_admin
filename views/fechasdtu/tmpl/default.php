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
$dateC = date("d/m/Y"); //fecha actual
JViewLegacy::loadHelper('sasfehp');
$grid = SasfehpHelper::ObtTodasLasFechasDTU($this->idFracc);

?> 
<div>
    <span>Nota filtro grid.</span>
    <ul>
        <li><strong>Palabra igual que:</strong> Encuentra n&uacute;mero de departamento exactamente igual a la palabra escrita.</li>
        <li><strong>Contiene la palabra:</strong> Encuentr&aacute; n&uacute;mero de departamento que contenga la palabra al inicio, en medio o al final.</li>
        <li><strong>Comienza palabra con:</strong> Buscar&aacute; el n&uacute;mero de departamento que contenga la palabra solo al inicio de su contenido.</li>
        <li><strong>Palabra final con:</strong> Buscara n&uacute;mero de departamento que contenga la palabra solo al final de su contenido.</li>
    </ul>
</div>
<br/>

<form action="<?php echo JRoute::_('index.php?option=com_sasfe&task=fechasdtu');?>" method="post" id="adminForm" name="adminForm">         
    <?php     
        echo $koolajax->Render();
        echo $grid->Render();   
    ?>          
    <input type="hidden" name="task" value="horarios" />                    
    <?php echo JHtml::_('form.token'); ?>                
</form>

<br/>
<br/>
