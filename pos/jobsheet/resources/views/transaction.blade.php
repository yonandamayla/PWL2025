<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <body>
        <h1>HISTORY PENJUALAN</h1>
        <table>
            <tr>
                <th>NO</th>
                <th>PRODUCT</th>
                <th>QTY</th>
                <th>PRICE</th>
                <th>TOTAL</th>
            </tr>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction['product'] }}</td>
                    <td>{{ $transaction['qty'] }}</td>
                    <td>{{ $transaction['price'] }}</td>
                    <td>{{ $transaction['total'] }}</td>
                </tr>
            @endforeach
        </table>
    </body>
</body>

</html> 