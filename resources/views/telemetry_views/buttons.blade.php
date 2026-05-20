<!DOCTYPE html>
<html>
<head>
    <title>Buttons</title>
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

<h2>Buttons</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Page ID</th>
        <th>Name</th>
    </tr>

    @foreach($buttons as $b)
    <tr>
        <td>{{ $b->id }}</td>
        <td>{{ $b->page_id }}</td>
        <td>{{ $b->name }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>
