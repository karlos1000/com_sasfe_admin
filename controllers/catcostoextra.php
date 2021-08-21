<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerCatcostoextra extends JControllerForm {
                     
    function cancel()
    {                 
        $id_cat = JRequest::getVar('dato_cat_catId');                                 
        $this->setRedirect( 'index.php?option=com_sasfe&view=catcostoextras&id_cat='.$id_cat);        
    }
    
    public function add(){           
       $varid = JRequest::getVar('id');
       $cat_id = JRequest::getVar('id_cat');
       $id = ($varid!='') ? $varid : 0;        
       
       $this->setRedirect( 'index.php?option=com_sasfe&view=catcostoextra&layout=edit&id='.$id.'&id_cat='.$cat_id );                     
    }
    
    public function edit(){               
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];        
        $cat_id = JRequest::getVar('id_cat');
        
        $this->setRedirect( 'index.php?option=com_sasfe&view=catcostoextra&layout=edit&id='.$id.'&id_cat='.$cat_id );                     
    }        
    
    function apply(){
      $this->procesarDatosCatCE();         
    }
    function save()
    {  
     $this->procesarDatosCatCE();                
    }
    
    function saveandnew(){        
        $this->procesarDatosCatCE();       
    }
    
    public function procesarDatosCatCE(){
        jimport('joomla.filesystem.file');
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));        
        $model = JModelLegacy::getInstance('Catcostoextra', 'SasfeModel');  //leer el modelo correspondiente                                 
                
        //obtener valores del formulario       
        $catalogoId = JRequest::getVar('dato_cat_catId');        
        $idFracc = JRequest::getVar('dato_cat_fracc');        
        $nombre = JRequest::getVar('dato_cat_nombre');                
        $costo = JRequest::getVar('dato_cat_costo');                
        $activo = (JRequest::getVar('dato_cat_activo')!='') ? JRequest::getVar('dato_cat_activo') : '0';                   
        $idUrl = JRequest::getVar('check_un');         
                              
        echo '$catalogoId: ' .$catalogoId. '<br/>';        
        echo '$idFracc: ' .$idFracc. '<br/>';
        echo '$nombre: ' .$nombre. '<br/>';        
        echo '$costo: ' .$costo . '<br/>';        
        echo '$activo: ' .$activo . '<br/>';        
        echo '$idUrl: ' .$idUrl;
                  
        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){                                              
             $id = $model->insertarDatosCatalogosCE($catalogoId, $idFracc, $nombre, $costo, $activo);
             echo 'El id creado es: ' .$id;           
        }else{            
            $model->actualizarDatosCatalogosCE($catalogoId, $idFracc, $nombre, $costo, $activo, $idUrl);
        }        
              
        $msg = JText::sprintf('Registro salvado correctamente.');
        $idoption = ($idUrl==0) ? $id: $idUrl;        
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');

        switch ($task) {
            case "apply":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catcostoextra&layout=edit&id='.$idoption.'&id_cat='.$catalogoId.'  ', $msg); 
                break;
            case "save":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catcostoextras&id_cat='.$catalogoId,$msg);                          
                break;
            case "saveandnew":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catcostoextra&layout=edit&id=0&id_cat='.$catalogoId ,$msg);           
                break;                        
        }                              
      
    }
                
}

?>
