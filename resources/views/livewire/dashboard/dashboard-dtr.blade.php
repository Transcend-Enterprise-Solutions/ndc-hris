<div x-data="dashboardDtr()"
     x-init="init()"
     class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Employee DTR For the last 30 days</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Attendance Chart -->
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white">Attendance Overview</h3>
            <canvas id="attendanceChart"></canvas>
        </div>

        <!-- Overtime Chart -->
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white">Overtime Trends</h3>
            <canvas id="overtimeChart"></canvas>
        </div>

        <!-- Late Chart -->
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white">Late Arrivals</h3>
            <canvas id="lateChart"></canvas>
        </div>

        <!-- Summary Stats -->
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">30-Day Summary</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Present</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalPresent }}</p>
                </div>
                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Absent</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $totalAbsent }}</p>
                </div>
                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Late</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $totalLate }}</p>
                </div>
                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg. Overtime</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $avgOvertime }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function dashboardDtr() {
        return {
            charts: {
                attendance: null,
                overtime: null,
                late: null
            },
            init() {
                this.renderCharts();
            },
            renderCharts() {
                this.renderAttendanceChart();
                this.renderOvertimeChart();
                this.renderLateChart();
            },
            getChartOptions(title) {
                return {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: document.querySelector('html').classList.contains('dark') ? 'white' : 'black'
                            }
                        },
                        title: {
                            display: true,
                            text: title,
                            color: document.querySelector('html').classList.contains('dark') ? 'white' : 'black'
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: document.querySelector('html').classList.contains('dark') ? 'white' : 'black'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: document.querySelector('html').classList.contains('dark') ? 'white' : 'black'
                            }
                        }
                    }
                };
            },
            renderAttendanceChart() {
                const ctx = document.getElementById('attendanceChart').getContext('2d');
                this.charts.attendance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($attendanceData->pluck('date')),
                        datasets: [
                            {
                                label: 'Present',
                                data: @json($attendanceData->pluck('present_count')),
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            },
                            {
                                label: 'Absent',
                                data: @json($attendanceData->pluck('absent_count')),
                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            },
                            {
                                label: 'Late',
                                data: @json($attendanceData->pluck('late_count')),
                                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                            },
                        ],
                    },
                    options: this.getChartOptions('Attendance Overview')
                });
            },
            renderOvertimeChart() {
                const ctx = document.getElementById('overtimeChart').getContext('2d');
                this.charts.overtime = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($overtimeData->pluck('date')),
                        datasets: [{
                            label: 'Overtime',
                            data: @json($overtimeData->map(function($day) {
                                $parts = explode(':', $day['total_overtime']);
                                return floatval($parts[0]) + floatval($parts[1]) / 60;
                            })),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            tension: 0.1,
                        }],
                    },
                    options: this.getChartOptions('Overtime Trends')
                });
            },
            renderLateChart() {
                const ctx = document.getElementById('lateChart').getContext('2d');
                this.charts.late = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($lateData->pluck('date')),
                        datasets: [{
                            label: 'Late',
                            data: @json($lateData->map(function($day) {
                                $parts = explode(':', $day['total_late']);
                                return floatval($parts[0]) + floatval($parts[1]) / 60;
                            })),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            tension: 0.1,
                        }],
                    },
                    options: this.getChartOptions('Late Arrivals')
                });
            },
        };
    }
</script>
