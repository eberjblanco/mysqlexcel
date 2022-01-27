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
    <br>
    <h4>* Solo se permiten archivos excel</h4>
    <h4>* La  columna A en el archivo excel debe ser el autonomérico.No deje casillas en blanco en esta columna</h4>
    <h4>* La  Fila 1 en el archivo excel deben ser los nombres de los campos. Deben ser iguales a los nombres de la tabla.</h4>
    <h4>* El Archivo Excel debe tener una sola pestaña</h4>
    <h4>* Si va a subir fechas debe cerciorarse que la columna en excel este formateada en "texto" y el formato de la fecha debe ser YYYY-MM-DD</h4>

    <form action="{{ route('enviarArchivo') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div>
            <input class="form-control" type="text" placeholder="Nombre de la Base de datos" required name="Bd" id="Bd">
        </div>
        <br>
        <div>
        <input class="form-control" type="text" placeholder="Nombre de la tabla" required  name="tabla" id="tabla">              
        </div>
        <br>
        <div>
        <input class="form-control" type="file"  name="archivo" id="archivo" required>
        </div>
        <br>
        <button type="submit" class="btn btn-success">Siguiente</button>
    </form>

    <script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </script>
</body>
</html>