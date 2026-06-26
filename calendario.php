<?php
require_once("db/auth.php");
include_once("db/dataAccess.php");

$idFuncionario = $_SESSION['idFuncionario'];
$idArea = $_SESSION['idArea'];

$da = new dataAccess();
$res = $da->getFerias($idArea, $cargo, $idFuncionario);

$ferias = [];

if ($res === -1) {
    echo "<script>alert('Erro ao carregar Férias!')</script>";
} else {
  while ($row = mysqli_fetch_assoc($res)) {
    $ferias[] = [
      'nome' => $row['nome'],
      'dataInicio' => date('Y-m-d', strtotime($row['dataInicio'])),
      'dataFim' => date('Y-m-d', strtotime($row['dataFim']))
    ];
  }
}

$seccao = "Férias";
$paginaAtual = "CalendarioFerias";
$tituloPagina = "Calendário de Férias";
?>
<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendário das Férias</title>
  <link rel="stylesheet" href="ferias.css" />
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
  <div class="app">
    <?php include_once("sidebar.php"); ?>
    <div class="main-wrapper">
      <?php include_once("navbar.php"); ?>
      
      <main class="main-content" id="main-content">
        <section class="cf-page">
          <div class="cf-head">
            <div class="cf-month-nav">
              <button type="button" class="cf-month-btn" id="prevMonth" aria-label="Mês anterior">&#8249;</button>
              <div class="cf-month-label" id="monthLabel">Junho de 2026</div>
              <button type="button" class="cf-month-btn" id="nextMonth" aria-label="Mês seguinte">&#8250;</button>
            </div>
          </div>

          <div class="cf-layout">
            <aside class="cf-sidebar">
              <div class="cf-stat-box">
                <span class="cf-stat-label">Dias marcados no mês</span>
                <strong class="cf-stat-value" id="selectedCount">0</strong>
                <span class="cf-stat-sub">Total de dias com férias visíveis neste mês.</span>
              </div>
              <div class="cf-stat-box">
                <span class="cf-stat-label">Primeiro dia</span>
                <strong class="cf-stat-value" id="firstDay">—</strong>
              </div>
              <div class="cf-stat-box">
                <span class="cf-stat-label">Último dia</span>
                <strong class="cf-stat-value" id="lastDay">—</strong>
              </div>
              <div class="cf-legend-box">
                <div class="cf-legend-item"><span class="cf-dot cf-dot--approved"></span> Aprovado</div>
                <div class="cf-legend-item"><span class="cf-dot cf-dot--weekend"></span> Fim de semana</div>
              </div>
            </aside>

            <section class="cf-calendar-panel">
              <div class="cf-calendar" id="calendarGrid" aria-label="Calendário de férias"></div>
            </section>
          </div>
        </section>
      </main>
    </div>
  </div>
  
  <script src="js/layout.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
    const monthLabel = document.getElementById('monthLabel');
    const calendarGrid = document.getElementById('calendarGrid');
    const selectedCount = document.getElementById('selectedCount');
    const firstDay = document.getElementById('firstDay');
    const lastDay = document.getElementById('lastDay');
    const prevMonth = document.getElementById('prevMonth');
    const nextMonth = document.getElementById('nextMonth');

    const ferias = <?php echo json_encode($ferias, JSON_UNESCAPED_UNICODE); ?>;

    if (!monthLabel || !calendarGrid || !selectedCount || !firstDay || !lastDay || !prevMonth || !nextMonth) {
      console.error('Elementos do calendário em falta no HTML.');
      return;
    }

    let currentDate = new Date();
    currentDate.setDate(1);

    function isWeekend(date) {
      const d = date.getDay();
      return d === 0 || d === 6;
    }

    function formatDate(date) {
      const y = date.getFullYear();
      const m = String(date.getMonth() + 1).padStart(2, '0');
      const d = String(date.getDate()).padStart(2, '0');
      return `${y}-${m}-${d}`;
    }

    function diaTemFerias(date) {
      if (isWeekend(date)) {
        return false;
      }

      const dataAtual = formatDate(date);
      
      return ferias.some(item => {
        return dataAtual >= item.dataInicio && dataAtual <= item.dataFim;
      });
    }

    function buildDayButton(day, weekend, marcado) {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'cf-day';

      if (weekend) btn.classList.add('is-weekend');
      if (marcado) btn.classList.add('is-approved');

      btn.innerHTML = `<strong>${day}</strong><small>${marcado ? 'Férias' : (weekend ? 'Bloq.' : '')}</small>`;
      return btn;
    }

    function renderCalendar() {
      calendarGrid.innerHTML = '';

      ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'].forEach(label => {
        const weekday = document.createElement('div');
        weekday.className = 'cf-weekday';
        weekday.textContent = label;
        calendarGrid.appendChild(weekday);
      });

      const year = currentDate.getFullYear();
      const month = currentDate.getMonth();
      monthLabel.textContent = currentDate.toLocaleDateString('pt-PT', { month: 'long', year: 'numeric' });

      const first = new Date(year, month, 1);
      const startOffset = (first.getDay() + 6) % 7;
      const daysInMonth = new Date(year, month + 1, 0).getDate();

      for (let i = 0; i < startOffset; i++) {
        const empty = document.createElement('div');
        empty.className = 'cf-day cf-day--empty';
        calendarGrid.appendChild(empty);
      }

      const diasMarcados = [];

      for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        const weekend = isWeekend(date);
        const marcado = diaTemFerias(date);

        if (marcado) {
          diasMarcados.push(new Date(date));
        }

        calendarGrid.appendChild(buildDayButton(day, weekend, marcado));
      }

      selectedCount.textContent = String(diasMarcados.length);
      firstDay.textContent = diasMarcados.length ? diasMarcados[0].toLocaleDateString('pt-PT') : '—';
      lastDay.textContent = diasMarcados.length ? diasMarcados[diasMarcados.length - 1].toLocaleDateString('pt-PT') : '—';
    }

    prevMonth.addEventListener('click', () => {
      currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
      renderCalendar();
    });

    nextMonth.addEventListener('click', () => {
      currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
      renderCalendar();
    });

    renderCalendar();

    if (window.lucide) {
      lucide.createIcons();
    }
  });
  </script>
</body>
</html>