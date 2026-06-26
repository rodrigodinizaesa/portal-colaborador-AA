<?php
require_once("auth.php");

function verificarIBAN($iban): bool {
  $iban = strtoupper(str_replace(' ', '', trim($iban)));

  if (strlen($iban) !== 25) {
    return false;
  }

  if (!preg_match('/^[A-Z0-9]+$/', $iban)) {
    return false;
  }
  
  return true;
}

function verificarNIF($nif): bool {
  $nif = trim($nif);

  if (!preg_match('/^[0-9]{9}$/', $nif)) {
    return false;
  }

  return true;
}

function verificarNISS($niss): bool {
  $niss = trim($niss);
  
  if (!preg_match('/^[0-9]{11}$/', $niss)) {
    return false;
  }

  return true;
}

if (isset($_SESSION['idFuncionario'])){

  $idFuncionario = $_SESSION['idFuncionario'];
  $nome = $_POST['nome'];
  $nif = $_POST['nif'];
  $dataNascimento = $_POST['dataNascimento'];
  $sexo = $_POST['sexo'];
  $iban = $_POST['iban'];
  $niss = $_POST['niss'];
  $telefone = $_POST['telefone'];
  $nacionalidade = $_POST['nacionalidade'];
  $morada = $_POST['morada'];
  $codigoPostal = $_POST['codigoPostal'];
  $distrito = $_POST['distrito'];
  $concelho = $_POST['concelho'];
  $freguesia = $_POST['freguesia'];
  $contactoEmergenciaNome = $_POST['contactoEmergenciaNome'];
  $contactoEmergenciaTelefone = $_POST['contactoEmergenciaTelefone'];
  $contactoEmergenciaParentesco = $_POST['contactoEmergenciaParentesco'];
  $titulares = $_POST['titulares'];
  $dependentes = $_POST['dependentes'];
  $comprovativo = $_FILES['comprovativo'];
  /*$nota = $_POST['nota']; PENSAR NISTO*/


  if(!verificarIBAN($iban)){
    $erros[] = "IBAN inválido.";
  }

  if(!verificarNIF($nif)){
    $erros[] = "NIF inválido.";
  }

  if(!verificarNISS($niss)){
    $erros[] = "NISS inválido.";
  }


  if (empty($comprovativo['name']) || $comprovativo['error'] === UPLOAD_ERR_NO_FILE){
    $erros[] = "É obrigatório anexar comprovativo.";
  }

  $extensao = strtolower(pathinfo($_FILES['comprovativo']['name'], PATHINFO_EXTENSION));
  $permitidos = ['pdf', 'jpg', 'jpeg', 'png']; // formatos permitidos
    
  if (!in_array($extensao, $permitidos)) {
    $erros[] = "Formato do ficheiro não permitido.";
  }

  if (!empty($erros)) {
    $mensagem = addslashes(implode(" ", $erros));
    echo "<script>alert('$mensagem');</script>";
    echo "<script>window.location.href='../perfil.php'</script>";
    exit;
  }

  $data = date('Ymd');
  $nomeComprovativo = $data . '_' . $iniciais . '_' . $idFuncionario . '.' . $extensao;

  $caminho = "C:/xampp/uploads/" . $nomeComprovativo;
    
  if (!move_uploaded_file($comprovativo['tmp_name'], $caminho)) {
    echo "<script>alert('Erro ao guardar o ficheiro.');</script>";
    echo "<script>window.location.href='../faltas.php'</script>";
    exit;
  }

  include_once("dataAccess.php");
  $da = new dataAccess();
  $res = $da->criarPedidoFiscal($idFuncionario, $nome, $nif, $dataNascimento, $sexo, $iban, 
                                $niss, $telefone, $nacionalidade, $morada, $distrito, $concelho, $freguesia,
                                $codigoPostal, $contactoEmergenciaNome, $contactoEmergenciaTelefone,
                                $contactoEmergenciaParentesco, $titulares, $dependentes, $caminho);
  if ( $res == -1 )
    echo "<script>alert('Erro Contacte o administrador.')</script>";
  else 
    echo "<script>alert('Sucesso')</script>";
}

echo "<script>window.location.href='../ferias.php'</script>";

?>