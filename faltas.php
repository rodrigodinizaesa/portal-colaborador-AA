<?php
require_once("db/auth.php");
include_once("db/dataAccess.php");

$idFuncionario = $_SESSION['idFuncionario'];
$idArea = $_SESSION['idArea'];

$da = new dataAccess();
$resPedidos = false;

if ($idArea === '13') {
  $res = $da->getFaltasTodos();
  $resPedidos = $da->getPedidos($idFuncionario);
} else {
  $res = $da->getFalta($idFuncionario);
}

$paginaAtual = "Faltas";
$tituloPagina = "Justificação de Faltas";
$subtituloPagina = "Consulta o estado das tuas faltas e submete os comprovativos necessários.";
?>
<!DOCTYPE html>
<html lang="pt" data-theme="light">
  <head>
    <title>Página de Faltas</title>
    <link rel="stylesheet" href="faltas.css" />
    <script src="https://unpkg.com/lucide@latest"></script>
  </head>
  <body>
    <div class="app">
      <?php include_once("sidebar.php"); ?>
      <div class="main-wrapper">
        <?php include_once("navbar.php"); ?>
        <main class="main-content" id="main-content">
          <section class="pagina-rh">
            <div class="tabs-linha">
              <button type="button" class="tab-item active" data-tab="faltas">
                <i data-lucide="file-pen-line"></i>
                Faltas
              </button>
              <?php if ($idArea === '13') { ?>
              <button type="button" class="tab-item" data-tab="pedidos">
                <i data-lucide="clipboard-list"></i>
                Pedidos
              </button>
              <?php } ?>
            </div>
            
            <section class="painel-faltas tab-panel active" data-panel="faltas">
              <div class="pesquisa-box">
                <label for="pesquisaFalta">Pesquisa:</label>
                <div class="input-pesquisa">
                  <input type="text" id="pesquisaFalta">
                  <span class="icone-lupa">
                    <i data-lucide="search"></i>
                  </span>
                </div>
              </div>
              
              <div class="tabela-wrapper">
                <table class="tabela-faltas" id="tabelaFaltas">
                  <?php if ($idArea === '13') {?>
                  <thead>
                    <tr>
                      <th><strong>Data de Inicio</strong></th>
                      <th><strong>Data de Fim</strong></th>
                      <th><strong>Colaborador</strong></th>
                      <th><strong>Area</strong></th>
                      <th><strong>Tipo</strong></th>
                      <th><strong>Motivo</strong></th>
                      <th><strong>Estado</strong></th>
                      <th></th>
                    </tr>
                  </thead>
                  <?php }else {?>
                  <thead>
                    <tr>
                      <th><strong>Data de Inicio</strong></th>
                      <th><strong>Data de Fim</strong></th>
                      <th><strong>Tipo</strong></th>
                      <th><strong>Motivo</strong></th>
                      <th><strong>Estado</strong></th>
                      <th></th>
                    </tr>
                  </thead>
                  <?php }?>
                  
                  <tbody>
                    <?php if ($res === -1) { ?>
                    <tr class="linha-vazia">
                      <td colspan="<?php echo ($idArea === '13') ? 8 : 6; ?>">
                        Erro. Contacta o administrador.
                      </td>
                    </tr>
                    <?php } elseif ($res && mysqli_num_rows($res) > 0) { ?>
                    <?php while ($row = mysqli_fetch_object($res)) { ?>
                    <?php
                    $estado = strtolower(trim($row->estado));
                    $textoEstado = "Injustificada";
                    $classeEstado = "estado-injustificada";
                    $iconeEstado = "circle-x";

                    if ($estado === "aprovado") {
                      $textoEstado = "Justificada";
                      $classeEstado = "estado-justificada";
                      $iconeEstado = "circle-check";
                    }
                    
                    $dataInicio = date('d/m/Y', strtotime($row->dataInicio)) . ' às ' . date('H:i', strtotime($row->dataInicio));
                    $dataFim    = date('d/m/Y', strtotime($row->dataFim))    . ' às ' . date('H:i', strtotime($row->dataFim));
                    $faltaIdFuncionario = $row->idFuncionario;
                    ?>
                    
                    <?php if ($idArea === '13') { ?>
                    <tr
                    data-id-falta="<?php echo (int)$row->idFaltas; ?>"
                    data-data-inicio="<?php echo htmlspecialchars($dataInicio); ?>"
                    data-data-fim="<?php echo htmlspecialchars($dataFim); ?>"
                    data-colaborador="<?php echo htmlspecialchars($row->colaborador); ?>"
                    data-area="<?php echo htmlspecialchars($row->area); ?>"
                    data-tipo="<?php echo htmlspecialchars($row->tipoFalta); ?>"
                    data-motivo="<?php echo htmlspecialchars($row->motivoFalta); ?>"
                    data-estado="<?php echo htmlspecialchars($textoEstado); ?>">
                    
                    <td><?php echo htmlspecialchars($dataInicio); ?></td>
                    <td><?php echo htmlspecialchars($dataFim); ?></td>
                    <td><?php echo htmlspecialchars($row->colaborador); ?></td>
                    <td><?php echo htmlspecialchars($row->area); ?></td>
                    <td><?php echo htmlspecialchars($row->tipoFalta); ?></td>
                    <td><?php echo htmlspecialchars($row->motivoFalta); ?></td>
                    <td class="estado-texto <?php echo $classeEstado; ?>">
                      <?php echo htmlspecialchars($textoEstado); ?>
                    </td>
                    <td class="estado-icone">
                      <i data-lucide="<?php echo $iconeEstado; ?>"></i>
                    </td>
                    </tr>
                    <?php } else { ?>
                    <tr
                    data-id-falta="<?php echo (int)$row->idFaltas; ?>"
                    data-data-inicio="<?php echo htmlspecialchars($dataInicio); ?>"
                    data-data-fim="<?php echo htmlspecialchars($dataFim); ?>"
                    data-tipo="<?php echo htmlspecialchars($row->tipoFalta); ?>"
                    data-motivo="<?php echo htmlspecialchars($row->motivoFalta); ?>"
                    data-estado="<?php echo htmlspecialchars($textoEstado); ?>">
                    
                    <td><?php echo htmlspecialchars($dataInicio); ?></td>
                    <td><?php echo htmlspecialchars($dataFim); ?></td>
                    <td><?php echo htmlspecialchars($row->tipoFalta); ?></td>
                    <td><?php echo htmlspecialchars($row->motivoFalta); ?></td>
                    <td class="estado-texto <?php echo $classeEstado; ?>">
                      <?php echo htmlspecialchars($textoEstado); ?>
                    </td>
                    <td class="estado-icone">
                      <i data-lucide="<?php echo $iconeEstado; ?>"></i>
                    </td>
                  </tr>
                  <?php } ?>
                  <?php } ?>
                  <?php } else { ?>
                  <tr class="linha-vazia">
                    <td colspan="<?php echo ($idArea === '13') ? 8 : 6; ?>">
                      Não existem faltas registadas.
                    </td>
                  </tr>
                  <?php }?>
                  </tbody>  
                </table>
              </div>
            </section>

            <?php if ($idArea === '13') { ?>
            <section class="painel-faltas tab-panel" data-panel="pedidos">
              <div class="pesquisa-box">
                <label for="pesquisaPedidos">Pesquisa:</label>
                <div class="input-pesquisa">
                  <input type="text" id="pesquisaPedidos">
                  <span class="icone-lupa">
                    <i data-lucide="search"></i>
                  </span>
                </div>
              </div>

              <div class="tabela-wrapper">
                <table class="tabela-faltas" id="tabelaPedidos">
                  <thead>
                    <tr>
                      <th><strong>Colaborador</strong></th>
                      <th><strong>Area</strong></th>
                      <th><strong>Tipo</strong></th>
                      <th><strong>Data do Pedido</strong></th>
                      <th><strong>Estado</strong></th>
                      <th><strong>Ações</strong></th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if ($resPedidos === -1) { ?>
                    <tr class="linha-vazia">
                      <td colspan="6">Erro ao carregar os pedidos.</td>
                    </tr>
                    
                    <?php } elseif ($resPedidos && mysqli_num_rows($resPedidos) > 0) { ?>
                    <?php while ($rowPedido = mysqli_fetch_object($resPedidos)) { ?>
                    <?php
                    $estadoPedido = strtolower(trim($rowPedido->estado));
                    $textoEstadoPedido = ucfirst($estadoPedido);
                    $classeEstadoPedido = "estado-pendente";

                    if ($estadoPedido === "aprovado") {
                      $classeEstadoPedido = "estado-justificada";
                    } elseif ($estadoPedido === "rejeitado") {
                      $classeEstadoPedido = "estado-injustificada";
                    }

                    $dataPedido = date('d-m-Y H:i', strtotime($rowPedido->dataPedido));
                    $dataInicioPedido = date('d/m/Y', strtotime($rowPedido->dataInicio)) . ' às ' . date('H:i', strtotime($rowPedido->dataInicio));
                    $dataFimPedido    = date('d/m/Y', strtotime($rowPedido->dataFim))    . ' às ' . date('H:i', strtotime($rowPedido->dataFim));
                    ?>

                    <tr
                    class="linha-pedido"
                    data-id-falta="<?php echo (int)$rowPedido->idFaltas; ?>"
                    data-data-inicio="<?php echo htmlspecialchars($dataInicioPedido); ?>"
                    data-data-fim="<?php echo htmlspecialchars($dataFimPedido); ?>"
                    data-colaborador="<?php echo htmlspecialchars($rowPedido->colaborador); ?>"
                    data-area="<?php echo htmlspecialchars($rowPedido->area ?? ''); ?>"
                    data-tipo="<?php echo htmlspecialchars($rowPedido->tipoFalta); ?>"
                    data-motivo="<?php echo htmlspecialchars($rowPedido->motivoFalta); ?>"
                    data-estado="<?php echo htmlspecialchars($textoEstadoPedido); ?>"
                    data-justificacao="<?php echo htmlspecialchars($rowPedido->motivo ?? ''); ?>"
                    data-comprovativo="<?php echo htmlspecialchars($rowPedido->comprovativo ?? ''); ?>"
                    data-comentario-rh="<?php echo htmlspecialchars($rowPedido->comentarioRH ?? ''); ?>">

                    <td><?php echo htmlspecialchars($rowPedido->colaborador); ?></td>
                    <td><?php echo htmlspecialchars($rowPedido->area); ?></td>
                    <td><?php echo htmlspecialchars($rowPedido->tipoFalta); ?></td>
                    <td><?php echo htmlspecialchars($dataPedido); ?></td>
                    <td class="estado-texto <?php echo $classeEstadoPedido; ?>">
                      <?php echo htmlspecialchars($textoEstadoPedido); ?>
                    </td>
                    <td>
                      <button type="button" class="btn-aceitar">Ver pedido</button>
                    </td>
                    </tr>
                    <?php } ?>

                    <?php } else { ?>
                    <tr class="linha-vazia">
                      <td colspan="6">Não existem pedidos.</td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </section>
            <?php } ?>
          </section>

          <dialog id="modalJustificacao" class="modal-justificacao">
            <form method="post" action="db/pedidoFalta.php" class="modal-conteudo" enctype="multipart/form-data">
              <div class="modal-header">
                <h3>Justificação de Falta</h3>
                <button type="button" id="fecharModal" class="fechar-modal" aria-label="Fechar">
                  <i data-lucide="x"></i>
                </button>
              </div>

              <input type="hidden" name="idFaltas" id="idFaltaModal">

              <div class="tabela-detalhes-wrapper">
                <table class="tabela-detalhes-falta">
                  <tbody>
                    <tr>
                      <th>Data</th>
                      <td id="modalData"></td>
                    </tr>
                    <tr>
                      <th>Tipo</th>
                      <td id="modalTipo"></td>
                    </tr>
                    <tr>
                      <th>Motivo</th>
                      <td id="modalMotivo"></td>
                      <th>Estado</th>
                      <td id="modalEstado"></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="campo-form">
                <label for="textoJustificacao">Descrição</label>
                <textarea id="textoJustificacao" name="justificacao" rows="6"></textarea>
              </div>
              <div class="campo-form">
                <label for="ficheiroComprovativo">Anexos</label>
                <input type="file" id="ficheiroComprovativo" name="comprovativo" required>
              </div>

              <?php if ($idFuncionario === $faltaIdFuncionario) { ?>  
              <div class="modal-acoes">
                <button type="button" class="btn-cancelar" id="cancelarModal">Cancelar</button>
                <button type="submit" class="btn-justificar">Justificar</button>
              </div>
              <?php } ?>
            </form>
          </dialog>

          
          <dialog id="modalPedido" class="modal-justificacao">
            <div class="modal-conteudo">
              <div class="modal-header">
                <h3>Análise do Pedido</h3>
                <button type="button" id="fecharModalPedido" class="fechar-modal" aria-label="Fechar">
                  <i data-lucide="x"></i>
                </button>
              </div>

              <div class="tabela-detalhes-wrapper">
                <table class="tabela-detalhes-falta">
                  <tbody>
                    <tr>
                      <th>Data</th>
                      <td id="pedidoModalData"></td>
                      <th>Colaborador</th>
                      <td id="pedidoModalColaborador"></td>
                    </tr>
                    <tr>
                      <th>Area</th>
                      <td id="pedidoModalArea"></td>
                      <th>Tipo</th>
                      <td id="pedidoModalTipo"></td>
                    </tr>
                    <tr>
                      <th>Motivo</th>
                      <td id="pedidoModalMotivo"></td>
                      <th>Estado</th>
                      <td id="pedidoModalEstado"></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="campo-form">
                <label for="pedidoModalJustificacao">Justificação</label>
                <textarea id="pedidoModalJustificacao" rows="5" readonly></textarea>
              </div>
              
              <div class="campo-form">
                <label>Comprovativo</label>
                <div id="pedidoModalComprovativo"></div>
              </div>

              <div class="campo-form">
                <label for="pedidoModalComentario">Comentário RH</label>
                <textarea id="pedidoModalComentario" rows="4"></textarea>
              </div>

              <div class="modal-acoes">
                <form method="post" action="db/rejeitarPedidoFalta.php" id="formRejeitarPedido">
                  <input type="hidden" name="idFaltas" id="pedidoRejeitarIdFalta">
                  <input type="hidden" name="comentarioRH" id="pedidoRejeitarComentarioRH">
                  <button type="submit" class="btn-cancelar" onclick='return confirmarRejeitar()'>Rejeitar</button>
                </form>

                <form method="post" action="db/aceitarPedidoFalta.php" id="formAceitarPedido">
                  <input type="hidden" name="idFaltas" id="pedidoAceitarIdFalta">
                  <input type="hidden" name="comentarioRH" id="pedidoAceitarComentarioRH">
                  <button type="submit" class="btn-justificar" onclick='return confirmarAceitar()'>Aceitar</button>
                </form>
              </div>
            </div>
          </dialog>
          <script>
            document.addEventListener("DOMContentLoaded", function () {
              if (window.lucide) {
                lucide.createIcons();
              }
              
              const tabButtons = document.querySelectorAll(".tab-item[data-tab]");
              const tabPanels = document.querySelectorAll(".tab-panel[data-panel]");

              const inputPesquisaFaltas = document.getElementById("pesquisaFalta");
              const linhasFaltas = Array.from(document.querySelectorAll("#tabelaFaltas tbody tr"));

              const inputPesquisaPedidos = document.getElementById("pesquisaPedidos");
              const linhasPedidos = Array.from(document.querySelectorAll("#tabelaPedidos tbody tr"));
              const modalPedido = document.getElementById("modalPedido");
              const fecharModalPedido = document.getElementById("fecharModalPedido");
              
              const pedidoModalData = document.getElementById("pedidoModalData");
              const pedidoModalColaborador = document.getElementById("pedidoModalColaborador");
              const pedidoModalArea = document.getElementById("pedidoModalArea");
              const pedidoModalTipo = document.getElementById("pedidoModalTipo");
              const pedidoModalMotivo = document.getElementById("pedidoModalMotivo");
              const pedidoModalEstado = document.getElementById("pedidoModalEstado");
              const pedidoModalJustificacao = document.getElementById("pedidoModalJustificacao");
              const pedidoModalComprovativo = document.getElementById("pedidoModalComprovativo");
              const pedidoModalComentario = document.getElementById("pedidoModalComentario");
              const pedidoAceitarIdFalta = document.getElementById("pedidoAceitarIdFalta");
              const pedidoRejeitarIdFalta = document.getElementById("pedidoRejeitarIdFalta");
              const pedidoAceitarComentarioRH = document.getElementById("pedidoAceitarComentarioRH");
              const pedidoRejeitarComentarioRH = document.getElementById("pedidoRejeitarComentarioRH");

              const idFaltaModal = document.getElementById("idFaltaModal");
              const modal = document.getElementById("modalJustificacao");
              const modalData = document.getElementById("modalData");
              const modalTipo = document.getElementById("modalTipo");
              const modalMotivo = document.getElementById("modalMotivo");
              const modalEstado = document.getElementById("modalEstado");
              const fecharModal = document.getElementById("fecharModal");
              const cancelarModal = document.getElementById("cancelarModal");

              tabButtons.forEach(function (button) {
                button.addEventListener("click", function () {
                  const target = this.dataset.tab;

                  tabButtons.forEach(function (btn) {
                    btn.classList.remove("active");
                  });

                  tabPanels.forEach(function (panel) {
                    panel.classList.remove("active");
                  });

                  this.classList.add("active");

                  const painel = document.querySelector('.tab-panel[data-panel="' + target + '"]');
                  if (painel) {
                    painel.classList.add("active");
                  }
                });
              });

              if (inputPesquisaFaltas) {
                inputPesquisaFaltas.addEventListener("input", function () {
                  const termo = this.value.toLowerCase().trim();

                  linhasFaltas.forEach(function (linha) {
                    const texto = linha.textContent.toLowerCase();
                    linha.style.display = texto.includes(termo) ? "" : "none";
                  });
                });
              }

              if (inputPesquisaPedidos) {
                inputPesquisaPedidos.addEventListener("input", function () {
                  const termo = this.value.toLowerCase().trim();

                  linhasPedidos.forEach(function (linha) {
                    const texto = linha.textContent.toLowerCase();
                    linha.style.display = texto.includes(termo) ? "" : "none";
                  });
                });
              }
              
              const botoesVerPedido = document.querySelectorAll("#tabelaPedidos .btn-aceitar");

              botoesVerPedido.forEach(function (botao) {
                botao.addEventListener("click", function (event) {
                  event.stopPropagation();

                  const linha = this.closest("tr");

                  if (!linha || linha.classList.contains("linha-vazia") || !linha.dataset.idFalta || !modalPedido) {
                    return;
                  }

                  pedidoModalData.textContent = (linha.dataset.dataInicio || "") + " até " + (linha.dataset.dataFim || "");
                  pedidoModalColaborador.textContent = linha.dataset.colaborador || "-";
                  pedidoModalArea.textContent = linha.dataset.area || "-";
                  pedidoModalTipo.textContent = linha.dataset.tipo || "-";
                  pedidoModalMotivo.textContent = linha.dataset.motivo || "-";
                  pedidoModalEstado.textContent = linha.dataset.estado || "-";
                  pedidoModalJustificacao.value = linha.dataset.justificacao || "";
                  pedidoModalComentario.value = linha.dataset.comentarioRh || "";

                  pedidoAceitarIdFalta.value = linha.dataset.idFalta;
                  pedidoRejeitarIdFalta.value = linha.dataset.idFalta;
                  pedidoAceitarComentarioRH.value = linha.dataset.comentarioRh || "";
                  pedidoRejeitarComentarioRH.value = linha.dataset.comentarioRh || "";

                  if (linha.dataset.comprovativo) {
                    pedidoModalComprovativo.innerHTML =
                      '<a href="' + linha.dataset.comprovativo + '" target="_blank" rel="noopener noreferrer">Abrir comprovativo</a>';
                  } else {
                    pedidoModalComprovativo.textContent = "Sem comprovativo.";
                  }

                  modalPedido.showModal();
                });
              });

              linhasFaltas.forEach(function (linha) {
                linha.addEventListener("click", function () {
                  if (this.classList.contains("linha-vazia") || !this.dataset.dataInicio || !modal) {
                    return;
                  }

                  const estadoFalta = (this.dataset.estado || "").toLowerCase().trim();

                  if (estadoFalta === "justificada") {
                    return;
                  }

                  modalData.textContent = (this.dataset.dataInicio || "") + " até " + (this.dataset.dataFim || "");
                  modalTipo.textContent = this.dataset.tipo || "-";
                  modalMotivo.textContent = this.dataset.motivo || "-";
                  modalEstado.textContent = this.dataset.estado || "-";
                  idFaltaModal.value = this.dataset.idFalta || "";

                  modal.showModal();
                });
              });
              
              if (pedidoModalComentario) {
                pedidoModalComentario.addEventListener("input", function () {
                  pedidoAceitarComentarioRH.value = this.value;
                  pedidoRejeitarComentarioRH.value = this.value;
                });
              }

              function fecharJustificacao() {
                if (modal) {
                  modal.close();
                }
              }

              if (fecharModal) {
                fecharModal.addEventListener("click", fecharJustificacao);
              }

              if (cancelarModal) {
                cancelarModal.addEventListener("click", fecharJustificacao);
              }
              
              if (fecharModalPedido && modalPedido) {
                fecharModalPedido.addEventListener("click", function () {
                  modalPedido.close();
                });
              }
            });

            function confirmarAceitar(){
              return confirm("Tem a certeza que quer Aceitar este Pedido?");
            }

            function confirmarRejeitar(){
              return confirm("Tem a certeza que quer Rejeitar este Pedido?");
            }      
          </script>
          <script src="js/layout.js"></script>
        </main>
      </div>
    </div>
  </body>
</html>