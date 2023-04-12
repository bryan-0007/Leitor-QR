<?php

// configurações da bd (sqlite)

include_once 'db-config.php'; 

//só para consulta

$mapa_codigos=["

    'A'='NIF DO EMITENTE',  //validar sempre este= 508169950

    'B'='NIF DO ADQUIRENTE',

    'C'='PAIS DO ADQUIRENTE',

    'D'='TIPO DE DOCUMENTO',

    'E'='ESTADO DO DOCUMENTO',

    'F'='DATA DO DOCUMENTO',  //formato YYYYMMDD validar  data de hoje

    'G'='IDENTIFICACAO DO DOCUMENTO',

    'H'='ATCUD',



    'I1'='ESPACO FISCAL',

    'I2'='BASE TRIBUTAVEL ISENTA DE IVA',

    'I3'='BASE TRIBUTAVEL DE IVA A TAXA REDUZIDA',

    'I4'='TOTAL DE IVA A TAXA REDUZIDA',

    'I5'='BASE TRIBUTAVEL DE IVA A TAXA INTERMEDIA',

    'I6'='TOTAL DE IVA A TAXA INTERMEDIA',

    'I7'='BASE TRIBUTAVEL DE IVA A TAXA NORMAL',

    'I8'='TOTAL DE IVA A TAXA NORMAL',



    'J1'='ESPACO FISCAL',

    'J2'='BASE TRIBUTAVEL ISENTA DE IVA',

    'J3'='BASE TRIBUTAVEL DE IVA A TAXA REDUZIDA',

    'J4'='TOTAL DE IVA A TAXA REDUZIDA',

    'J5'='BASE TRIBUTAVEL DE IVA A TAXA INTERMEDIA',

    'J6'='TOTAL DE IVA A TAXA INTERMEDIA',

    'J7'='BASE TRIBUTAVEL DE IVA A TAXA NORMAL',

    'J8'='TOTAL DE IVA A TAXA NORMAL',



    'K1'='ESPACO FISCAL',

    'K2'='BASE TRIBUTAVEL ISENTA DE IVA',

    'K3'='BASE TRIBUTAVEL DE IVA A TAXA REDUZIDA',

    'K4'='TOTAL DE IVA A TAXA REDUZIDA',

    'K5'='BASE TRIBUTAVEL DE IVA A TAXA INTERMEDIA',

    'K6'='TOTAL DE IVA A TAXA INTERMEDIA',

    'K7'='BASE TRIBUTAVEL DE IVA A TAXA NORMAL',

    'K8'='TOTAL DE IVA A TAXA NORMAL',



    'L'='NAO SUJEITO   NAO TRIBUTAVEL  EM IVA   OUTRAS  SITUACOES',

    'M'='IMPOSTO DE SELO',

    'N'='TOTAL IMPOSTOS',

    'O'='TOTAL DO DOCUMENTO COM IMPOSTOS', //validar maior que 5

    'P'='RETENCOES NA FONTE',

    'Q'='4 CARACTERES DO HASH',

    'R'='NUMERO DO CERTIFICADO',

    'S'='OUTRAS INFORMACOES',

"];

if(isset($_GET['qr'])){

    $qr="A500172382B515747769CPTDFTENF20220307GFT 705327407H0I1PTI339.22I42.35I534.74I64.52I728.67I86.59N13.46O116.09QKxsbR1724";
    $qr="A508169950B511266561CPTDFTENF20220627GFAC 14463H0-4463I1PTI725.50I85.87N5.87O31.37P0.00QQxrMR2123";

    $qr=$_GET['qrcontent'];
    $qr_items=explode(",",$qr);
    $qr_temp=[];

    $abrir_portao=false;

    $valido=false;

    if(!is_array($qr_items)){
        //print Erro (1) QR CODE INVALIDO.;
        $valido=false;
    }else{
        foreach ($qr_items as $item){
            $tmp=explode("*",$item);
            if(isset($tmp[0])){
                $key=$tmp[0];
                $value=$tmp[0];
                if(isset($tmp[1])){
                    $value=$tmp[1];
                }
                $qr_temp[$key]=$value;
            }
        }

        $valido=true;
        if(!isset($qr_temp['F']) && !isset($qr_temp['G']) && !isset($qr_temp['O']) && !isset($qr_temp['N'])){
            //print Erro (2) QR CODE INCOMPLETO.;
            $valido=false;
        }
        if(empty($qr_content)){
            //print Erro (2) QR CODE INCOMPLETO.;
            $valido=false;
        }

        //se o QR code está bem formatado e é valido
        if($valido==true){
            if(isset($qr_temp['F'])){
                $qr_temp['F']=date("Y-m-d H:i:s",strtotime($qr_temp['F']));
            }

            //config
            $nif_correto=508169950;
            $consumo_minimo=5;

            $data_agora=date("Y-m-d");  //data de hoje

            //dados do QR - Consultar mapa em cima
            $nif_fatura=$qr_temp['A'];
            $valor_fatura=$qr_temp['O'];
            $data_fatura=$qr_temp['F'];
            $id_fatura=$qr_temp['G'];

            if($nif_correto==$nif_fatura && $valor_fatura=$consumo_minimo && strtotime($data_fatura)==strtotime($data_agora)){
                
                $query_select = "SELECT * FROM faturas_corretas WHERE DATE(Época) >= DATE(NOW()) - INTERVAL 30 DAY and id=".$id_fatura.""; // query que seleciona a fatura utilizada para abrir a porta, a condição de terem o mesmo id da que for utilizada no momento e no caso da mesma ter sido utilizada nos últimos 30 dias
                $result_select = mysqli_query($conn,$query_select);
                $numero_linhas_query = mysqli_num_rows($result_select); // para verificar através do id se já utilizaram a mesma ou não   
                
                if($numero_linhas_query==0){
                    $abrir_portao=true;
                }
            }

        }

    }

    if($abrir_portao==true){
        $query_insert = "INSERT INTO faturas_corretas(ID, NIF, Valor, Época) Values(".$id_fatura.",".$nif_fatura.",".$valor_fatura.",".$data_fatura.")"; 
        $result_insert = mysqli_query($conn,$query_insert);   
        shell_exec("sudo python teste.py");
    }else{
        shell_exec("sudo python teste.py");
    }

}
?>




<!DOCTYPE html>
<html>

<head>
    <title>Leitor de QR</title>
    <link href="httpsfonts.googleapis.comcssfamily=Roboto300,400,500,700" rel="stylesheet">
    <link rel="stylesheet href=httpsuse.fontawesome.comreleasesv5.4.1cssall.css"
        integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <style>
    html,
    body {
        display: flex;
        justify-content: center;
        height: 100%;
    }

    body,
    div,
    h1,
    form,
    input,
    p {
        padding: 0;
        margin: 0;
        outline: none;
        font-family: Roboto, Arial, sans-serif;
        font-size: 16x;
        color: #666;
    }

    h1 {
        padding: 10px 0;
        font-size: 32px;
        font-weight: 300;
        text-align: center;
    }

    p {
        font-size: 12px;
    }

    hr {
        color: #a9a9a9;
        opacity: 0.3;
    }

    .main-block {
        width: 90vw;
        height: 90vh;
        padding: 10px 0;
        margin: auto;
        border-radius: 5px;
        border: 1px #ccc;
        box-shadow: 1px 2px 5px rgba(0, 0, 0, .31);
        background: #ebebeb;
    }

    form {
        margin: 0 30px;
    }

    .account-type,
    .gender {
        margin: 15px 0;
    }

    input[type=radio] {
        display: none;
    }

    label#icon {
        margin: 0;
        border-radius: 5px 0 0 5px;
    }

    label.radio {
        position: relative;
        display: inline-block;
        padding-top: 4px;
        margin-right: 20px;
        text-indent: 30px;
        overflow: visible;
        cursor: pointer;
    }

    label.radiobefore {
        content: "";
        position: absolute;
        top: 2px;
        left: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #1c87c9;
    }

    label.radioafter {
        content: "";
        position: absolute;
        width: 9px;
        height: 4px;
        top: 8px;
        left: 4px;
        border: 3px solid #fff;
        border-top: none;
        border-right: none;
        transform: rotate(-45deg);
        opacity: 0;
    }

    input[type=radio]checked+labelafter {
        opacity: 1;
    }

    input[type=text],
    input[type=password] {
        width: calc(100% - 57px);
        height: 36px;
        margin: 13px 0 0 -5px;
        padding-left: 10px;
        border-radius: 0 5px 5px 0;
        border: solid 1px #cbc9c9;
        box-shadow: 1px 2px 5px rgba(0, 0, 0, .09);
        background: #fff;
    }

    input[type=password] {
        margin-bottom: 15px;
    }

    #icon {
        display: inline-block;
        padding: 9.3px 15px;
        box-shadow: 1px 2px 5px rgba(0, 0, 0, .09);
        background: #1c87c9;
        color: #fff;
        text-align: center;
    }

    .btn-block {
        margin-top: 10px;
        text-align: center;
    }

    button {
        width: 100%;
        padding: 10px 0;
        margin: 10px auto;
        border-radius: 5px;
        border: none;
        background: #1c87c9;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
    }

    buttonhover {
        background: #26a9e0;
    }
    </style>
</head>

<body>
    <div class="main-block" style="height: 800px">
        <h1>Passe o código QR no leitor</h1>
        <div style="text-align: center"><img src="qr.png"> </div>
        <form action="index (1).php" method="get" style="text-align:
                center">
            <input type="text" autofocus name="qr" id="qr" style="height: 1px;width: 1px;opacity: 0">
        </form>
    </div>
    <script>
    setInterval(function() {
        document.getElementById(" qr").focus();
    }, 500);
    </script>
    </div>
</body>

</html>