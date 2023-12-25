<?php

namespace App\Http\Controllers;

use App\Models\QuotationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\SalesModel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\QuotationController;


class SalesController extends Controller
{
    protected $SalesModel;
    protected $quotationModel;
    protected $quotationController;
    public function __construct()
    {
        $this->SalesModel = new SalesModel();
        $this->quotationModel = new QuotationModel();
        $this->quotationController = new QuotationController();
    }
    public function index()
    {
        $serch = request()->get('serch');
        if ($serch) {

            $data = $this->SalesModel->index($serch);
        } else {
            $data = $this->SalesModel->index();
        }
        // dump(DB::table('penjualan')->distinct()->select('no_penjualan')->get());
        $data = [
            'tittle' => 'Sales Order',
            "data" => $data,
            "deta" => DB::table('penjualan')->distinct()->select('no_penjualan')->get()

        ];

        return view('sales.index', $data);
    }

    public function show($kode_transaksi)
    {
        $data = [
            'tittle' => "Create Sales",
            "data" => $this->SalesModel->show($kode_transaksi),
        ];
        return view('sales.create', $data);
    }

    public function store(Request $request)
    {
        // echo 'test';die;
        $kode_transaksi = $request->input('kode_transaksi');
        $tgl_penjualan = $request->input('tgl_penjualan');
        // check data
        $quotation = $this->SalesModel->edit($kode_transaksi);

        $no_po_customer = $request->input('no_po_customer');
        // $tgl_quotation = $quotation[0]->tgl_penawaran;
        // $rules = [
        //     'tgl_penjualan' => " after_or_equal:$tgl_quotation",
        //     // 'id_transaksi[]'=>'required'
        // ];
        // $message = [
        //     "tgl_penjualan.after_or_equal" => "Choose a date after the quotation date or equal",
        //     // "id_transaksi[].required" => "Pelase choose your item please"
        // ];
        // $validated = Validator::make($request->all(), $rules, $message);
        // if ($validated->fails()) {
        //     return redirect()->back()->with("failed", "Choose a date after the quotation date or equal");
        // }

        if (!isset($request->id_transaksi)) {
            return redirect()->back()->with("failed", "Choose Item First !");
        }

        if (count($quotation) != count($request->id_transaksi)) {
            $id_transaksi_quotation = [];
            foreach ($quotation as $quo) {
                array_push($id_transaksi_quotation, $quo->id_transaksi);
            }

            $id_transaksi_tidak_terpakai = array_diff($id_transaksi_quotation, $request->id_transaksi);

            $this->quotationModel->updateIdTidakTerpakai($id_transaksi_tidak_terpakai, ['tidak_terpakai' => 1]);

            $quotation = $this->SalesModel->edit($kode_transaksi, $request->id_transaksi);
        }

        // dd('test');



        //    kumpulan array data penjualan
        $id_transaksi = [];
        $data_penjualan = [];
        $data_detail_penjualan = [];

        // Persiapan no penjualan
        $no_penjualan = $this->SalesModel->no_penjualan($tgl_penjualan);
        $tgl_penjualan = $request->input('tgl_penjualan');
        $no_quotation = explode('-', $tgl_penjualan);
        $no_penjualan = "SO/$no_penjualan/$no_quotation[0]/$no_quotation[1]/$no_quotation[2]";




        for ($i = 0; $i < count($quotation); $i++) {

            $id_transaksi[] = $quotation[$i]->id_transaksi;

            $data_penjualan[] = [
                'id_transaksi' => $quotation[$i]->id_transaksi,
                'no_penjualan' => $no_penjualan,
                'tgl_penjualan' => $tgl_penjualan,
                'no_po_customer' => $no_po_customer
            ];



            $data_detail_penjualan[] = [
                'id_penjualan' => 0,
                'id_produk' => $quotation[$i]->id_produk,
                'jumlah_detail_penjualan' => $quotation[$i]->jumlah
            ];
        }



        $no_penjualan = $this->SalesModel->insert_penjualan($id_transaksi, $data_penjualan, $data_detail_penjualan, $kode_transaksi);
        return redirect()->route('sales.detail', str_replace('/', '-', $no_penjualan))
            ->with('success', "Data entered successfully, Your Sales Number $no_penjualan ");
    }
    public function detail($no_penjualan)
    {

        $data = $this->SalesModel->detail(str_replace("-", "/", $no_penjualan));
        // dd($data);
        $data = [
            'tittle' => "Detail Sales Order",
            'data' => $data
        ];
        return view('sales.detail', $data);
    }

    public function print($no_transaksi)
    {
        $data = $this->SalesModel->print(str_replace("-", "/", $no_transaksi));

        $goods = (count($data["goods"]) > 0) ? $data["goods"] : null;
        $service = (count($data["service"]) > 0) ? $data["service"] : null;
        // $namaFile = $data["namaFile"];
        $namaFile = str_replace("/", "_", $data["namaFile"]);



        if ($goods != null) {


            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/sales_template.xlsx');

            $worksheet = $spreadsheet->getActiveSheet();

            $worksheet->getCell('J4')->setValue($goods[0]->tgl_penjualan);
            $worksheet->getCell('J5')->setValue($goods[0]->no_penjualan);
            $worksheet->mergeCells("J5:K5");
            $no_ref_qtn = ($goods[0]->no_po_customer == '' || $goods[0]->no_po_customer == '-') ? $goods[0]->no_penawaran : $goods[0]->no_po_customer;

            $worksheet->getCell('J6')->setValue($no_ref_qtn);
            $worksheet->getCell('J7')->setValue($goods[0]->nomor_transaksi);
            $worksheet->getCell('A12')->setValue($goods[0]->perwakilan);
            $worksheet->getCell('A13')->setValue($goods[0]->nama_pelanggan);
            $worksheet->getCell('A14')->setValue($goods[0]->alamat_pelanggan);
            $worksheet->getCell('D17')->setValue($goods[0]->layanan);




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

            $baris_setelah += 9;
            $worksheet->setCellValue("H$baris_setelah", $goods[0]->nama_pegawai);
        }

        if ($service != null) {
            $spreadsheet1 = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/sales_template_2.xlsx');

            $worksheet1 = $spreadsheet1->getActiveSheet();

            $worksheet1->getCell('J4')->setValue($service[0]->tgl_penjualan);
            $worksheet1->getCell('J5')->setValue($service[0]->no_penjualan);
            $worksheet1->mergeCells("J5:K5");
            $no_ref_qtn = ($service[0]->no_po_customer == '' || $service[0]->no_po_customer == '-') ? $service[0]->no_penawaran : $service[0]->no_po_customer;

            $worksheet1->getCell('J6')->setValue($no_ref_qtn);
            $worksheet1->getCell('J7')->setValue($service[0]->nomor_transaksi);
            $worksheet1->getCell('A12')->setValue($service[0]->perwakilan);
            $worksheet1->getCell('A13')->setValue($service[0]->nama_pelanggan);
            $worksheet1->getCell('A14')->setValue($service[0]->alamat_pelanggan);
            $worksheet1->getCell('D17')->setValue($service[0]->layanan);




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

            $baris_setelah += 9;
            $worksheet1->setCellValue("H$baris_setelah", $service[0]->nama_pegawai);
        }




        if ($goods != null && $service != null) {
            $this->quotationController->printAll($spreadsheet, $spreadsheet1, $namaFile);
        } else if ($goods != null) {

            $this->quotationController->printAll($spreadsheet, null, $namaFile);
        } else if ($service != null) {
            $this->quotationController->printAll(null, $spreadsheet1, $namaFile);
        }
    }
}
