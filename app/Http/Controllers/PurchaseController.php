<?php

namespace App\Http\Controllers;

use App\Models\PurchaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Terbilang;
use function app\helper\penyebut;
use App\Http\Controllers\QuotationController;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class PurchaseController extends Controller
{
    protected $PurchaseModel;
    protected $SupplierModel;
    protected $qc;
    public function __construct()
    {
        $this->PurchaseModel = new PurchaseModel();
        $this->qc = new QuotationController;
    }
    public function index()
    {
        $serch = request()->get('serch');
        if ($serch) {
            $data = $this->PurchaseModel->index($serch);
        } else {
            $data = $this->PurchaseModel->index();
        }
        // dd($data);
        $data = [
            'tittle' => 'Purchase Order',
            "data" => $data,
            'deta' => $this->PurchaseModel->index(),


        ];
        return view('purchase.index', $data);
    }

    public function show($kode_transaksi)
    {

        $data = [
            'tittle' => "Create purchase",
            "data" => $this->PurchaseModel->show($kode_transaksi),
            "supplier" => DB::table('pemasok')->get(),
        ];
        // dd($data);
        return view('purchase.create', $data);
    }

    public function store(Request $request)
    {
        // Persiapan Variable
        // dd($request->input());
        $kode_transaksi = $request->input('kode_transaksi');
        $id_transaksi = $request->input('id_transaksi');
        $tgl_pembelian = $request->input('tgl_pembelian');
        $id_pemasok =  $request->input('id_pemasok');
        $unit = $request->input('unit');
        $quotation = $this->PurchaseModel->edit($kode_transaksi);
        $tgl_penjualan = $quotation[0]->tgl_penjualan;
        $id_produk = $request->input('id_produk');
        $id_penawaran = $request->input('id_penawaran');
        // ukuran sales
        $tebal_transaksi = $request->input('tebal_transaksi');
        $lebar_transaksi = $request->input('lebar_transaksi');
        $panjang_transaksi = $request->input('panjang_transaksi');
        $berat = $request->input('berat');
        // ukuran pembelian
        $tebal_transaksi_asli = $request->input('tebal_transaksi_asli');
        $lebar_transaksi_asli = $request->input('lebar_transaksi_asli');
        $panjang_transaksi_asli = $request->input('panjang_transaksi_asli');
        $berat_asli = $request->input('berat_asli');



        $bentuk_produk = $request->input('bentuk_produk');
        $layanan = $request->input('layanan');
        $harga = $request->input('harga');
        $jumlah_unit = $request->input('jumlah');
        $produk = [];
        $arr_produk = [];

        // echo    $this->containsOnlyNull($request->input('tebal_transaksi_asli'));
        // die;

        // if (
        //     $tebal_transaksi_asli != null ||  $tebal_transaksi_asli != '' ||
        //     $lebar_transaksi_asli != null ||  $lebar_transaksi_asli != '' ||
        //     $panjang_transaksi_asli != null ||  $panjang_transaksi_asli != ''
        // ) {
        //     echo "masuk sini";
        //     dd($tebal_transaksi_asli);

        //     $validator = Validator::make($request->all(), [

        //         "tebal_transaksi_asli.*"  => "regex:/^\d*(\.\d{2})?$/",
        //         "lebar_transaksi_asli.*"  => "regex:/^\d*(\.\d{2})?$/",
        //         "panjang_transaksi_asli.*"  => "regex:/^\d*(\.\d{2})?$/",
        //     ]);

        //     if ($validator->fails()) {
        //         return redirect()->back()->with("failed", "if your transaction has a comma please use it '.'");
        //     }
        // }


        //    check array apa bukan
        if (is_array($unit)) {


            // Validation Proses

            for ($ip = 0; $ip < count($id_pemasok); $ip++) {
                if ($id_pemasok[$ip] == "null") {
                    return redirect()->back()->with("failed", "Choose Your Supplier ");
                }
            }


            foreach ($harga as $h) {
                if ($h == null) {
                    return redirect()->back()->with("failed", "Please Fill Coloumn Price");
                } else {
                    if ($h == 0) {
                        return redirect()->back()->with("failed", "Please Fill Coloumn Price More Then 0");
                    }
                }
            }

            for ($ip = 0; $ip < count($unit); $ip++) {
                if ($unit[$ip] == null) {
                    return redirect()->back()->with("failed", "Please Fill Coloumn Unit ")->withInput();
                }
            }

            foreach ($tebal_transaksi_asli as $tbl) {

                if ($tbl != null) {

                    
                    if (preg_match("/^\d*(\.\d{2})?$/", $tbl) == 0) {
                        return redirect()->back()->with("failed", "if your transaction has a comma please use it '.'");
                    }
                }
            }

            foreach ($lebar_transaksi_asli as $lbr) {

                if ($lbr != null) {

                    if (preg_match("/^\d*(\.\d{2})?$/", $lbr) == 0) {
                        return redirect()->back()->with("failed", "if your transaction has a comma please use it '.'");
                    }
                }
            }
            foreach ($panjang_transaksi_asli as $pjg) {

                if ($pjg != null) {

                    if (preg_match("/^\d*(\.\d{2})?$/", $pjg) == 0) {
                        return redirect()->back()->with("failed", "if your transaction has a comma please use it '.'");
                    }
                }
            }







            // mengisi var produk
            for ($ipo = 0; $ipo < count($id_produk); $ipo++) {
                foreach ($quotation as $quoi) {
                    if ($quoi->id_produk == $id_produk[$ipo]) {
                        if ($quoi->id_penawaran == $id_penawaran[$ipo]) {

                            if ($berat_asli[$ipo] != null) {
                                $berat_produk = (float)$berat_asli[$ipo];
                            } else {
                                $berat_produk = (float) $this->qc->CalculateWeight(
                                    $bentuk_produk[$ipo],
                                    $layanan[$ipo],
                                    $tebal_transaksi[$ipo],
                                    $lebar_transaksi[$ipo],
                                    $panjang_transaksi[$ipo],
                                    (int) $unit[$ipo]
                                );
                            }



                            $panjang_produk = ($panjang_transaksi[$ipo] != (float)$panjang_transaksi_asli[$ipo]) ? $panjang_transaksi_asli[$ipo] : null;

                            $lebar_produk = ($lebar_transaksi[$ipo] != (float)$lebar_transaksi_asli[$ipo]) ? $lebar_transaksi_asli[$ipo] : null;

                            $tebal_produk = ($tebal_transaksi[$ipo] != (float)$tebal_transaksi_asli[$ipo]) ? $tebal_transaksi_asli[$ipo] : null;


                            $produk[$ipo] = [
                                'id_produk' => $id_produk[$ipo],
                                'id_penjualan' => $quoi->id_penjualan,
                                'id_penawaran' => $quoi->id_penawaran,
                                'unit' => (int) $unit[$ipo],
                                'harga' => (int)$harga[$ipo],
                                'berat' => $berat_produk,
                                'jumlah_unit' => $jumlah_unit[$ipo],
                                'panjang_detail_pembelian' => (float) $panjang_produk,
                                'lebar_detail_pembelian' => (float)$lebar_produk,
                                'tebal_detail_pembelian' => (float) $tebal_produk,
                                'id_pemasok' => $id_pemasok[$ipo],

                            ];
                        }
                    }
                }
            }

            $ipdk = 0;
            foreach ($quotation as $quo) {
                $total_unit_produk = 0;
                $total_harga_produk = 0;
                foreach ($produk as $prdk) {

                    if ($prdk['id_produk'] == $quo->id_produk) {
                        if ($prdk['id_penawaran'] == $quo->id_penawaran) {
                            $arr_produk[$ipdk] = [
                                'id_penjualan' => $quo->id_penjualan,
                                'id_transaksi' => $quo->id_transaksi,
                                'id_produk' => $quo->id_produk,
                                'id_penawaran' => $quo->id_penawaran,
                                'unit' => $total_unit_produk += $prdk['unit'],
                                'jumlah' => $prdk['unit'],
                                'harga' => $prdk['harga'],
                                'berat' => $prdk['berat'],
                                'subtotal' => $prdk['harga'] * $prdk['berat'],
                                'ppn' => ($prdk['harga'] * $prdk['berat']) * 0.11,
                                'total' => $prdk['harga'] * $prdk['berat'] + (($prdk['harga'] * $prdk['berat']) * 0.11),
                                'sisa_detail_penjualan' => $prdk['jumlah_unit'] - $prdk['unit'],
                                'panjang_detail_pembelian' => $prdk['panjang_detail_pembelian'],
                                'lebar_detail_pembelian' => $prdk['lebar_detail_pembelian'],
                                'tebal_detail_pembelian' => $prdk['tebal_detail_pembelian'],
                                'id_pemasok' => $prdk['id_pemasok'],
                            ];
                            $ipdk++;
                        }
                    }
                }
            }


            foreach ($arr_produk as $apdk) {
                foreach ($produk as $aprdk) {

                    for ($pdkv = 0; $pdkv < count($id_produk); $pdkv++) {
                        if ($produk[$pdkv]['unit'] == null) {
                            return redirect()->back()->with("failed", "Please Fill Unit Coloumn more then 0 ");
                        } else {
                            if ($id_produk[$pdkv] == $apdk['id_produk']) {
                                if ($id_penawaran[$pdkv] == $apdk['id_penawaran']) {

                                    // validasi jumlah produk

                                    if ($apdk['unit'] > $aprdk['jumlah_unit']) {
                                        return redirect()->back()->with("failed", "Please Fill Unit Coloumn with Less Value");
                                    }
                                }

                                // validasi harga

                                //  else {
                                //     if ($apdk['harga'] > $quo1->harga) {
                                //         return redirect()->back()->with("failed", "Please Fill Price Coloumn with Less Value");
                                //     }
                                // }
                            }
                        }
                    }
                }
            }
        } else {
            if ($request->input('id_pemasok') == null) {
                return redirect()->back()->with("failed", "Please click the add button for choose your supplier ");
            }
        }




        // $rules = [
        //     'tgl_pembelian' => " after_or_equal:$tgl_penjualan",


        // ];
        // $message = [
        //     "tgl_pembelian.after_or_equal" => "Choose a date after the quotation date or equal",

        // ];
        // $validated = Validator::make($request->all(), $rules, $message);
        // if ($validated->fails()) {
        //     return redirect()->back()->with("failed", "Choose a date after the sales date or equal");
        // }

        //    kumpulan array data penjualan
        $data_pembelian = [];
        $data_detail_pembelian = [];
        $array_no_pembelian = [];

        // Persiapan no penjualan
        // Dasar pembentukan no pembelian





        // check banyaknya id pemasok yang masok

        $no_pembelian = $this->PurchaseModel->no_pembelian($tgl_pembelian, $id_pemasok);

        $tgl_exploade = explode('-', $tgl_pembelian);

        if (gettype($id_pemasok) != 'string' && count(array_flip($id_pemasok)) == 2) {


            $i = 0;
            foreach ($no_pembelian as $nop) {
                $nop = $nop + $i;
                $no_purchase = "PO/$nop/$tgl_exploade[0]/$tgl_exploade[1]/$tgl_exploade[2]";
                array_push($array_no_pembelian, $no_purchase);
                $i++;
            }
        } elseif (gettype($id_pemasok) != 'string' && count(array_flip($id_pemasok)) == 1) {
            $no_purchase = "PO/$no_pembelian[0]/$tgl_exploade[0]/$tgl_exploade[1]/$tgl_exploade[2]";
            array_push($array_no_pembelian, $no_purchase);
        } else {

            $no_purchase = "PO/$no_pembelian/$tgl_exploade[0]/$tgl_exploade[1]/$tgl_exploade[2]";
            array_push($array_no_pembelian, $no_purchase);
        }

        //    Mengisi array data pembelian dan detail pembelian
        // Kemungkinan yang bisa terjadi dalam pemebelian:
        // a. 1 supplier data dari table   
        // b. 1 supplier dengan custom data dari table
        // c. N supplier dengan custom data dari table
        if (gettype($id_pemasok) == 'array') {
            $array_pemasok = count(array_flip($id_pemasok));
        }

        if (gettype($id_pemasok) == 'string' && $unit == null) {

            $i = 0;
            foreach ($quotation as $quo) {
                $data_pembelian[$i] = [
                    'id_penjualan' => $quo->id_penjualan,
                    'id_transaksi' => $quo->id_transaksi,
                    'no_pembelian' => $array_no_pembelian[0],
                    'tgl_pembelian' => $tgl_pembelian,
                    'id_pemasok' => $id_pemasok[0]
                ];



                $data_detail_pembelian[$i] = [
                    'id_pembelian' => 0,
                    'id_produk' => $quo->id_produk,
                    'jumlah_detail_pembelian' => $quo->jumlah_unit,
                    'harga_detail_pembelian' => $quo->harga,
                    'total_detail_pembelian' => ($quo->harga * $quo->berat) + (($quo->harga * $quo->berat) * 0.11),
                    'berat_detail_pembelian' => $quo->berat,
                    'subtotal_detail_pembelian' => $quo->harga * $quo->berat,
                    'ppn_detail_pembelian' => ($quo->harga * $quo->berat) * 0.11,
                    'sisa_detail_penjualan' => 0,


                ];

                $id_transaksi[]
                    = $quo->id_transaksi;
                $i++;
            }
            $kemungkinan = 'A';
        } else if ($array_pemasok == 1 && $unit != null) {

            $i = 0;
            foreach ($arr_produk as $ap) {
                $data_pembelian[$i] = [
                    'id_penjualan' => $ap['id_penjualan'],
                    'id_transaksi' => $ap['id_transaksi'],
                    'no_pembelian' => $array_no_pembelian[0],
                    'tgl_pembelian' => $tgl_pembelian,
                    'id_pemasok' => $ap['id_pemasok']
                ];

                // dump($ap);

                $data_detail_pembelian[$i] = [
                    'id_pembelian' => 0,
                    'id_produk' => $ap['id_produk'],
                    'jumlah_detail_pembelian' => $ap['jumlah'],
                    'harga_detail_pembelian' => $ap['harga'],
                    'total_detail_pembelian' => $ap['total'],
                    'berat_detail_pembelian' => $ap['berat'],
                    'subtotal_detail_pembelian' => $ap['subtotal'],
                    'ppn_detail_pembelian' => $ap['ppn'],
                    'sisa_detail_penjualan' => $ap['sisa_detail_penjualan'],
                    'panjang_detail_pembelian' => $ap['panjang_detail_pembelian'],
                    'lebar_detail_pembelian' => $ap['lebar_detail_pembelian'],
                    'tebal_detail_pembelian' => $ap['tebal_detail_pembelian'],


                ];

                $i++;
            }
            $id_pemasok = $id_pemasok[0];
            $kemungkinan = 'B';
        } elseif ($array_pemasok == 2 && $unit != null) {
            // echo 'masuk sini';
            $i = 0;
            foreach ($arr_produk as $ap) {
                // dump($ap);
                $data_pembelian[$i] = [
                    'id_penjualan' => $ap['id_penjualan'],
                    'id_transaksi' => $ap['id_transaksi'],
                    'no_pembelian' => $array_no_pembelian[$i],
                    'tgl_pembelian' => $tgl_pembelian,
                    'id_pemasok' => $ap['id_pemasok']

                ];



                $data_detail_pembelian[$i] = [
                    'id_pembelian' => 0,
                    'id_produk' => $ap['id_produk'],
                    'jumlah_detail_pembelian' => $ap['jumlah'],
                    'harga_detail_pembelian' => $ap['harga'],
                    'total_detail_pembelian' => $ap['total'],
                    'berat_detail_pembelian' => $ap['berat'],
                    'subtotal_detail_pembelian' => $ap['subtotal'],
                    'ppn_detail_pembelian' => $ap['ppn'],
                    'sisa_detail_penjualan' => $ap['sisa_detail_penjualan'],
                    'panjang_detail_pembelian' => $ap['panjang_detail_pembelian'],
                    'lebar_detail_pembelian' => $ap['lebar_detail_pembelian'],
                    'tebal_detail_pembelian' => $ap['tebal_detail_pembelian'],
                ];

                $i++;
            }

            // dump("kemungkinan C");
            $kemungkinan = 'C';
        }


        // check isi array
        // dump($produk);
        // dump($arr_produk);
        // dump($array_no_pembelian);
        // dump($quotation);
        // dump($kemungkinan);
        // dump($request->input());
        // dump($data_pembelian);
        // dd($data_detail_pembelian);





        $this->PurchaseModel->insert_penjualan($id_transaksi, $data_pembelian, $data_detail_pembelian, $id_pemasok, $kemungkinan);

        return redirect('purchase')->with('success', "Data entered successfully Please Chek Your Detail Transaction for more information");
    }
    public function detail($no_pembelian)
    {

        $data = $this->PurchaseModel->detail(str_replace("-", "/", $no_pembelian));
        $data = [
            'tittle' => "Detail purchase Order",
            'data' => $data
        ];
        // dd($data);
        return view('purchase.detail', $data);
    }

    public function print($no_transaksi)
    {
        $data = $this->PurchaseModel->detail(str_replace("-", "/", $no_transaksi));


        // dd($data);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/purchase_template.xlsx');

        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->getCell('J4')->setValue($data[0]->tgl_pembelian);
        $worksheet->getCell('J5')->setValue($data[0]->no_pembelian);
        $worksheet->mergeCells("J5:K5");
        $worksheet->getCell('J6')->setValue($data[0]->no_penawaran);
        $worksheet->getCell('A12')->setValue($data[0]->perwakilan_pemasok);
        $worksheet->getCell('A13')->setValue($data[0]->nama_pemasok);
        $worksheet->getCell('A14')->setValue($data[0]->alamat_pemasok);
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
            $tebal = ((int)$data[$i]->tebal_detail_pembelian != 0) ? $data[$i]->tebal_detail_pembelian : $data[$i]->tebal_transaksi;
            $lebar = ((int)$data[$i]->lebar_detail_pembelian != 0) ? $data[$i]->lebar_detail_pembelian : $data[$i]->lebar_transaksi;
            $panjang = ((int)$data[$i]->panjang_detail_pembelian != 0) ? $data[$i]->panjang_detail_pembelian : $data[$i]->panjang_transaksi;

            $worksheet->setCellValue("E$tambahan_baris", $tebal);
            $worksheet->setCellValue("F$tambahan_baris", $lebar);
            $worksheet->setCellValue("G$tambahan_baris", $panjang);
            $worksheet->setCellValue("H$tambahan_baris", $data[$i]->jumlah_detail_pembelian);
            $worksheet->setCellValue("I$tambahan_baris", $data[$i]->berat_detail_pembelian);
            $worksheet->setCellValue("J$tambahan_baris", $data[$i]->harga_detail_pembelian);
            $worksheet->setCellValue("K$tambahan_baris", $data[$i]->subtotal_detail_pembelian);
            $worksheet->mergeCells("K$tambahan_baris:L$tambahan_baris");



            $subtotal += $data[$i]->subtotal_detail_pembelian;
            $ongkir += $data[$i]->ongkir;
            $total += $data[$i]->total_detail_pembelian;
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
        $worksheet->setCellValue("H$baris_setelah", $data[0]->nama_pengguna);







        $namaFile = $data[0]->no_pembelian;

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename='$namaFile.xlsx'"); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        // $writer->save('report/quotation.xls');


    }

    function containsOnlyNull($input)
    {
        return empty(array_filter($input, function ($a) {
            return $a !== null;
        }));
    }
}
