<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
JHTML::_('behavior.formvalidation');
$timeZone = SasfehpHelper::obtDateTimeZone();
$colUsrGtesVentas = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11);

$gteVentasId = "";
if(in_array("11", $this->groups)){
    $gteVentasId = $this->user->id;
    //obtener los asesores por el id del gerente de ventas
    $colAsesores = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($gteVentasId);
}else{
    //Obtener todos los asesores ya que esta como super usuario o direccion
    $colAsesores = SasfehpHelper::obtColElemPorIdCatalogo(3); //Agentes de venta
}
$fechaDel = SasfehpHelper::diasPrevPos(7, $timeZone->fecha, "prev");//define fecha del
$fechaAl = $timeZone->fechaF2; //define fecha al
?>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=reportes'); ?>" method="post" name="adminForm" id="adminForm">

    <div>
        <fieldset class="adminform" style="width:450px;">
        <?php if(in_array("10", $this->groups) || in_array("8", $this->groups) || in_array("14", $this->groups) || in_array("13", $this->groups)){ ?>
        <legend>Reporte Sembrado</legend>
        <div>
            <label for="fracc">Fraccionamiento:</label>
            <select id="fracc" name="fracc" class="required">
                <option value="">--Selecciona--</option>
                <!--<option value="0">Todos</option>-->
                <?php
                    foreach($this->Fracc as $elem):
                        echo '<option value='.$elem->idFraccionamiento.'>'.$elem->nombre.'</option>';
                    endforeach;
                ?>
            </select>
        </div>
        <div style="clear: both;">
            <br/><br/>
            <button type="button" id="btn_exportar">Exportar reporte</button>
        </div>
        <?php } ?>

        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("20", $this->groups)){ ?>
        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("20", $this->groups)){ ?><br/><br/><?php }?>
        <legend>Reporte productividad de prospectos</legend>
        <div class="control-group">
            <div class="control-label">
                <label for="filter_fechaDel" style="display: inline-block;">Del: </label>
                <input type="text" name="filter_fechaDel" id="filter_fechaDel" value="<?php echo $fechaDel; ?>" class="required" style="width:70px !important;" readonly/>
                <span class="separaFiltro"></span>
            </div>
            <div class="controls">
                <label for="filter_fechaAl" style="display: inline-block;line-height:38px;">Al: </label>
                <input type="text" name="filter_fechaAl" id="filter_fechaAl" value="<?php echo $fechaAl; ?>" class="required" style="width:70px !important;" readonly/>
                <span class="separaFiltro"></span>
            </div>
        </div>

        <!-- gerentes de ventas -->
        <!--
        <div class="control-group">
            <div class="control-label">
                <label for="usuarioIdGteVenta">Gerente Venta:</label>
            </div>
            <div class="controls">
                <select id="usuarioIdGteVenta" name="usuarioIdGteVenta" class="required" <?php echo ($gteVentasId!="")?"disabled":""; ?>>
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
                <?php if($gteVentasId!=""){ ?>
                <input type="hidden" name="usuarioIdGteVenta" value="<?php echo $gteVentasId; ?>" />
                <?php } ?>
            </div>
        </div>
        -->
        <div class="control-group">
            <div class="control-label">
                <label for="asig_agtventas">Agente Venta:</label>
            </div>
            <div class="controls">
                <select name="asig_agtventas" id="asig_agtventas" class="required">
                    <option value="0">--Todos--</option>
                    <?php
                    foreach ($colAsesores as $itemAse) {
                        echo '<option value="' . $itemAse->idDato . '">' . $itemAse->nombre . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="asig_fuente">Por fuente:</label>
            </div>
            <div class="controls">
                <input type="checkbox" name="asig_fuente" id="asig_fuente" value="0" />
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <button type="button" id="btn_exportarProsp" style="width:190px;">Ver reporte prospecto</button>
            </div>
        </div>
        <?php } ?>


        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("20", $this->groups)){ ?>
            <br/><br/>
            <legend>Reporte contactos por fuente</legend>
            <div class="control-group">
                <div class="control-label">
                    <label for="filter_fechaDelCF" style="display: inline-block;">Del: </label>
                    <input type="text" name="filter_fechaDelCF" id="filter_fechaDelCF" value="<?php echo $fechaDel; ?>" class="required" style="width:70px !important;" readonly/>
                    <span class="separaFiltro"></span>
                </div>
                <div class="controls">
                    <label for="filter_fechaAlCF" style="display: inline-block;line-height:38px;">Al: </label>
                    <input type="text" name="filter_fechaAlCF" id="filter_fechaAlCF" value="<?php echo $fechaAl; ?>" class="required" style="width:70px !important;" readonly/>
                    <span class="separaFiltro"></span>
                </div>
            </div>
            <?php
                // $arrTiposCaptados = SasfehpHelper::obtColTipoCaptados(); //obtiene coleccion de tipos de captados
            ?>
            <div class="control-group">
                <div class="control-label">
                    <button type="button" id="btn_exportarContactosFuente" style="width:190px;">Exportar reporte contactos</button>
                </div>
            </div>
        <?php } ?>

        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("20", $this->groups)){ ?>
            <?php
                $idGteVentas = 0;
                if(in_array("11", $this->groups)){
                    $idGteVentas = $this->user->id;
                }
            ?>
            <br/><br/>
            <legend>Reporte acciones de contactos</legend>
            <div class="control-group">
                <div class="control-label">
                    <label for="filter_fechaDelAC" style="display: inline-block;">Del: </label>
                    <input type="text" name="filter_fechaDelAC" id="filter_fechaDelAC" value="<?php echo $fechaDel; ?>" class="required" style="width:70px !important;" readonly/>
                    <span class="separaFiltro"></span>
                </div>
                <div class="controls">
                    <label for="filter_fechaAlAC" style="display: inline-block;line-height:38px;">Al: </label>
                    <input type="text" name="filter_fechaAlAC" id="filter_fechaAlAC" value="<?php echo $fechaAl; ?>" class="required" style="width:70px !important;" readonly/>
                    <span class="separaFiltro"></span>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="nombreGteJoomlaVentas">Gerente Ventas:</label>
                </div>
                <div class="controls">
                    <select id="nombreGteJoomlaVentas" name="nombreGteJoomlaVentas" class="opcGteSel">
                        <option value="0">--Todos--</option>
                        <?php
                        foreach ($colUsrGtesVentas as $itemGteV) {
                            echo '<option value="' . $itemGteV->id . '">' . $itemGteV->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="asig_agtventas2">Agente Venta:</label>
                </div>
                <div class="controls">
                    <select name="asig_agtventas2" id="asig_agtventas2">
                        <option value="0">--Todos--</option>
                    </select>
                </div>
            </div>
            <?php
                // $arrTiposCaptados = SasfehpHelper::obtColTipoCaptados(); //obtiene coleccion de tipos de captados
            ?>
            <div class="control-group">
                <div class="control-label">
                    <button type="button" id="btn_exportarAccionesContactos" style="width:190px;">Exportar reporte acciones contactos</button>
                </div>
            </div>
        <?php } ?>

            <input type="hidden" id="racIdGteVentas" value="<?php echo $idGteVentas; ?>"/>
        </fieldset>
    </div>

    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
        <input type="hidden" id="rutaCalendario" value="<?php echo JURI::root().'media/com_sasfe/images/calendar.gif'; ?>" />
        <input type="hidden" name="usuarioIdGteVenta" id="usuarioIdGteVenta" value="<?php echo $gteVentasId; ?>" />
        <input type="hidden" id="path" value="index.php?option=com_sasfe&task=reportes." />
    </div>
</form>

<?php
// Obtener col asesores por id de gerente
$arrColAsesoresXGte = [];
foreach ($colUsrGtesVentas as $key=>$elemGte) {
    $colAsesorTmp = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($elemGte->id);
    foreach ($colAsesorTmp as $key2=>$elemAgte) {
        // unset($colAsesorTmp[$key2]->idDato);
        unset($colAsesorTmp[$key2]->catalogoId);
        unset($colAsesorTmp[$key2]->pordefault);
        unset($colAsesorTmp[$key2]->activo);
        unset($colAsesorTmp[$key2]->usuarioIdGteJoomla);
    }
    $arrColAsesoresXGte[$elemGte->id] = $colAsesorTmp;
}
?>

<script>
var arrColAsesoresXGte = [];
JQ(document).ready(function(){
  arrColAsesoresXGte = '<?php echo json_encode($arrColAsesoresXGte); ?>';
});
</script>

<style type="text/css">
    .modal-body{
        width: 90% !important;
        height: 800px !important;
        overflow: scroll;
    }
    /*.modal-content{
        height: 650px !important;
        overflow: scroll;
    }*/
</style>
<!-- Imp. 08/09/21, Mostrar el reporte deproductividad en patalla -->
<a href="#" data-toggle="modal" data-target="#popup_rpt_productividad" class="btn btn-small button-apply btn-success" id="btnVerRptProductividad" style="display:none;">Abrir reporte en pantalla</a>
<div class="modal fade" id="popup_rpt_productividad" role="dialog" style="width:1200px;height:600px;position:relative !important; display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Indices de productividad</h4>
            </div>
            <div class="modal-body cont_form_popup">
                <form id="form_agregar_evento" class="form-horizontal" method="post">
                    <div><a href="javascript:void(0)" class="btn btn-small button-apply btn-success" id="btnPdfRptProductividad">Descargar Reporte</a></div><br>
                    <div id="cont_tabla_productividad"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Imp. 09/09/21, Mostrar detalle de eventos de prospectos -->
<a href="#" data-toggle="modal" data-target="#popup_detalle_evento" class="btn btn-small button-apply btn-success" id="btnVerDetalleEvento" style="display:none;">Abrir reporte en pantalla</a>
<div class="modal fade" id="popup_detalle_evento" role="dialog" style="width:1200px;height:600px;position:relative !important; display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Detalle evento(s)</h4>
            </div>
            <div class="modal-body cont_form_popup">
                <form id="form_detalle_evento" class="form-horizontal" method="post">
                    <div id="cont_tabla_detalle_evento"></div>
                </form>
            </div>
        </div>
    </div>
</div>