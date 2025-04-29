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
<form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Transaksi Penjualan</h5>
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
                            <li class="list-group-item"><strong>Nama Pembeli:</strong>
                                <input type="text" name="pembeli" class="form-control" value="{{ $penjualan->pembeli }}">
                            </li>
                            <li class="list-group-item"><strong>Nama Pengguna:</strong> {{ $penjualan->user->username }}</li>
                        </ul>
                    </div>
                </div>

                <h6 class="text-muted">Edit Detail Barang</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penjualan->details as $index => $detail)
                                <tr>
                                    <td>
                                        {{ $detail->barang->barang_nama }}
                                        <input type="hidden" name="detail[{{ $index }}][barang_id]" value="{{ $detail->barang_id }}">
                                    </td>
                                    <td>
                                        <input type="number" name="detail[{{ $index }}][jumlah]" class="form-control text-center" value="{{ $detail->jumlah }}" min="1">
                                    </td>
                                    <td>
                                        <input type="number" name="detail[{{ $index }}][harga]" class="form-control text-right" value="{{ $detail->harga }}" min="0">
                                    </td>
                                    <td class="text-right font-weight-bold text-success">
                                        Rp{{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer bg-light">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: this.action,
            method: this.method,
            data: $(this).serialize(),
            success: function(res) {
                if (res.status) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message
                    });
                    dataPenjualan.ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server.'
                });
                console.error(xhr.responseText);
            }
        });
    });
</script>
@endempty
