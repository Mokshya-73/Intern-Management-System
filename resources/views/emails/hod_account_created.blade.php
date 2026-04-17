<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>HOD Account Created</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Welcome, {{ $name }}!</h2>

    <p>Your Head of Department (HOD) account has been successfully created in the Internship Management System.</p>

    <p><strong>Login Credentials:</strong></p>
    <ul>
        <li><strong>Reg No:</strong> {{ $regNo }}</li>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>Please log in as soon as possible and change your password for security reasons.</p>

    <p>Thank you,<br>The Internship Management System Team</p>
</body>
</html>
