<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerListareventos extends JControllerForm {
      
    function cancel($key=NULL)
    {         
        $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos');
    }

    function cancelSololectura()
    {         
        $this->setRedirect( 'index.php?option=com_sasfe&view=listareventos');
    }

    public function atenderevento(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Listareventos', 'SasfeModel');
        
        $ate_fechareg = JRequest::getVar('ate_fechareg');
        $fechaAtendido = SasfehpHelper::conversionFechaF4($ate_fechareg);
        $ate_comentario = JRequest::getVar('ate_comentario');   
        $idMovPros = JRequest::getVar('idMovPros');
        $opc = JRequest::getVar('hiddopc');
        $resp = $model->marcarAtendido($fechaAtendido, $ate_comentario, $idMovPros);        
        $msg = JText::sprintf('Registro salvado correctamente.');
        $this->setRedirect('index.php?option=com_sasfe&view=listareventos&opc='.$opc, $msg);
    }
    
    //Descargar el evento para outlook    
    public function descargarEventoOutlook(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $arrDateTime = SasfehpHelper::obtDateTimeZone();        
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN')); // Check for request forgeries
        $model = JModelLegacy::getInstance('Listareventos', 'SasfeModel');

        //Obtener datos por el id 
        $idMovPros = JRequest::getVar('idHidEvento');
        $datosEvento = $model->obtEventoPorId($idMovPros);
        $tiempoRestar = $datosEvento->tiempo;

        // echo "<pre>";
        // print_r($datosEvento);
        // echo "</pre>";    

        // exit();    
        $nuevafecha = strtotime ( '-'.$tiempoRestar.' minute' , strtotime ( $datosEvento->fechaHora ) ) ;
        $nuevafecha = date ( 'Y-m-d H:i' , $nuevafecha );
        
        $dtstart = $nuevafecha;
        $dtend = $datosEvento->fechaHora;
        $description = $datosEvento->comentario;
        $location = "";
        $titulo_invite = "";        
        $file_name = "Evento_".$arrDateTime->fecha."_".$arrDateTime->hora."_".$idMovPros;

        // echo $dtstart.'<br/>';
        // echo $dtend.'<br/>';
        // echo $description.'<br/>';
        // echo $location.'<br/>';
        // echo $titulo_invite.'<br/>';        
        // echo $file_name.'<br/>';
            
        SasfehpHelper::generarICS($dtstart, $dtend, $description, $location, $titulo_invite, $file_name);
    }

    //Descargar el evento para navegador
    public function descargarEventonavegador(){

    }


    // function delete(){
    //      // Check for request forgeries
    //     JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));                
    //     $idsProspectos = JRequest::getVar('cid', array(), '', 'array');                
    //     // Sanitize the input
    //     // JArrayHelper::toInteger($idsProspectos);        
    //     //print_r($idsProspectos);
        
    //     $model = JModelLegacy::getInstance('listareventos', 'SasfeModel'); 
    //     $result = $model->removerProspectoPorId($idsProspectos);
             
    //     // echo '<pre>'; print_r($result); echo '</pre>';
                                
    //    if(count($result['resultDel'])>0){                      
    //         $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';            
    //         $text = JText::sprintf($msn);                
    //         $this->setRedirect('index.php?option=com_sasfe&view=listareventos', $text);                        
    //     }
    //     else{            
    //         $text = JText::sprintf('Registro no eliminado');                
    //         $this->setRedirect('index.php?option=com_sasfe&view=listareventos', $text);                        
    //     }
    // }
    function Export(){
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');        
        include_once JPATH_ADMINISTRATOR.'/components/com_sasfe/common/Classes/PHPExcel.php';
        $model = JModelLegacy::getInstance('Listareventos', 'SasfeModel');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        // Se crea el objeto PHPExcel
        $objPHPExcel = new PHPExcel();
        $fechadesde = JRequest::getVar('filter_fechaDel');
        $fechahasta = JRequest::getVar('filter_fechaAl');
        $fechaDel = explode('/', $fechadesde);
        $fechaFDel = $fechaDel[2] . "-" . $fechaDel[1] . "-" . $fechaDel[0];        
        $fechaAl = explode('/', $fechahasta);
        $fechaFAl = $fechaAl[2] . "-" . $fechaAl[1] . "-" . $fechaAl[0];
        $resp = $model->listaEventosFilter($fechaFDel, $fechaFAl);
        /*$count = count($resp);
       // echo $resp[0]->comentario;
        $con = 0;
        while($con < $count ){
            echo $resp[$con]->comentario;
            echo $con . '<br>';
            $con++;
        }*/
       //if(count($resp) > 0 ){
            date_default_timezone_set('America/Mexico_City');
            //header('Content-Encoding: UTF-8');
            if (PHP_SAPI == 'cli')
                die('Este archivo solo se puede ver desde un navegador web');
            // Se asignan las propiedades del libro
            $objPHPExcel->getProperties()->setCreator("Framelova") //Autor
                                        ->setLastModifiedBy("Framelova") //Ultimo usuario que lo modificó
                                        ->setTitle("Lista de Eventos")
                                        ->setSubject("Lista de Eventos")
                                        ->setDescription("Lista de Eventos")
                                        ->setKeywords("lista eventos")
                                        ->setCategory("eventos excel");
            $tituloReporte = "Lista de Eventos";
            $titulosColumnas = array('Fecha', 'Prospecto', 'Tipo', 'Comentario', 'Atendido');
            $objPHPExcel->setActiveSheetIndex(0)
                        ->mergeCells('A1:E1');
            // Se agregan los titulos del reporte
            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A1',$tituloReporte)
                                ->setCellValue('A2',  $titulosColumnas[0])
                                ->setCellValue('B2',  $titulosColumnas[1]) 
                                ->setCellValue('C2',  $titulosColumnas[2]) 
                                ->setCellValue('D2',  $titulosColumnas[3]) 
                                ->setCellValue('E2',  $titulosColumnas[4])
                                ;
            //Setea información de los eventos
            //$objPHPExcel->setActiveSheetIndex(0)
                            //->setCellValue('A3',  "Hola") 
                            //->setCellValue('B3',  $resultDataByCondoIdU['location'])                            
                            //->setCellValue('C3',  $resultDataByCondoIdU['currentBalance'])
                            //->setCellValue('D3',  $resultDataByCondoIdU['residualBalance2'])
                            //->setCellValue('E3',  $resp['comentario'])
              //              ;
                $i = 3;
               if(count($resp) > 0 ){ 
                foreach($resp as $item):
                   $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  SasfehpHelper::conversionFechaF3($item->fechaHora))
                    ->setCellValue('B'.$i,  $item->nombre ." ". $item->aPaterno ." ". $item->aManterno)
                    ->setCellValue('C'.$i,  $item->tipoEvento)                 
                    ->setCellValue('D'.$i,  html_entity_decode($item->comentarioevcom))                 
                    ->setCellValue('E'.$i,  ($item->atendido == 1) ? "Si" : "No")                 
                          ;
                   $i++;
                endforeach;
                }
            $estiloTituloPagos = array(
                                'font' => array(
                                'name'=>'Arial',
                                'bold'=>false,
                                'italic'=>false,
                                'strike'=> false,
                                'size'=>12,
                                'color'=> array(
                                    'rgb' => '000000'
                                     )
                                ),                
                                'alignment' =>  array(
                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                    'rotation'   => 0,
                                    'wrap'          => FALSE
                                )
                    );
            $estiloTituloReporte = array(
                                'font' => array(
                                'name'=>'Arial',
                                'bold'=>false,
                                'italic'=>false,
                                'strike'=> false,
                                'size'=>12,
                                'color'=> array(
                                    'rgb' => '000000'
                                     )
                                ),                
                                'alignment' =>  array(
                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                    'rotation'   => 0,
                                    'wrap'          => FALSE
                                )
                    );
            $estiloTituloColumnas = array(
                                'font' => array(
                                'name'=> 'Arial',
                                'bold'=> false,                          
                                'color'=> array(
                                    'rgb' => '000000'
                                )
                            ),                                
                            'alignment'  =>  array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            'wrap'       => FALSE
                    ));
            $estiloInformacion = new PHPExcel_Style();
            $estiloInformacion->applyFromArray(
                    array(
                    'font' => array(
                                'name'=>'Arial',               
                                'color'=> array(
                                    'rgb' => '000000'
                            )
                        )
                    ));
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($estiloTituloReporte);
           // $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($estiloTituloColumnas);                                       
                    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A2:F2");
                    //$objPHPExcel->getActiveSheet()->getStyle('A7:F7')->applyFromArray($estiloTituloPagos);                      
            for($i = 'A'; $i <= 'E'; $i++){
                $objPHPExcel->setActiveSheetIndex(0)            
                    ->getColumnDimension($i)->setAutoSize(TRUE);
            }
            // Se asigna el nombre a la hoja
            $objPHPExcel->getActiveSheet()->setTitle('Lista de Eventos');
            // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
            $objPHPExcel->setActiveSheetIndex(0);
            // Inmovilizar paneles 
            //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
            $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,2);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="Lista_Eventos'.'.xls"');
                    header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                    $objWriter->save('php://output');
                    exit;    
       //}
    }
    
}

?>
