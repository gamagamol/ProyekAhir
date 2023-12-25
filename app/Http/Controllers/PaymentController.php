<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentModel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use function app\helper\penyebut;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\QuotationController;



class PaymentController extends Controller
{
    public $model;
    public $QuotationController;

    public function __construct()
    {
        $this->model = new PaymentModel();
        $this->QuotationController = new QuotationController();
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


        // dd($data);

        $data = [
            'tittle' => 'Payment',
            'data' => $data,
            'deta' => $this->model->index()

        ];
        // dd($data);
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
        // dd($data_pembayaran);


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
        // dd("sni");
        $data = $this->model->print(str_replace("-", "/", $no_transaksi));
        $dueDate = $this->model->index(str_replace("-", "/", $no_transaksi));

        $goods = (count($data["goods"]) > 0) ? $data["goods"] : null;
        $service = (count($data["service"]) > 0) ? $data["service"] : null;
        $namaFile = $data["namaFile"];
        // dd($data);

        if ($goods != null) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/payment_template.xlsx');

            $worksheet = $spreadsheet->getActiveSheet();

            $worksheet->getCell('J4')->setValue($goods[0]->tgl_pembayaran);
            $worksheet->getCell('J5')->setValue($goods[0]->no_pembayaran);
            $worksheet->mergeCells("J5:L5");
            $worksheet->getCell('J6')->setValue($goods[0]->no_tagihan);
            $worksheet->mergeCells("J6:K6");
            $worksheet->getCell('A12')->setValue($goods[0]->perwakilan);
            $worksheet->getCell('A13')->setValue($goods[0]->nama_pelanggan);
            $worksheet->getCell('A14')->setValue($goods[0]->alamat_pelanggan);




            $baris_awal = 19;
            $subtotal = 0;
            $total = 0;
            $ongkir = 0;
            $worksheet->insertNewRowBefore(20, count($goods));
            for ($i = 0; $i < count($goods); $i++) {


                $tambahan_baris = $baris_awal + 1;

                $worksheet->setCellValue("A$tambahan_baris", ($i + 1));
                $worksheet->setCellValue("B$tambahan_baris", $goods[$i]->nomor_pekerjaan);
                $worksheet->MergeCells("B$tambahan_baris:C$tambahan_baris");

                $worksheet->setCellValue("D$tambahan_baris", $goods[$i]->nama_produk);
                $tebal =  $goods[$i]->tebal_penawaran;
                $lebar =  $goods[$i]->lebar_penawaran;
                $panjang =  $goods[$i]->panjang_penawaran;

                $worksheet->setCellValue("E$tambahan_baris", $tebal);
                $worksheet->setCellValue("F$tambahan_baris", $lebar);
                $worksheet->setCellValue("G$tambahan_baris", $panjang);
                $worksheet->setCellValue("H$tambahan_baris", $goods[$i]->jumlah);
                $worksheet->setCellValue("I$tambahan_baris", $goods[$i]->berat);
                $worksheet->setCellValue("J$tambahan_baris", $goods[$i]->harga);
                $worksheet->setCellValue("K$tambahan_baris", $goods[$i]->subtotal);
                $worksheet->mergeCells("K$tambahan_baris:L$tambahan_baris");



                $subtotal += $goods[$i]->subtotal;
                $ongkir += $goods[$i]->ongkir;
                $total += $goods[$i]->total;
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
            $worksheet->setCellValue("J$baris_setelah", "Bekasi," . ' ' . $goods[0]->tgl_tagihan);
            $worksheet->MergeCells("J$baris_setelah:L$baris_setelah");
        }
        if ($service != null) {
            $spreadsheet1 = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/payment_template_2.xlsx');

            $worksheet1 = $spreadsheet1->getActiveSheet();

            $worksheet1->getCell('J4')->setValue($service[0]->tgl_pembayaran);
            $worksheet1->getCell('J5')->setValue($service[0]->no_pembayaran);
            $worksheet1->mergeCells("J5:L5");
            $worksheet1->getCell('J6')->setValue($service[0]->no_tagihan);
            $worksheet1->mergeCells("J6:K6");
            $worksheet1->getCell('A12')->setValue($service[0]->perwakilan);
            $worksheet1->getCell('A13')->setValue($service[0]->nama_pelanggan);
            $worksheet1->getCell('A14')->setValue($service[0]->alamat_pelanggan);




            $baris_awal = 19;
            $subtotal = 0;
            $total = 0;
            $ongkir = 0;
            $worksheet1->insertNewRowBefore(20, count($service));
            for ($i = 0; $i < count($service); $i++) {


                $tambahan_baris = $baris_awal + 1;

                $worksheet1->setCellValue("A$tambahan_baris", ($i + 1));
                $worksheet1->setCellValue("B$tambahan_baris", $service[$i]->nomor_pekerjaan);
                $worksheet1->MergeCells("B$tambahan_baris:C$tambahan_baris");

                $worksheet1->setCellValue("D$tambahan_baris", $service[$i]->nama_produk);
                $tebal =  $service[$i]->tebal_penawaran;
                $lebar =  $service[$i]->lebar_penawaran;
                $panjang =  $service[$i]->panjang_penawaran;

                $worksheet1->setCellValue("E$tambahan_baris", $tebal);
                $worksheet1->setCellValue("F$tambahan_baris", $lebar);
                $worksheet1->setCellValue("G$tambahan_baris", $panjang);
                $worksheet1->setCellValue("H$tambahan_baris", $service[$i]->jumlah);
                $worksheet1->setCellValue("I$tambahan_baris", $service[$i]->berat);
                $worksheet1->setCellValue("J$tambahan_baris", $service[$i]->harga);
                $worksheet1->setCellValue("K$tambahan_baris", $service[$i]->subtotal);
                $worksheet1->mergeCells("K$tambahan_baris:L$tambahan_baris");



                $subtotal += $service[$i]->subtotal;
                $ongkir += $service[$i]->ongkir;
                $total += $service[$i]->total;
                $baris_awal = $tambahan_baris;
            }
            $baris_setelah = $baris_awal + 2;
            $worksheet1->setCellValue("K$baris_setelah", $subtotal);
            $worksheet1->MergeCells("K$baris_setelah:L$baris_setelah");

            $baris_setelah += 1;
            $worksheet1->setCellValue("K$baris_setelah", $subtotal * 0.11);
            $worksheet1->MergeCells("K$baris_setelah:L$baris_setelah");
            $baris_setelah += 1;
            $worksheet1->setCellValue("K$baris_setelah", $subtotal * 0.12);
            $worksheet1->MergeCells("K$baris_setelah:L$baris_setelah");

            $baris_setelah += 1;
            $worksheet1->setCellValue("K$baris_setelah", $total);
            $worksheet1->MergeCells("K$baris_setelah:L$baris_setelah");

            $baris_setelah += 2;
            $worksheet1->setCellValue("A$baris_setelah", penyebut($total));
            $worksheet1->MergeCells("A$baris_setelah:H$baris_setelah");

            $baris_setelah += 2;
            $worksheet1->setCellValue("J$baris_setelah", "Bekasi," . ' ' . $service[0]->tgl_tagihan);
            $worksheet1->MergeCells("J$baris_setelah:L$baris_setelah");
        }



        if ($goods != null && $service != null) {
            $this->QuotationController->printAll($spreadsheet, $spreadsheet1, $namaFile);
        } else if ($goods != null) {

            $this->QuotationController->printAll($spreadsheet, null, $namaFile);
        } else if ($service != null) {
            $this->QuotationController->printAll(null, $spreadsheet1, $namaFile);
        }
    }
}
