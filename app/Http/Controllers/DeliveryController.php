<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\GoodsController;
use App\Models\SalesModel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class DeliveryController extends Controller
{
    public $model;
    public $PDF;
    public $QuotationController;
    public $GoodsController;
    public $SalesModel;
    public function __construct()
    {
        $this->model = new DeliveryModel();
        $this->QuotationController = new QuotationController;
        $this->GoodsController = new GoodsController;
        $this->SalesModel = new SalesModel();
    }
    public function index()
    {
        $serch = request()->get('serch');
        if ($serch) {
            // dd($serch);
            $data = $this->model->index($serch);
        } else {
            $data = $this->model->index();
        }


        $data = [
            'tittle' => "Delivery Order",
            'data' => $data,
            'deta' => $this->model->index()
        ];
        return view('delivery.index', $data);
    }

    public function show($no_penerimaan)
    {
        $no_penerimaan = str_replace('-', '/', $no_penerimaan);
        $penerimaan = $this->model->show($no_penerimaan);
        $data = [
            'tittle' => "Delivery Order",
            "data" => $penerimaan
        ];
        return view("delivery.create", $data);
    }

    public function store(Request $request)
    {
        // perispan Variable
        $tgl_pengiriman = $request->post('tgl_pengiriman');
        $id_transaksi = $request->post('id_transaksi');
        $no_penerimaan = $request->input('no_penerimaan');
        $penerimaan = $this->model->edit($no_penerimaan);
        // dd($penerimaan);

        $tgl_penerimaan = $penerimaan[0]->tgl_penerimaan;
        $unit = $request->input('unit');
        $id_produk = $request->input('id_produk');
        $id_penawaran = $request->input('id_penawaran');
        $id_penerimaan_barang = $request->input('id_penerimaan_barang');
        $tebal_transaksi = $request->input('tebal_transaksi');
        $lebar_transaksi = $request->input('lebar_transaksi');
        $panjang_transaksi = $request->input('panjang_transaksi');
        $bentuk_produk = $request->input('bentuk_produk');
        $layanan = $request->input('layanan');

        // dd($request->input());
        // persiapan array
        $produk = [];
        $arr_produk = [];


        if ($unit) {


            // mengisi variable produk
            for ($ipo = 0; $ipo < count($id_produk); $ipo++) {
                foreach ($penerimaan as $quoi) {
                    if ($quoi->id_produk == $id_produk[$ipo]) {
                        if ($quoi->id_penawaran == $id_penawaran[$ipo]) {

                            $produk[$ipo] = [
                                'id_penawaran' => $quoi->id_penawaran,
                                'id_produk' => $id_produk[$ipo],
                                'unit' => (int) $unit[$ipo],
                                'id_penerimaan_barang' => $quoi->id_penerimaan_barang,
                                'berat' => $this->QuotationController->CalculateWeight(
                                    $bentuk_produk[$ipo],
                                    $layanan[$ipo],
                                    $tebal_transaksi[$ipo],
                                    $lebar_transaksi[$ipo],
                                    $panjang_transaksi[$ipo],
                                    (int) $unit[$ipo]
                                ),
                                'harga' => $quoi->harga


                            ];
                        }
                    }
                }
            }



            // // mengisi arr produk
            $ipdk = 0;
            foreach ($penerimaan as $quo) {
                $total_unit_produk = 0;
                foreach ($produk as $prdk) {
                    if ($prdk['id_produk'] == $quo->id_produk) {
                        if ($prdk['id_penawaran'] == $quo->id_penawaran) {

                            if ($quo->sisa_detail_penerimaan) {
                                $arr_produk[$ipdk] = [
                                    'id_produk' => $quo->id_produk,
                                    'unit' => $total_unit_produk += $prdk['unit'],
                                    'jumlah_unit' => $quo->sisa_detail_penerimaan,



                                ];
                            } else {

                                $arr_produk[$ipdk] = [
                                    'id_produk' => $quo->id_produk,
                                    'unit' => $total_unit_produk += $prdk['unit'],
                                    'jumlah_unit' => $quo->jumlah_detail_penerimaan,



                                ];
                            }
                        }
                    }
                }
                $ipdk++;
            }
            // dump($penerimaan);
            // dd($produk);


            // validasi unit produk
            foreach ($arr_produk as $apdk) {
                foreach ($penerimaan as $quo1) {

                    for ($pdkv = 0; $pdkv < count($id_produk); $pdkv++) {
                        if ($produk[$pdkv]['unit'] == null) {
                            return redirect()->back()->with("failed", "Please Fill Unit Coloumn more then 0 ");
                        } else {
                            if ($id_produk[$pdkv] == $apdk['id_produk']) {
                                if ($prdk['id_penawaran'] == $quo->id_penawaran) {
                                    if ($apdk['unit'] > $apdk['jumlah_unit']) {
                                        return redirect()->back()->with("failed", "Please Fill Unit Coloumn with Less Value");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }





        // check isi produk dan array produk
        // dump($produk);
        // dd($arr_produk);





        if ($id_transaksi == null) {
            return back()->with("failed", "Please select the item you want to send first!");
        }
        $rules = [
            'tgl_pengiriman' => " after_or_equal:$tgl_penerimaan",
        ];
        $message = [
            "tgl_pengiriman.after_or_equal" => "Choose a date after the goods receipt date or equal"
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->back()->with("failed", "Choose a date after the goods receipt date or equal");
        }

        //    kumpulan array data penjualan
        $data_pengiriman = [];
        $data_detail_pengiriman = [];
        $arr_no_pengiriman = [];
        $data = [];


        // Persiapan no penerimaan
        for ($pop = 0; $pop < count($penerimaan); $pop++) {

            $no_pengiriman = $penerimaan[$pop]->no_pengiriman;
            if ($no_pengiriman) {
                break;
            }
        }





        $no_pengiriman = $this->model->no_delivery($tgl_pengiriman, $unit);
        if ($request->select_all != 'select_all') {
            if (gettype($no_pengiriman) == 'array') {

                foreach ($no_pengiriman as $anp) {

                    $no_purchase = explode('-', $tgl_pengiriman);
                    $pengiriman = "DO/$anp/$no_purchase[0]/$no_purchase[1]/$no_purchase[2]";
                    array_push($arr_no_pengiriman, $pengiriman);
                }
            } else {
                $no_purchase = explode(
                    '-',
                    $tgl_pengiriman
                );
                $pengiriman = "DO/$no_pengiriman/$no_purchase[0]/$no_purchase[1]/$no_purchase[2]";
                array_push($arr_no_pengiriman, $pengiriman);
            }
        } else {
            $no_purchase = explode(
                '-',
                $tgl_pengiriman
            );
            $pengiriman = "DO/$no_pengiriman/$no_purchase[0]/$no_purchase[1]/$no_purchase[2]";
            array_push($arr_no_pengiriman, $pengiriman);
        }






        if ($unit) {

            for ($i = 0; $i < count($unit); $i++) {
                foreach ($penerimaan as $pcsss) {
                    if ($pcsss->id_produk == $produk[$i]['id_produk']) {
                        if ($pcsss->id_penawaran == $produk[$i]['id_penawaran']) {
                            $data_pengiriman[$i] = [
                                'id_penerimaan_barang' => $pcsss->id_penerimaan_barang,
                                'id_transaksi' => $id_transaksi[$i],
                                'id_penjualan' => $pcsss->id_penjualan,
                                'id_pembelian' => $pcsss->id_pembelian,
                                'no_pengiriman' => $arr_no_pengiriman[0],
                                'tgl_pengiriman' => $tgl_pengiriman
                            ];

                            if ($pcsss->sisa_detail_penerimaan) {
                                $data_detail_pengiriman[$i] = [
                                    'id_pengiriman' => 0,
                                    'id_produk' => $id_produk[$i],
                                    'jumlah_detail_pengiriman' => (int) $unit[$i],
                                    'sisa_detail_pengiriman' => (int) $pcsss->sisa_detail_penerimaan - $unit[$i],
                                    'berat_detail_pengiriman' => $produk[$i]['berat'],
                                    'ppn_detail_pengiriman' => ($produk[$i]['harga'] * $produk[$i]['berat']) * 0.11,
                                    'subtotal_detail_pengiriman' => $produk[$i]['harga'] * $produk[$i]['berat'],
                                    'total_detail_pengiriman' => ($produk[$i]['harga'] * $produk[$i]['berat']) + (($produk[$i]['harga'] * $produk[$i]['berat']) * 0.11)



                                ];

                                $jumlah_item = (int) $unit[$i];
                                $berat_item = $produk[$i]['berat'];
                            } else {
                                $data_detail_pengiriman[$i] = [
                                    'id_pengiriman' => 0,
                                    'id_produk' => $id_produk[$i],
                                    'jumlah_detail_pengiriman' => (int) $unit[$i],
                                    'sisa_detail_pengiriman' => (int) $pcsss->jumlah_detail_penerimaan - $unit[$i],
                                    'berat_detail_pengiriman' => $produk[$i]['berat'],
                                    'ppn_detail_pengiriman' => ($produk[$i]['harga'] * $produk[$i]['berat']) * 0.11,

                                    'subtotal_detail_pengiriman' => $produk[$i]['harga'] * $produk[$i]['berat'],
                                    'total_detail_pengiriman' => ($produk[$i]['harga'] * $produk[$i]['berat']) + (($produk[$i]['harga'] * $produk[$i]['berat']) * 0.11)
                                ];
                                $jumlah_item = (int) $unit[$i];
                                $berat_item = $produk[$i]['berat'];
                            }

                            $data[$i] = [
                                'tgl_pengiriman' => $tgl_pengiriman,
                                'no_pengiriman' => $arr_no_pengiriman[0],
                                'nama_produk' => $pcsss->nama_produk,
                                'tebal_produk' => $pcsss->tebal_transaksi,
                                'lebar_produk' => $pcsss->lebar_transaksi,
                                'panjang_produk' => $pcsss->panjang_transaksi,
                                'jumlah' => (int) $unit[$i],
                                'berat' => $produk[$i]['berat'],
                                'no_penjualan' => $pcsss->no_penjualan,
                                'perwakilan' => $pcsss->perwakilan,
                                'nama_pelanggan' => $pcsss->nama_pelanggan,
                                'alamat_pelanggan' => $pcsss->alamat_pelanggan,
                                'nomor_pekerjaan' => $pcsss->nomor_pekerjaan,
                                'jumlah_penawaran' => $pcsss->jumlah,
                                'berat_penawaran' => $pcsss->berat,
                            ];
                        }
                    }
                }
            }
        } else {
            // $i = 0;
            for ($i = 0; $i < count($id_transaksi); $i++) {
                foreach ($penerimaan as $pcss) {
                    if ($pcss->id_transaksi == $id_transaksi[$i]) {
                        $data_pengiriman[$i] = [
                            'id_penerimaan_barang' => $pcss->id_penerimaan_barang,
                            'id_transaksi' => $pcss->id_transaksi,
                            'id_penjualan' => $pcss->id_penjualan,
                            'id_pembelian' => $pcss->id_pembelian,
                            'no_pengiriman' => $arr_no_pengiriman[0],
                            'tgl_pengiriman' => $tgl_pengiriman
                        ];


                        if ($pcss->sisa_detail_penerimaan) {
                            $berat = $this->QuotationController->CalculateWeight(
                                $pcss->bentuk_produk,
                                $pcss->layanan,
                                $pcss->tebal_penawaran,
                                $pcss->lebar_penawaran,
                                $pcss->panjang_penawaran,
                                $pcss->sisa_detail_penerimaan,

                            );

                            $data_detail_pengiriman[$i] = [
                                'id_pengiriman' => 0,
                                'id_produk' => $pcss->id_produk,
                                'jumlah_detail_pengiriman' => (int) $pcss->sisa_detail_penerimaan,
                                'sisa_detail_pengiriman' => 0,
                                'berat_detail_pengiriman' => $berat,
                                'ppn_detail_pengiriman' => ($pcss->harga * $berat) * 0.11,
                                'subtotal_detail_pengiriman' => $pcss->harga * $berat,
                                'total_detail_pengiriman' => ($pcss->harga * $berat) + (($pcss->harga * $berat) * 0.11)


                            ];
                            $jumlah_item = $pcss->sisa_detail_penerimaan;
                            $berat_item = $berat;
                        } else {


                            $data_detail_pengiriman[$i] = [
                                'id_pengiriman' => 0,
                                'id_produk' => $pcss->id_produk,
                                'jumlah_detail_pengiriman' => (int) $pcss->jumlah_detail_penerimaan,
                                'sisa_detail_pengiriman' => 0,
                                // perhitungan berat
                                'berat_detail_pengiriman' => $pcss->berat_detail_pembelian,
                                'ppn_detail_pengiriman' => ($pcss->harga * $pcss->berat_detail_pembelian) * 0.11,
                                'subtotal_detail_pengiriman' => $pcss->harga * $pcss->berat_detail_pembelian,
                                'total_detail_pengiriman' => ($pcss->harga * $pcss->berat_detail_pembelian) + (($pcss->harga * $pcss->berat_detail_pembelian) * 0.11)


                            ];

                            $jumlah_item = $pcss->jumlah_detail_penerimaan;
                            $berat_item = $pcss->berat_detail_pembelian;
                        }

                        $data[$i] = [
                            'tgl_pengiriman' => $tgl_pengiriman,
                            'no_pengiriman' => $arr_no_pengiriman[0],
                            'nama_produk' => $pcss->nama_produk,
                            'tebal_produk' => $pcss->tebal_transaksi,
                            'lebar_produk' => $pcss->lebar_transaksi,
                            'panjang_produk' => $pcss->panjang_transaksi,
                            'jumlah' => $jumlah_item,
                            'berat' => $berat_item,
                            'no_penjualan' => $pcss->no_penjualan,
                            'perwakilan' => $pcss->perwakilan,
                            'nama_pelanggan' => $pcss->nama_pelanggan,
                            'alamat_pelanggan' => $pcss->alamat_pelanggan,
                            'nomor_pekerjaan' => $pcss->nomor_pekerjaan,
                            'jumlah_penawaran' => $pcss->jumlah,
                            'berat_penawaran' => $pcss->berat,

                        ];
                    }
                }
            }
        }
        // check isi data yang mau di insert
        // dump($id_transaksi);
        // dump($penerimaan);
        // dump($data_pengiriman);
        // dd($data_detail_pengiriman);
        // dd($data_pengiriman);


        $this->model->insert_delivery($id_transaksi, $data_pengiriman, $data_detail_pengiriman, $unit);

        $data = [
            'tittle' => 'Print document Delivery',
            'data' => $data,
        ];
        // dd($data);
        // return view('delivery.print_item', $data);
        return redirect('delivery')->with('success', "Data entered successfully,Please Click Detail For more Information");
    }



    public function detail($no_pengiriman)
    {

        $data = $this->model->detail(str_replace("-", "/", $no_pengiriman));
        $data = [
            'tittle' => "Detail Delivery Order",
            'data' => $data
        ];
        // dd($data);
        return view('delivery.detail', $data);
    }


   

    public function print($no_transaksi)
    {
        $data = $this->model->detail(str_replace('-', '/', $no_transaksi));


        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/delivery_template.xlsx');

        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->getCell('J4')->setValue($data[0]->tgl_pengiriman);
        $worksheet->getCell('J5')->setValue($data[0]->no_pengiriman);
        $worksheet->mergeCells("J5:K5");
        $worksheet->getCell('J6')->setValue($data[0]->no_penjualan);
        $worksheet->getCell('A12')->setValue($data[0]->perwakilan);
        $worksheet->getCell('A13')->setValue($data[0]->nama_pelanggan);
        $worksheet->getCell('A14')->setValue($data[0]->alamat_pelanggan);
        // $worksheet->getCell('D17')->setValue($data[0]->layanan);


        $baris_awal = 18;
        $total_berat = 0;
        $total_jumlah = 0;

        $worksheet->insertNewRowBefore(20, count($data));
        for ($i = 0; $i < count($data); $i++) {


            $tambahan_baris = $baris_awal + 1;

            $worksheet->setCellValue("C$tambahan_baris", ($i + 1));
            $worksheet->setCellValue("D$tambahan_baris", $data[$i]->nomor_pekerjaan);
            $worksheet->MergeCells("D$tambahan_baris:E$tambahan_baris");

            $worksheet->setCellValue("F$tambahan_baris", $data[$i]->nama_produk);
            $tebal = $data[$i]->tebal_penawaran;
            $lebar = $data[$i]->lebar_penawaran;
            $panjang =  $data[$i]->panjang_penawaran;

            $worksheet->setCellValue("G$tambahan_baris", $tebal);
            $worksheet->setCellValue("H$tambahan_baris", $lebar);
            $worksheet->setCellValue("I$tambahan_baris", $panjang);
            $worksheet->setCellValue("J$tambahan_baris", $data[$i]->jumlah_detail_pengiriman);
            $worksheet->setCellValue("K$tambahan_baris", $data[$i]->berat_detail_pengiriman);
            $worksheet->MergeCells("K$tambahan_baris:L$tambahan_baris");

            $total_berat += $data[$i]->berat_detail_pengiriman;
            $total_jumlah += $data[$i]->jumlah_detail_pengiriman;


            $baris_awal = $tambahan_baris;
        }
        // dd($baris_awal);
        $baris_setelah = $baris_awal + 1;

        
        $worksheet->setCellValue("C$baris_setelah", "TOTAL");
        $worksheet->MergeCells("C$baris_setelah:I$baris_setelah");
        $worksheet->setCellValue("J$baris_setelah", $total_jumlah);
        $worksheet->setCellValue("K$baris_setelah", $total_berat);
        $worksheet->MergeCells("K$baris_setelah:L$baris_setelah");
        
        $baris_setelah+=3;
        $worksheet->setCellValue("G$baris_setelah", $data[0]->layanan);
        $worksheet->MergeCells("G$baris_setelah:H$baris_setelah");
        
        $baris_setelah+=7;
        $worksheet->setCellValue("E$baris_setelah", "/".date('m-Y'));
        $worksheet->setCellValue("I$baris_setelah", "/".date('m-Y'));
        $worksheet->setCellValue("M$baris_setelah", "/".date('m-Y'));








        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Delivery Report.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        // $writer->save('report/quotation.xls');


    }
}
