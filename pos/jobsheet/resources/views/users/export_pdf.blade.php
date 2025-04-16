<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
            font-style: italic;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Data Pengguna</h1>
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama</th>
                <th width="30%">Email</th>
                <th width="20%">Peran</th>
                <th width="20%">Tanggal Registrasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->name }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Diekspor pada: {{ date('Y-m-d H:i:s') }}
    </div>
</body>
</html>