<?php
require_once("auth.php"); 

if(isset($_POST['id'])){
    $idPedido = $_POST['id'] ?? '';
    $idFuncionario = $_POST['idFuncionario'] ?? '';
    $nome = $_POST['novoNome'] ?? '';
    $nif = $_POST['novoNif'] ?? '';
    $dataNascimento = $_POST['novaDataNascimento'] ?? '';
    $sexo = $_POST['novoSexo'] ?? '';
    $iban = $_POST['novoIban'] ?? '';
    $niss = $_POST['novoNiss'] ?? '';
    $telefone = $_POST['novoTelefone'] ?? '';
    $nacionalidade = $_POST['novaNacionalidade'] ?? '';
    $morada = $_POST['novaMorada'] ?? '';
    $distrito = $_POST['novoDistrito'] ?? '';
    $concelho = $_POST['novoConcelho'] ?? '';
    $freguesia = $_POST['novaFreguesia'] ?? '';
    $codigoPostal = $_POST['novoCodigoPostal'] ?? '';
    $contactoEmergenciaNome = $_POST['contactoEmergenciaNome'] ?? '';
    $contactoEmergenciaTelefone = $_POST['contactoEmergenciaTelefone'] ?? '';
    $contactoEmergenciaParentesco = $_POST['contactoEmergenciaParentesco'] ?? '';
    $titulares = $_POST['novoTitulares'] ?? '';
    $dependentes = $_POST['novoDependentes'] ?? '';
    $idResponsavelRH = $_SESSION['idFuncionario'];
    $comentarioRH = $_POST['comentarioRH'] ?? '';

    include_once("dataAccess.php");
    $da = new dataAccess();
    $atual = $da->getDadosFiscais($idFuncionario);

    $campos = [];
    $valores = [];

    if ($nome != $atual->nome) { 
        $campos[] = "nome";
        $valores[] = $nome; 
    }
    if ($nif != $atual->nif) { 
        $campos[] = "nif";          
        $valores[] = $nif; 
    }
    if ($dataNascimento != $atual->dataNascimento) { 
        $campos[] = "dataNascimento"; 
        $valores[] = $dataNascimento; 
    }
    if ($sexo != $atual->sexo) { 
        $campos[] = "sexo";         
        $valores[] = $sexo; 
    }
    if ($iban != $atual->iban) { 
        $campos[] = "iban";         
        $valores[] = $iban; 
    }
    if ($niss != $atual->niss) { 
        $campos[] = "niss";         
        $valores[] = $niss; 
    }
    if ($telefone != $atual->telefone) { 
        $campos[] = "telefone";     
        $valores[] = $telefone; 
    }
    if ($nacionalidade != $atual->nacionalidade) { 
        $campos[] = "nacionalidade"; 
        $valores[] = $nacionalidade; 
    }
    if ($morada != $atual->morada) { 
        $campos[] = "morada";       
        $valores[] = $morada; 
    }
    if ($distrito != $atual->distrito) { 
        $campos[] = "distrito";     
        $valores[] = $distrito; 
    }
    if ($concelho != $atual->concelho) { 
        $campos[] = "concelho";     
        $valores[] = $concelho; 
    }
    if ($freguesia != $atual->freguesia) { 
        $campos[] = "freguesia";    
        $valores[] = $freguesia; 
    }
    if ($codigoPostal != $atual->codigoPostal) { 
        $campos[] = "codigoPostal"; 
        $valores[] = $codigoPostal; 
    }
    if ($contactoEmergenciaNome != $atual->contactoEmergenciaNome) { 
        $campos[] = "contactoEmergenciaNome";        
        $valores[] = $contactoEmergenciaNome; 
    }
    if ($contactoEmergenciaTelefone != $atual->contactoEmergenciaTelefone) { 
        $campos[] = "contactoEmergenciaTelefone";    
        $valores[] = $contactoEmergenciaTelefone; 
    }
    if ($contactoEmergenciaParentesco != $atual->contactoEmergenciaParentesco) { 
        $campos[] = "contactoEmergenciaParentesco"; 
        $valores[] = $contactoEmergenciaParentesco; 
    }
    if ($titulares != $atual->titulares) { 
        $campos[] = "titulares";    
        $valores[] = $titulares; 
    }
    if ($dependentes != $atual->dependentes) { 
        $campos[] = "dependentes";  
        $valores[] = $dependentes; 
    }
    
    if (!empty($campos)) {
        $res = $da->updateDadosFiscais($idFuncionario, $campos, $valores);
    } else {
        $res = 0; // nada mudou
    }

    $resPedido = $da->updatePedidoFiscalAceite($idPedido, $idResponsavelRH, $comentarioRH);

    if ( $res == -1 || $resPedido == -1)
        echo "<script>alert('Erro Contacte o administrador.')</script>";
    else 
        echo "<script>alert('Sucesso')</script>";
    }

    echo "<script>window.location.href='../pedidos_fiscais.php'</script>";
?>