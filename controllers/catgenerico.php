<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerCatgenerico extends JControllerForm {
                     
    function cancel()
    {                 
        $id_cat = JRequest::getVar('dato_cat_catId');                                 
        $this->setRedirect( 'index.php?option=com_sasfe&view=catgenericos&id_cat='.$id_cat);        
    }
    
    public function add(){           
       $varid = JRequest::getVar('id');
       $cat_id = JRequest::getVar('id_cat');
       $id = ($varid!='') ? $varid : 0;        
       //index.php?option=com_sasfe&view=catgenerico&layout=edit&id=1&id_cat=1 
       $this->setRedirect( 'index.php?option=com_sasfe&view=catgenerico&layout=edit&id='.$id.'&id_cat='.$cat_id );                     
    }
    
    public function edit(){               
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];        
        $cat_id = JRequest::getVar('id_cat');
        
        $this->setRedirect( 'index.php?option=com_sasfe&view=catgenerico&layout=edit&id='.$id.'&id_cat='.$cat_id );                     
    }        
    
    function apply(){
      $this->procesarDatosCat();         
    }
    function save()
    {  
     $this->procesarDatosCat();                
    }
    
    function saveandnew(){        
        $this->procesarDatosCat();       
    }
    
    public function procesarDatosCat(){
        jimport('joomla.filesystem.file');
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));        
        $model = JModelLegacy::getInstance('Catgenerico', 'SasfeModel');  //leer el modelo correspondiente                         
        //$arrForm = JRequest::get( 'post' ); //lee todas las variables por post
                
        //obtener valores del formulario       
        $catalogoId = JRequest::getVar('dato_cat_catId');        
        $nombre = JRequest::getVar('dato_cat_nombre');        
        $default = (JRequest::getVar('dato_cat_default')!='') ? JRequest::getVar('dato_cat_default') : '0';                   
        $activo = (JRequest::getVar('dato_cat_activo')!='') ? JRequest::getVar('dato_cat_activo') : '0';                   
        $idUrl = JRequest::getVar('check_un');         
                              
        $idCat = JRequest::getVar('id_cat');
        $usuarioIdJoomla = (JRequest::getVar('usuarioIdJoomla')!='') ? JRequest::getVar('usuarioIdJoomla') : 'NULL';
        $usuarioIdGteJoomla = (JRequest::getVar('usuario_gte_id')!='') ? JRequest::getVar('usuario_gte_id') : 'NULL';
        // echo '$catalogoId: ' .$catalogoId. '<br/>';
        // echo '$nombre: ' .$nombre. '<br/>';        
        // echo '$default: ' .$default . '<br/>';        
        // echo '$activo: ' .$activo . '<br/>';        
        // echo '$idUrl: ' .$idUrl;
        // exit();
               
        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){                                              
             $id = $model->insertarDatosCatalogos($catalogoId, $nombre, $default, $activo, $usuarioIdJoomla, $usuarioIdGteJoomla);
             echo 'El id creado es: ' .$id;           
        }else{            
            $model->actualizarDatosCatalogos($catalogoId, $nombre, $default, $activo, $usuarioIdJoomla, $usuarioIdGteJoomla, $idUrl);
        }      
        
        //Guardar o actualizar fraccionamientos especialista de gerentes 
        if($catalogoId == 1){
            $arrFraccIds = JRequest::getVar('fraccEspecialitas');
            $fraccIds = implode(",", $arrFraccIds);
            // echo "<pre>";print_r($fraccIds);echo "</pre>";
            // exit();
            $modelFracc = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
            $gerenteFracc = $modelFracc->obtFraccionamientosGerenteDB($usuarioIdJoomla);

            if($gerenteFracc->idGteFracc == 0){
                $idGteFracc = $modelFracc->guardarFraccionamientosGerenteDB($usuarioIdJoomla, ",".$fraccIds);
            }else{
                $resFracc = $modelFracc->actualizaFraccionamientosGerenteDB($usuarioIdJoomla, ",".$fraccIds);
            }

        }
                
        $msg = JText::sprintf('Registro salvado correctamente.');                        
        $idoption = ($idUrl==0) ? $id: $idUrl;        
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');

        switch ($task) {
            case "apply":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catgenerico&layout=edit&id='.$idoption.'&id_cat='.$catalogoId.'  ', $msg); 
                break;
            case "save":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catgenericos&id_cat='.$catalogoId,$msg);                          
                break;
            case "saveandnew":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catgenerico&layout=edit&id=0&id_cat='.$catalogoId ,$msg);           
                break;                        
        }                      
         
        
    }
                
}

?>
