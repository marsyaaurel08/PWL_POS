@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-triangle"></i> Data Tidak Ditemukan!</h5>
                    Transaksi yang Anda cari tidak tersedia.
                </div>
                <a href="{{ url('/penjualan') }}" class="btn btn-warning mt-3">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-receipt mr-2"></i>Detail Transaksi Penjualan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>ID Transaksi:</strong> {{ $penjualan->penjualan_id }}</li>
                            <li class="list-group-item"><strong>Kode Penjualan:</strong> {{ $penjualan->penjualan_kode }}</li>
                            <li class="list-group-item"><strong>Tanggal:</strong> {{ $penjualan->penjualan_tanggal }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Nama Pembeli:</strong> {{ $penjualan->pembeli }}</li>
                            <li class="list-group-item"><strong>Nama Pengguna:</strong> {{ $penjualan->user->username }}</li>
                        </ul>
                    </div>
                </div>

                <h6 class="text-muted">Detail Transaksi Penjualan</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penjualan->details as $detail)
                                <tr>
                                    <td>{{ $detail->barang->barang_nama }}</td>
                                    <td class="text-center">{{ $detail->jumlah }}</td>
                                    <td class="text-right">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="text-right text-success font-weight-bold">
                                        Rp{{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-light">
                                <td colspan="3" class="text-right"><strong>Total Transaksi:</strong></td>
                                <td class="text-right text-primary font-weight-bold">
                                    Rp{{ number_format($total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty
