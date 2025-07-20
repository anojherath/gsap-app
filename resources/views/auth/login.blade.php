<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Green Sun Agri Products</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            flex-direction: row;
        }

        .left-panel {
            width: 50%;
            background-color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-panel {
            width: 50%;
            background-color: #a78bfa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .subtitle {
            margin-bottom: 30px;
            color: #777;
        }

        form {
            max-width: 350px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .actions input[type="checkbox"] {
            margin-right: 5px;
        }

        .actions a {
            color: #6b21a8;
            text-decoration: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #6b21a8;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .google-signin {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: white;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }

        .signup {
            text-align: center;
            font-size: 0.9rem;
        }

        .signup a {
            color: #6b21a8;
            text-decoration: none;
        }

        .error {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .illustration {
            max-width: 80%;
            height: auto;
        }
    </style>
</head>
<body>

    <div class="left-panel">
        <div class="brand">Green Sun Agri Products</div>

        <h2>Welcome back</h2>
        <div class="subtitle">Please enter your details</div>

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="nic">NIC</label>
            <input type="text" id="nic" name="nic" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <div class="actions">
                <label><input type="checkbox" name="remember"> Remember for 30 days</label>
                <a href="#">Forgot password?</a>
            </div>

            <button type="submit">Sign in</button>

            <div class="google-signin">
                <img src="https://developers.google.com/identity/images/g-logo.avif" alt="G" style="height: 16px; vertical-align: middle; margin-right: 8px;">
                Sign in with Google
            </div>

            <div class="signup">
                Don't have an account? <a href="#">Sign up</a>
            </div>
        </form>
    </div>

    <div class="right-panel">
        <img src="{{ asset('images/login-illustration.avif') }}" alt="Login Illustration" class="illustration">
    </div>

</body>
</html>
