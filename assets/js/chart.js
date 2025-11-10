// Line Chart for Moisture Levels
const ctx = document.getElementById('moistureChart').getContext('2d');

const moistureChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
            label: 'Soil Moisture %',
            data: [30, 45, 60, 40, 50],
            borderColor: 'rgba(47, 153, 55, 1)',
            backgroundColor: 'rgba(33, 218, 101, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Line Chart for Water Usage 
const ctx2 = document.getElementById('waterUsageChart').getContext('2d');

const waterUsageChart = new Chart(ctx2, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
            label: 'Soil Moisture %',
            data: [30, 45, 60, 40, 50],
            borderColor: 'rgba(90, 153, 224, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Multi Line Chart for Temperature and Humidity
document.addEventListener('DOMContentLoaded', () => {
  const ctx3 = document.getElementById('tempHumChart').getContext('2d');

  new Chart(ctx3, {
    type: 'line',
    data: {
      labels: ['6 AM', '9 AM', '12 PM', '3 PM', '6 PM', '9 PM'],
      datasets: [
        {
          label: 'Temperature (°C)',
          data: [24, 28, 32, 31, 27, 25],  // replace with real data
          borderColor: 'rgba(255, 99, 132, 1)',
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          yAxisID: 'yTemp',
          fill: false,
          tension: 0.3
        },
        {
          label: 'Humidity (%)',
          data: [80, 75, 60, 65, 78, 85],  // replace with real data
          borderColor: 'rgba(54, 162, 235, 1)',
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          yAxisID: 'yHum',
          fill: false,
          tension: 0.3
        }
      ]
    },
    options: {
      responsive: true,
      interaction: {
        mode: 'index',
        intersect: false
      },
      stacked: false,
      plugins: {
        title: {
          display: true,
          text: 'Daily Temperature & Humidity Trend'
        }
      },
      scales: {
        yTemp: {
          type: 'linear',
          display: true,
          position: 'left',
          title: {
            display: true,
            text: 'Temperature (°C)'
          },
          beginAtZero: true,
          suggestedMax: 40
        },
        yHum: {
          type: 'linear',
          display: true,
          position: 'right',
          title: {
            display: true,
            text: 'Humidity (%)'
          },
          beginAtZero: true,
          suggestedMax: 100,
          grid: {
            drawOnChartArea: false  // so grid lines of humidity don’t clutter
          }
        },
        x: {
          title: {
            display: true,
            text: 'Time of Day'
          }
        }
      }
    }
  });
});

