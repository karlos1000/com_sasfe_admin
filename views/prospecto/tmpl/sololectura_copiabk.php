<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');

$idDatoProspecto = (isset($this->data[0]->idDatoProspecto))?$this->data[0]->idDatoProspecto:'';
$fechaAlta = (isset($this->data[0]->fechaAlta))? SasfehpHelper::conversionFechaF2($this->data[0]->fechaAlta) :$this->arrDateTime->fechaF2;
$nombre = (isset($this->data[0]->nombre))?$this->data[0]->nombre:'';
$aPaterno = (isset($this->data[0]->aPaterno))?$this->data[0]->aPaterno:'';
$aManterno = (isset($this->data[0]->aManterno))?$this->data[0]->aManterno:'';
$RFC = (isset($this->data[0]->RFC))?$this->data[0]->RFC:'';
$fechaNac = (isset($this->data[0]->fechaNac))? SasfehpHelper::conversionFechaF2($this->data[0]->fechaNac) : $this->arrDateTime->fechaF2;
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
$captadoen = (isset($this->data[0]->captadoen))?$this->data[0]->captadoen:'';
$email = (isset($this->data[0]->email))?$this->data[0]->email:'';
$fraccionamientoId = (isset($this->data[0]->fraccionamientoId))?$this->data[0]->fraccionamientoId:'';
$usuarioId = (isset($this->data[0]->usuarioId))?$this->data[0]->usuarioId:'';
$gteProspeccionId = (isset($this->data[0]->gteProspeccionId))?$this->data[0]->gteProspeccionId:'';
$gteVentasId = (isset($this->data[0]->gteVentasId))?$this->data[0]->gteVentasId:'';
$prospectadorId = (isset($this->data[0]->prospectadorId))?$this->data[0]->prospectadorId:'';
$agtVentasId = (isset($this->data[0]->agtVentasId))?$this->data[0]->agtVentasId:'';
$estatusId = (isset($this->data[0]->estatusId))?$this->data[0]->estatusId:'';
	
$gteVentasId = "Luis Sandoval";
$prospectadorId = "Raul Gonzales";
$agtVentasId = "Mario Lopéz";
//echo '<pre>';
//print_r($this->data);
//echo '</pre>';

?>
<style>
.defaultKCD{ position: absolute;margin: 0px;padding: 0px;} label{ width: 170px; } 
.adminform label{ min-width: 170px; padding: 0 5px 0 0; }
</style>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=prospecto'); ?>" method="post" name="adminForm" id="adminForm">    
    <div class="notesUlEsphabit">        
    </div>        
    
    <div style="">
        <fieldset class="adminform">        
            <!-- <div class="control-group">
                <div class="control-label">
                    <label for="idFracc">Fraccionamiento: <span class="star">&nbsp;*</span></label>                
                </div>
                <div class="controls">
                    <select id="idFracc" name="idFracc" class="required">                  
                        <option value="">--Seleccione--</option>
                        <?php
                        foreach ($this->Fracc as $item) {
                            if ($idFracc == $item->idFraccionamiento)
                                $sel = 'selected';
                            else
                                $sel = '';
                            echo '<option ' . $sel . ' value="' . $item->idFraccionamiento . '">' . $item->nombre . '</option>';                        
                        }
                        ?>                    
                    </select>               
                </div>
            </div> -->
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
                    <label for="nombre">Nombre:</label>
                </div>
                <div class="controls">
                    <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" class="required" style="width:180px;"/>                        
                </div>
            </div>                                    
            <div class="control-group">
                <div class="control-label">
                    <label for="aPaterno">Apellido Paterno:</label>
                </div>
                <div class="controls">
                    <input type="text" name="aPaterno" id="aPaterno" value="<?php echo $aPaterno; ?>" class="required" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="aManterno">Apellido Materno:</label>
                </div>
                <div class="controls">
                    <input type="text" name="aManterno" id="aManterno" value="<?php echo $aManterno; ?>" class="required" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="fechaNac">Fecha Nacimiento:</label>
                </div>
                <div class="controls">
                    <div id="contFechaNac"></div>
                    <!-- <input type="text" name="fechaNac" id="fechaNac" value="<?php echo $fechaNac; ?>" class="required" style="width:100px;" readonly/> -->                    
                    <input type="hidden" name="fechaNac" id="fechaNac" value="<?php echo $fechaNac; ?>" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="RFC">RFC:</label>
                </div>
                <div class="controls">
                    <input type="text" name="RFC" id="RFC" value="<?php echo $RFC; ?>" class="required" style="width:150px;"/>
                    <div class="addInfo" style="display:inline-block;"></div>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="edad">Edad:</label>
                </div>
                <div class="controls">
                    <input type="text" name="edad" id="edad" value="<?php echo $edad; ?>" class="required digits" style="width:50px;"/>
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
                    <input type="text" name="celular" id="celular" value="<?php echo $celular; ?>" class="required" style="width:180px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="gteVentasId"><span class="star">&nbsp;*</span> Gerente Ventas:</label>
                </div>
                <div class="controls">
                    <input type="text" name="gteVentasId" id="gteVentasId" value="<?php echo $gteVentasId; ?>" class="required" style="width:180px;" readonly/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="prospectadorId"><span class="star">&nbsp;*</span> Prospectador:</label>
                </div>
                <div class="controls">
                    <input type="text" name="prospectadorId" id="prospectadorId" value="<?php echo $prospectadorId; ?>" class="required" style="width:180px;" readonly/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="agtVentasId"><span class="star">&nbsp;*</span> Asesor:</label>
                </div>
                <div class="controls">
                    <input type="text" name="agtVentasId" id="agtVentasId" value="<?php echo $agtVentasId; ?>" class="required" style="width:180px;" readonly/>
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
                    <input type="text" name="NSS" id="NSS" value="<?php echo $NSS; ?>" class="required" style="width:150px;" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="montoCredito">Monto Cr&eacute;dito:</label>
                </div>
                <div class="controls">
                    <input type="text" name="montoCredito" id="montoCredito" value="<?php echo $montoCredito; ?>" class="required number" style="width:180px;" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="tipoCreditoId">Tipo Cr&eacute;dito:</label>                
                </div>
                <div class="controls">
                    <select id="tipoCreditoId" name="tipoCreditoId" class="required">                  
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
                    <input type="text" name="subsidio" id="subsidio" value="<?php echo $subsidio; ?>" class="required number" style="width:180px;" />
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
                    <textarea name="comentario" id="comentario" rows="5" class="required" style="width:350px;"><?php echo $comentario; ?></textarea>                    
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="empresa">Empresa donde labora:</label>
                </div>
                <div class="controls">
                    <input type="text" name="empresa" id="empresa" value="<?php echo $empresa; ?>" class="required" style="width:180px;" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="captadoen">Captado en:</label>
                </div>
                <div class="controls">
                    <input type="text" name="captadoen" id="captadoen" value="<?php echo $captadoen; ?>" class="required" style="width:180px;" />
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

        </fieldset>
    </div>
        
    <input type="hidden" name="task" value="prospecto.edit" />
    <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />
    <input type="hidden" id="rutaCalendario" value="<?php echo JURI::root().'media/com_sasfe/images/calendar.gif'; ?>" />
    <input type="hidden" id="path" value="<?php echo $this->pathUrl;?>" />
    <input type="hidden" id="loading_img" value="<?php echo $this->imgLoading; ?>" /> 
    
    <?php echo JHtml::_('form.token'); ?>
</form>