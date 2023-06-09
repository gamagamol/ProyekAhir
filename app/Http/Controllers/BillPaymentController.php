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


use function app\helper\penyebut;

class BillPaymentController extends Controller
{
    protected $model;
    protected $pembelianModel;
    protected $pegawaiModel;
    public function __construct()
    {
        $this->model = new BillPaymentModel();
        $this->pembelianModel = new PurchaseModel();
        $this->pegawaiModel = new pegawaiModel();
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


        $data = [
            'tittle' => "Bill Payment ",
            'data' => $data,
            'deta' => $this->model->index()
        ];
        return view('bill.index', $data);
    }



    public function show()
    {

        $data = [
            "tittle" => "Bill payment",
            'no_penjualan' => $this->model->getNoSalesForBill()
        ];

        // dd($data);

        return view('bill.create', $data);
    }

    public function store(Request $request)
    {
        // prepare data

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
        $rules = [
            'tgl_pembayaran' => " after_or_equal:$tgl_pengiriman",
        ];
        $message = [
            "tgl_pembayaran.after_or_equal" => "Choose a date after the delivery date or equal"
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->back()->with("failed", "Choose a date after the delivery date or equal");
        }





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
        $data = $this->model->detail(str_replace("-", "/", $no_transaksi));
        $dueDate = $this->model->index(str_replace("-", "/", $no_transaksi));

        // dd($data);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/bill_template.xlsx');

        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->getCell('J4')->setValue($data[0]->tgl_tagihan);
        $worksheet->getCell('J5')->setValue($dueDate[0]->DUE_DATE);
        $worksheet->mergeCells("J5:K5");
        $worksheet->getCell('J6')->setValue($data[0]->no_tagihan);
        $worksheet->mergeCells("J6:K6");
        $worksheet->getCell('J7')->setValue($data[0]->no_penjualan);
        $worksheet->mergeCells("J7:K7");
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

        $baris_setelah += 2;
        $worksheet->setCellValue("A$baris_setelah", penyebut($total));
        $worksheet->MergeCells("A$baris_setelah:H$baris_setelah");

        $baris_setelah += 2;
        $worksheet->setCellValue("J$baris_setelah", "Bekasi," .' '. $data[0]->tgl_tagihan);
        $worksheet->MergeCells("J$baris_setelah:L$baris_setelah");










        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Bill Report.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        // $writer->save('report/quotation.xls');


    }

    // public function print($no_tagihan)
    // {
    //     $no_tagihan = str_replace('-', '/', $no_tagihan);
    //     // dd($no_tagihan);
    //     $total = $this->model->detail($no_tagihan);
    //     $ttl = 0;
    //     foreach ($total as $t) {
    //         $ttl += $t->total;
    //     }
    //     $dueDate = $this->model->index($no_tagihan);


    //     $data = [
    //         'tittle' => "Print INVOICE",
    //         'data' => $this->model->detail($no_tagihan),
    //         'total_penyebut' => $total = penyebut($ttl),
    //         'due_date' => $dueDate[0]->DUE_DATE,
    //         'pegawai'=>$this->pegawaiModel->getEmployee('FINANCE')

    //     ];

    //     // dd($data);
    //     return view('bill.print', $data);
    // }

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
