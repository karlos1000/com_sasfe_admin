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

$idDatoProspecto = (isset($this->data[0]->idDatoProspecto))?$this->data[0]->idDatoProspecto:'';
$fechaAlta = (isset($this->data[0]->fechaAlta))? SasfehpHelper::conversionFechaF2($this->data[0]->fechaAlta) :$this->arrDateTime->fechaF2;
$nombre = (isset($this->data[0]->nombre))?$this->data[0]->nombre:'';
$aPaterno = (isset($this->data[0]->aPaterno))?$this->data[0]->aPaterno:'';
$aManterno = (isset($this->data[0]->aManterno))?$this->data[0]->aManterno:'';
$RFC = (isset($this->data[0]->RFC))?$this->data[0]->RFC:'';
// $fechaNac = (isset($this->data[0]->fechaNac))? SasfehpHelper::conversionFechaF2($this->data[0]->fechaNac) : $this->arrDateTime->fechaF2;
$fechaNac = (isset($this->data[0]->fechaNac))? $this->data[0]->fechaNac : "";
$edad = (isset($this->data[0]->edad))?$this->data[0]->edad:'';
$telefono = (isset($this->data[0]->telefono))?$this->data[0]->telefono:'';
$celular = (isset($this->data[0]->celular))?$this->data[0]->celular:'';
$genero = (isset($this->data[0]->genero))?$this->data[0]->genero:'';
$NSS = (isset($this->data[0]->NSS))?$this->data[0]->NSS:'';
$montoCredito = (isset($this->data[0]->montoCredito))?$this->data[0]->montoCredito:'0';
$tipoCreditoId = (isset($this->data[0]->tipoCreditoId))?$this->data[0]->tipoCreditoId:'';
$subsidio = (isset($this->data[0]->subsidio))?$this->data[0]->subsidio:'0';
$puntosHasta = (isset($this->data[0]->puntosHasta))? SasfehpHelper::conversionFechaF2($this->data[0]->puntosHasta) :'';
$comentario = (isset($this->data[0]->comentario))?$this->data[0]->comentario:'';
$empresa = (isset($this->data[0]->empresa))?$this->data[0]->empresa:'';
$idTipoCaptado = (isset($this->data[0]->idTipoCaptado))?$this->data[0]->idTipoCaptado:'';
$email = (isset($this->data[0]->email))?$this->data[0]->email:'';
$departamentoId = (isset($this->data[0]->departamentoId))?$this->data[0]->departamentoId:'';
$estatusId = (isset($this->data[0]->estatusId))?$this->data[0]->estatusId:'';
//Datos Casa
$numeroDpto = (isset($this->data[0]->numeroDpto))?$this->data[0]->numeroDpto:'';
$nFracc = (isset($this->data[0]->nFracc))?$this->data[0]->nFracc:'';
$fechaLimiteApartado = (isset($this->data[0]->fechaLimiteApartado))?$this->data[0]->fechaLimiteApartado:'';
$fechaDptoAsignado = (isset($this->data[0]->fechaDptoAsignado))?$this->data[0]->fechaDptoAsignado:'';
$desarrolloId = (isset($this->data[0]->desarrolloId))?$this->data[0]->desarrolloId:'';  //id desarrollo
$estatusDpto = "";

if($fechaDptoAsignado!=""){
    $estatusDpto = "Asignado";    
}else{
    if($fechaLimiteApartado!=""){        
        $datetime1 = new DateTime($fechaLimiteApartado);
        $datetime2 = new DateTime($timeZone->fecha);
        $interval = date_diff($datetime1, $datetime2);
        $estatusDpto = "Apartado " .$interval->format('%a'). " d&iacute;as";
    }
}
// $usuarioId = (isset($this->data[0]->usuarioId))?$this->data[0]->usuarioId:'';
// $gteProspeccionId = (isset($this->data[0]->gteProspeccionId))?$this->data[0]->gteProspeccionId:'';
// $gteVentasId = (isset($this->data[0]->gteVentasId))?$this->data[0]->gteVentasId:'';
// $prospectadorId = (isset($this->data[0]->prospectadorId))?$this->data[0]->prospectadorId:'';
$agtVentasId = (isset($this->data[0]->agtVentasId))?$this->data[0]->agtVentasId:'';
$altaProspectadorId = (isset($this->data[0]->altaProspectadorId))?$this->data[0]->altaProspectadorId:'';
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
$this->opc = 1000;
$noSegEdit = false;
$nombreGteJoomla = "";
//Saber si esta creando o editando
if($this->id==0){    
}else{
    //Esta editando    
    //Tiene asociado un gerente de prospeccion
    if($this->data[0]->gteProspeccionId!="" && $this->data[0]->gteProspeccionId>0){
        $userGte = JFactory::getUser($this->data[0]->gteProspeccionId);        
        $idGteJoomla =  $userGte->get('id');  //id del gerente
        $this->opcGerente = "gteprospeccion";
        
        $colProspectadores = SasfehpHelper::obtColProspectadoresXIdGte($idGteJoomla); //Obtener coleccion de usuarios Prospectador  por gerente de ventas
        $idUsuarioJoomla = $altaProspectadorId;
        $this->opcUsuario = "prospectador";                
    }
    //Tiene asociado un gerente de ventas
    if($this->data[0]->gteVentasId!="" && $this->data[0]->gteVentasId>0){
        $userGte = JFactory::getUser($this->data[0]->gteVentasId);        
        $idGteJoomla =  $userGte->get('id');  //id del gerente 
        $nombreGteJoomla = $userGte->get('name'); //Nombre del gerente joomla       
        $this->opcGerente = "gteventas";

        // $this->groupsgte = array(11);        
        $colAsesores = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($idGteJoomla); //Obtener coleccion de usuarios Agentes venta por gerente de ventas        
        $colProspectadores = SasfehpHelper::obtColProspectadoresXIdGte($idGteJoomla); //Obtener coleccion de usuarios Prospectador  por gerente de ventas    
       
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


//Si esta en edicion agregar grid de eventos y comentarios
if( $this->id>0 && (in_array("8", $this->groups) || in_array("10", $this->groups)) ){
    $tipoOpc = "";   
    if(isset($_POST['filter_tipo'])){
        $tipoOpc = $_POST['filter_tipo'];
        $gridEvCom = SasfehpHelper::ObtEventosComentariosGrid($this->id, $tipoOpc);
    }else{
        $gridEvCom = SasfehpHelper::ObtEventosComentariosGrid($this->id, $tipoOpc);
    }
    // print_r(SasfehpHelper::obtenerDepartamentosDisponibles(7,14));
}


// //Saber a que agente esta asociado 
// if(in_array("11", $this->groupsgte)){
// }
// if(in_array("19", $this->groupsgte)){
// }                
// echo "<pre>"; print_r($this->groupsgte); echo "</pre>";

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
                    <label for="fechaAlta"><span class="star">&nbsp;*</span> Fecha Alta:</label>
                </div>
                <div class="controls">
                    <input type="text" name="fechaAlta" id="fechaAlta" value="<?php echo $fechaAlta; ?>" class="required" style="width:100px;" readonly/>                        
                </div>
            </div>
            <?php if($noSegEdit==false){ ?>            
                <?php if($permisosUsrGteAgtPro->usrGteVentas==true){ ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="nombreGteJoomlaVentas"><span class="star">&nbsp;*</span> Gerente Ventas:</label>
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
                <?php if($permisosUsrGteAgtPro->usrGteProspeccion==true){ ?>
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
                <?php } ?>
                <?php if($permisosUsrGteAgtPro->usrAgtVentas==true){ ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="usuarioAgenteVentas">Usuario Agente Ventas:</label>
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
                <?php if($permisosUsrGteAgtPro->usrProspectador==true){ ?>
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
                <?php } ?>
            <?php }else{ ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="nombreGteJoomla"><span class="star">&nbsp;*</span> Gerente:</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="nombreGteJoomla" id="nombreGteJoomla" value="<?php echo $nombreGteJoomla; ?>" class="required" style="width:180px;" readonly/>                    
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label for="nombreUsrJoomla"><span class="star">&nbsp;*</span> Usuario:</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="nombreUsrJoomla" id="nombreUsrJoomla" value="<?php echo $nombreUsrJoomla; ?>" class="required" style="width:180px;" readonly/>                    
                    </div>
                </div>
            <?php } ?>

            <?php if( $this->id>0 && (in_array("8", $this->groups) || in_array("10", $this->groups)) ){ ?>
            <div class="control-group">
                <div class="control-label">
                    <label for="fraccionamiento">Fraccionamiento:</label>
                </div>
                <div class="controls">
                    <input type="text" name="fraccionamiento" id="fraccionamiento" value="<?php echo $nFracc; ?>" style="width:180px;" readonly/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="casadpto">Casa/Dpto:</label>
                </div>
                <div class="controls">
                    <input type="text" name="casadpto" id="casadpto" value="<?php echo $numeroDpto; ?>" style="width:180px;" readonly/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="estatusCasa">Estatus:</label>
                </div>
                <div class="controls">
                    <input type="text" name="estatusCasa" id="estatusCasa" value="<?php echo $estatusDpto; ?>" style="width:180px;" readonly/>
                </div>
            </div>                
                <div>
                    <?php if($departamentoId=="" || $departamentoId==0){ ?>
                        <?php if($agtVentasId!="" && $agtVentasId>0){ ?>
                        <button type="button" data-toggle="modal" data-target="#popup_asignarcasa" class="btn btn-small button-new" id="selAsigCasa">Asignar</button>
                        <button type="button" data-toggle="modal" data-target="#popup_noprocede" class="btn btn-small button-new" id="selNoProcede"><!-- No procede -->No interesados</button>
                        <?php } ?>
                    <?php } ?>
                    <?php if($departamentoId!="" && $fechaLimiteApartado!=""){ ?>
                    <button type="button" class="btn btn-small button-new" id="selLiberarCasa">Liberar</button>
                    <input type="hidden" name="dptId" value="<?php echo $departamentoId;?>">
                    <?php } ?>
                </div>                    
            <?php } ?>

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
                    <input type="text" name="aManterno" id="aManterno" value="<?php echo $aManterno; ?>" class="required mayuscula" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="fechaNac">Fecha Nacimiento:</label>
                </div>
                <div class="controls">
                    <!--<div id="contFechaNac"></div>-->
                    <!-- <input type="text" name="fechaNac" id="fechaNac" value="<?php echo $fechaNac; ?>" class="required" style="width:100px;" readonly/> -->                    
                    <!--<input type="hidden" name="fechaNac" id="fechaNac" value="<?php echo $fechaNac; ?>" />-->                    
                    <input type="date" name="fechaNac" id="fechaNac" data-date-format="dd/mm/yyyy" value="<?php echo $fechaNac; ?>">
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="RFC">RFC:</label>
                </div>
                <div class="controls">
                    <input type="text" name="RFC" id="RFC" value="<?php echo $RFC; ?>" class="mayuscula" style="width:150px;"/>
                    <div class="addInfo" style="display:inline-block;"></div>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="edad">Edad:</label>
                </div>
                <div class="controls">
                    <input type="text" name="edad" id="edad" value="<?php echo $edad; ?>" class="digits" style="width:50px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="telefono">Tel&eacute;fono:</label>
                </div>
                <div class="controls">
                    <input type="text" name="telefono" id="telefono" value="<?php echo $telefono; ?>" class="required" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="celular">Celular:</label>
                </div>
                <div class="controls">
                    <input type="text" name="celular" id="celular" value="<?php echo $celular; ?>" class="" style="width:180px;"/>
                </div>
            </div>            
            <div class="control-group">
                <div class="control-label">
                    <label for="genero">G&eacute;nero:</label>
                </div>
                <div class="controls">
                    <div style="clear:both;">                             
                        <div><label for="genero1" style="display:inline-block;min-width:10px !important;width:10px;">M</label> <input type="radio" name="genero" id="genero1" value="1" <?php echo ($genero==1) ? 'checked': '' ?> class="required" required></div>
                        <div><label for="genero2" style="display:inline-block;min-width:10px !important;width:10px;">F</label> <input type="radio" name="genero" id="genero2" value="2" <?php echo ($genero==2) ? 'checked': '' ?> class="required" required></div>                        
                    </div>
                </div> 
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="NSS">NSS:</label>
                </div>
                <div class="controls">
                    <input type="text" name="NSS" id="NSS" value="<?php echo $NSS; ?>" class="mayuscula" style="width:150px;" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="montoCredito">Monto Cr&eacute;dito:</label>
                </div>
                <div class="controls">
                    <input type="text" name="montoCredito" id="montoCredito" value="<?php echo "$".number_format($montoCredito,2); ?>" class="fmonto" style="width:180px;" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="tipoCreditoId">Tipo Cr&eacute;dito:</label>                
                </div>
                <div class="controls">
                    <select id="tipoCreditoId" name="tipoCreditoId" class="">                  
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($this->ColTiposCto as $item) {
                            if ($tipoCreditoId == $item->idDato)
                                $sel = 'selected';
                            else
                                $sel = '';
                            echo '<option ' . $sel . ' value="' . $item->idDato . '">' . $item->nombre . '</option>';                        
                        }
                        ?>
                    </select>               
                </div>
            </div>
            
            <div class="control-group">
                <div class="control-label">
                    <label for="subsidio">Subsidio:</label>
                </div>
                <div class="controls">
                    <input type="text" name="subsidio" id="subsidio" value="<?php echo "$".number_format($subsidio,2); ?>" class="required fmonto" style="width:180px;" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="puntosHasta">Puntos Hasta:</label>                
                </div>
                <div class="controls">
                    <div id="contPuntosHasta"></div>
                    <input type="hidden" name="puntosHasta" id="puntosHasta" value="<?php echo $puntosHasta; ?>" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="comentario">Comentario:</label>
                </div>
                <div class="controls">
                    <textarea name="comentario" id="comentario" rows="5" class="mayuscula" style="width:350px;"><?php echo $comentario; ?></textarea>                    
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="empresa">Empresa donde labora:</label>
                </div>
                <div class="controls">
                    <input type="text" name="empresa" id="empresa" value="<?php echo $empresa; ?>" class="mayuscula" style="width:180px;" />
                </div>
            </div>            
            <div class="control-group">
                <div class="control-label">
                    <label for="captadoen">Captado en:</label>
                </div>
                <div class="controls">
                    <select id="captadoen" name="captadoen" class="">                  
                        <option value="">--Seleccione--</option>
                        <?php
                        // foreach ($this->arrTiposCaptados as $item) {
                        foreach ($colCaptadosRand as $item) {
                            if ($idTipoCaptado == $item->idTipoCaptado)
                                $sel = 'selected';
                            else
                                $sel = '';
                            
                            echo '<option ' . $sel . ' value="' . $item->idTipoCaptado . '">' . $item->tipoCaptado . '</option>';                        
                        }
                        ?>
                    </select>
                </div>                
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="email">E-mail:</label>
                </div>
                <div class="controls">
                    <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="" style="width:180px;" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="idFracc">Desarrollo de inter&eacute;s: </label>
                </div>
                <div class="controls">
                    <select id="idFracc" name="idFracc">
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($colFracc as $itemFra) {
                            if ($desarrolloId == $itemFra->idFraccionamiento)
                                    $sel = 'selected';
                                else                                
                                    $sel = '';
                            echo '<option ' . $sel . ' value="' . $itemFra->idFraccionamiento . '">' . $itemFra->nombre . '</option>';
                        }
                        ?>                    
                    </select>
                </div>
            </div>
        </fieldset>
    </div>
        
    <input type="hidden" name="task" value="prospecto.edit" />
    <input type="hidden" id="check_un" name="check_un" value="<?php echo $this->id; ?>" />
    <input type="hidden" id="rutaCalendario" value="<?php echo JURI::root().'media/com_sasfe/images/calendar.gif'; ?>" />
    <input type="hidden" id="path" value="<?php echo $this->pathUrl;?>" />
    <input type="hidden" id="loading_img" value="<?php echo $this->imgLoading; ?>" /> 
    <input type="hidden" name="rfc_duplicado" id="rfc_duplicado" value="0" />
    <input type="hidden" id="verBtnGuardar" value="<?php echo $verBtnGuardar; ?>" />
    <input type="hidden" name="vistaCtr" value="1" />
    <input type="hidden" name="opcDatosProsp" value="<?php echo $this->opc;?>" />    
    <input type="hidden" name="gtvId_rfc" id="gtvId_rfc" value="0" />    
    <input type="hidden" id="msg_duplicado" value="El prospecto ya existe en la base de datos, se enviar&aacute; a revisi&oacute;n y de confirmarse la duplicidad se dar&aacute; de baja." />        
    
    <?php echo JHtml::_('form.token'); ?>
</form>

<?php if( $this->id>0  && (in_array("8", $this->groups) || in_array("10", $this->groups)) ){ ?>
    <hr>
    <div style="display:block;width:70%;">        
        <div style="display:inline-block;width:40%;">
            <form action="" method="post" name="formgrid_evcom" id="formgrid_evcom">
                <label for="filter_tipo" style="display:inline-block;width:30px;">Ver: </label>
                <select name="filter_tipo" id="filter_tipo" class="inputbox" style="width:90px;">
                   <option value="" <?php echo ($tipoOpc=="")?"selected":""; ?> ><?php echo JText::_('-Todos-');?></option>
                   <option value="1" <?php echo ($tipoOpc=="1")?"selected":""; ?> ><?php echo JText::_('Evento');?></option>
                   <option value="2" <?php echo ($tipoOpc=="2")?"selected":""; ?> ><?php echo JText::_('Comentario');?></option>
                </select>
            </form>
        </div>
        <div style="display:inline-block;width:40%;text-align:right;">
            <?php if($fechaDptoAsignado==""){ ?>
            <button type="button" data-toggle="modal" data-target="#popup_agregrarcomentario" class="selProspectoComEdit btn btn-small button-new">+ Comentario</button>
            <button type="button" data-toggle="modal" data-target="#popup_agregarevento" class="selProspecto btn btn-small button-apply">+ Evento</button>
            <?php } ?>
        </div>
    </div>
    <?php echo $koolajax->Render(); ?>
    <?php echo $gridEvCom->Render(); ?>

<!-- Modal -->
<div class="modal fade" id="popup_agregrarcomentario" role="dialog" style="width:500px;position:relative !important;display:none;">
    <div class="modal-dialog">          
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comentario para prospecto</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregar_comentario" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.addcomentario'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="ev_comentario" id="ev_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div> 
                
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_agregrarcomentario">Agregar comentario</button>                    
                </div>

                <input type="hidden" name="com_idPros" id="com_idPros" value="<?php echo $this->id; ?>" />
                <input type="hidden" name="opcDatosProsp" value="<?php echo $this->opc;?>" />
            </form>
          </div>              
      </div>
    </div>
</div>
<!-- Modal agregar evento -->
<div class="modal fade" id="popup_agregarevento" role="dialog" style="width:500px;height:600px;position:relative !important;display:none;">
    <div class="modal-dialog">          
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Evento para prospecto</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregar_evento" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.addevento'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="ev_tipoevento">Evento:</label>
                    </div>
                    <div class="controls">
                        <select name="ev_tipoevento" id="ev_tipoevento" class="required">
                            <option value="">--Seleccionar--</option>
                            <?php
                            foreach ($this->arrTipoEventos as $itemTE) {                                    
                                echo '<option value="' . $itemTE->idTipoEvento . '">' . $itemTE->tipoEvento . '</option>';                        
                            }
                            ?>
                        </select>                            
                    </div>
                </div>                

                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="ev_fecha">Fecha y hora:</label>
                    </div>
                    <div class="controls">
                        <!-- <input type="text" name="ev_fecha" id="ev_fecha" class="required" style="width:100px;" readonly/> -->
                        <div id="ev_fecha_linea" style="display:inline-block;"></div>
                        <input type="hidden" name="ev_fecha" id="ev_fecha" value="<?php echo $timeZone->fechaF2; ?>" />
                        <div style="display:inline-block;"><input class="timepicker timepicker-with-dropdown text-center" name="ev_hora" id="ev_hora" value="7:00" style="width:50px;cursor:pointer;" readonly></div>
                    </div>
                </div>                                        
               
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="ev_optrecordatorio">Recordatorio:</label>
                    </div>
                    <div class="controls">
                        <input type="checkbox" name="ev_optrecordatorio" id="ev_optrecordatorio" class="" value="0">
                    </div>
                </div>
                <div class="control-group ctrgr_popup" style="display:none;" id="cont_ev_tiempo">
                    <div class="control-label">                            
                        <label for="ev_tiempo">Tiempo:</label>
                    </div>
                    <div class="controls">
                        <select name="ev_tiempo" id="ev_tiempo" class="">
                            <option value="">--Selecciona--</option>
                            <?php
                            foreach ($this->arrTiempoRecordatorios as $itemTR) {                                    
                                echo '<option value="' . $itemTR->idTiempoRecordatorio . '">' . $itemTR->texto . '</option>';                        
                            }
                            ?>                                
                        </select>                            
                    </div>
                </div>                    
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="ev_comentario" id="ev_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div> 
                
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_agregarevento">Agregar evento</button>                    
                </div>

                <input type="hidden" name="ev_idPros" id="ev_idPros" value="<?php echo $this->id; ?>" />
                <input type="hidden" name="edit_evpros" value="1" />
                <input type="hidden" name="opcDatosProsp" value="<?php echo $this->opc;?>" />
            </form>
          </div>              
      </div>
    </div>
</div>
<!-- Modal asignar casa -->
<div class="modal fade" id="popup_asignarcasa" role="dialog" style="width:500px;position:relative !important;display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Apartar Propiedad</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_asignarcasa" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.asignarcasa'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="asigcasa_fraccionamiento">Fraccionamiento:</label>
                    </div>
                    <div class="controls">
                        <select name="asigcasa_fraccionamiento" id="asigcasa_fraccionamiento" class="required">
                            <option value="">--Seleccionar--</option>
                            <?php
                                foreach ($this->Fracc as $item) {
                                    echo '<option ' . $sel . ' value="' . $item->idFraccionamiento . '">' . $item->nombre . '</option>';
                                }
                            ?>
                        </select>                            
                    </div>
                </div>
                <!-- style="display:none;" id="cont_asigcasa_casadpto" -->
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="asigcasa_casadpto">Casa/Dpto:</label>
                    </div>
                    <div class="controls" id="edit_cont_dptos">                        
                        <select name="asigcasa_casadpto" id="asigcasa_casadpto" class="required custom-select">
                            <option value="">--Seleccionar--</option>                         
                        </select>                        
                        
                        <div id="edit_cont_dptos_loading"><div class="addInfo" style="display:inline-block;"></div></div>
                    </div>                     
                </div>

                <div>    
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_asignarcasa">Aceptar</button>                    
                </div>

                <input type="hidden" name="asigcasa_idPros" id="asigcasa_idPros" value="<?php echo $this->id; ?>" />
                <input type="hidden" name="opcDatosProsp" value="<?php echo $this->opc;?>" />
            </form>
          </div>              
      </div>
    </div>
</div>

 <!-- Modal popup noprocede -->
<div class="modal fade" id="popup_noprocede" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">          
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><!-- No proceder -->No interesados</h4>
          </div>                          
          <div class="modal-body cont_form_popup">
            <form id="form_noprocede" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.noprocede'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="noprocede_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="noprocede_comentario" id="noprocede_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_noprocede">Aceptar</button>                    
                </div>

                <input type="hidden" name="noprocede_idPros" value="<?php echo $this->id; ?>" />
                <input type="hidden" name="opcDatosProsp" value="<?php echo $this->opc;?>" />
            </form>
          </div>              
      </div>
    </div>
</div>

<?php } ?>