<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PHPUnit\Event\TestSuite\Skipped;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok Barang',
            'list' => ['Home', 'Stok']
        ];

        $activeMenu = 'stok';

        $barang = BarangModel::select('barang_id', 'barang_nama')->get();

        return view('stok.index', ['breadcrumb' => $breadcrumb, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $stoks = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->with('supplier', 'barang', 'user');

        $barang_id = $request->input('filter_barang');
        if (!empty($barang_id)) {
            $stoks->where('barang_id', $barang_id);
        }
        return DataTables::of($stoks)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                $btn  = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    // Menambah data baru dengan ajax
    public function create_ajax()
    {
        $barang = BarangModel::all(); // ambil semua barang
        $supplier = SupplierModel::all(); // ambil semua supplier

        return view('stok.create_ajax')
            ->with('barang', $barang)
            ->with('supplier', $supplier);
    }

    //Simpan data melalui ajax
    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {

            // Menentukan aturan validasi untuk input
            $rules = [
                'barang_id' => 'required|integer|exists:m_barang,barang_id', // Validasi barang_id yang valid
                'supplier_id' => 'required|integer|exists:m_supplier,supplier_id', // Validasi supplier_id yang valid
                'stok_jumlah' => 'required|integer|min:1', // Validasi jumlah stok
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal, kirimkan response error
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, //response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            // Simpan data stok baru
            StokModel::create([
                'barang_id' => $request->barang_id,
                'supplier_id' => $request->supplier_id,
                'stok_jumlah' => $request->stok_jumlah,
                'stok_tanggal' => now(), // Tanggal saat ini
                'user_id' => Auth::id(), // ID user yang sedang login
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil disimpan'
            ]);
        }

        return redirect('/');
    }


    //Menampilkan halaman form edit barang ajax
    public function edit_ajax(string $id)
    {
        $stok = StokModel::find($id);
        $barang = BarangModel::all();
        $supplier = SupplierModel::all();

        return view('stok.edit_ajax', [
            'stok' => $stok,
            'barang' => $barang,
            'supplier' => $supplier
        ]);
    }


    //Mengakomodir request update data barang melalui ajax
    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'barang_id' => 'required|integer',
                'supplier_id' => 'required|integer',
                'stok_jumlah' => 'required|numeric|min:1',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $stok = StokModel::find($id);
            if ($stok) {
                $stok->update([
                    'barang_id' => $request->barang_id,
                    'supplier_id' => $request->supplier_id,
                    'stok_jumlah' => $request->stok_jumlah,
                    'stok_tanggal' => now(),
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data stok berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data stok tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    //menampilkan detail data barang dengan ajax
    public function show_ajax($id)
    {
        $stok = StokModel::with('barang')->find($id);
        return view('stok.show_ajax', ['stok' => $stok]);
    }


    //Confirm ajax
    public function confirm_ajax(string $id)
    {
        $stok = StokModel::find($id);

        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    // Delete ajax
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $stok = StokModel::find($id);
                if ($stok) {
                    $stok->delete();
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

    public function import()
    {
        return view('stok.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024'] // Validasi file
            ];

            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal, kembalikan response error
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Ambil file yang diupload
            $file = $request->file('file_stok');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();

            // Mengambil data dari sheet
            $data = $sheet->toArray(null, false, true, true);
            $user_id = auth()->id();

            $inserted = 0;
            $skipped = 0;

            if (count($data) > 1) {
                // Loop melalui data dan insert ke database
                foreach ($data as $baris => $value) {
                    if ($baris <= 1) continue; // Lewati header

                    $supplier_id   = $value['A'];
                    $barang_id     = $value['B'];
                    $stok_jumlah   = $value['E']; // Menggunakan E untuk jumlah stok

                    // Jika ada data yang kosong, skip baris tersebut
                    if (!$barang_id || !$supplier_id || !$stok_jumlah) {
                        $skipped++;
                        continue;
                    }

                    try {
                        // Membuat data stok baru tanpa memeriksa data yang sudah ada
                        StokModel::create([
                            'supplier_id'  => $supplier_id,
                            'barang_id'    => $barang_id,
                            'user_id'      => $user_id,
                            'stok_tanggal' => now(),
                            'stok_jumlah'  => $stok_jumlah,
                            'created_at'   => now(),
                        ]);
                        $inserted++;
                    } catch (\Exception $e) {
                        // Log jika terjadi error
                        Log::error('Import Gagal: ' . $e->getMessage(), ['row' => $value]);
                        $skipped++;
                    }
                }

                // Mengirimkan response setelah data diimport
                return response()->json([
                    'status'  => true,
                    'message' => "Data berhasil diimport. $inserted data berhasil disimpan"
                ]);
            }

            // Jika tidak ada data yang diimport, kirimkan response error
            return response()->json([
                'status'  => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }

        return redirect('/');
    }



    public function export_excel()
    {
        // ambil data barang yang akan di export
        $stok = StokModel::select('supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->orderBy('stok_id')
            ->with('barang', 'supplier', 'user')
            ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Supplier');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Nama Pengguna');
        $sheet->setCellValue('E1', 'Stok Pada Tanggal');
        $sheet->setCellValue('F1', 'Jumlah Stok');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold header

        $no = 1; //nomor data dimulai dari 1
        $baris = 2; //baris data dimulai dari baris ke 2
        foreach ($stok as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->supplier->supplier_nama);
            $sheet->setCellValue('C' . $baris, $value->barang->barang_nama);
            $sheet->setCellValue('D' . $baris, $value->user->username);
            $sheet->setCellValue('E' . $baris, $value->stok_tanggal);
            $sheet->setCellValue('F' . $baris, $value->stok_jumlah);
            $no++;
            $baris++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Stok Barang'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok Barang' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, mustrevalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $stok = StokModel::select('supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->with('barang', 'supplier', 'user')
            ->get();

        // use Barryvdh\Dompdf\Facade\pdf;
        $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
        $pdf->setPaper('a4', 'potrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Stok Barang' . date('Y-m-d H:i:s') . '.pdf');
    }
}
