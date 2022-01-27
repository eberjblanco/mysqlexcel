<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body class="container">
   
<form action="{{ route('index') }}">
<br>
    
  <button type="submit" class="btn btn-danger">Anterior</button>
</form>   
    
    <hr>
    <table class="table">
        <tr>
            <th>Campos en la Tabla de la BD</th>
            <th>Campos en el archivo excel</th>
        </tr>
        <?php
                $seg =0;
            ?>
        @for($i=0; $i<$totalTabla; $i++)
           
            <tr>
                <td>{{ $CamposTemp[$i]}}</td>
                @if($CamposAr[$i] != $CamposTemp[$i])
                    <td style="background: red">{{ $CamposAr[$i]}}</td>
                    <?php
                        $seg =1;
                    ?>
                
                @else
                    <td style="background: green; color:white">{{ $CamposAr[$i]}}</td>
                @endif
                
            </tr>
        @endfor
    </table>
    <hr>
    <form action="{{ route('subida') }}" method="POST">
        @csrf
        <input type="hidden" name="CamposTemp" value="{{ json_encode($CamposTemp) }}">
        <input type="hidden" name="info" value=" {{ (json_encode($info)) }}">
        <input type="hidden" name="totalArchivo" value="{{ $totalArchivo }}">
        <input type="hidden" name="Bd" value="{{ $Bd }}">
        <input type="hidden" name="tabla" value="{{ $tabla }}">
       
        
       
        <div>
            @if($seg==1)
            <h6>* No concuerdan los campos del archivo excel con los de la base de datos. Verifique y vuelva a intentar</h6>
                <button type="submit" class="btn btn-success" disabled>Siguiente</button>
            @else
                <h6>* Test Correcto!. Puede Continuar</h6>
                <button type="submit" class="btn btn-success" >Siguiente</button>
            @endif
        
        </div>
    </form>
    <hr>
   
  
    <hr>
    <h6>* {{  $totalArchivo }} registros en el archivo excel </h6>
    {{ var_dump($info) }}

    <script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        
    </script>
</body>
</html>