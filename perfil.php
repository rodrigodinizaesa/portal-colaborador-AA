<?php
require_once("db/auth.php");
include_once("db/dataAccess.php");

$idFuncionario = $_SESSION['idFuncionario'];
$da = new dataAccess();
$res = $da->getDadosFiscais($idFuncionario);


$paginaAtual = "Perfil";
$tituloPagina = "Dados Pessoais";
$subtituloPagina = "Consulta os teus Dados Pessoais";
?>
<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
  <title>Perfil</title>
  <link rel="stylesheet" href="ferias.css" />
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
  <div class="app">
    <?php include_once("sidebar.php"); ?>
    <div class="main-wrapper">
      <?php include_once("navbar.php"); ?>
      <main class="main-content" id="main-content">

        <form method="post" action="db/pedidoFiscal.php" id="formPerfil" class="pf-card" novalidate enctype="multipart/form-data">
          <div class="pf-section">
            <h2 class="pf-section-title">Dados Pessoais</h2>
            <div class="pf-grid">
              <div class="pf-field pf-field-full">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($res->nome); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="nif">NIF</label>
                <input type="text" id="nif" name="nif"
                  value="<?php echo htmlspecialchars($res->nif); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="dataNascimento">Data de Nascimento</label>
                <input type="date" id="dataNascimento" name="dataNascimento"
                  value="<?php echo htmlspecialchars($res->dataNascimento); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="sexo">Sexo</label>
                <select id="sexo" name="sexo" disabled>
                  <option value="Masculino" <?php echo ($res->sexo) === 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                  <option value="Feminino" <?php echo ($res->sexo) === 'Feminino' ? 'selected' : ''; ?>>Feminino</option>
                  <option value="Outro" <?php echo ($res->sexo) === 'Outro' ? 'selected' : ''; ?>>Outro</option>
                </select>
              </div>

              <div class="pf-field">
                <label for="nacionalidade">Nacionalidade</label>
                <input type="text" id="nacionalidade" name="nacionalidade"
                  value="<?php echo htmlspecialchars($res->nacionalidade); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="niss">NISS</label>
                <input type="text" id="niss" name="niss"
                  value="<?php echo htmlspecialchars($res->niss); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="iban">IBAN</label>
                <input type="text" id="iban" name="iban"
                  value="<?php echo htmlspecialchars($res->iban); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone"
                  value="<?php echo htmlspecialchars($res->telefone); ?>" readonly>
              </div>
            </div>
          </div>
          
          <!-- MORADA -->
          <div class="pf-section">
            <h2 class="pf-section-title">Endereço</h2>
            <div class="pf-grid">
              <div class="pf-field pf-field-full">
                <label for="morada">Morada</label>
                <input type="text" id="morada" name="morada" value="<?php echo htmlspecialchars($res->morada); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="codigoPostal">Código Postal</label>
                <input type="text" id="codigoPostal" name="codigoPostal" value="<?php echo htmlspecialchars($res->codigoPostal); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="distrito">Distrito</label>
                <input type="text" id="distrito" name="distrito" value="<?php echo htmlspecialchars($res->distrito); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="concelho">Concelho</label>
                <input type="text" id="concelho" name="concelho" value="<?php echo htmlspecialchars($res->concelho); ?>" readonly>
              </div>
              
              <div class="pf-field">
                <label for="freguesia">Freguesia</label>
                <input type="text" id="freguesia" name="freguesia" value="<?php echo htmlspecialchars($res->freguesia); ?>" readonly>
              </div>
            </div>
          </div>
          
          <!-- CONTACTO DE EMERGÊNCIA -->
          <div class="pf-section">
            <h2 class="pf-section-title">Contacto de Emergência</h2>
            <div class="pf-grid">
              <div class="pf-field">
                <label for="nomeEmergencia">Nome do Contacto</label>
                <input type="text" id="nomeEmergencia" name="contactoEmergenciaNome" value="<?php echo htmlspecialchars($res->contactoEmergenciaNome); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="telefoneEmergencia">Telefone do Contacto</label>
                <input type="text" id="telefoneEmergencia" name="contactoEmergenciaTelefone" value="<?php echo htmlspecialchars($res->contactoEmergenciaTelefone); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="grauParentesco">Grau de Parentesco</label>
                <select id="grauParentesco" name="contactoEmergenciaParentesco" disabled>
                <!-- ESTE MOSTRA O VALOR VINDO DA BD MAS NAO SELECIONA--><option value="<?= htmlspecialchars($res->contactoEmergenciaParentesco) ?>" selected hidden><?= htmlspecialchars($res->contactoEmergenciaParentesco) ?></option>
                  <!--<option value="<?= htmlspecialchars($res->contactoEmergenciaParentesco) ?>"><?= htmlspecialchars($res->contactoEmergenciaParentesco) ?></option>-->
                  <option value="Conjuge"    <?php echo ($res->contactoEmergenciaParentesco) === 'Conjuge'    ? 'selected' : ''; ?>>Cônjuge</option>
                  <option value="Pai"    <?php echo ($res->contactoEmergenciaParentesco) === 'Pai'    ? 'selected' : ''; ?>>Pai</option>
                  <option value="Mae"    <?php echo ($res->contactoEmergenciaParentesco) === 'Mae'    ? 'selected' : ''; ?>>Mãe</option>
                  <option value="Filho"<?php echo ($res->contactoEmergenciaParentesco) === 'Filho'? 'selected' : ''; ?>>Filho(a)</option>
                  <option value="Irmao_Irma" <?php echo ($res->contactoEmergenciaParentesco) === 'Irmao_Irma' ? 'selected' : ''; ?>>Irmão / Irmã</option>
                  <option value="Outro"      <?php echo ($res->contactoEmergenciaParentesco) === 'Outro'      ? 'selected' : ''; ?>>Outro</option>
                </select>
              </div>
            </div>
          </div>
          
          <!-- AGREGADO FAMILIAR -->
          <div class="pf-section">
            <h2 class="pf-section-title">Agregado Familiar</h2>
            <div class="pf-grid">
              
              <div class="pf-field">
                <label for="titulares">Titulares</label>
                <input type="number" id="titulares" name="titulares" min="0" value="<?php echo htmlspecialchars($res->titulares ?? '0'); ?>" readonly>
              </div>

              <div class="pf-field">
                <label for="dependentes">Dependentes</label>
                <input type="number" id="dependentes" name="dependentes" min="0" value="<?php echo htmlspecialchars($res->dependentes ?? '0'); ?>" readonly>
              </div>
            </div>
          </div>

          <!-- COMPROVATIVO -->
          <div class="pf-section" id="secaoComprovativo" hidden>
            <h2 class="pf-section-title">Comprovativo</h2>
            <div class="pf-grid">
              <div class="pf-field pf-field-full">
                <label for="comprovativo">Anexar Documento</label>
                <input type="file" id="comprovativo" name="comprovativo" disabled>
              </div>
            </div>
          </div>

          <!-- AÇÕES -->
          <div class="pf-actions">
            <button type="button" class="pf-btn pf-btn-secondary" id="btnEditar">
              <i data-lucide="square-pen"></i>
              Editar
            </button>
            <button type="button" class="pf-btn pf-btn-light" id="btnCancelar" hidden>
              <i data-lucide="x"></i>
              Cancelar
            </button>
            <button type="submit" class="pf-btn pf-btn-primary" id="btnConcluir" disabled hidden>
              <i data-lucide="check"></i>
              Concluir
            </button>
          </div>

        </form>
        <div id="pfToastContainer" class="pf-toast-container"></div>
      </main>
    </div>
  </div>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    if (window.lucide) {
      lucide.createIcons();
    }

    const form = document.getElementById("formPerfil");
    const btnEditar = document.getElementById("btnEditar");
    const btnCancelar = document.getElementById("btnCancelar");
    const btnConcluir = document.getElementById("btnConcluir");
    const campos = Array.from(form.querySelectorAll("input, select, textarea"));

    let modoEdicao = false;
    let valoresOriginais = {};

    function guardarValoresOriginais() {
      valoresOriginais = {};
      campos.forEach(campo => {
        if(campo.type === "file") return;
        valoresOriginais[campo.name] = campo.value;
      });
    }

    function reporValoresOriginais() {
      campos.forEach(campo => {
        if (campo.type === "file") {
          campo.value = "";
          return;
        }

        if (Object.prototype.hasOwnProperty.call(valoresOriginais, campo.name)) {
          campo.value = valoresOriginais[campo.name];
        }
      });
    }

    function actualizarVisibilidadeBotoes() {
      btnEditar.hidden = modoEdicao;
      btnCancelar.hidden = !modoEdicao;
      btnConcluir.hidden = !modoEdicao;
    }

    function activarEdicao(estado) {
      modoEdicao = estado;

      campos.forEach(campo => {
        if (campo.tagName === "SELECT" || campo.type === "file") {
          campo.disabled = !estado;
        } else {
          campo.readOnly = !estado;
        }
      });
      
      // Mostrar/esconder secção do comprovativo
      const secaoComprovativo = document.getElementById("secaoComprovativo");
      if (secaoComprovativo) {
        secaoComprovativo.hidden = !estado;
      }

      // Limpar ficheiro ao sair de edição
      if (!estado) {
        const inputFicheiro = document.getElementById("comprovativo");
        if (inputFicheiro) inputFicheiro.value = "";
      }

      actualizarVisibilidadeBotoes();

      if (!estado) {
        btnConcluir.disabled = true;
        btnConcluir.classList.remove("is-active");
      } else {
        actualizarBotaoConcluir();
      }
    }

    function existemAlteracoes() {
      return campos.some(campo => {

        if (campo.type === "file") {
          return campo.files && campo.files.length > 0;
        }
        return campo.value !== valoresOriginais[campo.name];
      });
    }

    function actualizarBotaoConcluir() {
      if (!modoEdicao) {
        btnConcluir.disabled = true;
        btnConcluir.classList.remove("is-active");
        return;
      }

      const alterado = existemAlteracoes();
      btnConcluir.disabled = !alterado;
      btnConcluir.classList.toggle("is-active", alterado);
    }

    function mostrarToast(mensagem) {
      const container = document.getElementById("pfToastContainer");
      const toast = document.createElement("div");

      toast.className = "pf-toast pf-toast-success";
      toast.textContent = mensagem;
      container.appendChild(toast);

      requestAnimationFrame(() => {
        toast.classList.add("show");
      });

      setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 300);
      }, 3500);
    }

    guardarValoresOriginais();
    activarEdicao(false);

    btnEditar.addEventListener("click", function () {
      guardarValoresOriginais();
      activarEdicao(true);
    });

    btnCancelar.addEventListener("click", function () {
      reporValoresOriginais();
      activarEdicao(false);
    });

    campos.forEach(campo => {
      campo.addEventListener("input", actualizarBotaoConcluir);
      campo.addEventListener("change", actualizarBotaoConcluir);
    });

    btnConcluir.addEventListener("click", function () {
        if (btnConcluir.disabled) {
            return;
        }
    });
});
</script>
<script src="js/layout.js"></script>
</body>
</html>