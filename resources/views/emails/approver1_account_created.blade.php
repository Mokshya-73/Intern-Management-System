<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Approver 1 Account Created</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Welcome, {{ $name }}!</h2>

    <p>Your Approver 1 account has been successfully created.</p>

    <p><strong>Login Credentials:</strong></p>
    <ul>
        <li><strong>Reg No:</strong> {{ $regNo }}</li>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>Please log in and change your password upon first login.</p>

    <p>Thank you,<br>Internship Management System Team</p>
</body>
</html>
