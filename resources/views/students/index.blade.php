<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>
</head>

<body>

    <h1>Student Management System</h1>

    <h2>Student List</h2>

    <table border="1" cellpadding="10">

        <thead>
            <tr>
                <th>#</th>
                <th>Student Name</th>
            </tr>
        </thead>

        <tbody>

            @foreach($students as $index => $student)

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student }}</td>
                </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>