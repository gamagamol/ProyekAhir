<?php

namespace App\Http\Controllers;

use App\Models\BillPaymentModel;
use App\Models\PurchaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use NumberFormatter;
use Symfony\Component\Console\Helper\FormatterHelper;
use Terbilang;
use App\Models\pegawaiModel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\QuotationController;

use function app\helper\penyebut;

class BillPaymentController extends Controller
{
    protected $model;
    protected $pembelianModel;
    protected $pegawaiModel;
    protected $QuotationController;
    public function __construct()
    {
        $this->model = new BillPaymentModel();
        $this->pembelianModel = new PurchaseModel();
        $this->pegawaiModel = new pegawaiModel();
        $this->QuotationController = new QuotationController();
    }
    public function index()
    {
        $serch = request()->get('serch');
        if ($serch) {
            if ($serch == 'All') {
                $data = $this->model->index();
            } else {

                $data = $this->model->index($serch);
            }
        } else {
            $data = $this->model->index();
        }

        // dd($data);

        $data = [
            'tittle' => "Bill Payment ",
            'data' => $data,
            'deta' => $this->model->index()
        ];
        return view('bill.index', $data);
    }



    public function show($no_penjualan)
    {


        $data = [
            "tittle" => "Bill payment",
            // 'no_penjualan' => $this->model->getNoSalesForBill()
            'no_penjualan' => $no_penjualan
        ];



        return view('bill.create', $data);
    }

    public function store(Request $request)
    {
        // prepare data

        // dd($request->all());
        $id_transaksi = $request->input('id_transaksi');
        $id_pengiriman = $request->input('id_pengiriman');
        $no_pengiriman = $request->input('no_pengiriman');

        $tgl_pengiriman =
            DB::table('pengiriman')
            ->select('tgl_pengiriman')
            ->where('no_pengiriman', "=", $no_pengiriman[0])
            ->orderBy('tgl_pengiriman', 'desc')
            ->first();


        $tgl_pengiriman = $tgl_pengiriman->tgl_pengiriman;

        // $rules = [
        //     'tgl_pembayaran' => " after_or_equal:$tgl_pengiriman",
        // ];
        // $message = [
        //     "tgl_pembayaran.after_or_equal" => "Choose a date after the delivery date or equal"
        // ];
        // $validated = Validator::make($request->all(), $rules, $message);
        // if ($validated->fails()) {
        //     return redirect()->back()->with("failed", "Choose a date after the delivery date or equal");
        // }





        // set no pembayaran
        $no_pengiriman = DB::table('pengiriman')
            ->select('no_pengiriman')
            ->where('id_transaksi', "=", $id_transaksi[0])
            ->first();
        $no_pengiriman = explode("-", $request->input('tgl_pembayaran'));





        $no_tagihan = $this->model->no_tagihan($request->input('tgl_pembayaran'));
        $no_tagihan = "INV/$no_tagihan/$no_pengiriman[0]/$no_pengiriman[1]/$no_pengiriman[2]";
        //  set when table able to insert

        $data_transaksi = [
            'status_transaksi' => "bill",
        ];
        // set table payment able insert
        $data_tagihan = [];
        for ($i = 0; $i < count($id_transaksi); $i++) {
            ${"data_tagihan$i"} = [
                'id_transaksi' => (int)$id_transaksi[$i],
                'id_pengiriman' => (int)$id_pengiriman[$i],
                'no_tagihan' => $no_tagihan,
                'tgl_tagihan' => $request->input('tgl_pembayaran'),

            ];
            $data_tagihan[$i] = ${"data_tagihan$i"};
        }

        // check varibael final
        // dump($id_transaksi);
        // dd($data_tagihan);


        $this->model->insert($data_transaksi, $data_tagihan, $id_transaksi);
        return redirect('bill')->with('success', " Data Entered Successfully Inovice number $no_tagihan");
    }


    public function detail($no_tagihan)
    {

        $data = $this->model->detail(str_replace("-", "/", $no_tagihan));
        $data = [
            'tittle' => "Detail Bill Payment",
            'data' => $data
        ];
        return view('bill.detail', $data);
    }

    public function print($no_transaksi)
    {
        $data = $this->model->print(str_replace("-", "/", $no_transaksi));
        $dueDate = $this->model->index(str_replace("-", "/", $no_transaksi));

        $goods = (count($data["goods"]) > 0) ? $data["goods"] : null;
        $service = (count($data["service"]) > 0) ? $data["service"] : null;
        $namaFile = $data["namaFile"];

        if ($goods != null) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/bill_template.xlsx');

            $worksheet = $spreadsheet->getActiveSheet();

            $worksheet->getCell('J4')->setValue($goods[0]->tgl_tagihan);
            $worksheet->getCell('J5')->setValue($dueDate[0]->DUE_DATE);
            $worksheet->mergeCells("J5:K5");
            $worksheet->getCell('J6')->setValue($goods[0]->no_tagihan);
            $worksheet->mergeCells("J6:K6");
            $worksheet->getCell('J7')->setValue($goods[0]->no_penjualan);
            $worksheet->mergeCells("J7:K7");
            $no_ref_qtn = ($goods[0]->no_po_customer == '' || $goods[0]->no_po_customer == '-') ? $goods[0]->no_penawaran : $goods[0]->no_po_customer;

            $worksheet->getCell('J8')->setValue($no_ref_qtn);
            $worksheet->getCell('J9')->setValue($goods[0]->nomor_transaksi);

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
                // penawaran

                $worksheet->setCellValue("D$tambahan_baris", $goods[$i]->nama_produk);
                $worksheet->setCellValue("E$tambahan_baris", $goods[$i]->tebal_transaksi);
                $worksheet->setCellValue("F$tambahan_baris", $goods[$i]->lebar_transaksi);
                $worksheet->setCellValue("G$tambahan_baris", $goods[$i]->panjang_transaksi);
                // bill
                $worksheet->setCellValue("H$tambahan_baris", $goods[$i]->nama_produk);
                $tebal =  $goods[$i]->tebal_penawaran;
                $lebar =  $goods[$i]->lebar_penawaran;
                $panjang =  $goods[$i]->panjang_penawaran;

                $worksheet->setCellValue("I$tambahan_baris", $tebal);
                $worksheet->setCellValue("J$tambahan_baris", $lebar);
                $worksheet->setCellValue("K$tambahan_baris", $panjang);
                $worksheet->setCellValue("L$tambahan_baris", $goods[$i]->jumlah);
                $worksheet->setCellValue("M$tambahan_baris", $goods[$i]->berat);
                $worksheet->setCellValue("N$tambahan_baris", "Rp" . number_format($goods[$i]->harga));
                $worksheet->setCellValue("O$tambahan_baris", $goods[$i]->subtotal);
                $worksheet->mergeCells("O$tambahan_baris:P$tambahan_baris");



                $subtotal += $goods[$i]->subtotal;
                $ongkir += $goods[$i]->ongkir;
                $total += $goods[$i]->total;
                $baris_awal = $tambahan_baris;
            }
            $baris_setelah = $baris_awal + 2;
            $worksheet->setCellValue("O$baris_setelah", "Rp" . number_format($subtotal, '2', ',', '.'));
            $worksheet->MergeCells("O$baris_setelah:P$baris_setelah");

            $baris_setelah += 1;
            $worksheet->setCellValue("O$baris_setelah", "Rp" . number_format($subtotal * 0.11, '2', ',', '.'));
            $worksheet->MergeCells("O$baris_setelah:P$baris_setelah");

            $baris_setelah += 1;
            $worksheet->setCellValue("O$baris_setelah", "Rp" . number_format($total, '2', ',', '.'));
            $worksheet->MergeCells("O$baris_setelah:P$baris_setelah");

            $baris_setelah += 2;
            $worksheet->setCellValue("A$baris_setelah", penyebut($total));
            $worksheet->MergeCells("A$baris_setelah:H$baris_setelah");

            $baris_setelah += 2;
            $worksheet->setCellValue("J$baris_setelah", "Bekasi," . ' ' . $goods[0]->tgl_tagihan);
            $worksheet->MergeCells("J$baris_setelah:L$baris_setelah");
        }

        if ($service != null) {
            $spreadsheet1 = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/bill_template_2.xlsx');

            $worksheet1 = $spreadsheet1->getActiveSheet();

            $worksheet1->getCell('J4')->setValue($service[0]->tgl_tagihan);
            $worksheet1->getCell('J5')->setValue($dueDate[0]->DUE_DATE);
            $worksheet1->mergeCells("J5:K5");
            $worksheet1->getCell('J6')->setValue($service[0]->no_tagihan);
            $worksheet1->mergeCells("J6:K6");
            $worksheet1->getCell('J7')->setValue($service[0]->no_penjualan);
            $worksheet1->mergeCells("J7:K7");
            $no_ref_qtn = ($service[0]->no_po_customer == '' || $service[0]->no_po_customer == '-') ? $service[0]->no_penawaran : $service[0]->no_po_customer;

            $worksheet1->getCell('J8')->setValue($no_ref_qtn);
            $worksheet1->getCell('J9')->setValue($service[0]->nomor_transaksi);

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
                // penawaran

                $worksheet1->setCellValue("D$tambahan_baris", $service[$i]->nama_produk);
                $worksheet1->setCellValue("E$tambahan_baris", $service[$i]->tebal_transaksi);
                $worksheet1->setCellValue("F$tambahan_baris", $service[$i]->lebar_transaksi);
                $worksheet1->setCellValue("G$tambahan_baris", $service[$i]->panjang_transaksi);
                // bill
                $worksheet1->setCellValue("H$tambahan_baris", $service[$i]->nama_produk);
                $tebal =  $service[$i]->tebal_penawaran;
                $lebar =  $service[$i]->lebar_penawaran;
                $panjang =  $service[$i]->panjang_penawaran;

                $worksheet1->setCellValue("I$tambahan_baris", $tebal);
                $worksheet1->setCellValue("J$tambahan_baris", $lebar);
                $worksheet1->setCellValue("K$tambahan_baris", $panjang);
                $worksheet1->setCellValue("L$tambahan_baris", $service[$i]->jumlah);
                $worksheet1->setCellValue("M$tambahan_baris", $service[$i]->berat);
                $worksheet1->setCellValue("N$tambahan_baris", "Rp" . number_format($service[$i]->harga));
                $worksheet1->setCellValue("O$tambahan_baris", $service[$i]->subtotal);
                $worksheet1->mergeCells("O$tambahan_baris:P$tambahan_baris");



                $subtotal += $service[$i]->subtotal;
                $ongkir += $service[$i]->ongkir;
                $total += $service[$i]->total;
                $baris_awal = $tambahan_baris;
            }
            $baris_setelah = $baris_awal + 2;
            $worksheet1->setCellValue("O$baris_setelah", "Rp" . number_format($subtotal, '2', ',', '.'));
            $worksheet1->MergeCells("O$baris_setelah:P$baris_setelah");

            $baris_setelah += 1;
            $worksheet1->setCellValue("O$baris_setelah", "Rp" . number_format($subtotal * 0.11, '2', ',', '.'));
            $worksheet1->MergeCells("O$baris_setelah:P$baris_setelah");

            $baris_setelah += 1;
            $worksheet1->setCellValue("O$baris_setelah", "Rp" . number_format($subtotal * 0.12, '2', ',', '.'));
            $worksheet1->MergeCells("O$baris_setelah:P$baris_setelah");

            $baris_setelah += 1;
            $worksheet1->setCellValue("O$baris_setelah", "Rp" . number_format($total, '2', ',', '.'));
            $worksheet1->MergeCells("O$baris_setelah:P$baris_setelah");

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



    protected function getSalesDetail($no_penjualan)
    {

        // echo str_replace('-', '/', $no_penjualan);die;
        $data = $this->model->show(str_replace('-', '/', $no_penjualan));
        // dd($data);
        return response()->json($data);
    }



    // public function bill_email($no_transaksi){
    //     $no_transaksi = str_replace('-', '/', $no_transaksi);
    //     $total = $this->model->detail($no_transaksi);
    //     $ttl = 0;
    //     foreach ($total as $t) {
    //         $ttl += $t->total;
    //     }


    //     $data = [
    //         'tittle' => "Print INVOICE",
    //         'data' => $this->model->detail($no_transaksi),
    //         'total_penyebut' => $total = penyebut($ttl),

    //     ];
    //     return $data;
    // }
}
