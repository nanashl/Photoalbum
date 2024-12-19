<?php
include 'db_connect.php'; // Подключение к базе данных
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчёт о посещаемости</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Отчёт о посещаемости</h1>
    </header>
    <main>
        <section id="reportSection">
            <h2>Отчёт о посещаемости</h2>

            <!-- Выбор группировки -->
            <label for="groupBySelect">Группировать по:</label>
            <select id="groupBySelect">
                <option value="day">По дням</option>
                <option value="week">По неделям</option>
                <option value="month">По месяцам</option>
            </select>
            <button id="generateReportButton">Сформировать отчёт</button>
            
            <!-- Таблица для отображения отчёта -->
            <table id="reportTable">
                <thead>
                    <tr>
                        <th>Тип файла</th>
                        <th>Количество запросов</th>
                        <th>Общий объём (MB)</th>
                        <th>Период</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Данные будут добавляться сюда динамически через JavaScript -->
                </tbody>
            </table>
        </section>
    </main>
    <script>
        function loadReport(groupBy = 'day') {
            fetch(`generate_report.php?group_by=${groupBy}`)
                .then(response => response.json())
                .then(data => {
                    const reportTable = document.querySelector('#reportTable tbody');
                    reportTable.innerHTML = ''; // Очищаем предыдущие данные

                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.file_type}</td>
                            <td>${row.total_requests}</td>
                            <td>${(row.total_size / 1024 / 1024).toFixed(2)} MB</td>
                            <td>${row.period}</td>
                        `;
                        reportTable.appendChild(tr);
                    });
                })
                .catch(error => console.error('Ошибка загрузки отчёта:', error));
        }

        document.querySelector('#generateReportButton').addEventListener('click', () => {
            const groupBy = document.querySelector('#groupBySelect').value;
            loadReport(groupBy);
        });
    </script>
</body>
</html>
