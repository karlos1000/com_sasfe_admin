<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerSincontactos extends JControllerForm {

    function cancel($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe');
    }

    // Obtener desarrollo (LIKE)
    private function obtenerDesarrolloId($nombreDesarrollo){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        // $desarrollos = SasfehpHelper::obtTodosFraccionamientos(); //Obtener los fraccionamientos
        // $desarrolloId = 0;
        // foreach ($desarrollos as $desarrollo) {
        //     if($desarrollo->nombre == $nombreDesarrollo){
        //         $desarrolloId = $desarrollo->idFraccionamiento;
        //     }
        // }

        $desarrollo = SasfehpHelper::obtFraccionamientoByDesarrollo($nombreDesarrollo);
        $desarrolloId = "";
        if(isset($desarrollo->idFraccionamiento)){
            $desarrolloId = $desarrollo->idFraccionamiento;
        }
        return $desarrolloId;
    }

    // Obtener el ultimo gerente
    private function obtenerUltimoGerenteId(){
        $ultimoGerenteId = SasfehpHelper::obtUltimoGerenteSinc();
        return $ultimoGerenteId;
    }

    // Guardar id ultimo gerente
    private function guardarUltimoGerenteId($gerenteId){
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora

        $resp = SasfehpHelper::salvarIdGerenteSinc($gerenteId, $fechaHora);
        // if($resp){
        //     echo "El gerente se salvo correctamente";
        // }
    }

    // Obtener id ultimo agente
    private function obtenerUltimoAgenteId($gerenteId){
        $ultimoAgenteId = SasfehpHelper::obtUltimoAsesorSinc($gerenteId);
        return $ultimoAgenteId;
    }

    // Obtener id ultimo agente
    private function guardarUltimoAgenteId($gerenteId, $agenteId){
        // $_SESSION["ultimoAgenteId".$gerenteId] = $agenteId;
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora

        $resp = SasfehpHelper::salvarIdAsesorSinc($gerenteId, $agenteId, $fechaHora);
        // if($resp){
        //     echo "El asesor se salvo correctamente";
        // }
    }

    private function obtenerGerenteEspecialistaId($idsGerencias, $desarrolloId){
        $gerenteId = 0;
        $modelFracc = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $gerentesFracc = $modelFracc->obtFraccionamientosGerentePorDesarrolloDB($desarrolloId);
        $arrIdsGerenciasInclude = explode(",", $idsGerencias);

        foreach($gerentesFracc as $itemGerFracc){
            //Revisar si el gerente especialista se encuentra en los gerentes seleccionados
            if( in_array($itemGerFracc->gteVentasId, $arrIdsGerenciasInclude) ){
                $gerenteId = $itemGerFracc->gteVentasId;
            }
        }

        return $gerenteId;

    }

    //obtener gerente
    private function obtenerGerenteId($idsGerencias){
        $gerenteId = 0;
        $gerentes = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11); //Obtener los gerentes de ventas
        $asignar = false;
        // echo "<pre>";print_r($gerentes);echo "</pre>";

        $arrIdsGerenciasInclude = explode(",", $idsGerencias);
        if(count($arrIdsGerenciasInclude) > 1){
            while($gerenteId == 0){
                foreach($gerentes as $gerente){
                    $continuar = true;
                    $ultimoGerenteId = $this->obtenerUltimoGerenteId(); // obtener el ultimo id gerente
                    $agentes = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($gerente->id);
                    // foreach

                    if($gerente->id == $ultimoGerenteId || $ultimoGerenteId == 0){
                        $asignar = true;
                    }

                    //Verificar que el id del gerente este en los gerentes incluidos
                    //Verificar que el gerente tenga agentes
                    if(!in_array($gerente->id, $arrIdsGerenciasInclude) || count($agentes) == 0 || strpos($gerente->name, "demo") || strpos($gerente->name, "inactivo")){
                        $continuar = false;
                    }

                    if($gerente->id != $ultimoGerenteId && $asignar && $continuar){
                        $gerenteId = $gerente->id;
                        $asignar = false;
                        $this->guardarUltimoGerenteId($gerenteId);
                    }
                }
            }
        }else{
            foreach($arrIdsGerenciasInclude as $gerenciaId){
                $gerenteId = $gerenciaId;
            }
        }

        // die();
        return $gerenteId;
    }

    private function obtenerAgenteIdOpcAgentes($idsAsesores, $ultimoAgenteOpcAsesorId){
        $agenteId = 0;
        $gerenteId = 0;
        if($idsAsesores == ''){
            $agentes = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($gerenteId);
        }else{
            $agentes = SasfehpHelper::obtColAsesoresAgtVentaXIds($idsAsesores);
        }
        // echo "<pre>";print_r($agentes);echo "</pre>";
        $asignar = false;

        $arrAgentesIds = array();
        foreach($agentes as $agente){
            $arrAgentesIds[] = $agente->usuarioIdJoomla;
        }
        if(count($agentes) > 1){
            while($agenteId == 0){
                foreach($agentes as $agente){
                    if($agente->usuarioIdJoomla == $ultimoAgenteOpcAsesorId || $ultimoAgenteOpcAsesorId == 0){
                        $asignar = true;
                    }
                    if($agente->usuarioIdJoomla != $ultimoAgenteOpcAsesorId && $asignar){
                        $agenteId = $agente->usuarioIdJoomla;
                        $asignar = false;
                        $this->guardarUltimoAgenteId($gerenteId, $agenteId);
                        if($idsAsesores != ''){
                            $gerenteId = $agente->usuarioIdGteJoomla;
                        }
                    }
                }
            }
        }else{//Si solo hay un agente solo ese asigna
            foreach($agentes as $agente){
                $agenteId = $agente->usuarioIdJoomla;
                $asignar = false;
                $this->guardarUltimoAgenteId($gerenteId, $agenteId);
                if($idsAsesores != ''){
                    $gerenteId = $agente->usuarioIdGteJoomla;
                }
            }
        }

        if($idsAsesores == ''){
            return $agenteId;
        }else{
            return array("agenteId"=>$agenteId, "gerenteId"=>$gerenteId, "ultimoAgenteOpcAsesorId"=>$agenteId);
        }
    }

    // Obtener asesor
    private function obtenerAgenteId($gerenteId, $idsAsesores = ''){
        $agenteId = 0;
        // $gerenteId = 0;

        $agentes = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($gerenteId);

        $asignar = false;

        $arrAgentesIds = array();
        foreach($agentes as $agente){
            $arrAgentesIds[] = $agente->usuarioIdJoomla;
        }
        if(count($arrAgentesIds) > 1){
            $ultimoAgenteId = $this->obtenerUltimoAgenteId($gerenteId); // obtener el ultimo id agente

            $indexUltimoAgente = array_search($ultimoAgenteId, $arrAgentesIds);//Index del ultimo agente

            $lenghtArray = count($arrAgentesIds);//Contador de agentes
            $ultimoIndex = $lenghtArray-1;//Index ultimo del array

            $indexAsignar = ($ultimoIndex == $indexUltimoAgente)?0:$indexUltimoAgente+1;

            $agenteId = $arrAgentesIds[$indexAsignar];

            $this->guardarUltimoAgenteId($gerenteId, $agenteId);


            // while($agenteId == 0){
                // foreach($agentes as $agente){
                //     $ultimoAgenteId = $this->obtenerUltimoAgenteId($gerenteId); // obtener el ultimo id agente

                //     // $ultimoAgenteId = (in_array($ultimoAgenteId, $arrAgentesIds))?$ultimoAgenteId:0;
                //     $ultimoAgenteId = (in_array($ultimoAgenteId, $arrAgentesIds))?$ultimoAgenteId:0;

                //     if($agente->usuarioIdJoomla == $ultimoAgenteId || $ultimoAgenteId == 0){
                //         $asignar = true;
                //     }


                //     if($agente->usuarioIdJoomla != $ultimoAgenteId && $asignar){
                //         $agenteId = $agente->usuarioIdJoomla;
                //         $asignar = false;
                //         $this->guardarUltimoAgenteId($gerenteId, $agenteId);
                //     }
                // }
            // }
        }else{
            foreach($agentes as $agente){
                $agenteId = $agente->usuarioIdJoomla;
                $this->guardarUltimoAgenteId($gerenteId, $agenteId);
            }
        }

        return $agenteId;

    }

    private function obtenerContactosDistribucion($contactosGoogleSheet, $idsGerencias, $idsAsesores, $opcGteAsesor){
        $contactosDistribucion = array();

        //Dividir contactos entre gerencias seleccionadas y obtener el sobrante
        $arrIdsGerenciasInclude = explode(",", $idsGerencias);
        $sobrante = fmod(count($contactosGoogleSheet), count($arrIdsGerenciasInclude));
        $banderaEspecialista=($sobrante > 0)?true:false;
        $inicioSobrantes = count($contactosGoogleSheet)-$sobrante;

        //Contador contactos para saber en que momento iniciar la busqueda por especialista si se require
        $contContactos = 0;
        $arrGerentesEspecialistas = array();
        $contIntentoGerEsp = 0;
        $contIntentoGerEspANormal = 0;
        $gerentesFracc = array();
        $arrIdsGerenciasInclude = explode(",", $idsGerencias);
        $contCicloGerEsp = 0;
        $contIf = 0;

        //Jair 13/10/2020 opcion 0 gerentes
        if($opcGteAsesor == 0){
            foreach ($contactosGoogleSheet as $contactoSheet) {
                $desarrolloId = $this->obtenerDesarrolloId($contactoSheet["desarrollo"]);

                //obtener gerente normal u obtener especialista
                // if($banderaEspecialista && $inicioSobrantes==$contContactos){
                //     $contIntentoGerEsp++;
                //     $gerenteId = $this->obtenerGerenteEspecialistaId($idsGerencias, $desarrolloId);
                //     $modelFracc = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
                //     $gerentesFracc = $modelFracc->obtFraccionamientosGerentePorDesarrolloDB($desarrolloId);

                //     foreach($gerentesFracc as $itemGerFracc){
                //         $contCicloGerEsp++;
                //         //Revisar si el gerente especialista se encuentra en los gerentes seleccionados
                //         if( in_array($itemGerFracc->gteVentasId, $arrIdsGerenciasInclude) ){
                //             $contIf++;
                //             $gerenteId = $itemGerFracc->gteVentasId;
                //         }
                //     }
                //     //Si no se obtuvo un especialista obtenerlo de manera normal
                //     if($gerenteId == 0 ){
                //         $contIntentoGerEspANormal++;
                //         $gerenteId = $this->obtenerGerenteId($idsGerencias);
                //     }else{
                //         $arrGerentesEspecialistas[] = $gerenteId;
                //     }
                // }else{
                    $gerenteId = $this->obtenerGerenteId($idsGerencias);
                // }


                $agenteId = $this->obtenerAgenteId($gerenteId);

                $ultimoAgenteId = $this->obtenerUltimoAgenteId($gerenteId);
                $agentes = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($gerenteId);
                $arrAgentesIds = array();
                foreach($agentes as $agente){
                    $arrAgentesIds[] = $agente->usuarioIdJoomla;
                }

                $extras = array("desarrolloId"=>$desarrolloId, "gerenteId"=>$gerenteId, "agenteId"=>$agenteId, "agentes"=>$agentes, "ultimoAgenteId"=>$ultimoAgenteId, "arrGerentesEspecialistas"=>$arrGerentesEspecialistas, "sobrante"=>$sobrante,
                "banderaEspecialista"=>$banderaEspecialista,
                "inicioSobrantes"=>$inicioSobrantes,"contIntentoGerEsp"=>$contIntentoGerEsp,
                "contIntentoGerEspANormal"=>$contIntentoGerEspANormal,"contContactos"=>$contContactos,"gerentesFracc"=>$gerentesFracc, "arrIdsGerenciasInclude"=>$arrIdsGerenciasInclude, "contCicloGerEsp"=>$contCicloGerEsp,
                "contIf"=>$contIf);

                $contactoDist = array_merge($contactoSheet, $extras);

                $contactosDistribucion[] = (object)$contactoDist;
                $contContactos++;
            }
        }else{
            //jair 13/10/2020 opc 1 agentes
            $ultimoAgenteOpcAsesorId = 0;
            foreach ($contactosGoogleSheet as $contactoSheet) {
                $desarrolloId = $this->obtenerDesarrolloId($contactoSheet["desarrollo"]);

                $arrIds = $this->obtenerAgenteIdOpcAgentes($idsAsesores, $ultimoAgenteOpcAsesorId);
                // $arrIds = explode("|", $ids);
                $agenteId = $arrIds["agenteId"];
                $gerenteId = $arrIds["gerenteId"];
                $ultimoAgenteOpcAsesorId = $arrIds["ultimoAgenteOpcAsesorId"];

                $ultimoAgenteId = $this->obtenerUltimoAgenteId($gerenteId);
                $agentes = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($gerenteId);
                $arrAgentesIds = array();
                foreach($agentes as $agente){
                    $arrAgentesIds[] = $agente->usuarioIdJoomla;
                }

                $extras = array("desarrolloId"=>$desarrolloId, "gerenteId"=>$gerenteId, "agenteId"=>$agenteId, "agentes"=>$agentes, "ultimoAgenteId"=>$ultimoAgenteId, "arrGerentesEspecialistas"=>$arrGerentesEspecialistas, "sobrante"=>$sobrante,
                "banderaEspecialista"=>$banderaEspecialista,
                "inicioSobrantes"=>$inicioSobrantes,"contIntentoGerEsp"=>$contIntentoGerEsp,
                "contIntentoGerEspANormal"=>$contIntentoGerEspANormal,"contContactos"=>$contContactos,"gerentesFracc"=>$gerentesFracc, "arrIdsGerenciasInclude"=>$arrIdsGerenciasInclude, "contCicloGerEsp"=>$contCicloGerEsp,
                "contIf"=>$contIf);

                $contactoDist = array_merge($contactoSheet, $extras);

                $contactosDistribucion[] = (object)$contactoDist;
                $contContactos++;
            }
        }


        return $contactosDistribucion;
    }

    // Proceso para sincronizar contatos
    public function sincronizarContactos(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora
        $result = array("success"=>false);
        $idsGerencias = (isset($_POST['idsGerencias']))?$_POST['idsGerencias']:"";
        //Imp.13/10/20
        $idsAsesores = (isset($_POST['idsAsesores']))?$_POST['idsAsesores']:"";
        $opcGteAsesor = (isset($_POST['opcGteAsesor']))?$_POST['opcGteAsesor']:0; //Opcion de gerente=0 o asesor=1


        //Imp. 06/10/20
        //Obtener identificador de google sheet
        $datosConfiguracion = SasfehpHelper::obtDatosConfiguracionPorId(1);
        if(isset($datosConfiguracion) && $datosConfiguracion->valor!=""){

            // Descargar informacion de google sheet
            // $idGoogleSheet = "1VOdFsfA6apHPfkQ2-56Xf9nwifUf68de7WItLhKAa14";
            $idGoogleSheet = $datosConfiguracion->valor;
            // $url = 'https://spreadsheets.google.com/feeds/list/'.$idGoogleSheet.'/od6/public/basic?prettyprint=true&alt=json';
            // $formatoArr = array('nombre', 'apaterno', 'amaterno', 'telefono', 'email', 'fuente', 'desarrollo', 'tipocredito', 'aux');
            // $rows = $this->getSpreadsheetData($url, $formatoArr);

            // Imp 20/08/21, Carlos, Debido a que la opcion de arriba ya no trabaja con google sheet
            $url = 'https://sheets.googleapis.com/v4/spreadsheets/'.$idGoogleSheet.'/values/Hoja%201?key=AIzaSyCeXr9ZfErpbjFwJRHNlptytH_3dU-RU0s';
            $json = json_decode(file_get_contents($url));
            $rows = $json->values;

            // Remueve la primera fila
            if(count($rows)>0){ unset($rows[0]); }
            // echo "<pre>"; print_r($rows); echo "</pre>";
            // exit();

            $sheetData = array();
            if(count($rows)>0){
                foreach($rows as $key => $elem) {
                    // Comprobar que el email sea valido en caso contrario se omitira el contacto
                    // if(SasfehpHelper::validarEmail(trim($elem["email"])) == 1){
                        $sheetData[$key]["nombre"] = SasfehpHelper::limpPuntoSinc($elem[1]); //SasfehpHelper::limpPuntoSinc($elem["nombre"]); //SasfehpHelper::limpPuntoSinc($elem["nombre"]);
                        $sheetData[$key]["apaterno"] = SasfehpHelper::limpPuntoSinc($elem[2]); //SasfehpHelper::limpPuntoSinc($elem["apaterno"]);
                        $sheetData[$key]["amaterno"] = SasfehpHelper::limpPuntoSinc($elem[3]); //SasfehpHelper::limpPuntoSinc($elem["amaterno"]);
                        $sheetData[$key]["telefono"] = SasfehpHelper::soloNumeros($elem[4]); //SasfehpHelper::soloNumeros($elem["telefono"]);
                        $sheetData[$key]["email"] = SasfehpHelper::limpPuntoSinc($elem[5]); //SasfehpHelper::limpPuntoSinc($elem["email"]);
                        // $sheetData[$key]["fuente"] = $elem["fuente"];
                        $sheetData[$key]["fuente"] = (is_numeric($elem[6]))?$elem[6]:""; //(is_numeric($elem["fuente"]))?$elem["fuente"]:""; //Imp. 23/10/20
                        $sheetData[$key]["desarrollo"] = SasfehpHelper::limpPuntoSinc($elem[7]); //SasfehpHelper::limpPuntoSinc($elem["desarrollo"]);
                        // $sheetData[$key]["credito"] = (trim($elem["credito"])!=".")?$elem["credito"]:""; //Imp. 16/10/20
                        $sheetData[$key]["tipocredito"] = (is_numeric($elem[8]))?$elem[8]:""; //(is_numeric($elem["tipocredito"]))?$elem["tipocredito"]:""; //Imp. 16/10/20
                        // $sheetData[$key]["aux"] = (is_numeric($elem[9]))?$elem[9]:""; // Imp. 20/08/21, Carlos

                        //Revisar duplicidad por telefono y email
                        $sheetData[$key]["duplicado"] = 0;

                        //Imp. 05/11/20
                        // $resDup = SasfehpHelper::checkDuplicidadSinc($elem["email"], SasfehpHelper::soloNumeros($elem["telefono"]));
                        $resDup = SasfehpHelper::checkDuplicidadSinc($sheetData[$key]["email"], SasfehpHelper::soloNumeros($sheetData[$key]["telefono"]));
                        // echo "<pre>"; print_r($resDup); echo "</pre>";

                        if(count($resDup) > 0){
                        // if(is_array($resDup) && isset($resDup->gteVentasId)){
                            $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
                            $modelAcc = JModelLegacy::getInstance('Contacto', 'SasfeModel');
                            $gerenteAnterior = $model->obtInfoUsuariosJoomlaDB($resDup->gteVentasId); //Obtener gerente de ventas anterior
                            $acciones = $modelAcc->consultaAccionesContacto($resDup->idDatoContacto, "5"); //
                            $asesorAnterior = $model->obtInfoUsuariosJoomlaDB($resDup->agtVentasId); //Obtener gerente de ventas anterior
                            $sheetData[$key]["duplicado"] = 1;
                            $sheetData[$key]["idDatoContacto"] = $resDup->idDatoContacto;
                            $sheetData[$key]["contactoDup"] = $resDup;
                            $sheetData[$key]["gerenteAnterior"] = $gerenteAnterior;
                            $sheetData[$key]["asesorAnterior"] = $asesorAnterior;
                            $sheetData[$key]["acciones"] = $acciones;
                            $estutus = "";
                            switch($resDup->estatusId){
                                case 1: $estutus = "Asignado"; break;
                                case 2: $estutus = "Seguimiento"; break;
                                case 3: $estutus = "Contacto"; break;
                                case 4: $estutus = "Descartado"; break;
                                case 5: $estutus = "Prospecto"; break;
                            }
                            $sheetData[$key]["estatusAnterior"] = $estutus;
                        }

                    // }
                }

                // Proceso para distribuir los contactos
                // $contactosDistribucion = $this->obtenerContactosDistribucion($sheetData);
                // echo "ids: ".$idsAsesores." opcion ".$opcGteAsesor."<br>";die();
                $sheetData = $this->obtenerContactosDistribucion($sheetData, $idsGerencias, $idsAsesores, $opcGteAsesor);

                // echo "<pre>";
                // // print_r($rows);
                // print_r($sheetData);
                // echo "</pre>";

                $result = array("success"=>true, "sheetData"=>$sheetData, "total"=>count($sheetData), "idsGerencias"=>$idsGerencias);
            }else{
                $result = array("success"=>false, "msg"=>"No se encontro resultados de google sheets");
            }
        }
        $this->retornaJson($result);
    }

    // Salvar asignaciones
    public function salvarAsignaciones(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $model = JModelLegacy::getInstance('Sincontactos', 'SasfeModel');
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $fechaAlta = $arrDateTime->fechaHora;
        $fechaActualizacion = $arrDateTime->fechaHora;
        $arrContactos = array();
        $arrIdsRegistrados = array();
        $idsCheck = $_POST['idsCheck'];
        $usuarioId = $_POST['idUserJoomla'];
        $usuarioIdActualizacion = $_POST['idUserJoomla'];
        //Imp.13/10/20
        $idsAsesores = (isset($_POST['idsAsesores']))?$_POST['idsAsesores']:"";

        // Revisa si hay seleccionados
        if($idsCheck!=""){
            $explIdsCheck = explode(",", $idsCheck);

            foreach ($explIdsCheck as $elem){
                // $nombre = (isset($_POST["nombre_".$elem]))?$_POST["nombre_".$elem]:"";
                $nombre = (isset($_POST["h_nombre_".$elem]))?trim($_POST["h_nombre_".$elem]):"";
                $apaterno = (isset($_POST["h_apaterno_".$elem]))?trim($_POST["h_apaterno_".$elem]):"";
                $amaterno = (isset($_POST["h_amaterno_".$elem]))?trim($_POST["h_amaterno_".$elem]):"";
                $fuente = (isset($_POST["h_fuente_".$elem]))?trim($_POST["h_fuente_".$elem]):"";

                $telefono = (isset($_POST["tel_".$elem]))?trim($_POST["tel_".$elem]):"";
                $email = (isset($_POST["email_".$elem]))?trim($_POST["email_".$elem]):"";
                $email = (trim($email)!=".")?$email:""; //Imp. 16/20/20
                $idFracc = (isset($_POST["idFracc_".$elem]))?$_POST["idFracc_".$elem]:0;
                $idFracc = ($idFracc!="")?$idFracc:0; //Imp. 09/11/20
                $idGteVentas = (isset($_POST["idGteVentas_".$elem]))?$_POST["idGteVentas_".$elem]:0;
                $idAsesorVentas = (isset($_POST["idAsesorVentas_".$elem]))?$_POST["idAsesorVentas_".$elem]:0;
                $credito = (isset($_POST["h_credito_".$elem]))?trim($_POST["h_credito_".$elem]):""; //Imp. 19/10/20

                //Si no tiene id de repetido se inserta // JAIR 24/9/2020
                if(!isset($_POST["h_contactoDup_".$elem])){

                    // $arrContactos[] = array("nombre"=>$nombre, "apaterno"=>$apaterno, "amaterno"=>$amaterno, "telefono"=>$telefono, "email"=>$email, "idFracc"=>$idFracc,
                    //                         "idGteVentas"=>$idGteVentas, "idAsesorVentas"=>$idAsesorVentas, "fuente"=>$fuente);

                    $estatusId = 1;
                    $activo = 1;
                    $id = $model->insertarContacto($idGteVentas, $idAsesorVentas, $nombre, $apaterno, $amaterno, $email, $telefono, $fuente,
                            $idFracc, $estatusId, $fechaAlta, $fechaActualizacion, $activo, $usuarioId, $usuarioIdActualizacion, $credito);
                    if($id>0){
                        $arrIdsRegistrados[] = $id;
                    }
                }else{//Si tiene id de repetido, se actualiza el id de repetido // JAIR 24/9/2020
                    $estatusId = 1;
                    $activo = 1;

                    $res = $model->actualizarContacto($idGteVentas, $idAsesorVentas, $nombre, $apaterno, $amaterno, $email, $telefono, $fuente,
                            $idFracc, $estatusId, $fechaAlta, $fechaActualizacion, $activo, $usuarioId, $usuarioIdActualizacion, $credito,
                            $_POST["h_contactoDup_".$elem]);

                    if($res > 0){
                        $arrIdsRegistrados[] = $res;
                        $idDatoContacto = $_POST["h_contactoDup_".$elem];
                        $agtVentasId = $idAsesorVentas;
                        $accionId = 6;
                        $comentario = 'Se actualizo por recontacto en sincronizacion';
                        $modelAcc = JModelLegacy::getInstance('Contacto', 'SasfeModel');
                        $id = $modelAcc->insertarAccion($idDatoContacto, $agtVentasId, $accionId, $comentario, $arrDateTime->fechaHora);

                        if($idAsesorVentas != $_POST["h_agtVentasIdDup_".$elem]){
                            $agtVentasId = $idAsesorVentas;
                            $accionId = 7;
                            $comentario = 'Se reasigno en sincronizacion';
                            $id = $modelAcc->insertarAccion($idDatoContacto, $agtVentasId, $accionId, $comentario, $arrDateTime->fechaHora);
                        }
                    }
                }
            }

            $msg = JText::sprintf('Registro no salvado.');
            if(count($arrIdsRegistrados)>0){
                // Imp. 13/10/20
                // Salvar los ids de los asesores para posteriomente mostarlos en la lista del box 2
                $respActConfig =  SasfehpHelper::ActDatoConfiguracionPorId(2, $idsAsesores);

                if(count($arrIdsRegistrados)>1){
                    $msg = JText::sprintf('Registros salvados correctamente.');
                }else{
                    $msg = JText::sprintf('Registro salvado correctamente.');
                }
            }

            $this->setRedirect( 'index.php?option=com_sasfe&view=sincontactos',$msg);
        }else{
            $msg = "No se encuentrar contactos para ser guardados.";
            $this->setRedirect( 'index.php?option=com_sasfe&view=sincontactos',$msg);
        }

        // echo "<pre>";
        // print_r($_POST);
        // print_r($arrContactos);
        // print_r($arrIdsRegistrados);
        // echo "</pre>";
        // exit();
    }

    //Retornar solo json
    private function retornaJson($re){
        JFactory::getDocument()->setMimeEncoding( 'application/json' );
        JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
        echo json_encode($re, JSON_UNESCAPED_UNICODE);
        JFactory::getApplication()->close();
    }


    // >>>>>>>>>>>>>
    // >>>>>>>>>>>>>Obtener datos de la hoja de google sheet
    // >>>>>>>>>>>>>
    private function getSpreadsheetData($url, $rowFormatArr) {
        $content = file_get_contents($url);
        $contentArr = json_decode($content, true);

        $rows = array();
        foreach($contentArr['feed']['entry'] as $row) {
            if ($row['title']['$t'] == '-') {
                continue;
            }

            $rowItems = array();
            foreach($rowFormatArr as $item) {
                // $rowItems[$item] = self::getRowValue($row['content']['$t'], $rowFormatArr, $item);
                $rowItems[$item] = $this->getRowValue($row['content']['$t'], $rowFormatArr, $item);
            }

            $rows[] = $rowItems;
        }

        return $rows;
    }

    private function getRowValue($row, $rowFormatArr, $column_name) {
            // echo "getRowValue[$column_name]:$row"."<br/>";
        if (empty($column_name)) {
            throw new Exception('column_name must not empty');
        }

        $begin = strpos($row, $column_name);
            // echo "begin:$begin"."<br/>";

        if ($begin == -1) {
            return '-';
        }

        $begin = $begin + strlen($column_name) + 1;


        $end = -1;
        $found_begin = false;

        foreach($rowFormatArr as $entity) {
            // echo "checking:$entity";

            if ($found_begin && strpos($row, $entity) != -1) {
                $end = strpos($row, $entity) - 2;
            // echo "end1:$end";
                break;
            }

            if ($entity == $column_name) {
                $found_begin = true;
            }


            #check if last element
            if (substr($row, strlen($row) - 1) == $column_name) {
                $end = strlen($row);
            } else {
                if ($end == -1) {
                    $end = strlen($row);
                } else {
                    $end = $end + 2;
                }
            }
        }

        // echo "end:$end";
        // echo "$column_name:$row";
        $value = substr($row, $begin, $end - $begin);
        $value = trim($value);
        // echo "${column_name}[${begin}-${end}]:[$value]";

        return $value;
    }

    // >>>>>>>>>>>>>Fin par datos de la hoja de google sheet
}

?>
