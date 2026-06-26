<?php

class dataAccess{

   private $connection;

   private function connect(){ 
      try{     
         $server = "localhost";
         $db = "portal_colaborador_aa";
         $user = "root";
         $pwd = "";

         $this->connection = mysqli_connect($server, $user, $pwd, $db);
         if(!$this->connection)
            die("Contacte o Administrador!");
      }catch(Exception $ex){
         throw $ex;
      }
      
   }

   private function execute($query){
      try{
         $res = mysqli_query($this->connection, $query);
         if(!$res)
            die("invalid query");
         else
            return $res;
      }catch(Exception $ex){
         throw $ex;
      }
   }

   private function disconnect(){
      try{
         mysqli_close($this->connection);
      }catch(Exception $ex) {
         throw $ex;
      }
   }
   
   private function executeSQL($query){
      try{
         $this->connect();
         $res = $this->execute($query);
         $this->disconnect();
         return $res;
      }catch(Exception $ex) {
         throw $ex;
      }
   }

   public function login($nColaborador) {
      try{
         $query = "select u.email, u.idTipo, u.idFuncionario, u.password, f.nome as nomeUtilizador, f.idArea, c.nome AS cargo from utilizadores u INNER JOIN funcionarios f ON 
                  u.idFuncionario = f.id INNER JOIN cargos c ON f.idCargo = c.id where u.nColaborador = '$nColaborador'";
         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function dadosFuncionario($idFuncionario) {
      try{
         $query = "select u.id, f.nome AS nomeUtilizador, u.idFuncionario, f.idCargo, c.nome AS cargo from utilizadores u INNER JOIN
                  funcionarios f on u.idFuncionario = f.id INNER JOIN cargos c ON f.idCargo = c.id WHERE u.idFuncionario = '$idFuncionario'";
         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex){
         return -1;
      }
   } 

   public function getFuncionariosParaVerificar($dataHoje) {
      try{
         $query = "select f.id, f.nome
                  FROM funcionarios f
                  WHERE f.id NOT IN (
                  SELECT pf.idFuncionario
                  FROM pedidoFerias pf
                  WHERE pf.estado = 'aprovado'
                  AND '$dataHoje' BETWEEN pf.dataInicio AND pf.dataFim)";

         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex) {
         return -1;
      }
   }

   public function getRegistoDoDia($idFuncionario, $dataHoje) {
      try{
         $query = "select dataEntrada, dataSaida
                   FROM registos
                   WHERE idFuncionario = $idFuncionario
                   AND (
                     DATE(dataEntrada) = '$dataHoje'   
                     OR DATE(dataSaida) = '$dataHoje'  
                   )
                   LIMIT 1";

         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex) {
         return -1;
      }
   }

   public function existeFalta($idFuncionario, $dataInicio, $dataFim, $motivoFalta) {
      try{
         $query = "select idFaltas
                   FROM faltas
                   WHERE idFuncionario = $idFuncionario
                   AND dataInicio = '$dataInicio'
                   AND dataFim = '$dataFim'
                   AND motivoFalta = '$motivoFalta'";

         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex) {
         return -1;
      }
   }

   public function inserirFalta($idFuncionario, $dataInicio, $dataFim, $tipoFalta, $motivoFalta) {
      try{
         $query = "insert into faltas
                   (idFuncionario, dataInicio, dataFim, tipoFalta, motivoFalta)
                   VALUES
                   ($idFuncionario, '$dataInicio', '$dataFim', '$tipoFalta', '$motivoFalta')";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex) {
         return -1;
      }
   }
   
   public function getFalta($idFuncionario) {
      try{
         $query = "select idFaltas, idFuncionario, dataInicio, dataFim, tipoFalta, motivoFalta, estado from faltas where idFuncionario = '$idFuncionario'
                  ORDER BY dataInicio DESC";
         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function getFaltasTodos() {
      try{
         $query = "select faltas.idFaltas, faltas.idFuncionario, funcionarios.nome AS colaborador, areas.nome AS area, dataInicio, dataFim, tipoFalta, motivoFalta, estado from faltas INNER JOIN 
                  funcionarios ON faltas.idFuncionario = funcionarios.id INNER JOIN areas ON funcionarios.idArea = areas.id ORDER BY dataInicio DESC";
         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function criarPedidoFalta($idFaltas, $motivo, $caminho) {
      try{
         $query = "update faltas set motivo = '$motivo', comprovativo = '$caminho', estado = 'pendente', 
                  dataPedido = NOW() WHERE idFaltas = $idFaltas";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function getPedidos($idFuncionario) {
      try{
         $query = "select faltas.idFaltas, faltas.idFuncionario, funcionarios.nome AS colaborador, areas.nome AS area,
                  faltas.dataInicio, faltas.dataFim, faltas.tipoFalta, faltas.motivoFalta, faltas.motivo, faltas.comprovativo,
                  faltas.estado, faltas.dataPedido from faltas INNER JOIN funcionarios ON faltas.idFuncionario = funcionarios.id
                  INNER JOIN areas ON funcionarios.idArea = areas.id WHERE faltas.estado = 'pendente' AND faltas.idFuncionario != $idFuncionario 
                  ORDER BY faltas.dataPedido DESC";
         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function aceitarPedidoFalta($idFaltas, $comentarioRH, $idResponsavelRH) {
      try{
         $query = "update faltas set estado = 'aprovado', comentarioRH = '$comentarioRH', idResponsavelRH = '$idResponsavelRH',
                  dataDecisao = NOW() WHERE idFaltas = $idFaltas";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function rejeitarPedidoFalta($idFaltas, $comentarioRH, $idResponsavelRH) {
      try{
         $query = "update faltas set estado = 'rejeitado', comentarioRH = '$comentarioRH', idResponsavelRH = '$idResponsavelRH',
                  dataDecisao = NOW() WHERE idFaltas = $idFaltas";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function getDadosFiscais($idFuncionario) {
      try{
         $query = "select id, nome, nif, dataNascimento, sexo, iban, niss, telefone, nacionalidade, morada,
                  distrito, concelho, freguesia, codigoPostal, contactoEmergenciaNome, contactoEmergenciaTelefone,
                  contactoEmergenciaParentesco, titulares, dependentes from funcionarios WHERE id = $idFuncionario";
         $res = $this->executeSQL($query);
         return mysqli_fetch_object($res);
      }catch(Exception $ex){
         return -1;
      }
   }


   public function getPedidosFiscais($idFuncionario) {
      try{
         $query = "select pf.id, pf.idFuncionario, pf.novoNome, pf.novoNif, pf.novaDataNascimento, pf.novoSexo, pf.novoIban, pf.novoNiss, pf.novoTelefone, 
                  pf.novaNacionalidade, pf.novaMorada, pf.novoDistrito, pf.novoConcelho, pf.novaFreguesia, pf.novoCodigoPostal, 
                  pf.contactoEmergenciaNome, pf.contactoEmergenciaTelefone, pf.contactoEmergenciaParentesco, pf.novoTitulares, 
                  pf.novoDependentes, pf.comprovativo, pf.nota, pf.estado, pf.dataPedido, f.nome from pedidosdadosfiscais pf INNER JOIN funcionarios f ON f.id = pf.idFuncionario
                  WHERE pf.idFuncionario != $idFuncionario AND pf.estado = 'pendente' ORDER BY pf.dataPedido DESC";
         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function getFerias($idArea, $cargo, $idFuncionario) { 
      try{
         if ($idArea == 13 || strpos($cargo, 'Conselho Administrativo') === 0) { //RECURSOS HUMANOS e CA VEEM TUDO
            $query ="select f.nome, p.dataInicio, p.dataFim from pedidosferias p INNER JOIN funcionarios f ON 
                     f.id = p.idFuncionario WHERE p.estado = 'aprovado'";   
         } else if(strpos($cargo, 'Chefe') === 0) { // CHEFE SO VE OS FUNCIONARIOS DA SUA AREA
            $query ="select f.nome, p.dataInicio, p.dataFim from pedidosferias p INNER JOIN funcionarios f ON 
                     f.id = p.idFuncionario INNER JOIN cargos c on f.idCargo = c.id 
                     WHERE p.estado = 'aprovado' AND f.idArea = '$idArea'
                     AND c.nome NOT LIKE 'Chefe%'
                     AND c.nome NOT LIKE 'Diretor%'
                     AND c.nome NOT LIKE 'Conselho Administrativo%'";
         } else if(strpos($cargo, 'Diretor') === 0) { //DIRETOR VE DE TODOS DA SUA AREA
            $query ="select f.nome, p.dataInicio, p.dataFim from pedidosferias p INNER JOIN funcionarios f ON 
                     f.id = p.idFuncionario WHERE p.estado = 'aprovado'
                     AND f.idArea = '$idArea'";  
         } else { //FUNCIONARIO NORMAL SO VE AS DELE
            $query ="select f.nome, p.dataInicio, p.dataFim from pedidosferias p INNER JOIN funcionarios f ON 
                     f.id = p.idFuncionario WHERE p.estado = 'aprovado' AND p.idFuncionario = '$idFuncionario'";
         }
         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function marcarFerias($idFuncionario, $dataInicio, $dataFim, $nota) {
      try{
         $query = "insert into pedidosferias(idFuncionario, dataInicio, dataFim, nota)
                  values('$idFuncionario', '$dataInicio', '$dataFim', '$nota')";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function mostrarPedidosFerias($idArea, $cargo) {
      try{
         if (strpos($cargo, 'Chefe') === 0) {
            $query = "select p.id AS idPedido, p.idFuncionario, p.dataInicio, p.dataFim, p.estado, p.dataPedido, 
                     f.nome, c.nome AS cargo FROM pedidosferias p INNER JOIN funcionarios f
                     ON p.idFuncionario = f.id INNER JOIN cargos c ON f.idCargo = c.id
                     WHERE f.idArea = $idArea
                     AND c.nome NOT LIKE 'Chefe%'
                     AND c.nome NOT LIKE 'Diretor%'
                     AND c.nome NOT LIKE 'Conselho Administrativo%'
                     ORDER BY p.dataPedido DESC";
         } elseif (strpos($cargo, 'Diretor') === 0) {
            $query = "select p.id as idPedido, p.idFuncionario, p.dataInicio, p.dataFim, p.estado, p.dataPedido,
                     f.nome, c.nome AS cargo FROM pedidosferias p INNER JOIN funcionarios f
                     ON p.idFuncionario = f.id INNER JOIN cargos c ON f.idCargo = c.id
                     WHERE f.idArea = $idArea
                     AND c.nome LIKE 'Chefe%'
                     ORDER BY p.dataPedido DESC";
         } elseif (strpos($cargo, 'Conselho Administrativo') === 0) {
            $query = "select p.id AS idPedido, p.idFuncionario, p.dataInicio, p.dataFim, p.estado, p.dataPedido, 
                     f.nome, c.nome AS cargo FROM pedidosferias p INNER JOIN funcionarios f
                     ON p.idFuncionario = f.id  INNER JOIN cargos c ON f.idCargo = c.id
                     WHERE c.nome LIKE 'Diretor%' OR c.nome LIKE 'Conselho Administrativo%'
                     ORDER BY p.dataPedido DESC";
         }
         $res = $this->executeSQL($query);
         return $res;
      }catch(Exception $ex) {
         return -1;
      }
   }

   public function aceitarPedidoFerias($pedidoId, $idResponsavel) {
      try{
         $query = "update pedidosferias set estado = 'aprovado', idResponsavel = '$idResponsavel', dataDecisao = NOW()
                  WHERE id = $pedidoId";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex) {
         return -1;
      }
   }

   public function rejeitarPedidoFerias($pedidoId, $idResponsavel) {
      try{
         $query = "update pedidosferias set estado = 'rejeitado', idResponsavel = '$idResponsavel', dataDecisao = NOW()
                  WHERE id = $pedidoId";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex) {
         return -1;
      }
   }

   public function updateDadosFiscais($idFuncionario, $campos, $valores) {
      try{
         $updates = [];

         foreach ($campos as $i => $campo) {
               $valor = addslashes($valores[$i]);
               $updates[] = "$campo = '$valor'";
         }
         $query = "UPDATE funcionarios
                  SET " . implode(", ", $updates) . "
                  WHERE id = $idFuncionario";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function updatePedidoFiscalAceite($idPedido, $idResponsavelRH, $comentarioRH) {
      try{
         $query = "update pedidosdadosfiscais set estado = 'aprovado', idResponsavelRH = '$idResponsavelRH', 
                  dataDecisao = NOW(), comentarioRH = '$comentarioRH' WHERE id = $idPedido";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex){
         return -1;
      }
   }

   public function updatePedidoFiscalRejeitado($idPedido, $idResponsavelRH, $comentarioRH) {
      try{
         $query = "update pedidosdadosfiscais set estado = 'rejeitado', idResponsavelRH = '$idResponsavelRH', 
                  dataDecisao = NOW(), comentarioRH = '$comentarioRH' WHERE id = $idPedido";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex){
         die("Erro: " . $ex->getMessage());
      }
   }

   public function criarPedidoFiscal($idFuncionario, $nome, $nif, $dataNascimento, $sexo, $iban, $niss, $telefone,
                                    $nacionalidade, $morada, $distrito, $concelho, $freguesia, $codigoPostal,
                                    $contactoEmergenciaNome, $contactoEmergenciaTelefone, $contactoEmergenciaParentesco,
                                    $titulares, $dependentes, $caminho) {
      try{
         $query = "insert into pedidosdadosfiscais(idFuncionario, novoNome, novoNif, novaDataNascimento,
                  novoSexo, novoIban, novoNiss, novoTelefone, novaNacionalidade, novaMorada, novoDistrito,
                  novoConcelho, novaFreguesia, novoCodigoPostal, contactoEmergenciaNome, contactoEmergenciaTelefone,
                  contactoEmergenciaParentesco, novoTitulares, novoDependentes, comprovativo)
                  VALUES($idFuncionario, '$nome', '$nif', '$dataNascimento', '$sexo', '$iban', '$niss', '$telefone',
                  '$nacionalidade', '$morada', '$distrito', '$concelho', '$freguesia', '$codigoPostal', '$contactoEmergenciaNome',
                  '$contactoEmergenciaTelefone', '$contactoEmergenciaParentesco', '$titulares', '$dependentes', '$caminho')";
         $res = $this->executeSQL($query);
         return 1;
      }catch(Exception $ex){
         return -1;
      }
   }
   
   public function getUltimosPedidos($idFuncionario) {
      try {
         $query = "
               SELECT 'Marcação de Férias' AS tipo, estado, dataPedido FROM pedidosferias
               WHERE idFuncionario = $idFuncionario

               UNION ALL

               SELECT 'Alteração de Dados Fiscais' AS tipo, estado, dataPedido FROM pedidosdadosfiscais
               WHERE idFuncionario = $idFuncionario

               UNION ALL

               SELECT 'Justificação de Falta' AS tipo, estado, dataPedido FROM faltas
               WHERE idFuncionario = $idFuncionario

               ORDER BY dataPedido DESC
               LIMIT 5";
         $res = $this->executeSQL($query);
         return $res;
      }catch (Exception $ex){
         return -1;
      }
   }
}

?>