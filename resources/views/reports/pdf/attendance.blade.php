<!DOCTYPE html>
<html>

<head>
    <title>Attendance Report</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f0f0f0;
        }
    </style>
</head>

<body>

    <h2>Attendance Report</h2>

    <table>
        <tr>
            <th>Employee</th>
            <th>Date</th>
            <th>Status</th>
        </tr>

        @foreach ($attendance as $a)
            <tr>
                <td>{{ $a->employee->name }}</td>
                <td>{{ $a->attendance_date }}</td>
                <td>{{ $a->status }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>
