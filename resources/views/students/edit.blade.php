<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>

<body>

    <h1>Edit Student</h1>

    <form method="POST" action="/students/{{ $student->id }}">

        @csrf
        @method('PUT')

        <div>
            <label>Name</label>
            <input
                type="text"
                name="name"
                value="{{ $student->name }}"
            >
        </div>

        <br>

        <div>
            <label>Email</label>
            <input
                type="email"
                name="email"
                value="{{ $student->email }}"
            >
        </div>

        <br>

        <div>
            <label>Phone</label>
            <input
                type="text"
                name="phone"
                value="{{ $student->phone }}"
            >
        </div>

        <br>

        <div>
            <label>Gender</label>

            <select name="gender">
                <option value="">Select Gender</option>

                <option value="Male"
                    {{ $student->gender == 'Male' ? 'selected' : '' }}>
                    Male
                </option>

                <option value="Female"
                    {{ $student->gender == 'Female' ? 'selected' : '' }}>
                    Female
                </option>
            </select>
        </div>

        <br>

        <div>
            <label>Date of Birth</label>

            <input
                type="date"
                name="date_of_birth"
                value="{{ $student->date_of_birth }}"
            >
        </div>

        <br>

        <div>
            <label>Address</label>

            <textarea name="address">{{ $student->address }}</textarea>
        </div>

        <br>

        <div>
            <label>Admission Date</label>

            <input
                type="date"
                name="admission_date"
                value="{{ $student->admission_date }}"
            >
        </div>

        <br>

        <div>
            <label>Status</label>

            <select name="status">

                <option value="1"
                    {{ $student->status == 1 ? 'selected' : '' }}>
                    Active
                </option>

                <option value="0"
                    {{ $student->status == 0 ? 'selected' : '' }}>
                    Inactive
                </option>

            </select>
        </div>

        <br>

        <button type="submit">Update Student</button>

    </form>

</body>
</html>