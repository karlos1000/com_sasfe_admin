<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
//echo '<pre>';
//    print_r($this->Fracc);
//echo '</pre>';
// echo "<pre>"; print_r($this->groups); echo "</pre>";
?>

<div class="adminform comwrapper">
    <div class="cpanel">
        <?php if(!in_array("17", $this->groups)){ ?>
        <div class="cpanel-left img_large">
            <h2>Fraccionamientos</h2>
            <?php if(count($this->Fracc)>0){
                    foreach ($this->Fracc as $fracc){ ?>
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_sasfe&amp;view=listadodeptos&idFracc=<?php echo $fracc->idFraccionamiento; ?>" style="text-decoration:none;" title="<?php echo $fracc->nombre;?>">
                                    <img src="../media/com_sasfe/upload_files/<?php echo $fracc->imagen;?>" align="middle" border="0" width="48">
                                    <span><?php echo $fracc->nombre; ?></span>
                                </a>
                            </div>
                        </div>
            <?php   }
                }
            ?>
        </div>
        <?php } ?>

        <!--?php if($this->permiso==true){ ?>-->
        <div class="cpanel-right">
           <!--<?php if(in_array("10", $this->groups) || in_array("8", $this->groups) || in_array("12", $this->groups) || in_array("13", $this->groups) || in_array("14", $this->groups) || in_array("15", $this->groups)){ ?>
           <h2>Cat&aacute;logos / Reportes / Log de accesos</h2>
           <?php } ?>-->

           <?php if( in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("20", $this->groups) ){ ?>
           <h2>Cat&aacute;logos / Reportes / Log de accesos</h2>
           <?php }else{ ?>
                <?php if( in_array("11", $this->groups) || in_array("12", $this->groups) || in_array("13", $this->groups) || in_array("14", $this->groups) || in_array("15", $this->groups) ){ ?>
                <h2>Cat&aacute;logos / Log de accesos</h2>
                <?php } ?>
           <?php } ?>


           <!--?php if(in_array("10", $this->groups) || in_array("8", $this->groups) || in_array("12", $this->groups) || in_array("13", $this->groups) || in_array("14", $this->groups) || in_array("15", $this->groups) || in_array("11", $this->groups) || in_array("20", $this->groups)){ ?>-->
           <?php if( in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("20", $this->groups) ){ ?>
           <!--<h2>Cat&aacute;logos y Reporte</h2>-->
           <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=reportes" style="text-decoration:none;" title="Reportes">
                        <img src="../media/com_sasfe/images/reportes.png" align="middle" border="0">
                        <span>Reportes</span>
                    </a>
                </div>
            </div>
           <?php } ?>

           <?php if(in_array("10", $this->groups) || in_array("8", $this->groups) || in_array("11", $this->groups) || in_array("19", $this->groups)){ ?>
           <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=logaccesos" style="text-decoration:none;" title="Accesos de usuarios">
                        <img src="../media/com_sasfe/images/log_accesos.png" align="middle" border="0">
                        <span>Log de accesos</span>
                    </a>
                </div>
            </div>
            <?php } ?>

            <?php if(in_array("10", $this->groups) || in_array("8", $this->groups) || in_array("14", $this->groups)){ ?>
            <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=catalogos" style="text-decoration:none;" title="Catálogos">
                        <img src="../media/com_sasfe/images/catalogos.png" align="middle" border="0">
                        <span>Catálogos</span>
                    </a>
                </div>
            </div>
           <?php } ?>
            <?php if(in_array("17", $this->groups) || in_array("18", $this->groups)){ ?>
            <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=prospecto&amp;layout=edit&amp;id=0&amp;prosp=1" style="text-decoration:none;" title="Alta Prospectos">
                        <img src="../media/com_sasfe/images/alta-prospectos.png" align="middle" border="0">
                        <span>Alta Prospectos</span>
                    </a>
                </div>
        </div>
            <?php } ?>
            <?php if(in_array("8", $this->groups) || in_array("11", $this->groups) || in_array("10", $this->groups)){?>
            <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=prospecto&amp;layout=editsu&amp;id=0" style="text-decoration:none;" title="Alta Prospectos">
                        <img src="../media/com_sasfe/images/alta-prospectos.png" align="middle" border="0">
                        <span>Alta Prospectos</span>
                    </a>
                </div>
            </div>
            <?php } ?>
            <!-- ?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("19", $this->groups)){ ?> -->
            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("19", $this->groups) || in_array("20", $this->groups)){ ?>
            <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=prospectos" style="text-decoration:none;" title="Prospectos">
                        <img src="../media/com_sasfe/images/prospectos.png" align="middle" border="0">
                        <span>Prospectos</span>
                    </a>
                </div>
            </div>
            <?php } ?>
            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups)){ ?>
            <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=smsconfigcreditos" style="text-decoration:none;" title="Configuraci&oacute;n Cr&eacute;ditos">
                        <img src="../media/com_sasfe/images/envios-promo.png" align="middle" border="0">
                        <span>Configuraci&oacute;n Cr&eacute;ditos</span>
                    </a>
                </div>
        </div>
            <?php } ?>
            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups)){ ?>
            <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=smsenviosreferidos" style="text-decoration:none;" title="Envios SMS Referidos">
                        <img src="../media/com_sasfe/images/envios-referidos.png" align="middle" border="0">
                        <span>Envios SMS Referidos</span>
                    </a>
                </div>
            </div>
            <?php } ?>
            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("18", $this->groups)){ ?>
            <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=smsenviospromociones" style="text-decoration:none;" title="Envios SMS Promociones">
                        <img src="../media/com_sasfe/images/promociones.png" align="middle" border="0">
                        <span>Envios SMS Promociones</span>
                    </a>
                </div>
            </div>
            <?php } ?>
            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups)){ ?>
            <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=historialsms" style="text-decoration:none;" title="Historial SMS">
                        <img src="../media/com_sasfe/images/hist-mensajes.png" align="middle" border="0">
                        <span>Historial SMS</span>
                    </a>
                </div>
        </div>
            <?php } ?>
        </div>
         <!--?php } ?>-->

        <!-- Modulo de Acceso contactos -->
        <div class="cpanel-right">
            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("19", $this->groups) || in_array("20", $this->groups)){ ?>
            <h2>Acceso contactos</h2>
            <?php } ?>

            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("20", $this->groups)){ ?>
            <!-- ?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("19", $this->groups)){ ?> -->
            <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=sincontactos" style="text-decoration:none;" title="Sincronizar contactos">
                        <img src="../media/com_sasfe/images/sincronizar-cont.png" align="middle" border="0">
                        <span>Sincronizar contactos</span>
                    </a>
                </div>
            </div>
            <?php } ?>

            <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("19", $this->groups) || in_array("20", $this->groups)){ ?>
            <!-- ?php if(in_array("8", $this->groups)){ ?> -->
            <div class="icon-wrapper">
                <div class="icon">
                    <a href="index.php?option=com_sasfe&amp;view=contactos" style="text-decoration:none;" title="Contactos">
                        <img src="../media/com_sasfe/images/contactos.png" align="middle" border="0">
                        <span>Contactos</span>
                    </a>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php if(count($this->colEventosHoy)>0){ ?>
<style type="text/css">
.containerTable{width: 84% !important;}
</style>
<div class="modal fade" id="popup_agenda" role="dialog" style="width:600px;position:relative !important;display:none">
    <div class="modal-dialog">
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Eventos por atender</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agenda" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=listareventos'); ?>" method="post">
                <div class="containerTable">
                    <div class="rowDivHeader">
                        <div class="cellDivHeader">Prospecto</div>
                        <div class="cellDivHeader">Tipo</div>
                        <div class="cellDivHeader" style="width:25%;">Comentario</div>
                    </div>
                        <?php foreach ($this->colEventosHoy as $elemEvento){ ?>
                        <div class="rowDiv">
                                <div class="cellDiv"><?php echo $elemEvento->nombre ." ". $elemEvento->aPaterno ." ". $elemEvento->aManterno;?></div>
                                <div class="cellDiv text_left"><?php echo $elemEvento->tipoEvento;?></div>
                                <div class="cellDiv text_left"><?php echo $elemEvento->comentario;?></div>
                        </div>
                        <?php } ?>
                </div>
                <br/>
                <button type="submit" class="btn btn-small button-new btn-success">Ir a mis eventos</button>
                <input type="hidden" id="verEventos">
            </form>
          </div>
      </div>
    </div>
</div>
<?php
jimport('joomla.environment.uri');
$document = JFactory::getDocument();
$document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');
$document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');
$document->addScript(JURI::root().'media/com_sasfe/js/function.js');
?>
<script type="text/javascript">
JQ(document).ready(function(){
    JQ('#popup_agenda').css('position','fixed');
    jQuery("#popup_agenda").modal().show();
});
</script>
<?php } ?>