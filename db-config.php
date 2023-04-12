<?php

$DBHOST = 'localhost';
$DBUSER = 'admin';
$DBPASS = 'raspberry';
$DBNAME = 'DB_qr';
$link = mysqli_connect($DBHOST, $DBUSER, $DBPASS, $DBNAME);

if(! $link ) {
    die('Não foi possível conectar-se: ' . mysqli_connect_error());
}

echo 'Conectado com sucesso';

$query_insert = "INSERT INTO faturas_corretas(ID, NIF, Valor, dia) VALUES(248834083, 508169950, 5, '".'2022-07-15'."')"; 
$result_insert = mysqli_query($link,$query_insert);
if($result_insert){
    echo 'Dados inseridos com sucesso';    
}
else{
    echo 'Falha ao tentar inserir dados';
}
mysqli_close($link);
?>