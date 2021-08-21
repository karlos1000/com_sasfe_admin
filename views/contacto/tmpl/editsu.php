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
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolTabs' . DIRECTORY_SEPARATOR . 'kooltabs.php';
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolCalendar' . DIRECTORY_SEPARATOR . 'koolcalendar.php';
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'ext'. DIRECTORY_SEPARATOR .'datasources'. DIRECTORY_SEPARATOR .'MySQLiDataSource.php';
$base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
$calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';
$timeZone = SasfehpHelper::obtDateTimeZone();

// echo "<pre>";
// print_r($this->data);
// echo "</pre>";

$idDatoContacto = (isset($this->data[0]->idDatoContacto))?$this->data[0]->idDatoContacto:0;
$gteVentasId = (isset($this->data[0]->gteVentasId))?$this->data[0]->gteVentasId:'';
$agtVentasId = (isset($this->data[0]->agtVentasId))?$this->data[0]->agtVentasId:'';
$nombreAsesorJoomla = "";
if($agtVentasId!="" && $agtVentasId>0){
    $datosUsrJoomla2 = SasfehpHelper::obtInfoUsuariosJoomla($agtVentasId);
    if($datosUsrJoomla2!=""){
        $nombreAsesorJoomla = $datosUsrJoomla2->name;
    }
}
$nombre = (isset($this->data[0]->nombre))?$this->data[0]->nombre:'';
$aPaterno = (isset($this->data[0]->aPaterno))?$this->data[0]->aPaterno:'';
$aMaterno = (isset($this->data[0]->aMaterno))?$this->data[0]->aMaterno:'';
$email = (isset($this->data[0]->email))?$this->data[0]->email:'';
$telefono = (isset($this->data[0]->telefono))?$this->data[0]->telefono:'';
$fuente = (isset($this->data[0]->fuente))?$this->data[0]->fuente:'';
$estatusId = (isset($this->data[0]->estatusId))?$this->data[0]->estatusId:0;
$desarrolloId = (isset($this->data[0]->desarrolloId))?$this->data[0]->desarrolloId:'';  //id desarrollo
$fechaContacto = (isset($this->data[0]->fechaContacto))? SasfehpHelper::conversionFechaF2($this->data[0]->fechaContacto) :"";
$fechaAlta = (isset($this->data[0]->fechaAlta))? SasfehpHelper::conversionFechaF2($this->data[0]->fechaAlta) :$this->arrDateTime->fechaF2;
$fechaActualizacion = (isset($this->data[0]->fechaActualizacion))? SasfehpHelper::conversionFechaF2($this->data[0]->fechaActualizacion) :$this->arrDateTime->fechaF2;
$activo = (isset($this->data[0]->activo))?$this->data[0]->activo:0;
$usuarioId = (isset($this->data[0]->usuarioId))?$this->data[0]->usuarioId:0;
$usuarioIdAct = (isset($this->data[0]->usuarioIdActualizacion))?$this->data[0]->usuarioIdActualizacion:0;
$motivo_descarte = (isset($this->data[0]->motivo_descarte))?$this->data[0]->motivo_descarte:"";
$comentario_descarte = (isset($this->data[0]->comentario_descarte))?$this->data[0]->comentario_descarte:"";
$credito = (isset($this->data[0]->credito))?$this->data[0]->credito:"";
$colEstatusContacto = SasfehpHelper::colEstatusContacto(); //Obtener la coleccion de estatus
$colAccionesContacto = SasfehpHelper::colAccionesContacto(); //coleccion de acciones

// Obtener las acciones
if($this->id>0){
    $gridAcciones = SasfehpHelper::ObtAccionesGrid($this->id);
}


// echo "<pre>";
// print_r($colEstatusContacto);
// echo "</pre>";

$altaProspectadorId = "";
// $altaProspectadorId = (isset($this->data[0]->altaProspectadorId))?$this->data[0]->altaProspectadorId:'';
//echo "<pre>"; print_r($this->data[0]); echo "</pre>";

$this->groupsgte = array();
//>>>
//>>Super usuario y direccion
//>>>
$colUsrGtesVentas = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11); //Obtener gerente de ventas
$colUsrGtesProspeccion = SasfehpHelper::obtUsuariosJoomlaPorGrupo(19); //Obtener gerente de prospeccion
$verBtnGuardar=1;
$idGteJoomla =  "";
$colAsesores = array();
$colProspectadores = array();
$idUsuarioJoomla = "";
$this->opcUsuario = "";
$this->opcGerente = "";
// $noSegEdit = false;
$noSegEdit = true; //Despues cambiarlo a false
$nombreGteJoomla = "";

//Saber si esta creando o editando
if($this->id==0){
}else{
    //Esta editando
    /*//Tiene asociado un gerente de prospeccion
    if($this->data[0]->gteProspeccionId!="" && $this->data[0]->gteProspeccionId>0){
        $userGte = JFactory::getUser($this->data[0]->gteProspeccionId);
        $idGteJoomla =  $userGte->get('id');  //id del gerente
        $this->opcGerente = "gteprospeccion";

        $colProspectadores = SasfehpHelper::obtColProspectadoresXIdGte($idGteJoomla); //Obtener coleccion de usuarios Prospectador  por gerente de ventas
        $idUsuarioJoomla = $altaProspectadorId;
        $this->opcUsuario = "prospectador";
    }*/
    //Tiene asociado un gerente de ventas
    if($this->data[0]->gteVentasId!="" && $this->data[0]->gteVentasId>0){
        $userGte = JFactory::getUser($this->data[0]->gteVentasId);
        $idGteJoomla =  $userGte->get('id');  //id del gerente
        $nombreGteJoomla = $userGte->get('name'); //Nombre del gerente joomla
        $this->opcGerente = "gteventas";

        // $this->groupsgte = array(11);
        $colAsesores = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($idGteJoomla); //Obtener coleccion de usuarios Agentes venta por gerente de ventas
        // $colProspectadores = SasfehpHelper::obtColProspectadoresXIdGte($idGteJoomla); //Obtener coleccion de usuarios Prospectador  por gerente de ventas

        //comprobar si es un agente de ventas (asesor) o un prospectador
        if($altaProspectadorId!="" && $altaProspectadorId>0){
            $idUsuarioJoomla = $altaProspectadorId;
            $this->opcUsuario = "prospectador";
            $noSegEdit = true;

            $userLogJoomla = JFactory::getUser($idUsuarioJoomla);
            $nombreUsrJoomla = $userLogJoomla->get('name'); //Nombre del usuario joomla que creo el prospecto
        }else{
            $idUsuarioJoomla = $agtVentasId;
            $this->opcUsuario = "agenteventas";
        }
    }
}


/*//Si esta en edicion agregar grid de eventos y comentarios
if( $this->id>0 && (in_array("8", $this->groups) || in_array("10", $this->groups)) ){
    $tipoOpc = "";
    if(isset($_POST['filter_tipo'])){
        $tipoOpc = $_POST['filter_tipo'];
        $gridEvCom = SasfehpHelper::ObtEventosComentariosGrid($this->id, $tipoOpc);
    }else{
        $gridEvCom = SasfehpHelper::ObtEventosComentariosGrid($this->id, $tipoOpc);
    }
    // print_r(SasfehpHelper::obtenerDepartamentosDisponibles(7,14));
}*/



/**
 * Functiones para recargar via ajax usando el ajax de koolgrid
*/
$gteId = 0;
obtAgtVentasSu(trim($gteId));
$koolajax->enableFunction("obtAgtVentasSu");
obtProspectadorSu(trim($gteId));
$koolajax->enableFunction("obtProspectadorSu");
echo $koolajax->Render();

function obtAgtVentasSu($gteId){
    return SasfehpHelper::obtColUsuarioXGte(1, $gteId);
}
function obtProspectadorSu($gteId){
    return SasfehpHelper::obtColUsuarioXGte(2, $gteId);
}
//Permisos para mostrar u ocultar los usuario de la parte derecha (gerentes, agentes, prospectador)
$permisosUsrGteAgtPro = SasfehpHelper::obtPermisoABCProspectoSu();
//Obtener los fraccionamientos
$colFracc = SasfehpHelper::obtTodosFraccionamientos();
//Recorrer bucle para obtener coleccion mandando la opcion cambaceo en otro lugar
$colCaptadosRand = array();
// echo "<pre>";
// print_r($this->arrTiposCaptados);
// echo "</pre>";

foreach ($this->arrTiposCaptados as $keyCaptado=>$valCaptado){
    if($keyCaptado!=0){
        if($keyCaptado==5){
            $colCaptadosRand[] = (object)array("idTipoCaptado"=>$this->arrTiposCaptados[0]->idTipoCaptado, "tipoCaptado"=>$this->arrTiposCaptados[0]->tipoCaptado, "activo"=>$this->arrTiposCaptados[0]->activo);
            $colCaptadosRand[] = (object)array("idTipoCaptado"=>$valCaptado->idTipoCaptado, "tipoCaptado"=>$valCaptado->tipoCaptado, "activo"=>$valCaptado->activo);
        }else{
            $colCaptadosRand[] = (object)array("idTipoCaptado"=>$valCaptado->idTipoCaptado, "tipoCaptado"=>$valCaptado->tipoCaptado, "activo"=>$valCaptado->activo);
        }
    }
}

//Imp. 28/04/21
//Excluir para  Prospectadores=17, Agente Ventas=18
//Permitir para SuperAdmin=8, Direccion:10, Gerentes Venta:11, Gerentes prospeccin=19, Redes=20
$slFuente = "sel_sololectura";
if( in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("19", $this->groups) || in_array("20", $this->groups) ){
    $slFuente = "";
}

?>
<style>
.defaultKCD{ position: absolute;margin: 0px;padding: 0px;} label{ width: 170px; }
.adminform label{ min-width: 170px; padding: 0 5px 0 0; }
/*.mayuscula{text-transform: uppercase;}*/
</style>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=prospecto'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="notesUlEsphabit">
    </div>

    <?php if($verBtnGuardar==0){ ?>
        <div class="alert alert-danger">
          No es posible crear el prospecto ya que no tienes un gerente asociado, comunicate con tu superior para que te lo asocien y puedas continuar el proceso.
        </div>
    <?php } ?>
    <div style="">
        <fieldset class="adminform" style="display:inline-block;width:35%;float:right;">
            <div class="control-group">
                <div class="control-label">
                    <label for="fuente">Fuente:</label>
                </div>
                <div class="controls">
                    <!-- <input type="text" name="fuente" id="fuente" value="<?php echo $fuente; ?>" style="width:180px;" readonly/> -->

                    <!-- sel_sololectura -->
                    <select id="fuente" name="fuente" class="required <?php echo $slFuente;?>">
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($colCaptadosRand as $item) {
                            if ($fuente == $item->idTipoCaptado)
                                $sel = 'selected';
                            else
                                $sel = '';

                            echo '<option ' . $sel . ' value="' . $item->idTipoCaptado . '">' . $item->tipoCaptado . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php if($noSegEdit==false){ ?>
                <?php if($permisosUsrGteAgtPro->usrGteVentas==true){ ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="nombreGteJoomlaVentas">Gerente Ventas:</label>
                    </div>
                    <div class="controls">
                        <select id="nombreGteJoomlaVentas" name="nombreGteJoomlaVentas" class="opcGteSel <?php echo ($this->id==0) ?"required" :($this->opcGerente=="gteventas") ?"required" :""; ?> ">
                            <option value="">--Seleccione--</option>
                            <?php
                            foreach ($colUsrGtesVentas as $itemGteV) {
                                if ($idGteJoomla == $itemGteV->id)
                                    $sel = 'selected';
                                else
                                    $sel = '';
                                echo '<option ' . $sel . ' value="' . $itemGteV->id . '">' . $itemGteV->name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?php } ?>
                <!-- <?php if($permisosUsrGteAgtPro->usrGteProspeccion==true){ ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="nombreGteJoomlaProspeccion"><span class="star">&nbsp;*</span> Gerente Prospecci&oacute;n:</label>
                    </div>
                    <div class="controls">
                        <select id="nombreGteJoomlaProspeccion" name="nombreGteJoomlaProspeccion" class="opcGteSel <?php echo ($this->id==0) ?"required" :($this->opcGerente=="gteprospeccion") ?"required" :""; ?>">
                            <option value="">--Seleccione--</option>
                            <?php
                            foreach ($colUsrGtesProspeccion as $itemGteP) {
                                if ($idGteJoomla == $itemGteP->id)
                                    $sel = 'selected';
                                else
                                    $sel = '';
                                echo '<option ' . $sel . ' value="' . $itemGteP->id . '">' . $itemGteP->name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?php } ?> -->
                <?php if($permisosUsrGteAgtPro->usrAgtVentas==true){ ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="usuarioAgenteVentas">Agente Ventas:</label>
                    </div>
                    <div class="controls" id="cont_usuarioAgenteVentas">
                        <select id="usuarioAgenteVentas" name="usuarioAgenteVentas">
                            <option value="">--Seleccione--</option>
                            <?php if(count($colAsesores)>0){
                                    foreach ($colAsesores as $elemAgtV) {
                                        if ($idUsuarioJoomla == $elemAgtV->usuarioIdJoomla)
                                            $sel = 'selected';
                                        else
                                            $sel = '';
                                        echo '<option ' . $sel . ' value="' . $elemAgtV->usuarioIdJoomla . '">' . $elemAgtV->nombre . '</option>';
                                    }
                                } ?>
                        </select>
                    </div>
                </div>
                <?php } ?>
                <!-- <?php if($permisosUsrGteAgtPro->usrProspectador==true){ ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="usuarioProspectador">Usuario Prospectador:</label>
                    </div>
                    <div class="controls" id="cont_usuarioProspectador">
                        <select id="usuarioProspectador" name="usuarioProspectador">
                            <option value="">--Seleccione--</option>
                            <?php if(count($colProspectadores)>0){
                                    foreach ($colProspectadores as $elemProspec) {
                                        if ($idUsuarioJoomla == $elemProspec->usuarioIdJoomla)
                                            $sel = 'selected';
                                        else
                                            $sel = '';
                                        echo '<option ' . $sel . ' value="' . $elemProspec->usuarioIdJoomla . '">' . $elemProspec->nombre . '</option>';
                                    }
                                } ?>
                        </select>
                    </div>
                </div>
                <?php } ?> -->
            <?php }else{ ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="nombreGteJoomla">Gerente:</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="nombreGteJoomla" id="nombreGteJoomla" value="<?php echo $nombreGteJoomla; ?>" class="" style="width:180px;" readonly/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label for="nombreAsesorJoomla">Agente Ventas:</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="nombreAsesorJoomla" id="nombreAsesorJoomla" value="<?php echo $nombreAsesorJoomla; ?>" class="" style="width:180px;" readonly/>
                        <input type="hidden" id="usuarioAgenteVentas" name="usuarioAgenteVentas" value="<?php echo $agtVentasId;?>" >
                    </div>
                </div>
                <!-- <div class="control-group">
                    <div class="control-label">
                        <label for="nombreUsrJoomla"><span class="star">&nbsp;*</span> Usuario:</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="nombreUsrJoomla" id="nombreUsrJoomla" value="<?php echo $nombreUsrJoomla; ?>" class="required" style="width:180px;" readonly/>
                    </div>
                </div> -->
            <?php } ?>

            <div class="control-group">
                <div class="control-label">
                    <label for="fechaAlta">Fecha Alta:</label>
                </div>
                <div class="controls">
                    <input type="text" name="fechaAlta" id="fechaAlta" value="<?php echo $fechaAlta; ?>" class="required" style="width:100px;" readonly/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="fechaUltCambio">F. Ult. Cambio:</label>
                </div>
                <div class="controls">
                    <input type="text" name="fechaUltCambio" id="fechaUltCambio" value="<?php echo $fechaActualizacion; ?>" class="required" style="width:100px;" readonly/>
                </div>
            </div>
            <input type="hidden" name="idUsrJoomla" value="<?php echo $idUsuarioJoomla;?>">
            <input type="hidden" name="opcUsuario" value="<?php echo $this->opcUsuario;?>">

            <input type="hidden" name="idGte" value="<?php echo $idGteJoomla;?>">
            <input type="hidden" name="opcGerente" value="<?php echo $this->opcGerente;?>">

        </fieldset>

        <fieldset class="adminform">
            <div class="control-group">
                <div class="control-label">
                    <label for="nombre">Nombre:</label>
                </div>
                <div class="controls">
                    <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" class="required mayuscula" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="aPaterno">Apellido Paterno:</label>
                </div>
                <div class="controls">
                    <input type="text" name="aPaterno" id="aPaterno" value="<?php echo $aPaterno; ?>" class="required mayuscula" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="aManterno">Apellido Materno:</label>
                </div>
                <div class="controls">
                    <input type="text" name="aMaterno" id="aMaterno" value="<?php echo $aMaterno; ?>" class="required mayuscula" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="email">Email:</label>
                </div>
                <div class="controls">
                    <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="required email mayuscula" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="telefono">Tel&eacute;fono:</label>
                </div>
                <div class="controls">
                    <input type="text" name="telefono" id="telefono" value="<?php echo $telefono; ?>" class="required digits" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="estatusContactoId">Estatus:</label>
                </div>
                <div class="controls">
                    <select id="estatusContactoId" name="estatusContactoId" class="required">
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($colEstatusContacto as $item) {
                            if ($item->idEstatus!=6){
                                if ($estatusId == $item->idEstatus)
                                    $sel = 'selected';
                                else
                                    $sel = '';
                                echo '<option ' . $sel . ' value="' . $item->idEstatus . '">' . $item->nombre . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div id="cont_motivocomentario_descartado" style="<?php echo ($estatusId==4)?"":"display:none;"; ?>">
                <div class="control-group">
                    <div class="control-label">
                        <label for="motivo_descartado">Motivo de descarte:</label>
                    </div>
                    <div class="controls">
                        <textarea name="motivo_descartado" id="motivo_descartado" class="form-control" style="min-width:60%;"><?php echo $motivo_descarte; ?></textarea>
                    </div>
                </div>
                <div class="control-group" id="cont_comentario_descartado">
                    <div class="control-label">
                        <label for="comentario_descartado">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="comentario_descartado" id="comentario_descartado" class="form-control" style="min-width:60%;"><?php echo $comentario_descarte; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="control-group">
                <div class="control-label">
                    <label for="fechaContacto">Fecha Contacto:</label>
                </div>
                <div class="controls">
                    <input type="text" name="fechaContacto" id="fechaContacto" value="<?php echo $fechaContacto; ?>" style="width:100px;" readonly/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="idFracc">Desarrollo de inter&eacute;s: </label>
                </div>
                <div class="controls">
                    <select id="idFracc" name="idFracc" class="required">
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($colFracc as $itemFra) {
                            if ($desarrolloId == $itemFra->idFraccionamiento)
                                    $sel = 'selected';
                                else
                                    $sel = '';
                            echo '<option ' . $sel . ' value="' . $itemFra->idFraccionamiento . '">' . $itemFra->nombre . '</option>';
                            // echo '<option value="' . $itemFra->idFraccionamiento . '">' . $itemFra->nombre . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="credito">Tipo Cr&eacute;dito:</label>
                </div>
                <div class="controls">
                    <select id="credito" name="credito" class="">
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($this->ColTiposCto as $item) {
                            if ($credito == $item->idDato)
                                $sel = 'selected';
                            else
                                $sel = '';
                            echo '<option ' . $sel . ' value="' . $item->idDato . '">' . $item->nombre . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <!-- <div class="control-group">
                <div class="control-label">
                    <label for="credito">Cr&eacute;dito:</label>
                </div>
                <div class="controls">
                    <input type="text" name="credito" id="credito" value="<?php echo $credito; ?>" class="required mayuscula" style="width:180px;"/>
                </div>
            </div> -->
        </fieldset>
    </div>

    <input type="hidden" name="task" value="prospecto.edit" />
    <input type="hidden" id="check_un" name="check_un" value="<?php echo $this->id; ?>" />
    <input type="hidden" id="rutaCalendario" value="<?php echo JURI::root().'media/com_sasfe/images/calendar.gif'; ?>" />
    <input type="hidden" id="path" value="<?php echo $this->pathUrl;?>" />
    <input type="hidden" id="loading_img" value="<?php echo $this->imgLoading; ?>" />
    <!-- <input type="hidden" name="rfc_duplicado" id="rfc_duplicado" value="0" /> -->
    <input type="hidden" id="verBtnGuardar" value="<?php echo $verBtnGuardar; ?>" />
    <input type="hidden" name="vistaCtr" value="1" />
    <!-- <input type="hidden" name="gtvId_rfc" id="gtvId_rfc" value="0" /> -->
    <input type="hidden" id="msg_duplicado" value="El prospecto ya existe en la base de datos, se enviar&aacute; a revisi&oacute;n y de confirmarse la duplicidad se dar&aacute; de baja." />

    <input type="hidden" name="mandarAProspecto" id="mandarAProspecto" value="0" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<!-- ?php if( $this->id>0  && (in_array("8", $this->groups) || in_array("10", $this->groups)) ){ ?> -->
<?php if( $this->id>0 ){ ?>
    <hr>
    <div style="display:block;width:70%;">
        <div style="display:inline-block;width:20%;">
            <?php if($this->id>0){ ?>
            <button type="button" data-toggle="modal" data-target="#popup_agregaraccion" class="btn btn-small button-new selAgregarAccion">+ Agregar acci&oacute;n</button>
            <?php } ?>
            <br/><br/>
        </div>
    </div>
    <?php echo $koolajax->Render(); ?>
    <?php if($idDatoContacto>0){ ?>
        <?php echo $gridAcciones->Render(); ?>
    <?php } ?>

    <!-- Modal -->
    <div class="modal fade" id="popup_agregaraccion" role="dialog" style="width:400px;position:relative !important;display:none;">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nueva acci&oacute;n</h4>
              </div>
              <div class="modal-body cont_form_popup">
                <form id="form_agregaraccion" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=contactos&task=contacto.agregarAccion'); ?>" method="post">
                    <div class="control-group ctrgr_popup">
                        <div class="control-label">
                            <label for="agaccion_fecha">Fecha:</label>
                        </div>
                        <div class="controls">
                            <input type="text" name="agaccion_fecha" id="agaccion_fecha" value="<?php echo $timeZone->fechaF2; ?>" class="hasTooltip" style="width:80px;" readonly/>
                        </div>
                    </div>
                    <div class="control-group ctrgr_popup">
                        <div class="control-label">
                            <label for="agaccion_accion">Acci&oacute;n:</label>
                        </div>
                        <div class="controls">
                            <select name="agaccion_accion" id="agaccion_accion" class="required">
                                <option value="">--Seleccionar--</option>
                                <?php
                                foreach ($colAccionesContacto as $item) {
                                    echo '<option value="' . $item->idAccion . '">' . $item->nombre . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group ctrgr_popup">
                        <div class="control-label">
                            <label for="agaccion_comentario">Comentario:</label>
                        </div>
                        <div class="controls">
                            <textarea name="agaccion_comentario" id="agaccion_comentario" class="form-control required" style="min-width:90%;"></textarea>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-small button-new btn-success" id="btn_agregaraccion">Aceptar</button>
                    </div>

                    <input type="hidden" name="arrIdCont" id="arrIdCont" value="" />
                    <input type="hidden" name="idAgtVentasNA" id="idAgtVentasNA" value="0" />
                    <input type="hidden" name="vistaNA" id="vistaNA" value="1" />
                </form>
              </div>
          </div>
        </div>
    </div>
<?php } ?>