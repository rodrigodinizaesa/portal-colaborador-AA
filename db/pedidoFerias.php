<?php
require_once("auth.php");

if(isset($_POST['dataInicio'])){
    $idFuncionario = $_SESSION['idFuncionario'];
    $dataInicio = $_POST['dataInicio'];
    $dataFim = $_POST['dataFim'];
    $nota = $_POST['nota'];
  
    include_once("dataAccess.php");
    $da = new dataAccess();
    $res = $da->marcarFerias($idFuncionario, $dataInicio, $dataFim, $nota);
    if ( $res == -1 )
        echo "<script>alert('Erro Contacte o administrador.')</script>";
    else 
        echo "<script>alert('Sucesso')</script>";
}

echo "<script>window.location.href='../ferias.php'</script>";

?>