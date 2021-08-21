<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerCatfraccionamiento extends JControllerForm {
                     
    function cancel()
    {         
        $this->setRedirect( 'index.php?option=com_sasfe&view=catfraccionamientos');        
    }
    
    public function add(){           
       $varid = JRequest::getVar('id');
       $id = ($varid!='') ? $varid : 0; 
        
       $this->setRedirect( 'index.php?option=com_sasfe&view=catfraccionamiento&layout=edit&id='.$id.' ');                     
    }
    
    public function edit(){               
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];        
        
        $this->setRedirect( 'index.php?option=com_sasfe&view=catfraccionamiento&layout=edit&id='.$id.' ');               
    }        
    
    function apply(){
      $this->procesarFracc();         
    }
    function save()
    {  
     $this->procesarFracc();                
    }
    
    function saveandnew(){        
        $this->procesarFracc();       
    }
    
    public function procesarFracc(){
        jimport('joomla.filesystem.file');
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Catfraccionamiento', 'SasfeModel');                  
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');   
        // Initialise variables.
        $user = JFactory::getUser();
        
        $arrForm = JRequest::get( 'post' ); //lee todas las variables por post
        $file = JRequest::getVar('fracc_imagen', null, 'files', 'array'); 
        $save_folder = JPATH_SITE.'/media/com_sasfe/upload_files/';                                        
        
        //obtener valores del formulario       
        $nombre = JRequest::getVar('fracc_nombre');        
        $activo = (JRequest::getVar('fracc_activo')!='') ? JRequest::getVar('fracc_activo') : '0';           
        
        $idUrl = JRequest::getVar('check_un'); 
        $check = false;                       
        
        if($file['name']!=''){
            $filename = JFile::makeSafe($file['name']);
            $src = $file['tmp_name'];
            $type = strtolower(substr(strrchr($filename, '.'), 1));
            $newImg = md5(rand() * time()) . ".$type";        
            $dest = $save_folder.$newImg;                

            if(strtolower(JFile::getExt($filename) )=='jpg' || strtolower(JFile::getExt($filename) )=='png' || strtolower(JFile::getExt($filename) )=='gif') {
                if(JFile::upload($src, $dest)){                   
                   $nameMainImg = $newImg;
                   $msgImg="";
                }else{                   
                   $nameMainImg = '';
                   $msgImg="No fue posible subir la imagen intente nuevamente";
                }
            }else{            
                $nameMainImg = '';            
                $msgImg="La extensi&oacute;n del archivo no es correcta";
            }
        }else{
            //como no existe imagen entonces tomar el mismo nombre de la db si es que existe 
            $nameMainImg = $model->ObtNombreImgFracc($idUrl); 
            $check = true; 
        } 
        
        $imagen = $nameMainImg;           
        
//        echo '$fraccionamiento: ' .$nombre. '<br/>';
//        echo '$activo: ' .$activo. '<br/>';        
//        echo '$imagen: ' .$imagen;
               
        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){                                              
             $id = $model->insertarFracc($nombre, $imagen, $activo);
             echo 'El id creado es: ' .$id;           
        }else{
            //obtener el nombre de la imagen desde db para poderla borrar           
            if($imagen!=''){
                if($check!=true){
                    $nameImg = $model->ObtNombreImgFracc($idUrl);                  
                    $pathFolder = $save_folder.$nameImg;

                    if(file_exists($pathFolder)){
                         unlink ($pathFolder);                
                    }
                }
            }
                        
            $model->actualizarFracc($nombre, $imagen, $activo, $idUrl);
        }        
                
        $msg = JText::sprintf('Registro salvado correctamente.');                        
        $idoption = ($idUrl==0) ? $id: $idUrl;        
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');
                
        switch ($task) {
            case "apply":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catfraccionamiento&layout=edit&id='.$idoption.' ', $msg.$msgImg); 
                break;
            case "save":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catfraccionamientos',$msg.$msgImg);                          
                break;
            case "saveandnew":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=catfraccionamiento&layout=edit&id=0',$msg.$msgImg);           
                break;                        
        }                      
         
        
    }
                
}

?>
