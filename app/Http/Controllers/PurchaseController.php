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
use App\Models\QuotationModel;
use App\Models\TransaksiModel;


class PurchaseController extends Controller
{
    protected $PurchaseModel;
    protected $SupplierModel;
    protected $qc;
    protected $QuotationModel;
    protected $TransaksiModel;
    public function __construct()
    {
        $this->PurchaseModel = new PurchaseModel();
        $this->QuotationModel = new QuotationModel();
        $this->TransaksiModel = new TransaksiModel();
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
        // dd($data);
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

                                // // $id_layanan=$this->QuotationModel->getIdServices($layanan[$ipo]);

                                $transaksi  = $this->TransaksiModel->getTransaksi((int)$id_transaksi[$ipo]);
                                $id_layanan = $transaksi->id_layanan;
                                $type_transaksi = $transaksi->type;
                                $type_layanan = $this->QuotationModel->getServices((int)$id_layanan)->type;

                                if ($type_transaksi == 1) {


                                    $berat_produk = (float) $this->qc->CalculateWeight(
                                        $bentuk_produk[$ipo],
                                        $type_layanan,
                                        $tebal_transaksi[$ipo],
                                        $lebar_transaksi[$ipo],
                                        $panjang_transaksi[$ipo],
                                        (int) $unit[$ipo]
                                    );
                                } else {
                                    $berat_produk = $transaksi->berat;
                                }
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

            // dump($produk);
            // dump($quotation);

            $ipdk = 0;
            foreach ($quotation as $quo) {
                $total_unit_produk = 0;
                $total_harga_produk = 0;
                foreach ($produk as $prdk) {
                    if ($prdk['id_produk'] == $quo->id_produk) {
                        if ($prdk['id_penawaran'] == $quo->id_penawaran) {

                            $transaksi  = $this->TransaksiModel->getTransaksi((int)$quo->id_transaksi);
                            $type_transaksi = $transaksi->type;

                            if ($type_transaksi == 1) {
                                $total_detail_pembelian
                                    = $prdk['harga'] * $prdk['berat'] + (($prdk['harga'] * $prdk['berat']) * 0.11);
                            } else {
                                $total_detail_pembelian =
                                    $prdk['harga'] * $prdk['berat'] + (($prdk['harga'] * $prdk['berat']) * 0.11) - ($prdk['harga']  * 0.02);
                            }

                            $produk_penawaran = [
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
                                'total' => $total_detail_pembelian,
                                'sisa_detail_penjualan' => $prdk['jumlah_unit'] - $prdk['unit'],
                                'panjang_detail_pembelian' => $prdk['panjang_detail_pembelian'],
                                'lebar_detail_pembelian' => $prdk['lebar_detail_pembelian'],
                                'tebal_detail_pembelian' => $prdk['tebal_detail_pembelian'],
                                'id_pemasok' => $prdk['id_pemasok'],
                            ];
                            array_push($arr_produk, $produk_penawaran);
                            // $ipdk++;
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

                                    if ($aprdk['unit'] > $aprdk['jumlah_unit']) {
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


  
        //    kumpulan array data penjualan
        $data_pembelian = [];
        $data_detail_pembelian = [];
        $array_no_pembelian = [];


        $no_pembelian = $this->PurchaseModel->no_pembelian($tgl_pembelian, $id_pemasok);
       
        $tgl_exploade = explode('-', $tgl_pembelian);


        if (count(array_unique($id_pemasok)) > 1) {


            $i = 0;
            foreach ($no_pembelian as $nop) {
                $no_purchase = "PO/$nop[no_pembelian]/$tgl_exploade[0]/$tgl_exploade[1]/$tgl_exploade[2]";
                array_push($array_no_pembelian, [
                    'id_pemasok' => $nop['id_pemasok'],
                    'no_pembelian' => $no_purchase

                ]);
                $i++;
            }
        } elseif (count(array_unique($id_pemasok)) == 1) {
            // dd($no_pembelian);
            $no_purchase = "PO/$no_pembelian[0]/$tgl_exploade[0]/$tgl_exploade[1]/$tgl_exploade[2]";
            array_push($array_no_pembelian, $no_purchase);
        }


        //    Mengisi array data pembelian dan detail pembelian
        // Kemungkinan yang bisa terjadi dalam pemebelian:
        // a. 1 supplier data dari table   
        // b. 1 supplier dengan custom data dari table
        // c. N supplier dengan custom data dari table
        if (gettype($id_pemasok) == 'array') {

            $array_pemasok = count(array_unique($id_pemasok));
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


                $transaksi  = $this->TransaksiModel->getTransaksi((int)$quo->id_transaksi);
                $type_transaksi = $transaksi->type;
                if ($type_transaksi == 1) {
                    $total_detail_pembelian = ($quo->harga * $quo->berat) + (($quo->harga * $quo->berat) * 0.11);
                } else {
                    $total_detail_pembelian = ($quo->harga * $quo->berat) + (($quo->harga * $quo->berat) * 0.11) - ($quo->harga * 0.02);
                }


                $data_detail_pembelian[$i] = [
                    'id_pembelian' => 0,
                    'id_produk' => $quo->id_produk,
                    'jumlah_detail_pembelian' => $quo->jumlah_unit,
                    'harga_detail_pembelian' => $quo->harga,
                    'total_detail_pembelian' => $total_detail_pembelian,
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
        } elseif ($array_pemasok > 1 && $unit != null) {

            $i = 0;
            foreach ($arr_produk as $ap) {
                $data_pembelian[$i] = [
                    'id_penjualan' => $ap['id_penjualan'],
                    'id_transaksi' => $ap['id_transaksi'],
                    'no_pembelian' => $this->findPurchaseNumber($array_no_pembelian, $ap['id_pemasok']),
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

            $kemungkinan = 'C';
        }


        // check isi array
      
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
        return view('purchase.detail', $data);
    }

    public function print($no_transaksi)
    {
        $data = $this->PurchaseModel->print(str_replace("-", "/", $no_transaksi));
        $goods = (count($data["goods"]) > 0) ? $data["goods"] : null;
        $service = (count($data["service"]) > 0) ? $data["service"] : null;
        $namaFile = $data["namaFile"];



        if ($goods != null) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/purchase_template.xlsx');
            $worksheet = $spreadsheet->getActiveSheet();
            $worksheet->getCell('J4')->setValue($goods[0]->tgl_pembelian);
            $worksheet->getCell('J5')->setValue($goods[0]->no_pembelian);
            $worksheet->mergeCells("J5:K5");
            $worksheet->getCell('J6')->setValue($goods[0]->no_penawaran);
            $worksheet->getCell('J7')->setValue($goods[0]->nomor_transaksi);
            $worksheet->getCell('A12')->setValue($goods[0]->perwakilan_pemasok);
            $worksheet->getCell('A13')->setValue($goods[0]->nama_pemasok);
            $worksheet->getCell('A14')->setValue($goods[0]->alamat_pemasok);
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
                $tebal = ((int)$goods[$i]->tebal_detail_pembelian != 0) ? $goods[$i]->tebal_detail_pembelian : $goods[$i]->tebal_transaksi;
                $lebar = ((int)$goods[$i]->lebar_detail_pembelian != 0) ? $goods[$i]->lebar_detail_pembelian : $goods[$i]->lebar_transaksi;
                $panjang = ((int)$goods[$i]->panjang_detail_pembelian != 0) ? $goods[$i]->panjang_detail_pembelian : $goods[$i]->panjang_transaksi;

                $worksheet->setCellValue("E$tambahan_baris", $tebal);
                $worksheet->setCellValue("F$tambahan_baris", $lebar);
                $worksheet->setCellValue("G$tambahan_baris", $panjang);
                $worksheet->setCellValue("H$tambahan_baris", $goods[$i]->jumlah_detail_pembelian);
                $worksheet->setCellValue("I$tambahan_baris", $goods[$i]->berat_detail_pembelian);
                $worksheet->setCellValue("J$tambahan_baris", $goods[$i]->harga_detail_pembelian);
                $worksheet->setCellValue("K$tambahan_baris", $goods[$i]->subtotal_detail_pembelian);
                $worksheet->mergeCells("K$tambahan_baris:L$tambahan_baris");

                $subtotal += $goods[$i]->subtotal_detail_pembelian;
                $ongkir += $goods[$i]->ongkir;
                $total += $goods[$i]->total_detail_pembelian;
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
            $worksheet->setCellValue("H$baris_setelah", $goods[0]->nama_pengguna);
        }



        if ($service != null) {
            $spreadsheet1 = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/purchase_template_2.xlsx');
            $worksheet1 = $spreadsheet1->getActiveSheet();
            $worksheet1->getCell('J4')->setValue($service[0]->tgl_pembelian);
            $worksheet1->getCell('J5')->setValue($service[0]->no_pembelian);
            $worksheet1->mergeCells("J5:K5");
            $worksheet1->getCell('J6')->setValue($service[0]->no_penawaran);
            $worksheet1->getCell('J7')->setValue($service[0]->nomor_transaksi);
            $worksheet1->getCell('A12')->setValue($service[0]->perwakilan_pemasok);
            $worksheet1->getCell('A13')->setValue($service[0]->nama_pemasok);
            $worksheet1->getCell('A14')->setValue($service[0]->alamat_pemasok);
            $worksheet1->getCell('D17')->setValue($service[0]->layanan);

            $baris_awal = 19;
            $subtotal = 0;
            $total = 0;
            $ongkir = 0;
            $harga = 0;
            $worksheet1->insertNewRowBefore(20, count($service));
            for ($i = 0; $i < count($service); $i++) {


                $tambahan_baris = $baris_awal + 1;

                $worksheet1->setCellValue("A$tambahan_baris", ($i + 1));
                $worksheet1->setCellValue("B$tambahan_baris", $service[$i]->nomor_pekerjaan);
                $worksheet1->MergeCells("B$tambahan_baris:C$tambahan_baris");

                $worksheet1->setCellValue("D$tambahan_baris", $service[$i]->nama_produk);
                $tebal = ((int)$service[$i]->tebal_detail_pembelian != 0) ? $service[$i]->tebal_detail_pembelian : $service[$i]->tebal_transaksi;
                $lebar = ((int)$service[$i]->lebar_detail_pembelian != 0) ? $service[$i]->lebar_detail_pembelian : $service[$i]->lebar_transaksi;
                $panjang = ((int)$service[$i]->panjang_detail_pembelian != 0) ? $service[$i]->panjang_detail_pembelian : $service[$i]->panjang_transaksi;

                $worksheet1->setCellValue("E$tambahan_baris", $tebal);
                $worksheet1->setCellValue("F$tambahan_baris", $lebar);
                $worksheet1->setCellValue("G$tambahan_baris", $panjang);
                $worksheet1->setCellValue("H$tambahan_baris", $service[$i]->jumlah_detail_pembelian);
                $worksheet1->setCellValue("I$tambahan_baris", $service[$i]->berat_detail_pembelian);
                $worksheet1->setCellValue("J$tambahan_baris", $service[$i]->harga_detail_pembelian);
                $worksheet1->setCellValue("K$tambahan_baris", $service[$i]->subtotal_detail_pembelian);
                $worksheet1->mergeCells("K$tambahan_baris:L$tambahan_baris");



                $subtotal += $service[$i]->subtotal_detail_pembelian;
                $ongkir += $service[$i]->ongkir;
                $harga += $service[$i]->harga;
                $total += $service[$i]->total_detail_pembelian;
                $baris_awal = $tambahan_baris;
            }
            $baris_setelah = $baris_awal + 2;
            $worksheet1->setCellValue("K$baris_setelah", $subtotal);
            $worksheet1->MergeCells("K$baris_setelah:L$baris_setelah");

            $baris_setelah += 1;
            $worksheet1->setCellValue("K$baris_setelah", $subtotal * 0.11);
            $worksheet1->MergeCells("K$baris_setelah:L$baris_setelah");
            $baris_setelah += 1;
            $worksheet1->setCellValue("K$baris_setelah", $harga * 0.02);
            $worksheet1->MergeCells("K$baris_setelah:L$baris_setelah");

            $baris_setelah += 1;
            $worksheet1->setCellValue("K$baris_setelah", $total);
            $worksheet1->MergeCells("K$baris_setelah:L$baris_setelah");

            $baris_setelah += 9;
            $worksheet1->setCellValue("H$baris_setelah", $service[0]->nama_pengguna);
        }



        if ($goods != null && $service != null) {
            $this->qc->printAll($spreadsheet, $spreadsheet1, $namaFile);
        } else if ($goods != null) {

            $this->qc->printAll($spreadsheet, null, $namaFile);
        } else if ($service != null) {
            $this->qc->printAll(null, $spreadsheet1, $namaFile);
        }
    }



    function containsOnlyNull($input)
    {
        return empty(array_filter($input, function ($a) {
            return $a !== null;
        }));
    }



    function findPurchaseNumber($no_pembelian, $id_pemasok)
    {
        foreach ($no_pembelian as $np) {
            if ((int)$np['id_pemasok'] == (int)$id_pemasok) {
                // dd($np['no_pembelian']);
                return $np['no_pembelian'];
            }
        }
    }


    public function delete_detail(Request $request)
    {
        $id_pembelian = (int)$request->input('id_pembelian');
        $this->PurchaseModel->delete_detail($id_pembelian);
        return response()->json(['message' => 'success']);
    }
}
