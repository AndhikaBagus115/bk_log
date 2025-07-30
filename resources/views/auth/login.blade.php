<!DOCTYPE html>
<html>
<head>
    <title>Login Client</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .password-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
            margin-bottom: 10px;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 40px;
            padding-left: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            top: 40%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #333;
            font-size: 18px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: #3d3d3d;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }

        .login {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    <h2 class="header">LOGIN <br>Selamat Datang Admin!</h2>

    {{-- Pesan Sukses --}}
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    {{-- Pesan Error --}}
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    {{-- Validasi --}}
    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form class="login" method="POST" action="{{ url('/login') }}">
        @csrf
        <label>Username:</label>
        <input type="text" name="username" value="{{ old('username') }}"><br><br>

        <label>Password:</label>
        <div class="password-wrapper">
            <input type="password" id="password" name="password">
            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
        </div><br><br>

        <button type="submit">Login</button>
    </form>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const icon = document.querySelector(".toggle-password");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

</body>
</html>
