
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | FinanceFlow</title>
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
            max-width: 440px;
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
            margin: 18px 0 8px;
            font-size: 14px;
            font-weight: 600;
        }

        input {
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

        button {
            width: 100%;
            margin-top: 24px;
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

        <h1>Create your account</h1>
        <p class="muted">Start managing your money in one place.</p>

        @if ($errors->any())
            <div class="error" role="alert">
                Please correct the errors below.
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <label for="name">Full name</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                autocomplete="name"
                required
                autofocus
            >
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="email">Email address</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                autocomplete="email"
                required
            >
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                required
            >
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password_confirmation">Confirm password</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                required
            >

            <button type="submit">Create account</button>
        </form>

        <div class="footer">
            Already have an account?
            <a href="{{ route('login') }}">Sign in</a>
        </div>
    </main>
</body>
</html>

