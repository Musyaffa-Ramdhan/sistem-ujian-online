<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Siswa | Ujian Online SMP</title>
    <style>
        /* CSS Halaman Login */
        body{
            display: flex;
            height: 100vh; /* Full Height */
            justify-content: center; /* Center Horizontal */
            align-items: center; /* Center Vertical */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Gradient Background */
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
        .header img{
            width: 60px;
            height: 60px;
        }
        .header h3{
            font-weight: 600;
            margin-left: 15px;
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
            <h3>Login Siswa</h3>
        </div>
        
        {{-- Menampilkan Error Jika Login Gagal --}}
        @if ($errors->any())
            <div class="error">
                {{-- Ambil pesan error pertama --}}
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form Method POST ke Route Login Siswa --}}
        <form action="{{ route('siswa.login.post') }}" method="POST">
            @csrf {{-- Token Wajib --}}
            
            <label for="nama">Nama Lengkap:</label>
            {{-- Input Nama (required, autofocus) --}}
            <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Lengkap" value="{{ old('nama') }}" required autofocus>
            
            <label for="nisn">NISN:</label>
            {{-- Input NISN --}}
            <input type="text" name="nisn" id="nisn" placeholder="Masukkan NISN (10 digit)" maxlength="10" required>
            
            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>
