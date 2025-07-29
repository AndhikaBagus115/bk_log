<!DOCTYPE html>
<html>
<head>
    <title>Login Client</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <h2 class="header">LOGIN <br>
        Selamat Datang Admin!
    </h2>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form class="login" method="POST" action="{{ url('/login') }}">
        @csrf
        <label>Username:</label>
        <input type="text" name="username"><br><br>

        <label>Password:</label>
        <input type="password" name="password"><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
