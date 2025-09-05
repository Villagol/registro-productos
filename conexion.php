<?php
$conn = pg_connect("host=localhost dbname=tienda user=postgres password=CONTRASEÑA_EJEMPLO");
if(!$conn){die("No se realizó la conexión a la BD");}
