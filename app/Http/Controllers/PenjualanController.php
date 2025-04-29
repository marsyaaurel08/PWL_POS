<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Transaksi Penjualan',
            'list' => ['Home', 'Transaksi Penjualan']
        ];

        $activeMenu = 'penjualan';

        $penjualan = PenjualanModel::select('penjualan_id', 'pembeli')->get();

        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'penjualan' => $penjualan, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with('user');

        $penjualan_id = $request->input('filter_pembeli');
        if (!empty($penjualan_id)) {
            $penjualans->where('penjualan_id', $penjualan_id);
        }
        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) {
                $btn  = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menambah data baru dengan ajax
    public function create_ajax()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama', 'barang_kode', 'harga_jual', 'harga_beli')
            ->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('penjualan.create_ajax')
            ->with('barang', $barang)
            ->with('user', $user);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer',
                'pembeli' => 'required|string|max:50',
                'penjualan_kode' => 'required|string|max:20|unique:t_penjualan,penjualan_kode',
                'penjualan_tanggal' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.barang_id' => 'required|integer|exists:m_barang,barang_id',
                'items.*.jumlah' => 'required|integer|min:1',
                'items.*.harga' => 'required|integer|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            try {
                $penjualan = PenjualanModel::create([
                    'user_id' => $request->user_id,
                    'pembeli' => $request->pembeli,
                    'penjualan_kode' => $request->penjualan_kode,
                    'penjualan_tanggal' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $errorItems = [];

                foreach ($request->items as $item) {
                    try {
                        PenjualanDetailModel::create([
                            'penjualan_id' => $penjualan->penjualan_id,
                            'barang_id' => $item['barang_id'],
                            'jumlah' => $item['jumlah'],
                            'harga' => $item['harga'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    } catch (\Exception $e) {
                        $errorItems[] = $item['barang_id'] . ": " . $e->getMessage();
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => count($errorItems) > 0 ?
                        "Transaksi disimpan, tapi ada error pada detail:\n" . implode("\n", $errorItems)
                        : 'Transaksi penjualan berhasil disimpan'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan data utama: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/');
    }



    //Menampilkan halaman form edit barang ajax
    public function edit_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['details.barang', 'user'])->find($id); // ambil detail + relasi barang & user
        $user = UserModel::select('user_id', 'username')->get();
        $barang = BarangModel::all(); // untuk select option barang di detail

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ]);
        }

        return view('penjualan.edit_ajax', [ // pastikan view sesuai
            'penjualan' => $penjualan,
            'user' => $user,
            'barang' => $barang
        ]);
    }


    public function update_ajax(Request $request, $id)
    {
        // Menemukan penjualan berdasarkan ID
        $penjualan = PenjualanModel::findOrFail($id);
    
        // Validasi data utama
        $request->validate([
            'pembeli' => 'required|string|max:255',
            'detail' => 'required|array',
            'detail.*.barang_id' => 'required|exists:m_barang,barang_id',
            'detail.*.jumlah' => 'required|integer|min:1',
            'detail.*.harga' => 'required|numeric|min:0',
        ]);
    
        try {
            // Update data utama
            $penjualan->update([
                'pembeli' => $request->pembeli,
            ]);
    
            // Update setiap detail
            foreach ($request->detail as $item) {
                $detail = \App\Models\PenjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)
                            ->where('barang_id', $item['barang_id'])
                            ->first();
    
                if ($detail) {
                    $detail->jumlah = $item['jumlah'];
                    $detail->harga = $item['harga'];
                    // $detail->total = $item['jumlah'] * $item['harga'];
                    $detail->save();
                }
            }
    
            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil diperbarui!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    
            
        // Update detail barang
        foreach ($request->detail as $detail) {
            $penjualanDetail = PenjualanDetailModel::find($detail['id']);
            $penjualanDetail->update([
                'jumlah' => $detail['jumlah'],
                'harga' => $detail['harga'],
            ]);
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Data transaksi berhasil diperbarui.'
        ]);
            
            return redirect('/');
        }
    

    // menampilkan detail transaksi penjualan dengan ajax
    public function show_ajax($id)
    {
        // Ambil data penjualan beserta user dan detail + nama barang
        $penjualan = PenjualanModel::with(['user', 'details.barang'])->find($id);

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ]);
        }
        $total = $penjualan->getTotalAmount();

        return view('penjualan.show_ajax', ['penjualan' => $penjualan, 'total' => $total]);
    }



    //Confirm ajax
    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['details.barang', 'user'])->find($id);

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return view('penjualan.confirm_ajax', [
            'penjualan' => $penjualan
        ]);
    }
    // Delete ajax
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $penjualan = PenjualanModel::with('details')->find($id);

                if ($penjualan) {
                    // Hapus semua detail penjualan terlebih dahulu
                    foreach ($penjualan->details as $detail) {
                        $detail->delete();
                    }

                    // Baru hapus data penjualannya
                    $penjualan->delete();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak dapat dihapus karena terhubung dengan data lain'
                ]);
            }
        }

        return redirect('/');
    }



    public function export_excel()
    {
        $penjualan = PenjualanModel::with(['user', 'details.barang'])->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Pengguna');
        $sheet->setCellValue('C1', 'Nama Pembeli');
        $sheet->setCellValue('D1', 'Kode Penjualan');
        $sheet->setCellValue('E1', 'Tanggal Penjualan');
        $sheet->setCellValue('F1', 'Nama Barang');
        $sheet->setCellValue('G1', 'Jumlah');
        $sheet->setCellValue('H1', 'Harga Satuan');
        $sheet->setCellValue('I1', 'Total Harga');

        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        $row = 2;
        $no = 1;
        foreach ($penjualan as $p) {
            foreach ($p->details as $detail) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $p->user->username);
                $sheet->setCellValue('C' . $row, $p->pembeli);
                $sheet->setCellValue('D' . $row, $p->penjualan_kode);
                $sheet->setCellValue('E' . $row, $p->penjualan_tanggal);
                $sheet->setCellValue('F' . $row, $detail->barang->barang_nama);
                $sheet->setCellValue('G' . $row, $detail->jumlah);
                $sheet->setCellValue('H' . $row, $detail->harga);
                $sheet->setCellValue('I' . $row, $detail->jumlah * $detail->harga);
                $row++;
            }
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data Transaksi Penjualan');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Transaksi Penjualan ' . date('Y-m-d H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }


    public function export_pdf()
    {
        $penjualan = PenjualanModel::with(['user', 'details.barang'])->get();

        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Transaksi Penjualan ' . date('Y-m-d H-i-s') . '.pdf');
    }
}
