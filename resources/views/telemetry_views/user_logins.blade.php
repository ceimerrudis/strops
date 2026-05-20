<!DOCTYPE html>
<html>
<head>
    <title>UserLogins</title>
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

<h2>User Logins</h2>

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
        <td>{{ $l->user_id }}</td>
        <td>{{ $l->logged_in_at }}</td>
        <td>{{ $l->remember_me }}</td>
    </tr>
    @endforeach
</table>


</body>
</html>
