<?php
$dsn = 'mysql:host=localhost;dbname=tp2la4;port=3306';
$db_user = 'root';
$db_password = '';
$opt = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION];
$mysql = new PDO($dsn,$db_user,$db_password,$opt);
set_time_limit(400000);
error_reporting(0);

if($_POST){
  for ($codigo=1; $codigo <=300 ; $codigo++) {
  try {
  $api = file_get_contents("https://restcountries.eu/rest/v2/callingcode/$codigo");
  $json = json_decode($api);
  if(isset($json)){
    foreach ($json as $key => $pais) {
      $nombrePais = $pais->name;
      $capitalPais = $pais->capital;
      $region = $pais->region;
      $poblacion = $pais->population;
      $latitud = $pais->latlng[0];
      $longitud = $pais->latlng[1];
      $codigoPais = $pais->numericCode;
      $busquedaPais = $mysql->prepare("SELECT * FROM pais WHERE codigoPais = $codigoPais");
      $busquedaPais->execute();
      $result=$busquedaPais->rowCount();
      if($result != 0){
        //EXISTE
        $update = $mysql->prepare("UPDATE pais SET nombrePais = '$nombrePais' ,capitalPais = '$capitalPais', region = '$region',
        poblacion = '$poblacion', latitud = '$latitud',longitud = '$longitud' WHERE codigoPais = '$codigoPais'");
        $update->execute();
      }else {
        //NO EXISTE
        $sql = "INSERT INTO pais (codigoPais,nombrePais,capitalPais,region,poblacion,latitud,longitud) VALUES(?,?,?,?,?,?,?)";
        $insertar = $mysql->prepare($sql);
        $insertar->execute([$codigoPais,$nombrePais,$capitalPais,$region,$poblacion,$latitud,$longitud]);
      }
    }
  }else{
    continue;
      }
    } catch (\Exception $e) {}
  }
}
/*
for ($i=230; $i <= 235 ; $i++) {
$api = file_get_contents("https://restcountries.eu/rest/v2/callingcode/$i");
$json = json_decode($api);
if($api){
  foreach ($json as $key => $value) {
    // code...
    echo $value->name;
    echo "<br>";
  }
}else{
  continue;
}
}*/
 ?>
 <!DOCTYPE html>
 <html lang="es" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title></title>
   </head>
   <body>
     <h1>TP 2 LABORATORIO</h1>
     <ol>
       <li >Alumno: Alexander Castillo</li>
       <form class="migrar" action="index.php" method="post" onsubmit="return estaSeguro()">
         <button type="submit" name="button">MIGRAR PAISES</button>
       </form>
	</body>

  <!--Jquery-->
  <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
     <script type="text/javascript">
     function estaSeguro(){
       if(confirm("Esta Seguro? esto puede tardar alrededor un minuto")){
         return true;
       }else{
         return false;
       }
     }
     </script>
   </body>
 </html>
