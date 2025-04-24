<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LevelModel; // Import the LevelModel class
use Illuminate\Support\Facades\Validator; // Import the Validator class
use Illuminate\Support\Facades\Log; // Import the Log facade
use PhpOffice\PhpSpreadsheet\IOFactory; // Import the IOFactory class
use Barryvdh\DomPDF\Facade\Pdf; // Import the Pdf facade

class LevelController extends Controller
{
    // menampilkan halaman tabel level:
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar level pengguna'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        // Ambil data level untuk dropdown filter
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama')->get();

        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'levels' => $levels,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $levels = LevelModel::select('level_id', 'level_nama', 'level_kode');

        // Filter berdasarkan level_kode jika ada
        if ($request->has('filter_kode') && $request->filter_kode != '') {
            $levels->where('level_kode', $request->filter_kode);
        }

        return DataTables::of($levels)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                $btn = '';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    // menampilkan detail level
    public function show($id)
    {
        $level = LevelModel::findOrFail($id); // Ambil data level berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail level'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'level' => $level // Tambahkan variabel $level ke array data
        ]);
    }

    // Untuk menampilkan form edit level:
    public function edit($id)
    {
        $level = LevelModel::findOrFail($id); // Ambil data level berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit level'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'level' => $level // Kirim data level ke view
        ]);
    }

    // Untuk menyimpan perubahan data level
    public function update(Request $request, $id)
    {
        $request->validate([
            'level_nama' => 'required|string|max:100', // Validasi nama level
            'level_kode' => 'required|string|max:10', // Validasi kode level
        ]);

        $level = LevelModel::findOrFail($id);
        $level->update([
            'level_nama' => $request->level_nama,
            'level_kode' => $request->level_kode
        ]);

        return redirect('/level')->with('success', 'Level berhasil diperbarui.');
    }

    // Untuk menghapus level
    public function destroy($id)
    {
        $level = LevelModel::findOrFail($id);
        $level->delete();

        return redirect('/level')->with('success', 'Level berhasil dihapus.');
    }

    // Untuk menampilkan form tambah level
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah level baru'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // Untuk menyimpan data level baru
    public function store(Request $request)
    {
        $request->validate([
            'level_nama' => 'required|string|max:100', // Validasi nama level
            'level_kode' => 'required|string|max:10', // Validasi kode level
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Level berhasil ditambahkan.');
    }


    // menampilkan halaman form tambah level dengan ajax
    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    // menyimpan data level baru dengan ajax
    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax()) {
            $rules = [
                'level_kode' => 'required|string|min:2|max:10|unique:m_level,level_kode',
                'level_nama' => 'required|string|min:3|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            LevelModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil ditambahkan'
            ]);
        }

        return redirect('/');
    }

    // menampilkan halaman form edit level dengan ajax
    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', ['level' => $level]);
    }

    // menyimpan perubahan data level dengan ajax
    public function update_ajax(Request $request, string $id)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|min:2|max:10|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|min:3|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = LevelModel::find($id);

            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data level berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    // menampilkan konfirmasi hapus level dengan ajax
    public function confirm_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    // menghapus data level dengan ajax
    public function delete_ajax(Request $request, string $id)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);

            if ($level) {
                try {
                    $level->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus karena masih terkait dengan data lain'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    /**
     * Show the form for importing levels
     */
    public function import()
    {
        return view('level.import');
    }

    /**
     * Import levels from Excel file
     */
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_level' => ['required', 'mimes:xlsx,xls', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                $file = $request->file('file_level');
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, false, true, true);

                $insert = [];
                if (count($data) > 1) {
                    foreach ($data as $row => $value) {
                        if ($row > 1) { // Skip header row
                            $insert[] = [
                                'level_kode' => $value['A'],
                                'level_nama' => $value['B'],
                                'created_at' => now(),
                            ];
                        }
                    }
                    if (count($insert) > 0) {
                        LevelModel::insertOrIgnore($insert);
                        return response()->json([
                            'status' => true,
                            'message' => 'Data level berhasil diimport'
                        ]);
                    }
                }
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            } catch (\Exception $e) {
                Log::error('Level Import Error: ' . $e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengunggah file: ' . $e->getMessage()
                ], 500);
            }
        }
        return redirect('/level');
    }

    public function export_excel()
    {
        // Get level data to export
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama')
            ->orderBy('level_id')
            ->get();

        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Level');
        $sheet->setCellValue('C1', 'Kode Level');
        $sheet->setCellValue('D1', 'Nama Level');

        // Make headers bold
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Populate data
        $no = 1;
        $baris = 2;
        foreach ($levels as $level) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $level->level_id);
            $sheet->setCellValue('C' . $baris, $level->level_kode);
            $sheet->setCellValue('D' . $baris, $level->level_nama);
            $baris++;
            $no++;
        }

        // Auto-size columns
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set sheet title
        $sheet->setTitle('Data Level');

        // Create writer and set filename
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $fileName = 'Data Level' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set headers for file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        // Get level data to export
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama')
            ->orderBy('level_id')
            ->orderBy('level_kode')
            ->get();

        // use Barryvdh\DomPDF\Facade\PDF
        $pdf = Pdf::loadView('level.export_pdf', ['levels' => $levels]);
        $pdf->setPaper('a4', 'portrait'); // Set paper size and orientation
        $pdf->setOption("isRemoteEnabled", true); // Enable remote images 
        $pdf->render();

        return $pdf->stream('Data Level' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
