@include("header")
<!DOCTYPE html>
<html>

<head>
    <title>ButtonClicks</title>
    <style>
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

    <h2>Button Clicks</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Button ID</th>
            <th>Press_Count</th>
        </tr>

        @foreach($clicks as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>
                    @if($c->user)
                        {{ $c->user->name }} {{ $c->user->lname }}
                    @else
                        Nezināms lietotājs
                    @endif
                </td>

                <td>
                    @if($c->button)
                        {{ $c->button->name }}
                    @else
                        Nezināma poga
                    @endif
                </td>

                <td>{{ $c->press_count }}</td>
            </tr>
        @endforeach
    </table>


</body>

</html>