<?php
require_once("db/auth.php");

$seccao = "Férias";
$paginaAtual = "Ferias";
$tituloPagina = "Marcação de Férias";
$subtituloPagina = "Consulta e marcação de ferias";
?>
<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
  <title>Marcação de Férias</title>
  <link rel="stylesheet" href="ferias.css" />
</head>
<body>
  <div class="app">
    <?php include_once("sidebar.php"); ?>
    <div class="main-wrapper">
      <?php include_once("navbar.php"); ?>
      <main class="main-content" id="main-content">
        <div class="ferias-wrap">
          <div class="ferias-head">
            <div class="month-nav">
              <button type="button" id="prevMonth" aria-label="Mês anterior">&#8249;</button>
              <div class="month-label" id="monthLabel">Junho de 2026</div>
              <button type="button" id="nextMonth" aria-label="Mês seguinte">&#8250;</button>
              </div>
            </div>
            <form id="feriasForm" method="post" action="db/pedidoFerias.php">
              <div class="ferias-grid">
                <aside class="info-panel">
                  <div class="stat-box">
                    <span class="stat-label">Dias selecionados</span>
                    <strong class="stat-value" id="selectedCount">0</strong>
                    <span class="stat-sub">Seleciona o início e o fim.</span>
                  </div>
                  <div class="stat-box">
                    <span class="stat-label">Primeiro dia</span>
                    <strong class="stat-value" id="firstDay">—</strong>
                  </div>
                  <div class="stat-box">
                    <span class="stat-label">Último dia</span>
                    <strong class="stat-value" id="lastDay">—</strong>
                  </div>
                  <div class="legend-box">
                    <div class="legend-item"><span class="dot start"></span> Início</div>
                    <div class="legend-item"><span class="dot range"></span> Intervalo</div>
                    <div class="legend-item"><span class="dot end"></span> Fim</div>
                    <div class="legend-item"><span class="dot blocked"></span> Bloqueado</div>
                  </div>
                  <div class="form-row">
                    <button type="button" class="btn btn-ghost" id="clearSelection">Limpar</button>
                    <button type="submit" class="btn btn-primary" id="submitSelection">Submeter</button>
                  </div>
                </aside>

                <input type="hidden" name="dataInicio" id="dataInicio">
                <input type="hidden" name="dataFim" id="dataFim">
                
                <section class="calendar-panel">
                  <div class="calendar-top">
                    <div>
                      <strong>Calendário</strong>
                      <span>Selecciona apenas dias úteis.</span>
                    </div>
                  </div>
                  <div class="calendar" id="calendarGrid" aria-label="Calendário de férias"></div>
                </section>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
  <script src="js/layout.js"></script>
  <script>
    const monthLabel=document.getElementById('monthLabel');
    const calendarGrid=document.getElementById('calendarGrid');
    const selectedCount=document.getElementById('selectedCount');
    const firstDay=document.getElementById('firstDay');
    const lastDay=document.getElementById('lastDay');
    const prevMonth=document.getElementById('prevMonth');
    const nextMonth=document.getElementById('nextMonth');
    const clearSelection=document.getElementById('clearSelection');
    const inputDataInicio=document.getElementById('dataInicio');
    const inputDataFim=document.getElementById('dataFim');
    const feriasForm=document.getElementById('feriasForm');

    let currentDate=new Date();
    currentDate.setDate(1);
    let rangeStart=null;
    let rangeEnd=null;
    const selectedDates=new Set();

    function fmtISO(date){ return new Date(date.getTime()-date.getTimezoneOffset()*60000).toISOString().slice(0,10); }
    function formatPt(dateString){ return new Date(dateString+'T00:00:00').toLocaleDateString('pt-PT',{day:'2-digit',month:'2-digit',year:'numeric'}); }
    function isWeekend(date){ const d=date.getDay(); return d===0||d===6; }

    function isPastOrToday(date){
      const hoje = new Date();
      hoje.setHours(0, 0, 0, 0);

      const dataComparar = new Date(date);
      dataComparar.setHours(0, 0, 0, 0);

      return dataComparar <= hoje;
    }

    function updateSummary(){
      const s=Array.from(selectedDates).sort();
      selectedCount.textContent=s.length;
      firstDay.textContent=s.length?formatPt(s[0]):'—';
      lastDay.textContent=s.length?formatPt(s[s.length-1]):'—';
    }
    function clearRange(){
      rangeStart=null; rangeEnd=null; selectedDates.clear(); updateSummary(); renderCalendar();
    }
    function setRange(a,b){
      selectedDates.clear();
      let s=new Date(a+'T00:00:00');
      let e=new Date(b+'T00:00:00');
      if(e<s){ const t=s; s=e; e=t; }
      const cur=new Date(s);
      while(cur<=e){
        if(!isWeekend(cur) && !isPastOrToday(cur)) {
          selectedDates.add(fmtISO(cur));
        }
        cur.setDate(cur.getDate()+1);
      }
      updateSummary();
    }
    function onDayClick(dateStr){
      const date=new Date(dateStr+'T00:00:00');

      if (isWeekend(date) || isPastOrToday(date)) {
        return;
      }

      if (!rangeStart || rangeEnd) {
        rangeStart = dateStr;
        rangeEnd = null;
        setRange(rangeStart, rangeStart);
      } else {
        rangeEnd = dateStr;
        setRange(rangeStart, rangeEnd);
      }

      renderCalendar();
    }

    function dayButton(day,dateStr,blocked,selected,start,end,inRange){
      const btn=document.createElement('button');
      btn.type='button';
      btn.className='day' + (blocked?' weekend':'') + (selected?' selected':'') + (start?' range-start':'') + (end?' range-end':'') + (inRange?' in-range':'');
      btn.innerHTML=`<strong>${day}</strong><small>${blocked?'Bloq.':''}</small>`;
      btn.setAttribute('aria-label',`Dia ${day}${blocked?' bloqueado':''}`);
      if(!blocked) btn.addEventListener('click',()=>onDayClick(dateStr));
      return btn;
    }
    function renderCalendar(){
      calendarGrid.innerHTML='';
      ['Seg','Ter','Qua','Qui','Sex','Sáb','Dom'].forEach(l=>{
        const el=document.createElement('div');
        el.className='weekday';
        el.textContent=l;
        calendarGrid.appendChild(el);
      });
      const year=currentDate.getFullYear();
      const month=currentDate.getMonth();
      monthLabel.textContent=currentDate.toLocaleDateString('pt-PT',{month:'long',year:'numeric'});
      const first=new Date(year,month,1);
      const startOffset=(first.getDay()+6)%7;
      const daysInMonth=new Date(year,month+1,0).getDate();
      for(let i=0;i<startOffset;i++){
        const empty=document.createElement('div');
        empty.className='day empty';
        calendarGrid.appendChild(empty);
      }
      const rs=rangeStart?new Date(rangeStart+'T00:00:00'):null;
      const re=rangeEnd?new Date(rangeEnd+'T00:00:00'):null;
      for(let day=1;day<=daysInMonth;day++){
        const date=new Date(year,month,day);
        const blocked = isWeekend(date) || isPastOrToday(date);
        const iso=fmtISO(date);
        const selected=selectedDates.has(iso);
        let start=false,end=false,inRange=false;

        if (rs && re) {
          const a = rs.getTime();
          const b = re.getTime();
          const c = date.getTime();
          if (a <= b) {
            start = (c === a) && !blocked;
            end = (c === b) && !blocked;
            inRange = (c > a && c < b) && !blocked;
          } else {
            start = (c === b) && !blocked;
            end = (c === a) && !blocked;
            inRange = (c > b && c < a) && !blocked;
          }
        } else if (rs && !re) {
          start = (iso === rangeStart) && !blocked;
        }
        calendarGrid.appendChild(dayButton(day,iso,blocked,selected,start,end,inRange));
      }
    }

    prevMonth.addEventListener('click',()=>{ currentDate=new Date(currentDate.getFullYear(),currentDate.getMonth()-1,1); renderCalendar(); });
    nextMonth.addEventListener('click',()=>{ currentDate=new Date(currentDate.getFullYear(),currentDate.getMonth()+1,1); renderCalendar(); });
    clearSelection.addEventListener('click',clearRange);
    feriasForm.addEventListener('submit', (event) => {
      if (!selectedDates.size) {
        event.preventDefault();
        alert('Escolhe pelo menos um dia útil');
        return;
      }

      const sorted = Array.from(selectedDates).sort();
      inputDataInicio.value = sorted[0];
      inputDataFim.value = sorted[sorted.length - 1];
    });
    window.addEventListener('load',()=>{ renderCalendar(); updateSummary(); });
  </script>
</body>
</html>