<?php

require_once __DIR__ . '/template/header.php';

$query = $pdo->prepare("SELECT * FROM compt");
$query->execute();
$comt = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->prepare("SELECT * FROM tweet");
$query->execute();
$tweet = $query->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total View Accounts</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        :root {
            --primary-color: #6A80FD;
            --secondary-color: #f9f9fb;
            --text-color: #1e1e1e;
            --text-secondary: #777;
            --card-bg: white;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            --border-radius: 12px;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell,
            'Open Sans', 'Helvetica Neue', sans-serif;
            background-color: var(--secondary-color);
            margin: 0;
            padding: 20px;
            color: var(--text-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-card {
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 24px;
            margin-bottom: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: 600;
        }

        .info-icon {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background-color: #e1e1e1;
            color: var(--text-secondary);
            font-size: 12px;
            margin-left: 6px;
            cursor: pointer;
        }

        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .stat-box {
            flex: 1;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50px;
        }

        .stat-period {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }

        /* Navigation Styles */
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 80px;
            height: 80px;
        }

        .tabs {
            display: flex;
            gap: 10px;
        }

        .tab {
            padding: 8px 15px;
            background-color: var(--card-bg);
            width: 100px;
            height: 35px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 15px rgba(13, 0, 255, 0.11);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .tab:hover {
            box-shadow: 0 6px 20px rgba(13, 0, 255, 0.15);
            transform: translateY(-2px);
        }

        .tab.active {
            box-shadow: 0 8px 25px rgba(13, 0, 255, 0.2);
            background-color: var(--primary-color);
            color: white;
        }

        .search-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search {
            position: relative;
        }

        .search input {
            padding: 8px 15px 8px 35px;
            border-radius: 20px;
            border: 1px solid #ddd;
            width: 230px;
            height: 30px;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .search input:focus {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            border-color: #3102ff4a;
            outline: none;
        }

        .search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #b6bdff80;
        }

        .notifications {
            position: relative;
            cursor: pointer;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .profile-pic {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #666;
        }

        .welcome {
            margin-bottom: 20px;
            background-color: var(--card-bg);
            width: 100%;
            height: 80px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            padding: 20px;
            position: relative;
        }

        .welcome h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .welcome h2 span {
            font-weight: bold;
        }

        .welcome p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 5px;
        }

        .date-filters {
            position: absolute;
            right: 20px;
            top: 20px;
            display: flex;
            gap: 15px;
        }

        .date-filter {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            background-color: var(--card-bg);
        }

        .dashboard-grid {

        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .nav-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .search-profile {
                width: 100%;
                justify-content: space-between;
            }

            .search input {
                width: 180px;
            }

            .date-filters {
                position: static;
                margin-top: 15px;
                justify-content: flex-start;
            }
        }
    </style>
</head>

<body>
<div class="container">
    <div class="nav-container">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div class="logo">
                <img src="images/Frame 1000010341.png" alt="Logo">
            </div>
            <a href="dashboard.php" class="tabs">
                <div class="tab">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1v-7m-6 0a1 1 0 01-1-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Dashboard
                </div>
                <a href="analystic.php" class="tab active">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Analysis
                </a>
                <a href="ai.php" class="tab">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 4.354a4 4 0 110 5.292V15M15 21h-6v-6h4v6zm-4 0v-6a4 4 0 00-4-4h12a4 4 0 00-4 4v6h-4z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    AI Twintelli
                </a>
            </a>
        </div>
        <div class="search-profile">
            <div class="search">
                <input type="text" placeholder="Search">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                     style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #888;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2"
                          stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="notifications">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="profile">
                <div class="profile-pic">
                    <img src="<?= $comt[0]['image'] ?? "" ?>" alt="Profile">
                </div>
                <div style="font-size: 12px;">
                    <div><?= $comt[0]['display_name'] ?? "" ?></div>
                    <div style="color: #888;">admin</div>
                </div>
            </div>
        </div>
    </div>

    <div class="welcome">
        <h2>Hello, <span>oussama</span></h2>
        <p>Here's what's happening with your store today.</p>

        <div class="date-filters">
            <div class="date-filter">
                Month
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" />
                </svg>
            </div>
            <div class="date-filter">
                Day
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" />
                </svg>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="header">
                <div class="title">Total View Accounts</div>
            </div>
            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-label">Current</div>
                    <div class="stat-value">23,283.5</div>
                    <div class="stat-period">This Month</div>
                </div>
                <div class="stat-box" style="text-align: right;">
                    <div class="stat-label">Previous</div>
                    <div class="stat-value">21,450.2</div>
                    <div class="stat-period">Last Month</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="visitsChart1"></canvas>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="header">
                <div class="title">New Sign-ups</div>
            </div>
            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-label">Current</div>
                    <div class="stat-value">1,842</div>
                    <div class="stat-period">This Month</div>
                </div>
                <div class="stat-box" style="text-align: right;">
                    <div class="stat-label">Previous</div>
                    <div class="stat-value">1,650</div>
                    <div class="stat-period">Last Month</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="visitsChart2"></canvas>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="header">
                <div class="title">Active Users</div>
            </div>
            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-label">Current</div>
                    <div class="stat-value">15,327</div>
                    <div class="stat-period">This Month</div>
                </div>
                <div class="stat-box" style="text-align: right;">
                    <div class="stat-label">Previous</div>
                    <div class="stat-value">14,892</div>
                    <div class="stat-period">Last Month</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="visitsChart3"></canvas>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="header">
                <div class="title">Conversion Rate</div>
            </div>
            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-label">Current</div>
                    <div class="stat-value">3.2%</div>
                    <div class="stat-period">This Month</div>
                </div>
                <div class="stat-box" style="text-align: right;">
                    <div class="stat-label">Previous</div>
                    <div class="stat-value">2.9%</div>
                    <div class="stat-period">Last Month</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="visitsChart4"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data for all charts
        const chartData = [
            {
                id: 'visitsChart1',
                title: 'Total View Accounts',
                currentValue: '23,283.5',
                previousValue: '21,450.2',
                primaryData: [135, 170, 155, 140, 180, 210, 260, 220, 190, 200, 230, 250],
                secondaryData: [180, 160, 140, 180, 220, 190, 170, 210, 190, 200, 210, 230],
                primaryColor: '#4F46E5',
                secondaryColor: '#D1D5DB',
                yMin: 120,
                yMax: 280,
                stepSize: 40
            },
            {
                id: 'visitsChart2',
                title: 'New Sign-ups',
                currentValue: '1,842',
                previousValue: '1,650',
                primaryData: [145, 180, 165, 150, 190, 220, 240, 210, 180, 210, 200, 220],
                secondaryData: [170, 150, 130, 170, 210, 180, 160, 200, 180, 190, 170, 210],
                primaryColor: '#10B981',
                secondaryColor: '#D1D5DB',
                yMin: 120,
                yMax: 280,
                stepSize: 40
            },
            {
                id: 'visitsChart3',
                title: 'Active Users',
                currentValue: '15,327',
                previousValue: '14,892',
                primaryData: [155, 190, 175, 160, 200, 230, 270, 230, 200, 220, 210, 240],
                secondaryData: [190, 170, 150, 190, 230, 200, 180, 220, 200, 210, 190, 230],
                primaryColor: '#F59E0B',
                secondaryColor: '#D1D5DB',
                yMin: 120,
                yMax: 280,
                stepSize: 40
            },
            {
                id: 'visitsChart4',
                title: 'Conversion Rate',
                currentValue: '3.2%',
                previousValue: '2.9%',
                primaryData: [125, 160, 145, 130, 170, 200, 250, 210, 180, 190, 200, 220],
                secondaryData: [170, 150, 130, 170, 210, 180, 160, 200, 180, 190, 170, 210],
                primaryColor: '#EF4444',
                secondaryColor: '#D1D5DB',
                yMin: 120,
                yMax: 280,
                stepSize: 40
            }
        ];

        chartData.forEach(chart => {
            createChart(chart);
        });

        function createChart(chartConfig) {
            const ctx = document.getElementById(chartConfig.id).getContext('2d');

            const primaryGradient = ctx.createLinearGradient(0, 0, 0, 300);
            primaryGradient.addColorStop(0, `${chartConfig.primaryColor}33`);
            primaryGradient.addColorStop(1, `${chartConfig.primaryColor}00`);

            const secondaryGradient = ctx.createLinearGradient(0, 0, 0, 300);
            secondaryGradient.addColorStop(0, 'rgba(209, 213, 219, 0.2)');
            secondaryGradient.addColorStop(1, 'rgba(209, 213, 219, 0)');

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'This Year',
                            data: chartConfig.primaryData,
                            borderColor: chartConfig.primaryColor,
                            backgroundColor: primaryGradient,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: chartConfig.primaryColor,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Last Year',
                            data: chartConfig.secondaryData,
                            borderColor: chartConfig.secondaryColor,
                            backgroundColor: secondaryGradient,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: chartConfig.secondaryColor,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#fff',
                            titleColor: '#1e293b',
                            bodyColor: '#1e293b',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: chartConfig.yMin,
                            max: chartConfig.yMax,
                            ticks: {
                                stepSize: chartConfig.stepSize,
                                callback: function (value) {
                                    return value + 'k';
                                },
                                color: '#94a3b8'
                            },
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#94a3b8'
                            },
                            border: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        point: {
                            hitRadius: 10
                        }
                    }
                }
            });

            // Highlight the current month's data point
            const currentMonth = new Date().getMonth();
            chart.data.datasets[0].pointRadius = Array(chartConfig.primaryData.length).fill(0);
            chart.data.datasets[0].pointRadius[currentMonth] = 6;
            chart.update();
        }
    });
</script>
</body>

</html>