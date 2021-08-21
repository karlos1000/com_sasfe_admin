<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');
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
$montoCredito = (isset($this->data[0]->montoCredito))?$this->data[0]->montoCredito:'';
$tipoCreditoId = (isset($this->data[0]->tipoCreditoId))?$this->data[0]->tipoCreditoId:'';
$subsidio = (isset($this->data[0]->subsidio))?$this->data[0]->subsidio:'';
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
//$dptoAsignadoCheck = 0;
if($fechaDptoAsignado!=""){
    $estatusDpto = "Asignado";
    //$dptoAsignadoCheck = 1;
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

$verBtnGuardar=1;
$this->groupsgte = array();
$nombreUsrJoomla = "";
$idUsuarioJoomla = "";
$nombreGteJoomla = "";
$idGteJoomla =  "";

//Saber si esta creando o editando
if($this->id==0){
    //Esta creando
    $nombreUsrJoomla = $this->user->name; //Nombre del usuario joomla que esta creando el prospecto
    $idUsuarioJoomla = $this->user->id; //id del usuario joomla que esta creando el prospecto

    //obtener datos del gte joomla tabla usuario    
    $this->datosUsuario = SasfehpHelper::obtUsuarioDatosCatalogoPorIdUsrJoomla($idUsuarioJoomla);
    // echo "<pre>"; print_r($this->datosUsuario); echo "</pre>";
    if($this->datosUsuario[0]->usuarioIdGteJoomla!="" && $this->datosUsuario[0]->usuarioIdGteJoomla>0){
        $this->groupsgte = JAccess::getGroupsByUser($this->datosUsuario[0]->usuarioIdGteJoomla, true);  //obtiene grupo/s de los gerentes       
        $userGte = JFactory::getUser($this->datosUsuario[0]->usuarioIdGteJoomla);
        $nombreGteJoomla = $userGte->get('name'); //Nombre del gerente joomla
        $idGteJoomla =  $userGte->get('id');  //id del gerente
        // echo "<pre>"; print_r($userGte); echo "</pre>"; 
    }else{
       $verBtnGuardar=0; 
    }
}else{   
    //Esta editando
    // $userLogJoomla = JFactory::getUser($this->data[0]->usuarioId);
    // $nombreUsrJoomla = $userLogJoomla->get('name'); //Nombre del usuario joomla que creo el prospecto
    // $idUsuarioJoomla = $this->data[0]->usuarioId;
    //comprobar si es un agente de ventas (asesor) o un prospectador
    if($altaProspectadorId!="" && $altaProspectadorId>0){
        $idUsuarioJoomla = $altaProspectadorId;        
    }else{
        $idUsuarioJoomla = $agtVentasId;
    }
    if($idUsuarioJoomla!=""){
        $userLogJoomla = JFactory::getUser($idUsuarioJoomla);
    $nombreUsrJoomla = $userLogJoomla->get('name'); //Nombre del usuario joomla que creo el prospecto
    }    

    //Tiene asociado un gerente de prospeccion
    if($this->data[0]->gteProspeccionId!="" && $this->data[0]->gteProspeccionId>0){
        $userGte = JFactory::getUser($this->data[0]->gteProspeccionId);
        $nombreGteJoomla = $userGte->get('name'); //Nombre del gerente joomla
        $idGteJoomla =  $userGte->get('id');  //id del gerente
        $this->groupsgte = array(19);        
    }
    //Tiene asociado un gerente de ventas
    if($this->data[0]->gteVentasId!="" && $this->data[0]->gteVentasId>0){
        $userGte = JFactory::getUser($this->data[0]->gteVentasId);
        $nombreGteJoomla = $userGte->get('name'); //Nombre del gerente joomla
        $idGteJoomla =  $userGte->get('id');  //id del gerente
        $this->groupsgte = array(11);
    }

    //Obtener a que grupo pertenece el usuario que lo creo solo para esta vista
    $this->groups = JAccess::getGroupsByUser($idUsuarioJoomla, true);  //obtiene grupo/s del usuario que creo el registro
    // echo "<pre>"; print_r($this->groups); echo "</pre>"; 
}
//Saber si el usuario logueado es un prospectador o un agente de ventas
if(in_array("17", $this->groups)){
    $this->opcUsuario = "prospectador";
}
if(in_array("18", $this->groups)){            
    $this->opcUsuario = "agenteventas";
}
//Saber a que agente esta asociado 
if(in_array("11", $this->groupsgte)){
    $this->opcGerente = "gteventas";
}
if(in_array("19", $this->groupsgte)){
    $this->opcGerente = "gteprospeccion";
}                
// echo "<pre>"; print_r($this->groupsgte); echo "</pre>";

//Mostrar solo para los grupos gerentes
if(in_array("11", $this->groupsgte) || in_array("19", $this->groupsgte)){
    $colRFCDuplicados = SasfehpHelper::obtProspectosRelacionadosRepetidos($RFC, $this->id);
}
//Obtener los fraccionamientos 
$colFracc = SasfehpHelper::obtTodosFraccionamientos();


// if(in_array("19", $this->groupsOrig) || in_array("11", $this->groupsOrig)){
//     echo "Es gerente de ventas o prospeccion";
// }else{
//     echo "NA";
// }
?>
<style>
.defaultKCD{ position: absolute;margin: 0px;padding: 0px;} label{ width: 170px; } 
.adminform label{ min-width: 170px; padding: 0 5px 0 0; }
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
            <?php 
            // if($this->id>0 && $departamentoId!=""){                
               if($this->id>0 && ($agtVentasId!="" && $agtVentasId>0)){
             ?>
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
                <?php if(!in_array("19", $this->groupsgte) && !in_array("17", $this->groupsOrig)){ ?>
            <div>
                <?php if($departamentoId=="" || $departamentoId==0){ ?>
                    <?php if($agtVentasId!="" && $agtVentasId>0){ ?>
                    <button type="button" data-toggle="modal" data-target="#popup_asignarcasa" class="btn btn-small button-new" id="selAsigCasa">Asignar</button>
                    <?php } ?>
                <?php } ?>
                <?php if($departamentoId!="" && $fechaLimiteApartado!=""){ ?>
                <button type="button" class="btn btn-small button-new" id="selLiberarCasa">Liberar</button>
                <input type="hidden" name="dptId" value="<?php echo $departamentoId;?>">
                <?php } ?>
            </div>    
                <?php } ?>
            <?php } ?>
            
            <!-- Mostrar para los usuarios duplicados -->
            <?php if(count($colRFCDuplicados)>0){ ?>   
                <?php
                    // echo "<pre>"; print_r($this->groupsgte); echo "</pre>";
                    // echo "<pre>"; print_r($this->groupsOrig); echo "</pre>";
                 ?>
                <?php //No mostrar para el grupo prospectador ?>             
                <?php if( ( in_array("11", $this->groupsgte) || in_array("19", $this->groupsgte) ) && !in_array("17", $this->groupsOrig) ){ ?>
                <?php if($this->opc==1 ||  $this->opc==2){ ?>
                <?php if($fechaDptoAsignado=="" && $fechaLimiteApartado==""){ ?>

                    <!-- Comprobar si el usuario logueado es gerente -->
                    <?php if(in_array("19", $this->groupsOrig) || in_array("11", $this->groupsOrig)){
                        $respDifGerencia = SasfehpHelper::revisaGerenciasPorRfc($RFC);
                        if($respDifGerencia==0){ ?>
                            <button type="button" data-toggle="modal" data-target="#popup_validar_prospecto_repetido" idPros="<?php echo $this->id;?>" idAgteVenta="0" class="selValidarProspectoRepetidoLink">
                                </span>
                                <?php //Gerentes de ventas ?>
                                <?php if(in_array("11", $this->groupsgte) && $this->opc==1){ ?>
                                    Re/Asignar agente ventas al prospecto 
                                <?php } ?>
                                <?php if(in_array("11", $this->groupsgte) && $this->opc==2){ ?>
                                    Validar y asignar agente ventas al prospecto
                                <?php } ?>

                                <?php //Gerentes de prospeccion ?>
                                <?php if(in_array("19", $this->groupsgte) && $this->opc==1){ ?>
                                    Re/Asignar gerente ventas al prospecto
                                <?php } ?>
                                <?php if(in_array("19", $this->groupsgte) && $this->opc==2){ ?>
                                    Validar y asignar gerente ventas al prospecto
                                <?php } ?>        
                            </button>
                        <?php } ?>    
                    <?php }else{ ?>
                        <button type="button" data-toggle="modal" data-target="#popup_validar_prospecto_repetido" idPros="<?php echo $this->id;?>" idAgteVenta="0" class="selValidarProspectoRepetidoLink">
                            </span>
                            <?php //Gerentes de ventas ?>
                            <?php if(in_array("11", $this->groupsgte) && $this->opc==1){ ?>
                                Re/Asignar agente ventas al prospecto 
                            <?php } ?>
                            <?php if(in_array("11", $this->groupsgte) && $this->opc==2){ ?>
                                Validar y asignar agente ventas al prospecto
                            <?php } ?>

                            <?php //Gerentes de prospeccion ?>
                            <?php if(in_array("19", $this->groupsgte) && $this->opc==1){ ?>
                                Re/Asignar gerente ventas al prospecto
                            <?php } ?>
                            <?php if(in_array("19", $this->groupsgte) && $this->opc==2){ ?>
                                Validar y asignar gerente ventas al prospecto
                            <?php } ?>        
                        </button>        
                    <?php } ?>

                <?php } ?>
                <?php } ?>
                <?php } ?>

                <?php if($this->opc==2){ ?>           
                <div>
                    <table class="table" style="width:80%;">
                        <thead>
                          <tr>
                            <th>Nombre</th>
                            <th>NSS</th>
                            <th>Fecha Nacimiento</th>
                            <th>Resumen</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($colRFCDuplicados as $elemDupl) { ?>
                            <?php //$perCreadoPor=""; if($elemDupl->usuarioId!=""){ $datosUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($elemDupl->usuarioId); $perCreadoPor=$datosUsrJoomla->name; } ?>
                            <?php $perAgtV=""; if($elemDupl->agtVentasId!=""){ $datosUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($elemDupl->agtVentasId); $perAgtV=$datosUsrJoomla->name; } ?>
                            <?php $perGteV=""; if($elemDupl->gteVentasId!=""){ $datosUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($elemDupl->gteVentasId); $perGteV=$datosUsrJoomla->name; } ?>    
                                
                            <?php 
                                //Para obtener el estatus 
                                $estatusTexto = "";
                                if($elemDupl->departamentoId!="" && $elemDupl->fechaDptoAsignado==""){
                                    $estatusTexto = "En Proceso";
                                }
                                elseif($elemDupl->departamentoId!="" && $elemDupl->fechaDptoAsignado!=""){
                                    $estatusTexto = "Concretado";
                                }
                                elseif($elemDupl->idNoProcesados!="" && $elemDupl->idNoProcesados==1){
                                    $estatusTexto = "No Procede";
                                }
                                else{
                                    $estatusTexto = ($elemDupl->agtVentasId!="") ?"En Proceso" :"No asignado";
                                }
                            ?>
                        <tr>
                            <td><?php echo $elemDupl->nombre." ".$elemDupl->aPaterno." ".$elemDupl->aManterno; ?></td>
                            <td><?php echo $elemDupl->NSS; ?></td>
                            <td><?php echo ($elemDupl->fechaNac!="") ?SasfehpHelper::conversionFechaF2($elemDupl->fechaNac):""; ?></td>
                            <th>
                            <a href="#" data-toggle="modal" data-target="#popup_resumenprospecto" class="idSelResumen" idPros="<?php echo $elemDupl->idDatoProspecto;?>">Ver</a>
                            <div style="display:none;" id="idResumen_<?php echo $elemDupl->idDatoProspecto;?>">
                                <div class="resumen_prospecto"><span>ID:</span>                  <span><?php echo $elemDupl->idDatoProspecto; ?></span></div>
                                <div class="resumen_prospecto"><span>Fecha Alta:</span>          <span><?php echo SasfehpHelper::conversionFechaF2($elemDupl->fechaAlta); ?></span></div>
                                <div class="resumen_prospecto"><span>Nombre:</span>              <span><?php echo $elemDupl->nombre." ".$elemDupl->aPaterno." ".$elemDupl->aManterno; ?></span></div>
                                <div class="resumen_prospecto"><span>Fecha Nacimiento:</span>    <span><?php echo ($elemDupl->fechaNac!="") ?SasfehpHelper::conversionFechaF2($elemDupl->fechaNac):""; ?></span></div>
                                <div class="resumen_prospecto"><span>RFC:</span>                 <span><?php echo $elemDupl->RFC; ?></span></div>
                                <div class="resumen_prospecto"><span>Edad:</span>                <span><?php echo $elemDupl->edad; ?></span></div>
                                <div class="resumen_prospecto"><span>Tel&eacute;fono:</span>     <span><?php echo $elemDupl->telefono; ?></span></div>
                                <div class="resumen_prospecto"><span>Celular:</span>             <span><?php echo $elemDupl->celular; ?></span></div>
                                <div class="resumen_prospecto"><span>Sexo:</span>                <span><?php echo ($elemDupl->genero==1) ?"Masculino" :"Femenino"; ?></span></div>
                                <div class="resumen_prospecto"><span>NSS:</span>                 <span><?php echo $elemDupl->NSS; ?></span></div>
                                <div class="resumen_prospecto"><span>Monto Crédito:</span>       <span><?php echo "$ ".number_format($elemDupl->montoCredito,2); ?></span></div>
                                <div class="resumen_prospecto"><span>Tipo Crédito:</span>       <span><?php echo $elemDupl->tipoCredito; ?></span></div>
                                <!-- <div class="resumen_prospecto"><span>Creado Por:</span>          <span><?php echo $perCreadoPor; ?></span></div> -->
                                <div class="resumen_prospecto"><span>Agt. Ventas:</span>         <span><?php echo $perAgtV; ?></span></div>
                                <div class="resumen_prospecto"><span>Gte. Ventas:</span>         <span><?php echo $perGteV; ?></span></div>
                                <div class="resumen_prospecto"><span>Estatus:</span>         <span><?php echo $estatusTexto; ?></span></div>
                            </div>
                            </th>
                        </tr>                    
                        <?php } ?>
                        </tbody>
                    </table>                
                </div>
                <?php } ?>
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
                    <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" class="" style="width:180px;"/>                        
                </div>
            </div>                                    
            <div class="control-group">
                <div class="control-label">
                    <label for="aPaterno">Apellido Paterno:</label>
                </div>
                <div class="controls">
                    <input type="text" name="aPaterno" id="aPaterno" value="<?php echo $aPaterno; ?>" class="" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="aManterno">Apellido Materno:</label>
                </div>
                <div class="controls">
                    <input type="text" name="aManterno" id="aManterno" value="<?php echo $aManterno; ?>" class="" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="fechaNac">Fecha Nacimiento:</label>
                </div>
                <div class="controls">
                    <!-- <div id="contFechaNac"></div> -->
                    <!-- <input type="text" name="fechaNac" id="fechaNac" value="<?php echo $fechaNac; ?>" class="required" style="width:100px;" readonly/> -->                    
                    <!-- <input type="hidden" name="fechaNac" id="fechaNac" value="<?php echo $fechaNac; ?>" /> -->
                    <input type="date" name="fechaNac" id="fechaNac" data-date-format="dd/mm/yyyy" value="<?php echo $fechaNac; ?>">
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="RFC">RFC:</label>
                </div>
                <div class="controls">
                    <input type="text" name="RFC" id="RFC" value="<?php echo $RFC; ?>" class="" style="width:150px;"/>
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
                    <input type="text" name="telefono" id="telefono" value="<?php echo $telefono; ?>" class="" style="width:180px;"/>
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
                    <label for="genero">Sexo:</label>
                </div>
                <div class="controls">
                    <div style="clear:both;">                             
                        <div><label for="genero1" style="display:inline-block;min-width:10px !important;width:10px;">M</label> <input type="radio" name="genero" id="genero1" value="1" <?php echo ($genero==1) ? 'checked': '' ?> class="required" required></div>
                        <div><label for="genero2" style="display:inline-block;min-width:10px !important;width:10px;">F</label> <input type="radio" name="genero" id="genero2" value="2" <?php echo ($genero==2) ? 'checked': '' ?> class="required" required></div>
                        <!-- <label for="genero" class="error">Please select your gender</label><br> -->
                    </div>
                </div> 
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="NSS">NSS:</label>
                </div>
                <div class="controls">
                    <input type="text" name="NSS" id="NSS" value="<?php echo $NSS; ?>" class="" style="width:150px;" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="montoCredito">Monto Cr&eacute;dito:</label>
                </div>
                <div class="controls">
                    <input type="text" name="montoCredito" id="montoCredito" value="<?php echo $montoCredito; ?>" class="number" style="width:180px;" />
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
                    <input type="text" name="subsidio" id="subsidio" value="<?php echo $subsidio; ?>" class="number" style="width:180px;" />
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
                    <textarea name="comentario" id="comentario" rows="5" class="" style="width:350px;"><?php echo $comentario; ?></textarea>                    
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="empresa">Empresa donde labora:</label>
                </div>
                <div class="controls">
                    <input type="text" name="empresa" id="empresa" value="<?php echo $empresa; ?>" class="" style="width:180px;" />
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
                        foreach ($this->arrTiposCaptados as $item) {
                            if ($idTipoCaptado == $item->idTipoCaptado)
                                $sel = 'selected';
                            else
                                $sel = '';
                            echo '<option ' . $sel . ' value="' . $item->idTipoCaptado . '">' . $item->tipoCaptado . '</option>';                        
                        }
                        ?>
                    </select>
                </div>
                <!--
                <div class="controls">
                    <input type="text" name="captadoen" id="captadoen" value="<?php echo $idTipoCaptado; ?>" class="required" style="width:180px;" />
                </div>
                -->
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
    <input type="hidden" name="vistaCtr" value="2" />
    <input type="hidden" name="opcDatosProsp" value="<?php echo $this->opc;?>" />
    
    <?php echo JHtml::_('form.token'); ?>
</form>


<!-- Modal popup para mostrar el resumen -->
<div class="modal fade" id="popup_resumenprospecto" role="dialog" style="width:500px;position:relative !important;display:none;">
    <div class="modal-dialog">          
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Resumen prospecto</h4>
          </div>                          
          <div class="modal-body cont_form_popup">            
                <div class="control-group ctrgr_popup" id="contResumenProspecto">hola</div>
          </div>              
      </div>
    </div>
</div>

<!-- Modal para validar un prospectador repetido desde un gerente de prospeccion-->
<?php 
if(in_array("11", $this->groupsgte) || in_array("19", $this->groupsgte)){ 
    //igualar el arreglo de grupos
    $this->groups = $this->groupsgte;
?>
<div class="modal fade" id="popup_validar_prospecto_repetido" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">          
      <div class="modal-content">
          <?php //Para gerentes de prospeccion ?>
          <?php if(in_array("19", $this->groups)){ ?>
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Asignar gerente(s) de venta(s) a prospecto(s)</h4>
          </div>          
          <div class="modal-body cont_form_popup">            
            <form id="form_rep_prospecto_repetido" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.validarprospectorepetido'); ?>" method="post">                
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="rep_usuario">Gerente Venta:</label>
                    </div>
                    <div class="controls">
                        <select name="rep_usuario" id="rep_usuario" class="required">
                            <option value="">--Seleccionar--</option>                            
                            <?php
                            foreach ($this->ColGteVentas as $itemRep) {                                    
                                echo '<option value="' . $itemRep->idDato . '">' . $itemRep->nombre . '</option>';                        
                            }
                            ?>
                        </select>                            
                    </div>                    
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="rep_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="rep_comentario" id="rep_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>
                
                <div>    
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_validar_prospecto_repetido">Aceptar</button>
                </div>
                <input type="hidden" name="arrRepetidoId" id="arrRepetidoId" value="" />
                <input type="hidden" name="idGteSel" id="idGteSel" value="1" />
            </form>
          </div> 
          <?php } ?>

          <?php //Para gerentes de ventas ?>
          <?php if(in_array("11", $this->groups)){ ?>
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Asignar agente(s) de venta(s) a prospecto(s)</h4>
          </div>          
          <div class="modal-body cont_form_popup">            
            <form id="form_rep_prospecto_repetido" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.validarprospectorepetido'); ?>" method="post">                
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="rep_usuario">Agente Venta:</label>
                    </div>
                    <div class="controls">
                        <select name="rep_usuario" id="rep_usuario" class="required">
                            <option value="">--Seleccionar--</option>                            
                            <?php
                            foreach ($this->ColAsesores as $itemRep) {                                    
                                echo '<option value="' . $itemRep->idDato . '">' . $itemRep->nombre . '</option>';                        
                            }
                            ?>
                        </select>                            
                    </div>
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="rep_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="rep_comentario" id="rep_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_validar_prospecto_repetido">Aceptar</button>
                </div>
                <input type="hidden" name="arrRepetidoId" id="arrRepetidoId" value="" />
                <input type="hidden" name="idGteSel" id="idGteSel" value="0" />
                <div id="addHiddenUser"></div>
            </form>
          </div> 
          <?php } ?>
      </div>
    </div>
</div>
<?php } ?>
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