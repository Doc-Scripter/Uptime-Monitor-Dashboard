import { computed } from 'vue'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
  type ChartData,
  type ChartOptions
} from 'chart.js'

// Register Chart.js components
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
)

export function useChart() {
  const defaultOptions: ChartOptions<'line'> = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        mode: 'index',
        intersect: false,
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        titleColor: '#fff',
        bodyColor: '#fff',
        borderColor: 'rgba(255, 255, 255, 0.1)',
        borderWidth: 1,
        padding: 10,
        displayColors: false
      }
    },
    scales: {
      x: {
        grid: {
          display: false,
          drawTicks: false
        },
        ticks: {
          color: '#9CA3AF',
          font: {
            family: "'Inter', sans-serif",
            size: 11
          }
        }
      },
      y: {
        grid: {
          color: 'rgba(156, 163, 175, 0.1)',
          drawTicks: false
        },
        ticks: {
          color: '#9CA3AF',
          font: {
            family: "'Inter', sans-serif",
            size: 11
          }
        },
        beginAtZero: true
      }
    },
    interaction: {
      mode: 'nearest',
      axis: 'x',
      intersect: false
    }
  }

  const createChartData = (labels: string[], data: number[], label: string, color: string): ChartData<'line'> => {
    return {
      labels,
      datasets: [
        {
          label,
          data,
          borderColor: color,
          backgroundColor: (context) => {
            const ctx = context.chart.ctx
            const gradient = ctx.createLinearGradient(0, 0, 0, 300)
            gradient.addColorStop(0, `${color}33`) // 20% opacity
            gradient.addColorStop(1, `${color}00`) // 0% opacity
            return gradient
          },
          borderWidth: 2,
          pointRadius: 0,
          pointHoverRadius: 4,
          fill: true,
          tension: 0.4
        }
      ]
    }
  }

  return {
    defaultOptions,
    createChartData
  }
}
