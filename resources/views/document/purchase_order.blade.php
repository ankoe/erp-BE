<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Print PDF</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
        }

    </style>
</head>

<body>

    <div style="display: flex;margin-bottom:20px;">
        <table style="width: 50%;">
            <thead style="border-bottom: 1px solid black">
                <tr>
                    <th>Info Perusahaan</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <th>{{ $company->name }}</th>
                </tr>
                <tr>
                    <td>Telp : {{ $company->mobile }}</td>
                </tr>

                <tr>
                    <td>Email : {{ $company->email }}</td>
                </tr>
            </tbody>
        </table>

        <table style="width: 50%">
            <thead style="border-bottom: 1px solid black">
                <tr>
                    <th>Order Ke</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <th>{{ $vendor->name }}</th>
                </tr>
                <tr>
                    <th>{{ '-' }}</th>
                </tr>
                <tr>
                    <td>Telp : {{ '-' }}</td>
                </tr>

                <tr>
                    <td>Email : {{ $vendor->email }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <table class="table table-bordered w-100 mb-3">
        <thead style="background-color: #2e3e4d;color:white;text-align:center;font-weight:700">
            <tr>
                <th>Produk</th>
                <th>Kuantitas</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        @php
        $subtotal = 0;
        @endphp
        <tbody style="text-align: center">
            @foreach ($purchaseRequestItems as $purchaseRequestItem)
            <tr>
                <td>{{ $purchaseRequestItem->material->name }}</td>
                <td>{{ $purchaseRequestItem->winning_vendor_stock }}</td>
                <td>Rp {{ number_format($purchaseRequestItem->winning_vendor_price) }}</td>
                <td>Rp {{ number_format($purchaseRequestItem->winning_vendor_price * $purchaseRequestItem->winning_vendor_stock) }} </td>
                @php
                $subtotal += $purchaseRequestItem->winning_vendor_price * $purchaseRequestItem->winning_vendor_stock
                @endphp
            </tr>
            @endforeach
        </tbody>
    </table>
    <table style="width: 50%;float:right;">
        <tr>
            <th style="font-weight:700;text-align:right">Jumlah Tertagih</th>
            <td style="text-align:right">
                Rp {{ number_format($subtotal) }}
            </td>
        </tr>
    </table>



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>
