<?php

namespace App\Http\Controllers;

use App\Models\QuotationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\SalesModel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class SalesController extends Controller
{
    protected $SalesModel;
    protected $quotationModel;
    public function __construct()
    {
        $this->SalesModel = new SalesModel();
        $this->quotationModel = new QuotationModel();
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
        $kode_transaksi = $request->input('kode_transaksi');
        $tgl_penjualan = $request->input('tgl_penjualan');
        // check data
        $quotation = $this->SalesModel->edit($kode_transaksi);
        $tgl_quotation = $quotation[0]->tgl_penawaran;
        $rules = [
            'tgl_penjualan' => " after_or_equal:$tgl_quotation",
            // 'id_transaksi[]'=>'required'
        ];
        $message = [
            "tgl_penjualan.after_or_equal" => "Choose a date after the quotation date or equal",
            // "id_transaksi[].required" => "Pelase choose your item please"
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->back()->with("failed", "Choose a date after the quotation date or equal");
        }

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
                'tgl_penjualan' => $tgl_penjualan
            ];



            $data_detail_penjualan[] = [
                'id_penjualan' => 0,
                'id_produk' => $quotation[$i]->id_produk,
                'jumlah_detail_penjualan' => $quotation[$i]->jumlah
            ];
        }

        // update data quotation


        // dump($data_penjualan);
        // dump($data_detail_penjualan);
        // die;


        $no_penjualan = $this->SalesModel->insert_penjualan($id_transaksi, $data_penjualan, $data_detail_penjualan, $kode_transaksi);
        return redirect()->route('sales.detail', str_replace('/', '-', $no_penjualan))
            ->with('success', "Data entered successfully, Your Sales Number $no_penjualan ");
    }
    public function detail($no_penjualan)
    {

        $data = $this->SalesModel->detail(str_replace("-", "/", $no_penjualan));
        // dd($data);ur
        $data = [
            'tittle' => "Detail Sales Order",
            'data' => $data
        ];
        return view('sales.detail', $data);
    }

    public function print($no_transaksi)
    {
        $data = $this->SalesModel->detail(str_replace("-", "/", $no_transaksi));


        // dd($data);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/sales_template.xlsx');

        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->getCell('J4')->setValue($data[0]->tgl_penjualan);
        $worksheet->getCell('J5')->setValue($data[0]->no_penjualan);
        $worksheet->mergeCells("J5:K5");
        $worksheet->getCell('J6')->setValue($data[0]->no_penawaran);
        $worksheet->getCell('A12')->setValue($data[0]->perwakilan);
        $worksheet->getCell('A13')->setValue($data[0]->nama_pelanggan);
        $worksheet->getCell('A14')->setValue($data[0]->alamat_pelanggan);
        $worksheet->getCell('D17')->setValue($data[0]->layanan);




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
            $tebal =  $data[$i]->tebal_transaksi;
            $lebar =  $data[$i]->lebar_transaksi;
            $panjang =  $data[$i]->panjang_transaksi;

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

        $baris_setelah += 9;
        $worksheet->setCellValue("H$baris_setelah", $data[0]->nama_pegawai);







        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Sales Report.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        // $writer->save('report/quotation.xls');


    }
}
