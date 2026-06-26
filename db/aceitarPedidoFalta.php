<?php 

session_start();
$idFuncionario = $_SESSION['idFuncionario'];

if(isset($_POST['idFaltas'])){
    $idFaltas = $_POST['idFaltas'];
    $comentarioRH = $_POST['comentarioRH'];
    $idResponsavelRH = $_SESSION['idFuncionario'];
  
    include_once("dataAccess.php");
    $da = new dataAccess();
    $res = $da->aceitarPedidoFalta($idFaltas, $comentarioRH, $idResponsavelRH);
    if ( $res == -1 )
        echo "<script>alert('Erro Contacte o administrador.')</script>";
    else 
        echo "<script>alert('Sucesso')</script>";
}

echo "<script>window.location.href='../faltas.php'</script>";

?>