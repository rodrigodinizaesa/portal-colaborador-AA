<?php
require_once("db/auth.php");
include_once("db/dataAccess.php");

if($_SESSION['idArea'] !== '13') {
  header("Location: dashboard.php");
  exit();
}

$idFuncionario = $_SESSION['idFuncionario'];

$da = new dataAccess();
$res = $da->getPedidosFiscais($idFuncionario);

$paginaAtual = "PedidosDadosFiscais";
$tituloPagina = "Pedidos de Alteração de Dados Fiscais";
$subtituloPagina = "Aceita ou rejeita os pedidos submetidos pelos funcionários";
?>
<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pedidos de Alteração Fiscais</title>
  <link rel="stylesheet" href="ferias.css" />
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
  <div class="app">
    <?php include_once("sidebar.php"); ?>
    <div class="main-wrapper">
      <?php include_once("navbar.php"); ?>
      <main class="main-content">
        <section class="df-page">
          <div class="df-header">
            <h1><?php echo $tituloPagina; ?></h1>
            <p><?php echo $subtituloPagina; ?></p>
          </div>

          <div class="df-list">
            <?php if(is_int($res) &&  $res == -1) { ?>
            <p>Erro ao carregar os pedidos contacte o Administrador.</p>
            <?php } else if(mysqli_num_rows($res) > 0) {?>
            <?php while($row = mysqli_fetch_object($res)) {?>
              <article class="df-row">
                <div class="df-row-main">
                  <strong><?php echo htmlspecialchars($row->nome); ?></strong>
                  <span><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($row->dataPedido))); ?></span>
                </div>
                <div class="df-row-status">
                  <?php
                  $estado = strtolower(trim($row->estado));
                  $classeBadge = "df-badge-pendente";
                  if($estado === "aprovado") $classeBadge = "df-badge-aceite";
                  else if($estado === "rejeitado") $classeBadge = "df-badge-rejeitado";
                  ?>
                  <span class="df-badge <?php echo $classeBadge; ?>"><?php echo htmlspecialchars(ucfirst($row->estado));?></span>
                </div>
                <div class="df-row-action">
                  <button type="button" class="df-btn df-btn-view" data-open-modal="pedidoModal<?php echo htmlspecialchars($row->dataPedido . $row->novoNif); ?>">Ver</button>
                </div>
              </article>
              <?php } ?>
            <?php } else {?>
            <p>Não existem Pedidos.</p>
            <?php }?>
          </div>
        </section>
      </main>
    </div>
  </div>

  <?php if(!is_int($res) && mysqli_num_rows($res) > 0) { ?>
  <?php mysqli_data_seek($res, 0); ?>
  <?php while ($row = mysqli_fetch_object($res)) { ?>
  <?php
  $modalId = "pedidoModal" . htmlspecialchars($row->dataPedido . $row->novoNif);
      $campos = [
        'Nome'                         => $row->novoNome,
        'NIF'                          => $row->novoNif,
        'Data de Nascimento'           => $row->novoDataNascimento,
        'Sexo'                         => $row->novoSexo,
        'IBAN'                         => $row->novoIban,
        'NISS'                         => $row->novoNiss,
        'Telefone'                     => $row->novoTelefone,
        'Nacionalidade'                => $row->novaNacionalidade,
        'Morada'                       => $row->novaMorada,
        'Distrito'                     => $row->novoDistrito,
        'Concelho'                     => $row->novoConcelho,
        'Freguesia'                    => $row->novaFreguesia,
        'Código Postal'                => $row->novoCodigoPostal,
        'Nome do Contacto de Emergência'   => $row->contactoEmergenciaNome,
        'Telefone do Contacto de Emergência'   => $row->contactoEmergenciaTelefone,
        'Grau de Parentesco Contacto de Emergência'        => $row->contactoEmergenciaParentesco,
        'Titulares'                    => $row->novoTitulares,
        'Dependentes'                  => $row->novoDependentes,
        'Nota'                         => $row->nota,
      ];
      ?>
    <dialog id="<?php echo $modalId; ?>" class="df-modal">
      <div class="df-modal-content">
        <div class="df-modal-head">
          <div>
            <strong>Pedido de alteração de dados fiscais</strong>
            <span>Nome: <?php echo htmlspecialchars($row->nome); ?>  /  Data do Pedido: <?php echo htmlspecialchars(date('d/m/Y \á\s H:i', strtotime($row->dataPedido))); ?></span>
          </div>
          <button type="button" class="df-modal-close" data-close-modal aria-label="Fechar">x</button>
        </div>

        <div class="df-modal-grid">
          <?php foreach ($campos as $label => $valor) { ?>
          <?php if (!empty ($valor)) {?>
            <div class="df-field-row">
              <span><?php echo $label; ?></span>
              <strong><?php echo htmlspecialchars($valor); ?></strong>
            </div>
          <?php } ?>
          <?php } ?>
          <?php if(!empty($row->comprovativo)) { ?>
          <?php
          $ext = strtolower(pathinfo($row->comprovativo, PATHINFO_EXTENSION));
          $urlFicheiro = "db/visualizar_documento.php?comprovativo=" . urlencode($row->comprovativo);
          $imagem = in_array($ext, ['jpg', 'jpeg', 'png']);
          $pdf    = $ext === 'pdf';
          ?>
            <div class="df-field-row df-file-row">
                <span>Comprovativo</span>
                <?php if($imagem) { ?>
                <iframe
                src="<?php echo $urlFicheiro; ?>"
                alt="Comprovativo"
                class="df-preview-img"
                loading="lazy"></iframe>

                  <?php } elseif($pdf) { ?>
                  <iframe
                  src="<?php echo $urlFicheiro; ?>"
                  class="df-preview-pdf"
                  title="Pré-visualização do comprovativo"></iframe>
                  <?php } ?>
                <a href="<?php echo $urlFicheiro; ?>" target="_blank" rel="noopener noreferrer" class="df-btn df-btn-view" style="width:fit-content; margin-top:6px;">
                  Abrir Documento
                </a>
            </div>
            <?php } ?>
            
            <div class="df-field-row df-field-comentario">
              <span>Comentário / Observação</span>
              <textarea
                id="comentario-<?php echo $modalId; ?>"
                class="df-comentario-textarea"
                rows="3"
                placeholder="Escreve uma observação sobre este pedido..."
              ></textarea>
            </div>
          </div>

          <div class="df-modal-actions">
            <form action="db/aceitarPedidoFiscal.php" method="post">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($row->id); ?>">
              <input type="hidden" name="idFuncionario" value="<?php echo htmlspecialchars($row->idFuncionario); ?>">
              <input type="hidden" name="novoNome" value="<?php echo htmlspecialchars($row->novoNome); ?>">
              <input type="hidden" name="novoNif" value="<?php echo htmlspecialchars($row->novoNif); ?>">
              <input type="hidden" name="novaDataNascimento" value="<?php echo htmlspecialchars($row->novaDataNascimento); ?>">
              <input type="hidden" name="novoSexo" value="<?php echo htmlspecialchars($row->novoSexo); ?>">
              <input type="hidden" name="novoIban" value="<?php echo htmlspecialchars($row->novoIban); ?>">
              <input type="hidden" name="novoNiss" value="<?php echo htmlspecialchars($row->novoNiss); ?>">
              <input type="hidden" name="novoTelefone" value="<?php echo htmlspecialchars($row->novoTelefone); ?>">
              <input type="hidden" name="novaNacionalidade" value="<?php echo htmlspecialchars($row->novaNacionalidade); ?>">
              <input type="hidden" name="novaMorada" value="<?php echo htmlspecialchars($row->novaMorada); ?>">
              <input type="hidden" name="novoDistrito" value="<?php echo htmlspecialchars($row->novoDistrito); ?>">
              <input type="hidden" name="novoConcelho" value="<?php echo htmlspecialchars($row->novoConcelho); ?>">
              <input type="hidden" name="novaFreguesia" value="<?php echo htmlspecialchars($row->novaFreguesia); ?>">
              <input type="hidden" name="novoCodigoPostal" value="<?php echo htmlspecialchars($row->novoCodigoPostal); ?>">
              <input type="hidden" name="contactoEmergenciaNome" value="<?php echo htmlspecialchars($row->contactoEmergenciaNome); ?>">
              <input type="hidden" name="contactoEmergenciaTelefone" value="<?php echo htmlspecialchars($row->contactoEmergenciaTelefone); ?>">
              <input type="hidden" name="contactoEmergenciaParentesco" value="<?php echo htmlspecialchars($row->contactoEmergenciaParentesco); ?>">
              <input type="hidden" name="novoTitulares" value="<?php echo htmlspecialchars($row->novoTitulares); ?>">
              <input type="hidden" name="novoDependentes" value="<?php echo htmlspecialchars($row->novoDependentes); ?>">
              <input type="hidden" name="comentarioRH" class="comentario-hidden-<?php echo $modalId; ?>" value="">
              <button type="submit" class="df-btn df-btn-accept" onclick='return confirmarAceitar()'>Aceitar Pedido</button>
            </form>
            <form action="db/rejeitarPedidoFiscal.php" method="post">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($row->id); ?>">
              <input type="hidden" name="comentarioRH" class="comentario-hidden-<?php echo $modalId; ?>" value="">
              <button type="submit" class="df-btn df-btn-reject" onclick='return confirmarRejeitar()'>Rejeitar Pedido</button>
            </form>
          </div>
      </div>
    </dialog>
  <?php } ?>
  <?php } ?>

<script>
  document.querySelectorAll('[data-open-modal]').forEach(btn => {
  btn.addEventListener('click', () => {
    const modal = document.getElementById(btn.getAttribute('data-open-modal'));
    if (modal && typeof modal.showModal === 'function') modal.showModal();
    });
  });

  document.querySelectorAll('[data-close-modal]').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.closest('dialog')?.close();
    });
  });

  document.querySelectorAll('dialog.df-modal').forEach(modal => {
    modal.addEventListener('click', e => {
      if (e.target === modal) modal.close();
    });
  });

  document.querySelectorAll('.df-field-comentario textarea').forEach(textarea => {
    textarea.addEventListener('input', function () {
      const modalId = this.id.replace('comentario-', '');
      document.querySelectorAll('.comentario-hidden-' + modalId).forEach(input => {
        input.value = this.value;
      });
    });
  });

  function confirmarAceitar(){
    return confirm("Tem a certeza que quer Aceitar este Pedido?");
  }

  function confirmarRejeitar(){
    return confirm("Tem a certeza que quer Rejeitar este Pedido?");
  }
</script>
<script src="js/layout.js"></script>
</body>
</html>