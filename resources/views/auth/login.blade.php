
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | FinanceFlow</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #172033;
        }

        .card {
            width: 100%;
            max-width: 420px;
            padding: 36px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, .06);
        }

        .brand { font-size: 25px; font-weight: 700; }
        .brand span { color: #168568; }
        h1 { margin: 28px 0 8px; font-size: 26px; }
        .muted { color: #667085; line-height: 1.5; }

        label {
            display: block;
            margin: 20px 0 8px;
            font-size: 14px;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #d0d5dd;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus {
            outline: 2px solid #168568;
            outline-offset: 2px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 18px 0;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 8px;
            background: #168568;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .error {
            margin-top: 8px;
            color: #b42318;
            font-size: 13px;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
        }

        a { color: #08775c; }
    </style>
</head>
<body>
    <main class="card">
        <div class="brand">Finance<span>Flow</span></div>

        <h1>Welcome back</h1>
        <p class="muted">Sign in to manage your personal finances.</p>

        @if (session('status'))
            <p role="status">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <div class="error" role="alert">
                Please check your email and password and try again.
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <label for="email">Email address</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                autocomplete="username"
                required
                autofocus
            >
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
            >
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Remember me
            </label>

            <button type="submit">Sign in</button>
        </form>

        <div class="footer">
            Don't have an account?
            <a href="{{ route('register') }}">Create one</a>
        </div>
    </main>
</body>
</html>
