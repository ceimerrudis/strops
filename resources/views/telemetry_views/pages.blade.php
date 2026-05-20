<!DOCTYPE html>
<html>
<head>
    <title>Pages</title>
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

<h2>Pages</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
    </tr>

    @foreach($pages as $p)
    <tr>
        <td>{{ $p->id }}</td>
        <td>{{ $p->name }}</td>
    </tr>
    @endforeach
</table>


</body>
</html>
