async function loadMoistureChart() {
  const canvasEl = document.getElementById('moistureChart');
  if (!canvasEl) {
    console.error('moistureChart canvas not found');
    return;
  }
  
  const ctx = canvasEl.getContext('2d');

  try {
    console.log('🔄 Fetching moisture data from: /capstone-2025/functions/charts/get_daily_moisture.php');
    const response = await fetch('/capstone-2025/functions/charts/get_daily_moisture.php');
    console.log('📡 Moisture Response status:', response.status);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    console.log('✅ Moisture data received:', data);

    // If there's no data yet, skip drawing
    if (!data || data.length === 0) {
      console.warn("⚠️ No moisture data available for today");
      return;
    }

    const labels = data.map(entry => entry.hour);
    const values = data.map(entry => entry.avg_moisture);
    console.log('📊 Chart labels:', labels, 'values:', values);

    // Destroy old chart before drawing a new one
    if (window.moistureChartInstance) {
      window.moistureChartInstance.destroy();
    }

    window.moistureChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Soil Moisture (%)',
          data: values,
          borderColor: 'rgba(47, 153, 55, 1)',
          backgroundColor: 'rgba(33, 218, 101, 0.2)',
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: 'Daily Soil Moisture Trend'
          }
        },
        scales: {
          x: {
            title: {
              display: true,
              text: 'Time of Day'
            }
          },
          y: {
            beginAtZero: true,
            max: 100,
            title: {
              display: true,
              text: 'Moisture (%)'
            }
          }
        }
      }
    });

  } catch (error) {
    console.error('Error loading moisture chart:', error);
  }
}

document.addEventListener('DOMContentLoaded', loadMoistureChart);
setInterval(loadMoistureChart, 300000); // reload every 5 minutes


// Line Chart for Water Usage 
const waterCanvasEl = document.getElementById('waterUsageChart');
if (waterCanvasEl) {
  const ctx2 = waterCanvasEl.getContext('2d');

  const waterUsageChart = new Chart(ctx2, {
      type: 'line',
      data: {
          labels: ['wew', 'Feb', 'Mar', 'Apr', 'May'],
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
}

// Multi Line Chart for Temperature and Humidity
async function loadTempHumChart() {
  const canvasEl = document.getElementById('tempHumChart');
  if (!canvasEl) {
    console.error('tempHumChart canvas not found');
    return;
  }
  
  const ctx3 = canvasEl.getContext('2d');

  try {
    console.log('🔄 Fetching temp/humidity data from: /capstone-2025/functions/charts/get_daily_dht11.php');
    const response = await fetch('/capstone-2025/functions/charts/get_daily_dht11.php');
    console.log('📡 Temp/Humidity Response status:', response.status);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    console.log('✅ Temp/Humidity data received:', data);

    const labels = data.map(entry => entry.hour);
    const tempData = data.map(entry => entry.avg_temp);
    const humData = data.map(entry => entry.avg_humid);
    console.log('📊 Chart labels:', labels, 'temps:', tempData, 'humid:', humData);

    // If the chart already exists, destroy it before creating a new one
    if (window.tempHumChartInstance) {
      window.tempHumChartInstance.destroy();
    }

    window.tempHumChartInstance = new Chart(ctx3, {
      type: 'line',
      data: {
        labels,
        datasets: [
          {
            label: 'Temperature (°C)',
            data: tempData,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            yAxisID: 'yTemp',
            tension: 0.3
          },
          {
            label: 'Humidity (%)',
            data: humData,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            yAxisID: 'yHum',
            tension: 0.3
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: 'Daily Temperature & Humidity Trend'
          }
        },
        scales: {
          yTemp: {
            type: 'linear',
            position: 'left',
            title: { display: true, text: 'Temperature (°C)' }
          },
          yHum: {
            type: 'linear',
            position: 'right',
            title: { display: true, text: 'Humidity (%)' },
            grid: { drawOnChartArea: false }
          }
        }
      }
    });

  } catch (error) {
    console.error('Error loading chart data:', error);
  }
}

// Load the chart when the page is ready
document.addEventListener('DOMContentLoaded', loadTempHumChart);

// Refresh the chart data every 5 minutes (300000 ms)
setInterval(loadTempHumChart, 300000);

// Multi Line Chart for Gas and Ammonia
async function loadGasAmmoniaChart() {
  const canvasEl = document.getElementById('gasAmmoniaChart');
  if (!canvasEl) return;
  const ctx = canvasEl.getContext('2d');

  try {
    console.log('Fetching gas/ammonia data...');
    const response = await fetch('/capstone-2025/functions/charts/get_daily_gas_ammonia.php');
    const result = await response.json();
    console.log('✅ Received:', result);

    const gas = result.gas || [];
    const ammonia = result.ammonia || [];

    if (gas.length === 0 && ammonia.length === 0) {
      console.warn('⚠️ No data to display');
      return;
    }

    const gasLabels = gas.map(r => new Date(r.timestamp).toLocaleTimeString());
    const gasValues = gas.map(r => r.gas_percent);
    const ammoniaLabels = ammonia.map(r => new Date(r.timestamp).toLocaleTimeString());
    const ammoniaValues = ammonia.map(r => r.ammonia_value);

    const labels = gasLabels.length >= ammoniaLabels.length ? gasLabels : ammoniaLabels;

    if (window.gasAmmoniaChartInstance) window.gasAmmoniaChartInstance.destroy();

    window.gasAmmoniaChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [
          {
            label: 'CO₂ Gas (%)',
            data: gasValues,
            borderColor: 'rgba(220,38,38,1)',
            backgroundColor: 'rgba(220,38,38,0.2)',
            fill: true,
            tension: 0.3
          },
          {
            label: 'Ammonia (%)',
            data: ammoniaValues,
            borderColor: 'rgba(147,51,234,1)',
            backgroundColor: 'rgba(147,51,234,0.2)',
            fill: true,
            tension: 0.3
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: 'Daily Gas and Ammonia Trend'
          }
        },
        scales: {
          y: { beginAtZero: true, title: { display: true, text: 'Concentration (%)' } },
          x: { title: { display: true, text: 'Time' } }
        }
      }
    });

  } catch (err) {
    console.error('Error loading gas/ammonia chart:', err);
  }
}

document.addEventListener('DOMContentLoaded', loadGasAmmoniaChart);
