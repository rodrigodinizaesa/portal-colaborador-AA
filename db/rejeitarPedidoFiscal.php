<?php
require_once("auth.php");

if(isset($_POST['id'])){
    $idPedido = $_POST['id'];
    $comentarioRH = $_POST['comentarioRH'];
    $idResponsavelRH = $_SESSION['idFuncionario'];
  
    include_once("dataAccess.php");
    $da = new dataAccess();
    $res = $da->updatePedidoFiscalRejeitado($idPedido, $idResponsavelRH, $comentarioRH);
    if ( $res == -1 )
        echo "<script>alert('Erro Contacte o administrador.')</script>";
    else 
        echo "<script>alert('Sucesso')</script>";
}

echo "<script>window.location.href='../pedidos_fiscais.php'</script>";

?>