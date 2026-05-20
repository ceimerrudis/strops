<!DOCTYPE html>
<html>
<head>
    <title>PageMetrics</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>

<h2>Page Metrics</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Page ID</th>
        <th>Device ID</th>
        <th>Visit Count</th>
        <th>Avg Load Time</th>
        <th>Max Load Time</th>
    </tr>

    @foreach($metrics as $m)
    <tr>
        <td>{{ $m->id }}</td>
        <td>{{ $m->page_id }}</td>
        <td>{{ $m->device_id }}</td>
        <td>{{ $m->visit_count }}</td>
        <td>{{ $m->avg_load_time }}</td>
        <td>{{ $m->max_load_time }}</td>
    </tr>
    @endforeach
</table>


</body>
</html>
