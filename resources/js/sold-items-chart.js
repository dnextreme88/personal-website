// Chart.js bar charts for the Sold Items Summary. The summary is a fixed
// whole-collection overview (independent of the filters), so each chart is
// drawn once from the JSON embedded on its canvas and never needs updating.
// Every canvas sits in a `wire:ignore` container so Livewire re-renders (from
// filtering/sorting/pagination) never clobber the drawn charts.
import Chart from 'chart.js/auto';

const charts = new Map(); // canvas id -> Chart instance

// A summary canvas may still be `display:none` (0×0) when this runs (e.g. behind
// a transition). Defer until it has real size, or Chart.js caches a 0×0 canvas.
function whenSized(el, callback, tries = 30) {
  if ((el.parentElement && el.parentElement.offsetWidth > 0) || tries <= 0) {
    callback();

    return;
  }

  requestAnimationFrame(() => whenSized(el, callback, tries - 1));
}

function formatValue(value, money) {
  const number = Number(value).toLocaleString();

  return money ? `₱ ${number}` : number;
}

function buildConfig(spec) {
  const isDark = document.documentElement.classList.contains('dark');
  const axisColor = isDark ? '#e5e7eb' : '#374151';
  const gridColor = isDark ? '#374151' : '#e5e7eb';

  return {
    type: spec.type || 'bar',
    data: {
      labels: spec.labels,
      datasets: [
        {
          data: spec.data,
          borderColor: '#0ea5e9', // sky-500
          backgroundColor: '#7dd3fc', // sky-300 (light blue)
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            // For "top brand / top item" charts, lead with the name (spec.meta).
            label: context => {
              const value = formatValue(context.parsed.y, spec.money);

              return spec.meta ? `${spec.meta[context.dataIndex]}: ${value}` : value;
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { color: axisColor, callback: value => formatValue(value, spec.money) },
          grid: { color: gridColor },
        },
        x: {
          ticks: { color: axisColor },
          grid: { display: false },
        },
      },
    },
  };
}

function initChart(canvas) {
  if (charts.has(canvas.id)) {
    charts.get(canvas.id).destroy();
    charts.delete(canvas.id);
  }

  let spec;

  try {
    spec = JSON.parse(canvas.dataset.chart || 'null');
  } catch {
    spec = null;
  }

  if (!spec) {
    return;
  }

  whenSized(canvas, () => {
    charts.set(canvas.id, new Chart(canvas, buildConfig(spec)));
  });
}

function initAll() {
  document.querySelectorAll('canvas[data-chart]').forEach(initChart);
}

// Fires on the initial load and on every wire:navigate transition.
document.addEventListener('livewire:navigated', initAll);

// Cover the case where this module finishes loading after the page is ready.
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initAll);
} else {
  initAll();
}
