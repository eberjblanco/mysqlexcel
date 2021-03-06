<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HomeController extends Controller
{
    public function index()
    {      
        return view('home');
    }

    public function enviarArchivo(Request $request)
    {
        //busca BDs 
        $Bd = $request->Bd;
        $tabla = $request->tabla;
        $archivo = $request->archivo;
        /*$reg = $request->reg;
        $col = $request->col;*/

        $cadena = "SHOW DATABASES";
        $Bds = DB::select($cadena);
        $seg1=0;

        

        foreach ($Bds as $key ) {           
            if ($key->Database==$Bd) {
                $seg1=1;
            }    
        }         
       
        if ($seg1 == 0 ) {
            $data = array('mensaje' => 'La BD no existe');
            return view('volver', $data);
            
        }

        
        $cadena = "SHOW TABLES FROM ".$Bd;
        $TablasTemp = DB::select($cadena);
        $seg2=0;
        $Aux = 'Tables_in_'.$Bd;

        
        
        foreach ($TablasTemp as $key) {
            if ($key->$Aux==$tabla) {
                $seg2=1;
            }   
        }
        if ($seg2 == 0 ) {
            $data = array('mensaje' => 'La Tabla no existe');
            return view('volver', $data);
            
        }

        //Obtener campos

        $cadena = "SHOW FULL  COLUMNS  FROM ".$Bd.".".$tabla;
        $CamposTempAux = DB::select($cadena);
        $seg3=0;

        

        $totalTabla = count($CamposTempAux);
        $CamposTemp = [];
        $PlantillaTime=['date','dateTime','timestamp','time','year'];
        $camposFec=[];
        for ($c=0; $c < $totalTabla; $c++) {
            $cellValue = array('field' => $CamposTempAux[$c]->Field,'type' => $CamposTempAux[$c]->Type, );
            array_push($CamposTemp,$cellValue);
            $clave = array_search($CamposTempAux[$c]->Type, $PlantillaTime); 
            if ($clave > 0) {
                array_push($camposFec,$c);;
            }
        }

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $spreadsheet = new Spreadsheet();
        $spreadsheet = $reader->load($archivo);

        
        $CamposAr =[];
        for ($c=1; $c <= $totalTabla; $c++) {
            $cellValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($c, 1)->getValue(); 
            array_push($CamposAr,$cellValue);
        }

        $spreadsheet->getActiveSheet()->setCellValue('XFD1', '=DCOUNTA(A:A,,A:A)');
        $totalArchivo = $spreadsheet->getActiveSheet()->getCell('XFD1')->getCalculatedValue();

        $info =[];
        
        for ($r=2; $r <= $totalArchivo + 1; $r++) {      
            $fila=[];      
            for ($c=1; $c <= count($CamposAr); $c++) {                
                $cellValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($c, $r)->getValue();                 
               
                //verificas fecha
                    $clave = array_search($c, $camposFec); 
                    if ($clave > 0) {
                        $mystring = $cellValue;
                        $findme   = '-';
                        $pos = strpos($mystring, $findme);
                        if ($pos === false) {
                            $data = array('mensaje' => 'Error de datos en columna de Fecha (Col:'.$c.',Fil:'.$r.'). Puede sustituir por 0000-00-00');
                            return view('volver', $data);
                        }
                        
                    }   

                array_push($fila,$cellValue); 
            }
            array_push($info,$fila);    
        }

        $mensaje="";

        $data = array(
            'totalTabla' => $totalTabla,
            'totalArchivo' => $totalArchivo,
            'CamposTemp' => $CamposTemp,
            'CamposAr' => $CamposAr,
            'mensaje' => $mensaje,
            'info' => $info,
            'Bd' => $Bd,
            'tabla' => $tabla,
        );

        return view('campos', $data);
        
    }

    public function subida(request $request)
    {
        $info = json_decode($request->info);
        $CamposTemp = json_decode($request->CamposTemp);
        $totalArchivo = $request->totalArchivo;
        $Bd = $request->Bd;
        $tabla = $request->tabla;

       

        \Config::set("database.connections.mysql2", array_merge(
            \Config::get("database.connections.mysql2"),
            [
                "database"  => $Bd,
            ]
        ));   

        DB::purge('mysql2');
        
        $cabeza="INSERT INTO ". $tabla;
        $campos="";

       
         
        for ($i=1; $i < count($CamposTemp); $i++) { 
            if ($i==count($CamposTemp)-1) {
                $campos = $campos . $CamposTemp[$i]->field;
            }else{
                $campos = $campos . $CamposTemp[$i]->field.",";
            }
        }
       
        

        for ($r=0; $r < $totalArchivo; $r++) { 
            $valores="";
            for ($c=1; $c < count($CamposTemp) ; $c++) { 
                if ($c==count($CamposTemp)-1) {
                    $valores = $valores . "'".$info[$r][$c]."'";
                }else{
                    $valores = $valores . "'".$info[$r][$c]."'".",";
                }          
            }
           
            
            $cadena = $cabeza."(".$campos.")VALUES(".$valores.")" ;            
            $registros = DB::connection('mysql2')->select($cadena);
           
        }

        return 'Listo!';
        
    }
}
