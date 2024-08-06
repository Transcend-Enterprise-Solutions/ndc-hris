<div x-data="dashboardDtr()" class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">Employee DTR Dashboard</h2>

    <div class="mb-4 flex space-x-4">
        <div>
            <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" id="startDate" wire:model.live="startDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>
        <div>
            <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" id="endDate" wire:model.live="endDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Attendance Chart -->
        <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-2">Attendance Overview</h3>
            <canvas id="attendanceChart"></canvas>
        </div>

        <!-- Overtime Chart -->
        <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-2">Overtime Trends</h3>
            <canvas id="overtimeChart"></canvas>
        </div>

        <!-- Late Chart -->
        <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-2">Late Arrivals</h3>
            <canvas id="lateChart"></canvas>
        </div>

        <!-- Summary Stats -->
        <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-2">Summary</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Present</p>
                    <p class="text-2xl font-bold">{{ $totalPresent }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Absent</p>
                    <p class="text-2xl font-bold">{{ $totalAbsent }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Late</p>
                    <p class="text-2xl font-bold">{{ $totalLate }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Avg. Overtime</p>
                    <p class="text-2xl font-bold">{{ $avgOvertime }}</p>
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
                Livewire.on('dataUpdated', () => {
                    this.updateCharts();
                });
            },
            renderCharts() {
                this.renderAttendanceChart();
                this.renderOvertimeChart();
                this.renderLateChart();
            },
            updateCharts() {
                this.charts.attendance.data.labels = @this.attendanceData.map(day => day.date);
                this.charts.attendance.data.datasets[0].data = @this.attendanceData.map(day => day.present_count);
                this.charts.attendance.data.datasets[1].data = @this.attendanceData.map(day => day.absent_count);
                this.charts.attendance.data.datasets[2].data = @this.attendanceData.map(day => day.late_count);
                this.charts.attendance.update();

                this.charts.overtime.data.labels = @this.overtimeData.map(day => day.date);
                this.charts.overtime.data.datasets[0].data = @this.overtimeData.map(day => {
                    const [hours, minutes] = day.total_overtime.split(':');
                    return parseFloat(hours) + parseFloat(minutes) / 60;
                });
                this.charts.overtime.update();

                this.charts.late.data.labels = @this.lateData.map(day => day.date);
                this.charts.late.data.datasets[0].data = @this.lateData.map(day => {
                    const [hours, minutes] = day.total_late.split(':');
                    return parseFloat(hours) + parseFloat(minutes) / 60;
                });
                this.charts.late.update();
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
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true,
                            },
                        },
                    },
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
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Hours',
                                },
                            },
                        },
                    },
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
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Hours',
                                },
                            },
                        },
                    },
                });
            },
        };
    }
</script>
