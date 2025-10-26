<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MoodTracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: #333;
            margin: 0;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background: #5a6fd8;
        }

        .demo-credentials {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .demo-credentials h3 {
            margin: 0 0 0.5rem 0;
            color: #333;
        }

        .demo-credentials p {
            margin: 0.25rem 0;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🔐 Login MoodTracker</h1>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div style="color: red; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div style="color: red; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>

        <div class="demo-credentials">
            <h3>👤 Credenciales de Demo:</h3>
            <p><strong>Admin:</strong> evablancomart@gmail.com</p>
            <p><strong>Contraseña:</strong> secret123</p>
        </div>
    </div>
</body>

</html>
