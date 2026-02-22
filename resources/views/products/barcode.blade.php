<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Print Barcode - {{ $product->product_id }}</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <style>
        /*Jika suatu saat ukuran stiker label di toko berubah (misal ganti yang lebih besar), Anda cukup mengubah angka di bagian @page { size: 50mm 30mm; } dan .label-page { width: 50mm; height: 30mm; }.*/
        @page {
            margin: 0;
            size: 50mm 30mm; /* Standar ukuran stiker label */
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        /* Style untuk setiap satu stiker */
        .label-page {
            width: 50mm;
            height: 30mm;
            padding: 5px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: white;
            page-break-after: always; /* Barcode berikutnya pindah ke kertas baru */
            overflow: hidden;
            font-family: 'Arial', sans-serif;
        }
        .brand { font-size: 7px; font-weight: bold; margin-bottom: 2px; text-transform: uppercase; }
        .product-name { font-size: 8px; margin-bottom: 2px; text-align: center; width: 100%; }
        svg { width: 100%; max-height: 14mm; }
        .price { font-size: 10px; font-weight: bold; margin-top: 2px; }
        
        @media print {
            body { background-color: white; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    @for ($i = 0; $i < $quantity; $i++)
        <div class="label-page">
            <div class="brand">{{ $branch->branch_name ?? 'MJ STORE' }}</div>
            <div class="product-name">{{ $product->product_name }}</div>
            
            <svg class="barcode-item" data-value="{{ $product->product_id }}"></svg>
            
            <div class="price">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
        </div>
    @endfor

    <script>
        // Generate semua barcode yang ada di halaman
        document.querySelectorAll(".barcode-item").forEach(function(el) {
            JsBarcode(el, el.getAttribute('data-value'), {
                format: "CODE128",
                width: 1.3,
                height: 35,
                displayValue: true,
                fontSize: 10,
                margin: 0
            });
        });
    </script>
</body>
</html>