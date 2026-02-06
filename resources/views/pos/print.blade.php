<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk_{{ $transaction->transaction_code }}</title>
    <style>
        @page { size: 80mm 200mm; margin: 0; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 70mm; /* Sedikit lebih kecil dari kertas agar aman */
            margin: 0 auto;
            padding: 5mm;
            font-size: 12px;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        .flex { display: flex; justify-content: space-between; }
        .bold { font-weight: bold; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #fff3cd; padding: 10px; margin-bottom: 20px; font-family: sans-serif; font-size: 10px;">
        Klik "Print" atau tekan Ctrl+P. Pastikan Margin diset ke "None".
    </div>

    <div class="text-center">
        <h3 style="margin: 0;">MJ STORE</h3>
        <p style="margin: 0;">{{ $transaction->branch->branch_name }}</p>
        <p style="margin: 0; font-size: 10px;">{{ $transaction->branch->address ?? 'Jl. Raya MJ No. 01' }}</p>
    </div>

    <div class="line"></div>

    <div style="font-size: 10px;">
        <div class="flex"><span>Nota:</span> <span>{{ $transaction->transaction_id }}</span></div>
        <div class="flex"><span>Tgl :</span> <span>{{ $transaction->transaction_date }}</span></div>
        <div class="flex"><span>Ksr :</span> <span>{{ $transaction->user->name }}</span></div>
    </div>

    <div class="line"></div>

    @foreach($transaction->details as $item)
    <div style="margin-bottom: 5px;">
        <div class="bold">{{ $item->product->product_name }}</div>
        <div class="flex">
            <span>{{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}</span>
            <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <div class="line"></div>

    <div class="flex bold">
        <span>TOTAL:</span>
        <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
    </div>
    <div class="flex">
        <span>BAYAR:</span>
        <span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
    </div>
    <div class="flex">
        <span>KEMBALI:</span>
        <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
    </div>

    <div class="line"></div>
    <div class="text-center">
        <p>TERIMA KASIH</p>
        <p style="font-size: 9px;">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

    <script>
        // Tutup window otomatis setelah print (jika dibuka di tab baru)
        window.onafterprint = function() { window.close(); };
    </script>
</body>
</html>