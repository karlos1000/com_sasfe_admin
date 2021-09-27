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
// $grid = SasfehpHelper::ObtTodasLasFechasDTU($this->idFracc);
$grid = SasfehpHelper::ObtTodasLasFechasDTU2($this->idFracc);

?>
<style>
    .cont_btn_seleccionar{
        width: 65%;
        text-align: right;
    }
</style>
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
<div>
    <span><strong>Nota selecci&oacute;n multiple.</strong></span>
    <ul>
        <li>Puede utilizar el bot&oacute;n "Seleccionar todo" para chequear los registros que se encuentre en la vista o simplemente<br/> ir seleccionando una por uno, para seleccionar mas registros use el contro de tama&ntilde;o de p&aacute;gina.</li>
        <li>Despu&eacute;s presionar sobre el bot&oacute;n "Seleccionar Fecha", escoger una y aceptar.</li>
    </ul>
</div>
<br/>

<div class="cont_btn_fechadtu">
    <a href="#" data-toggle="modal" data-target="#popup_fechadtu" class="btn btn-small button-apply btn-success" id="btnSelFecha" >Seleccionar Fecha</a>
</div>
<br>
<div class="cont_btn_seleccionar">
    <input class="btn btn-warning" id="btnSeleccionarTodo" type="button" value="Seleccionar todo" />
    <input class="btn btn-warning" id="btnDeseleccionarTodo" type="button" value="Deseleccionar todo" style="display:none;" />
</div>
<hr>

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


<!-- Modal fechas DTU-->
<?php
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$fecha = $dateByZone->format('Y-m-d'); //fecha
?>
<div class="modal fade" id="popup_fechadtu" role="dialog" style="width:500px;height:400px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Seleccionar fecha DTU</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <!-- <form id="form_agregar_evento" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=fechasdtu&task=fechasdtu.dtuFechasMasivo'); ?>" method="post"> -->
                <form id="form_agregar_evento" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=fechasdtu&task=fechasdtu.dtuFechasMasivo'); ?>" method="post">

                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="fecha_dtu">Fecha:</label>
                    </div>
                    <div class="controls">
                        <div id="fecha_dtu_linea" style="display:inline-block;"></div>
                        <input type="hidden" name="fecha_dtu" id="fecha_dtu" value="<?php echo $fecha; ?>" />
                    </div>
                </div>
                <!-- <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="ev_comentario" id="ev_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div> -->

                <div>
                    <button type="button" class="btn btn-small button-new btn-success" id="btn_cambiarfechasdtu">Aceptar Fechas DTU</button>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_cambiarfechasdtu_hid" style="display:none;">Aceptar Fechas DTU</button>
                </div>

                <input type="hidden" name="idsDatoGeneral" id="idsDatoGeneral" value="" />
                <input type="hidden" name="idFracc" value="<?php echo $this->idFracc;?>" />
            </form>
          </div>
      </div>
    </div>
</div>
