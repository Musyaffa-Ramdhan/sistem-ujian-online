<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password Guru | Ujian Online SMP</title>
    <style>
        /* CSS styling form reset password */
        body{
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container{
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            min-width: 350px;
        }
        .header h3{
            font-weight: 600;
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        label{
            font-weight: 500;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }
        input{
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 2px solid #e0e0e0;
            box-sizing: border-box;
        }
        button{
            width: 100%;
            padding: 14px;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }
        .error{ color: #e74c3c; font-size: 14px; margin-bottom: 15px; }
        .success{ color: #27ae60; font-size: 14px; margin-bottom: 15px; }
        .back-link{ text-align: center; margin-top: 20px; }
        .back-link a{ color: #667eea; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Reset Password Guru</h3>
        </div>

        @if (session('status'))
            <div class="success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form Reset Password Baru --}}
        <form action="{{ route('guru.password.update') }}" method="POST">
            @csrf

            {{-- Token reset password dari URL --}}
            <input type="hidden" name="token" value="{{ $request->route('token') ?? $token }}">

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Masukkan Email" value="{{ old('email', $request->email ?? '') }}" required>

            <label for="password">Password Baru:</label>
            <input type="password" name="password" id="password" placeholder="Masukkan Password Baru" required>

            <label for="password_confirmation">Konfirmasi Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password" required>

            <button type="submit">Reset Password</button>
        </form>

        <div class="back-link">
            <a href="{{ route('guru.login') }}">Kembali ke Login</a>
        </div>
    </div>
</body>
</html>