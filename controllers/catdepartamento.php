<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerCatdepartamento extends JControllerForm {
                     
    function cancel()
    {         
        $this->setRedirect( 'index.php?option=com_sasfe&view=catdepartamentos');        
    }
    
    public function add(){           
       $varid = JRequest::getVar('id');
       $id = ($varid!='') ? $varid : 0; 
        
       $this->setRedirect( 'index.php?option=com_sasfe&view=catdepartamento&layout=edit&id='.$id.' ');                     
    }
    
    public function edit(){               
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];        
        
        $this->setRedirect( 'index.php?option=com_sasfe&view=catdepartamento&layout=edit&id='.$id.' ');               
    }        
    
    function apply(){
      $this->procesarDepto();         
    }
    function save()
    {  
     $this->procesarDepto();                
    }
    
    function saveandnew(){        
        $this->procesarDepto();       
    }
    
    public function procesarDepto(){
        jimport('joomla.filesystem.file');
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Catdepartamento', 'SasfeModel');                  
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');   
                
        //obtener valores del formulario       
        $precio = JRequest::getVar('depto_precio'); 
        $numero = JRequest::getVar('depto_numero');        
        $idFracc = JRequest::getVar('idFracc');                        
        $idUrl = JRequest::getVar('check_un');         
        
        echo '$numero: ' .$numero. '<br/>';
        echo '$idFracc: ' .$idFracc. '<br/>';        
        echo '$idUrl: ' .$idUrl;
        echo '$precio: ' .$precio;
        
        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){                                              
             $id = $model->insertarDepto($idFracc, $numero, $precio);
             echo 'El id creado es: ' .$id;           
        }else{                        
            $model->actualizarDepto($idFracc, $numero, $precio, $idUrl);
        }        
                
        $msg = JText::sprintf('Registro salvado correctamente.');                        
        $idoption = ($idUrl==0) ? $id: $idUrl;        
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');
                
        switch ($task) {
            case "apply":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catdepartamento&layout=edit&id='.$idoption.' ', $msg); 
                break;
            case "save":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catdepartamentos',$msg);                          
                break;
            case "saveandnew":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catdepartamento&layout=edit&id=0',$msg);           
                break;                        
        }                      
         
        
    }
                
}

?>
