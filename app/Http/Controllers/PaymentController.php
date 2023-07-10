<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentModel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use function app\helper\penyebut;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class PaymentController extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model = new PaymentModel();
    }
    public function index()
    {
        $serch = request()->get('serch');
        if ($serch) {
            if (request()->get('serch') == 'All') {
                $data = $this->model->index();
            } else {

                $data = $this->model->index($serch);
            }
        } else {
            $data = $this->model->index();
        }


        $data = [
            'tittle' => 'Payment',
            'data' => $data,
            'deta' => $this->model->index()

        ];
        return view('payment.index', $data);
    }



    public function show($no_penerimaan)
    {
        $no_penerimaan = str_replace("-", "/", $no_penerimaan);
        // dd($no_penerimaan);


        $data = [
            'tittle' => "Create Payment",
            'data' => $this->model->show($no_penerimaan),
        ];

        // dd($data);
        return view('payment.create', $data);
    }
    public function store(Request $request)
    {

        // persiapan data pembyaran
        $id_transaksi = $request->input('id_transaksi');
        $no_pembayaran = $request->input('no_tagihan');
        $tgl_pembayaran = $request->input('tgl_pembayaran');
        $id_produk = $request->input('id_produk');

        $tgl_tagihan =
            DB::table('tagihan')
            ->select('tgl_tagihan')
            ->where('id_transaksi', "=", $id_transaksi[0])
            ->first();

        $tgl_tagihan = $tgl_tagihan->tgl_tagihan;

        // $rules = [
        //     'tgl_pembayaran' => " after_or_equal:$tgl_tagihan",
        // ];
        // $message = [
        //     "tgl_pembayaran.after_or_equal" => "Choose a date after the bill payment date or equal"
        // ];
        // $validated = Validator::make($request->all(), $rules, $message);
        // if ($validated->fails()) {
        //     return redirect()->back()->with("failed", "Choose a date after the delivery date or equal");
        // }


        // persiapan no pembyaran
        $no_tagihan = $this->model->no_pembayaran($request->input('tgl_pembayaran'));
        $tgl_pembayaran_no = explode('-', $tgl_pembayaran);
        $no_pembayaran = "PYT/$no_tagihan/$tgl_pembayaran_no[0]/$tgl_pembayaran_no[1]/$tgl_pembayaran_no[2]";



        // persiapan

        $data_pembayaran = [];
        $data_detail_pembayaran = [];
        for ($i = 0; $i < count($id_transaksi); $i++) {
            $data_pembayaran[$i] = [
                'id_transaksi' => $id_transaksi[$i],
                'no_pembayaran' => $no_pembayaran,
                'tgl_pembayaran' => $tgl_pembayaran,


            ];
            $data_detail_pembayaran[$i] = [
                'id_pembayaran' => 0,
                'id_produk' => $id_produk[$i]
            ];
        }
        // dd($id_transaksi);
        // dd($id_transaksi);
        // $nominal=0;
        // if (count($id_transaksi)>1) {
        //     for ($i=0; $i <count($id_transaksi) ; $i++) { 
        //             $id_jurnal = DB::table('jurnal')
        //             ->select('nominal')
        //             ->where('id_transaksi', "=", $id_transaksi[$i])
        //             ->where('kode_akun', "=", 112)
        //             ->first();
        //             if ($id_jurnal) {
        //                 $nominal=$id_jurnal->nominal;
        //         }
        //     }
        // }
        // dd($id_transaksi);


        $this->model->insert($id_transaksi, $data_pembayaran, $data_detail_pembayaran, $request->input('no_tagihan'));

        return redirect('payment')->with('success', " Data Entered Successfully");
    }

    public function detail($no_transaksi)
    {
        $no_transaksi = str_replace('-', '/', $no_transaksi);




        $data = [
            'tittle' => 'Payment',
            'data' => $this->model->detail($no_transaksi),

        ];
        return view('payment.detail', $data);
    }


    public function print($no_transaksi)
    {
        $data = $this->model->detail(str_replace("-", "/", $no_transaksi));
        $dueDate = $this->model->index(str_replace("-", "/", $no_transaksi));

        // dd($data);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/payment_template.xlsx');

        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->getCell('J4')->setValue($data[0]->tgl_pembayaran);
        $worksheet->getCell('J5')->setValue($data[0]->no_pembayaran);
        $worksheet->mergeCells("J5:L5");
        $worksheet->getCell('J6')->setValue($data[0]->no_tagihan);
        $worksheet->mergeCells("J6:K6");
        $worksheet->getCell('A12')->setValue($data[0]->perwakilan);
        $worksheet->getCell('A13')->setValue($data[0]->nama_pelanggan);
        $worksheet->getCell('A14')->setValue($data[0]->alamat_pelanggan);




        $baris_awal = 19;
        $subtotal = 0;
        $total = 0;
        $ongkir = 0;
        $worksheet->insertNewRowBefore(20, count($data));
        for ($i = 0; $i < count($data); $i++) {


            $tambahan_baris = $baris_awal + 1;

            $worksheet->setCellValue("A$tambahan_baris", ($i + 1));
            $worksheet->setCellValue("B$tambahan_baris", $data[$i]->nomor_pekerjaan);
            $worksheet->MergeCells("B$tambahan_baris:C$tambahan_baris");

            $worksheet->setCellValue("D$tambahan_baris", $data[$i]->nama_produk);
            $tebal =  $data[$i]->tebal_penawaran;
            $lebar =  $data[$i]->lebar_penawaran;
            $panjang =  $data[$i]->panjang_penawaran;

            $worksheet->setCellValue("E$tambahan_baris", $tebal);
            $worksheet->setCellValue("F$tambahan_baris", $lebar);
            $worksheet->setCellValue("G$tambahan_baris", $panjang);
            $worksheet->setCellValue("H$tambahan_baris", $data[$i]->jumlah);
            $worksheet->setCellValue("I$tambahan_baris", $data[$i]->berat);
            $worksheet->setCellValue("J$tambahan_baris", $data[$i]->harga);
            $worksheet->setCellValue("K$tambahan_baris", $data[$i]->subtotal);
            $worksheet->mergeCells("K$tambahan_baris:L$tambahan_baris");



            $subtotal += $data[$i]->subtotal;
            $ongkir += $data[$i]->ongkir;
            $total += $data[$i]->total;
            $baris_awal = $tambahan_baris;
        }
        $baris_setelah = $baris_awal + 2;
        $worksheet->setCellValue("K$baris_setelah", $subtotal);
        $worksheet->MergeCells("K$baris_setelah:L$baris_setelah");

        $baris_setelah += 1;
        $worksheet->setCellValue("K$baris_setelah", $subtotal * 0.11);
        $worksheet->MergeCells("K$baris_setelah:L$baris_setelah");

        $baris_setelah += 1;
        $worksheet->setCellValue("K$baris_setelah", $total);
        $worksheet->MergeCells("K$baris_setelah:L$baris_setelah");

        $baris_setelah += 2;
        $worksheet->setCellValue("A$baris_setelah", penyebut($total));
        $worksheet->MergeCells("A$baris_setelah:H$baris_setelah");

        $baris_setelah += 2;
        $worksheet->setCellValue("J$baris_setelah", "Bekasi," . ' ' . $data[0]->tgl_tagihan);
        $worksheet->MergeCells("J$baris_setelah:L$baris_setelah");










        $namaFile = $data[0]->no_tagihan;

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$namaFile.xlsx"); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        // $writer->save('report/quotation.xls');


    }


    // public function print($no_transaksi)
    // {
    //     $no_transaksi = str_replace('-', '/', $no_transaksi);

    //     $total = $this->model->detail($no_transaksi);
    //     $ttl = 0;
    //     foreach ($total as $t) {
    //         $ttl += $t->total;
    //     }


    //     $data = [
    //         'tittle' => 'Print Payment Document',
    //         'data' => $this->model->detail($no_transaksi),
    //         'total_penyebut' =>  penyebut($ttl),

    //     ];
    //     // dd($data);

    //     return view('payment.print', $data);
    // }
}
