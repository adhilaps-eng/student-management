<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
</head>

<body>

    <h1>Add Student</h1>

    <form method="POST" action="/students">

        @csrf

        <div>
            <label>Name</label>
            <input type="text" name="name">
        </div>

        <br>

        <div>
            <label>Email</label>
            <input type="email" name="email">
        </div>

        <br>

        <div>
            <label>Phone</label>
            <input type="text" name="phone">
        </div>

        <br>

        <div>
            <label>Gender</label>
            <select name="gender">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <br>

        <div>
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth">
        </div>

        <br>

        <div>
            <label>Address</label>
            <textarea name="address"></textarea>
        </div>

        <br>

        <div>
            <label>Admission Date</label>
            <input type="date" name="admission_date">
        </div>

        <br>

        <div>
            <label>Status</label>
            <select name="status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <br>

        <button type="submit">Save Student</button>

    </form>

</body>
</html>