<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 4px 3px;
        }

        th {
            text-align: left;
        }

        .d-block {
            display: block;
        }

        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .p-1 {
            padding: 5px 1px 5px 1px;
        }

        .font-10 {
            font-size: 10pt;
        }

        .font-11 {
            font-size: 11pt;
        }

        .font-12 {
            font-size: 12pt;
        }

        .font-13 {
            font-size: 13pt;
        }

        .border-bottom-header {
            border-bottom: 1px solid;
        }

        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header mb-3">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ asset('polinema-bw.png') }}" style="height: 70px; width: auto; object-fit: contain;">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN TRANSAKSI PENJUALAN</h3>

    @foreach($penjualan as $b)
        <table class="mb-3">
            <tr>
                <td><strong>Nama Pengguna</strong></td>
                <td>: {{ $b->user->nama }}</td>
            </tr>
            <tr>
                <td><strong>Nama Pembeli</strong></td>
                <td>: {{ $b->pembeli }}</td>
            </tr>
            <tr>
                <td><strong>Kode Penjualan</strong></td>
                <td>: {{ $b->penjualan_kode }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Penjualan</strong></td>
                <td>: {{ $b->penjualan_tanggal }}</td>
            </tr>
        </table>

        <table class="border-all mb-3">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Nama Barang</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($b->details as $d)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $d->barang->barang_nama }}</td>
                    <td class="text-right">{{ $d->jumlah }}</td>
                    <td class="text-right">Rp{{ number_format($d->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($d->jumlah * $d->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
