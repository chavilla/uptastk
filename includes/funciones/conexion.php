<?php

$conexion=new mysqli('localhost','root','','uptask','3306');

//echo $conexion->ping();

//Para poder ver los acentos y ñ colocamos:

$conexion->set_charset('utf8');