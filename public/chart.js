document.addEventListener("DOMContentLoaded", function() {
  const ctxMoisture = document.getElementById('moistureChart');
  new Chart(ctxMoisture, {
    type: 'line',
    data: {
      labels: ['6AM', '9AM', '12PM', '3PM', '6PM'],
      datasets: [{
        label: 'Moisture (%)',
        data: [40, 45, 43, 38, 50],
        borderColor: '#4CAF50',
        tension: 0.3,
        fill: false
      }]
    },
    options: { responsive: true }
  });

  const ctxWater = document.getElementById('waterChart');
  new Chart(ctxWater, {
    type: 'doughnut',
    data: {
      labels: ['Used', 'Remaining'],
      datasets: [{
        data: [30, 70],
        backgroundColor: ['#4CAF50', '#E0E0E0']
      }]
    },
    options: {
      responsive: true,
      cutout: '70%'
    }
  });
});

