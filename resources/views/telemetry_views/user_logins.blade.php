<!DOCTYPE html>
<html>
<head>
    <title>User Logins</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }
        .box {
            padding: 10px;
            border: 1px solid #999;
            margin-bottom: 20px;
            width: 300px;
            background: #f7f7f7;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>

<h2>User Login Dati</h2>

<div class="box">
    <strong>Pēdējo 24h login skaits:</strong> {{ $last24hCount }}
</div>

<div class="box">
    <strong>“Atcerēties mani” izmanto </strong> {{ $rememberPercent }}%
</div>

<h3>100 jaunākie login ieraksti</h3>

<table>
    <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Logged In At</th>
        <th>Remember Me</th>
    </tr>

    @foreach($logins as $l)
    <tr>
        <td>{{ $l->id }}</td>
        <td>{{ $l->user->name }} {{ $l->user->lname }}</td>
        <td>{{ $l->logged_in_at }}</td>
        <td>{{ $l->remember_me }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>
