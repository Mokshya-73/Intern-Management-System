<!DOCTYPE html>
<html>
<head>
    <title>Supervisor Account Created</title>
</head>
<body>
    <h2>Congratulations, {{ $name }}!</h2>

    <p>Your supervisor account has been successfully created.</p>

    <p><strong>Login Credentials:</strong></p>
    <ul>
        <li><strong>Reg No:</strong> {{ $regNo }}</li>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>Please log in using the above credentials and make sure to change your password immediately after your first login.</p>

    <p>Best regards,<br>
    Internship Management System Team</p>
</body>
</html>
