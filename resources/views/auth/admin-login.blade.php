<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin | Ujian Online SMP</title>
    <style>
        body{
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-size: cover;
            background-position:center ;
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
        .header{
            display: flex;
            flex-direction: row;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h3{
            font-weight: 600;
            color: #333;
            font-size: 1.5rem;
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
            font-size: 14px;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        input:focus{
            outline: none;
            border-color: #667eea;
        }
        button{
            width: 100%;
            padding: 14px;
            border-radius: 8px;
            margin-top: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover{
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .error{
            color: #e74c3c;
            font-size: 14px;
            margin-top: -15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>Login Admin</h3>
        </div>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Masukkan Email" value="{{ old('email') }}" required autofocus>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Masukkan Password" required>

            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>