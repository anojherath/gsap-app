<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Green Sun Agri Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        html, body {
            height: 100%;
        }

        body {
            background: url('{{ asset("images/login.avif") }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            color: white;
        }

        .overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .top-left-logo {
            position: absolute;
            top: 20px;
            left: 30px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            z-index: 3;
        }

        .top-left-logo img {
            width: 80px;
            height: auto;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 24px;
            font-weight: 600;
            color: #f9fafb;
        }

        .container {
            display: flex;
            height: 100vh;
            width: 100%;
            position: relative;
            z-index: 2;
            padding: 0 5%;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .left-content {
            max-width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .headline {
            font-size: 48px;
            font-weight: 800;
            color: #f9fafb;
            margin-bottom: 10px;
        }

        .left-content p {
            font-size: 16px;
            line-height: 1.4;
            color: #d1d5db;
        }

        .form-wrapper {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 8px;
            max-width: 360px;
            width: 100%;
            color: #111827;
        }

        .form-wrapper h2 {
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 22px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #6b21a8;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .error {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                justify-content: center;
                text-align: center;
                gap: 40px;
            }

            .left-content {
                max-width: 100%;
                align-items: center;
            }

            .form-wrapper {
                max-width: 90%;
            }

            .headline {
                font-size: 36px;
            }

            .top-left-logo {
                align-items: center;
                top: 10px;
                left: 50%;
                transform: translateX(-50%);
                text-align: center;
            }

            .company-name {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Logo and Company Name in Top Left -->
    <div class="top-left-logo">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
        <div class="company-name">Green Sun Agri Products</div>
    </div>

    <div class="container">
        <div class="left-content">
            <div class="headline">Welcome Back</div>
            <p>Please enter your login details</p>
        </div>

        <div class="form-wrapper">
            <h2>Sign in</h2>

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

                <button type="submit">Sign in</button>
            </form>
        </div>
    </div>
</body>
</html>
