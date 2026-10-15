<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>
</head>

<body>

    <h1>Student Management System</h1>

    <h2>Student List</h2>

    <a href="/students/create">Add Student</a>

    <br><br>

    <table border="1" cellpadding="10">

        <thead>
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            @foreach($students as $index => $student)

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone }}</td>
                    <td>{{ $student->gender }}</td>
                    <td>
                        {{ $student->status ? 'Active' : 'Inactive' }}
                    </td>
                    <td>

                         <a href="/students/{{ $student->id }}/edit">Edit</a>

                      <form
                           method="POST"
                           action="/students/{{ $student->id }}"
                           style="display:inline;"
                         >

                            @csrf
                            @method('DELETE')

                         <button type="submit">
                            Delete
                         </button>

                        </form>

                    </td>
                </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>