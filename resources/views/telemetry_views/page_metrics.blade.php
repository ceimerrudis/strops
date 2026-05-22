@include("header")

<!DOCTYPE html>
<html>

<head>
    <title>PageMetrics</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }

        .box {
            padding: 10px;
            border: 1px solid #999;
            background: #f7f7f7;
            margin-bottom: 15px;
            width: 350px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
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

    @if($highestMaxLoad == null)
        <div class="box">
            <strong>Augstākais max_load_time:</strong>
            {{ $highestMaxLoad->max_load_time }} ms
            ({{ $highestMaxLoad->page->name }})
        </div>
    @endif

    <div class="box">
        <strong>Augstākais average_load_time:</strong>
        {{ $highestAvgLoad->avg_load_time }} ms
        ({{ $highestAvgLoad->page->name }})
    </div>

    <div class="box">
        <strong>Vidējais max_load_time:</strong>
        {{ $avgMaxLoad }} ms
    </div>

    <h3>Page Metrics(kārtoti pēc ierīces un vidējā load laika)</h3>

    <table>
        <tr>
            <th>ID / Ierīce</th>
            <th>Lapa</th>
            <th>Lietotājs</th>
            <th>Visit Count</th>
            <th>Avg Load Time</th>
            <th>Max Load Time</th>
        </tr>

        @foreach($metrics as $m)
            <tr>
                <td>{{ $m->id }} / {{ $m->device_id }}</td>
                <td>{{ $m->page->name }}</td>
                <td>
                    @if($m->device && $m->device->user)
                        {{ $m->device->user->name }} {{ $m->device->user->lname }}
                    @else
                        Nezināms lietotājs
                    @endif
                </td>
                <td>{{ $m->visit_count }}</td>
                <td>{{ $m->avg_load_time }}</td>
                <td>{{ $m->max_load_time }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>