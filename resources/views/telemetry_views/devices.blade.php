<!DOCTYPE html>
<html>
<head>
    <title>Device Info</title>
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

<h2>Device Info</h2>

<table>
    <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Browser</th>
        <th>OS</th>
        <th>Screen Width</th>
        <th>Screen Height</th>
    </tr>

    @foreach($devices as $d)
    <tr>
        <td>{{ $d->id }}</td>
        <td>@if ($d->user)
            {{ $d->user->name }} {{ $d->user->lname }}
        @else
            Nezināms lietotājs
        @endif
        </td>
        <td>{{ $d->browser_name }}</td>
        <td>{{ $d->os_name }}</td>
        <td>{{ $d->screen_width }}</td>
        <td>{{ $d->screen_height }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>
