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
            background: url('{{ asset("images/login.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            color: white;
        }

        .overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .top-left-logo {
            position: absolute;
            top: 20px;
            left: 30px;
            display: flex;
            flex-direction: row;
            align-items: center;
            z-index: 3;
        }

        .top-left-logo img {
            width: 170px;
            height: auto;
            object-fit: contain;
            margin-right: 15px;
        }

        .company-name {
            font-size: 60px;
            font-weight: 700;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.3);
        }

        .green {
            color: #064e3b; /* dark green */
        }

        .sun {
            color: #c2410c; /* dark orange */
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
            color: #ffffff;
            margin-bottom: 10px;
            text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.2);
        }

        .left-content p {
            font-size: 18px;
            line-height: 1.6;
            color: #dbeafe;
        }

        .form-wrapper {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            max-width: 380px;
            width: 100%;
            color: #1e293b;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .form-wrapper h2 {
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 24px;
            color: #111827;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #f8fafc;
            font-size: 15px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #7c3aed;
            color: white;
            font-weight: 600;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        button:hover {
            background-color: #6d28d9;
        }

        .error {
            color: #dc2626;
            font-size: 0.95rem;
            margin-bottom: 12px;
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
                flex-direction: column;
                align-items: center;
                top: 10px;
                left: 50%;
                transform: translateX(-50%);
                text-align: center;
            }

            .top-left-logo img {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .company-name {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Logo and Company Name in Top Left -->
    <div class="top-left-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <div class="company-name">
            <span class="green">Green</span> <span class="sun">Sun</span> <span class="green">Agri Products</span>
        </div>
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
