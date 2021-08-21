<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');


function ponerImgCatalogo($idCat, $nombre, $groups){      
    
  if($idCat!=8 && $idCat!=9 && $idCat!=10 && $idCat!=11){            
        
    switch ($idCat) {
        case 1: $img = 'gerentes_venta.png'; 
            if(in_array("10", $groups) || in_array("8", $groups)){
        ?>        
        <div class="comwrapper icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=catgenericos&id_cat=<?php echo $idCat; ?>" style="text-decoration:none;" title="Cat&aacute;logo <?php echo $nombre;?> ">
                    <img src="../media/com_sasfe/images/<?php echo $img; ?>" align="middle" border="0">
                    <span><?php echo $nombre; ?></span>
                </a>
            </div>        
        </div>       
        <?php 
            }
        break;            
        case 2: $img = 'titulacion.png'; 
            if(in_array("10", $groups) || in_array("8", $groups)){
        ?>
        <div class="icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=catgenericos&id_cat=<?php echo $idCat; ?>" style="text-decoration:none;" title="Cat&aacute;logo <?php echo $nombre;?> ">
                    <img src="../media/com_sasfe/images/<?php echo $img; ?>" align="middle" border="0">
                    <!-- <span><?php echo $nombre; ?></span> -->
                    <span><?php echo $nombre; ?></span>
                </a>
            </div>        
        </div>       
        <?php
            }
        break;            
        case 3: $img = 'asesores.png';
            if(in_array("10", $groups) || in_array("8", $groups) || in_array("14", $groups)){            
        ?>
        <div class="icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=catgenericos&id_cat=<?php echo $idCat; ?>" style="text-decoration:none;" title="Cat&aacute;logo <?php echo $nombre;?> ">
                    <img src="../media/com_sasfe/images/<?php echo $img; ?>" align="middle" border="0">
                    <span><?php echo $nombre .' (Agentes de venta)'; ?></span>
                </a>
            </div>        
        </div>       
        <?php
            }
        break;            
        case 4: $img = 'prospectadores.png'; 
            if(in_array("10", $groups) || in_array("8", $groups) || in_array("14", $groups)){ 
        ?>
        <div class="icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=catgenericos&id_cat=<?php echo $idCat; ?>" style="text-decoration:none;" title="Cat&aacute;logo <?php echo $nombre;?> ">
                    <img src="../media/com_sasfe/images/<?php echo $img; ?>" align="middle" border="0">
                    <span><?php echo $nombre; ?></span>
                </a>
            </div>        
        </div>       
        <?php   
            }
        break;            
        case 5: $img = 'estatus_vivienda.png'; 
            if(in_array("10", $groups) || in_array("8", $groups)){
        ?>
        <div class="icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=catgenericos&id_cat=<?php echo $idCat; ?>" style="text-decoration:none;" title="Cat&aacute;logo <?php echo $nombre;?> ">
                    <img src="../media/com_sasfe/images/<?php echo $img; ?>" align="middle" border="0">
                    <span><?php echo $nombre; ?></span>
                </a>
            </div>        
        </div>       
        <?php 
            }
        break;        
        case 6: $img = 'motivos_cancelacion.png'; 
            if(in_array("10", $groups) || in_array("8", $groups)){
        ?>
        <div class="icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=catgenericos&id_cat=<?php echo $idCat; ?>" style="text-decoration:none;" title="Cat&aacute;logo <?php echo $nombre;?> ">
                    <img src="../media/com_sasfe/images/<?php echo $img; ?>" align="middle" border="0">
                    <span><?php echo $nombre; ?></span>
                </a>
            </div>        
        </div>       
        <?php 
            }
        break;            
        case 7: $img = 'tipos_credito.png'; 
            if(in_array("10", $groups) || in_array("8", $groups)){
        ?>
        <div class="icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=catgenericos&id_cat=<?php echo $idCat; ?>" style="text-decoration:none;" title="Cat&aacute;logo <?php echo $nombre;?> ">
                    <img src="../media/com_sasfe/images/<?php echo $img; ?>" align="middle" border="0">
                    <span><?php echo $nombre; ?></span>
                </a>
            </div>        
        </div>       
        <?php 
            }
        break;                                    
    }        
    ?>        

    <?php 
    }else{ 
        switch ($idCat) {            
            case 8: $img = 'acabados.png'; 
                if(in_array("10", $groups) || in_array("8", $groups)){
            ?>
            <div class="icon-wrapper">
                <div class="icon"> 
                    <a href="index.php?option=com_sasfe&amp;view=catcostoextras&id_cat=<?php echo $idCat; ?>" style="text-decoration:none;" title="Cat&aacute;logo <?php echo $nombre;?> ">
                        <img src="../media/com_sasfe/images/<?php echo $img; ?>" align="middle" border="0">
                        <span><?php echo $nombre; ?></span>
                    </a>
                </div>        
            </div>         
            <?php 
                }
            break;
            case 9: $img = 'servicios.png'; 
                if(in_array("10", $groups) || in_array("8", $groups)){
            ?>
            <div class="icon-wrapper">
                <div class="icon"> 
                    <a href="index.php?option=com_sasfe&amp;view=catcostoextras&id_cat=<?php echo $idCat; ?>" style="text-decoration:none;" title="Cat&aacute;logo <?php echo $nombre;?> ">
                        <img src="../media/com_sasfe/images/<?php echo $img; ?>" align="middle" border="0">
                        <span><?php echo $nombre; ?></span>
                    </a>
                </div>        
            </div>         
            <?php 
                }
            break;
        }
    ?>            
          
   <?php    
      }
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_sasfe&task=catalogos');?>" method="post" id="adminForm" name="adminForm" >
                            
 <div class="adminform comwrapper">    
    <div class="cpanel">
        <?php if(in_array("10", $this->groups) || in_array("8", $this->groups)){ ?>
        <!-- <div class="cpanel-left">             -->
            <div class="icon-wrapper">
                <div class="icon"> 
                    <a href="index.php?option=com_sasfe&amp;view=catfraccionamientos" style="text-decoration:none;" title="Lista de fraccionamientos">
                        <img src="../media/com_sasfe/images/fraccionamientos.png" align="middle" border="0">
                        <span>Fraccionamientos</span>
                    </a>
                </div>        
            </div>
            <div class="icon-wrapper">
                <div class="icon"> 
                    <a href="index.php?option=com_sasfe&amp;view=catdepartamentos" style="text-decoration:none;" title="Lista de los departamentos">
                        <img src="../media/com_sasfe/images/departamentos.png" align="middle" border="0">
                        <span>Departamentos</span>
                    </a>
                </div>        
            </div>                        
        <!-- </div>                  -->
        <?php } ?>
        <br/><br/><br/>
         <!-- <div class="cpanel-right">              -->
             <?php 
             $img = '';
             if(count($this->CatalogosGen)>0){
                foreach($this->CatalogosGen as $itemCat){ 
                    switch ($itemCat->idCatalogo) {
                       case 1:                            
                       case 2:                            
                       case 3:                           
                       case 4:                           
                       case 5:                           
                       case 6:
                       case 7:                                                           
                       case 8: 
                       case 9: 
                           ponerImgCatalogo($itemCat->idCatalogo, $itemCat->nombre, $this->groups);                                               
                            break;
                    }
                }                    
            } 
            ?>
            
            <?php if(in_array("10", $this->groups) || in_array("8", $this->groups)){ ?> 
            <div class="icon-wrapper">
                <div class="icon"> 
                    <a href="index.php?option=com_sasfe&amp;view=catporcentajes" style="text-decoration:none;" title="Configuraci&oacute;n de porcentajes">
                        <img src="../media/com_sasfe/images/porcentajes.png" align="middle" border="0">
                        <span>Porcentajes</span>
                    </a>
                </div>        
            </div>
            <?php } ?> 
        <!-- </div>    -->
            <?php if(in_array("10", $this->groups) || in_array("8", $this->groups)){ ?> 
            <div class="icon-wrapper">
                <div class="icon"> 
                    <a href="index.php?option=com_sasfe&amp;view=cattipoeventos" style="text-decoration:none;" title="Cat&aacute;logo Tipo de Eventos">
                        <img src="../media/com_sasfe/images/tipo-eventos.png" align="middle" border="0">
                        <span>Tipo de Eventos</span>
                    </a>
        </div>   
            </div>
            <?php } ?>
            <?php if(in_array("10", $this->groups) || in_array("8", $this->groups)){ ?> 
            <div class="icon-wrapper">
                <div class="icon"> 
                    <a href="index.php?option=com_sasfe&amp;view=cattipocaptados" style="text-decoration:none;" title="Cat&aacute;logo Tipo Captados">
                        <img src="../media/com_sasfe/images/tipo-captados.png" align="middle" border="0">
                        <span>Tipo Captados</span>
                    </a>
                </div>        
            </div>
            <?php } ?>
            <?php if(in_array("10", $this->groups) || in_array("8", $this->groups)){ ?> 
            <div class="icon-wrapper">
                <div class="icon"> 
                    <a href="index.php?option=com_sasfe&amp;view=catgtedeptos" style="text-decoration:none;" title="Cat&aacute;logo Gte. Ventas - Departamento">
                        <img src="../media/com_sasfe/images/catalogo-gte-ventas.png" align="middle" border="0">
                        <span>Cat&aacute;logo Gte. Ventas - Departamento</span>
                    </a>
                </div>        
            </div>
            <?php } ?>            
    </div>
    <br><br>
    <div class="cpanel">
        <?php if(in_array("10", $this->groups) || in_array("8", $this->groups)){ ?> 
        <div class="icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=smsmensajes" style="text-decoration:none;" title="Mensajes">
                    <img src="../media/com_sasfe/images/mensajes.png" align="middle" border="0">
                    <span>Mensajes</span>
                </a>
            </div>
        </div>
        <?php } ?>
        <?php if(in_array("10", $this->groups) || in_array("8", $this->groups)){ ?> 
        <div class="icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=smspromociones" style="text-decoration:none;" title="Promociones">
                    <img src="../media/com_sasfe/images/promociones.png" align="middle" border="0">
                    <span>Promociones</span>
                </a>
            </div>
        </div>
        <?php } ?>
        <?php if(in_array("10", $this->groups) || in_array("8", $this->groups)){ ?> 
        <div class="icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=motivos" style="text-decoration:none;" title="Motivos Rechazo">
                    <img src="../media/com_sasfe/images/motivos-rechazo.png" align="middle" border="0">
                    <span>Motivos Rechazo</span>
                </a>
            </div>
        </div>
        <?php } ?>
        <?php if(in_array("10", $this->groups) || in_array("8", $this->groups)){ ?> 
        <div class="icon-wrapper">
            <div class="icon"> 
                <a href="index.php?option=com_sasfe&amp;view=catconfiguraciones" style="text-decoration:none;" title="Configuraciones">
                    <img src="../media/com_sasfe/images/configuracion.png" align="middle" border="0">
                    <span>Configuraciones</span>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
 </div>          
     <input type="hidden" name="task" value="catalogs" />
</form>  