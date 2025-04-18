document.addEventListener('DOMContentLoaded', function() {
  // Check if we're on the dashboard page
  if (document.querySelector('.charts-section')) {
      // Sales Chart
      const salesCtx = document.getElementById('salesChart').getContext('2d');
      const salesChart = new Chart(salesCtx, {
          type: 'line',
          data: {
              labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
              datasets: [{
                  label: 'Sales 2023',
                  data: [12000, 19000, 15000, 18000, 22000, 25000],
                  borderColor: '#ff0000',
                  backgroundColor: 'rgba(255, 0, 0, 0.1)',
                  borderWidth: 2,
                  fill: true,
                  tension: 0.4
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'top',
                      labels: {
                          color: '#f5f5f5'
                      }
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true,
                      grid: {
                          color: 'rgba(255, 255, 255, 0.1)'
                      },
                      ticks: {
                          color: '#f5f5f5'
                      }
                  },
                  x: {
                      grid: {
                          color: 'rgba(255, 255, 255, 0.1)'
                      },
                      ticks: {
                          color: '#f5f5f5'
                      }
                  }
              }
          }
      });
      
      // Users Chart
      const usersCtx = document.getElementById('usersChart').getContext('2d');
      const usersChart = new Chart(usersCtx, {
          type: 'bar',
          data: {
              labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
              datasets: [{
                  label: 'New Users',
                  data: [320, 450, 380, 510, 490, 600],
                  backgroundColor: 'rgba(255, 0, 0, 0.7)',
                  borderColor: '#ff0000',
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'top',
                      labels: {
                          color: '#f5f5f5'
                      }
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true,
                      grid: {
                          color: 'rgba(255, 255, 255, 0.1)'
                      },
                      ticks: {
                          color: '#f5f5f5'
                      }
                  },
                  x: {
                      grid: {
                          color: 'rgba(255, 255, 255, 0.1)'
                      },
                      ticks: {
                          color: '#f5f5f5'
                      }
                  }
              }
          }
      });
  }
});