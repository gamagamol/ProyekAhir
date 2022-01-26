  // masking
  $('#harga').mask('000.000.000.000.000', {
      reverse: true
  });
  $('#ongkir').mask('000.000.000.000.000', {
      reverse: true
  });




  function drop() {
      // bikin form nya
      let id_produk = document.getElementById('id_produk').value.split('|');

      let bentuk_produk = id_produk[1];

      let lebar = document.getElementById('lebar_transaksi');
      if (bentuk_produk == "CYLINDER") {

          lebar.setAttribute('readonly', true);
      } else {
          lebar.removeAttribute('readonly');
      }





  }

  //   function myFunction() {

  //       //    mengambil value dari element
  //       let tgl_transaksi = document.getElementById('tgl_transaksi').value;
  //       let nomor_pekerjaan = document.getElementById('nomor_pekerjaan').value;
  //       let id_produk = document.getElementById('id_produk').value.split('|');
  //       let id_pelanggan = document.getElementById('id_pelanggan').value;
  //       let tebal_transaksi = document.getElementById('tebal_transaksi').value;
  //       let lebar_transaksi = document.getElementById('lebar_transaksi').value;
  //       let panjang_transaksi = document.getElementById('panjang_transaksi').value;
  //       let jumlah = document.getElementById('jumlah').value;
  //       let layanan = document.getElementById('layanan').value;
  //       let tipe_pembayaran = document.getElementById('tipe_pembayaran').value;
  //       let harga = parseInt(document.getElementById('harga').value.replaceAll('.', ''));
  //       let ongkir = parseInt(document.getElementById('ongkir').value.replaceAll('.', ''));
  //       let id = document.getElementById("id").value;
  //       let tombol_tambah = document.getElementById("tambah").value;



  //       // pecahin isi array id produk

  //       let nama_produk = id_produk[0];
  //       let bentuk_produk = id_produk[1];
  //       id_produk = id_produk[2];

  //       // Deklarasi
  //       var tebal_penawaran;
  //       var lebar_penawaran;
  //       var panjang_penawaran;
  //       var berat;



  //       // nentuin quotation
  //   switch (bentuk_produk) {
  //       case "FLAT":
  //           if (layanan == "CUTTING") {
  //               //    membuat ukuran dan berat pxl 0,0000625
  //               tebal_penawaran = parseInt(tebal_transaksi);
  //               lebar_penawaran = parseInt(lebar_transaksi);
  //               panjang_penawaran = parseInt(panjang_transaksi);

  //               berat = tebal_transaksi * lebar_transaksi * panjang_transaksi * jumlah * 0.00000625;
  //               berat = berat.toFixed(1);
  //               berat = Number(berat);

  //           }
  //           if (layanan == "MILLING") {
  //               //    membuat ukuran dan berat pxl 0,00008
  //               tebal_penawaran = parseInt(tebal_transaksi) + 5;
  //               lebar_penawaran = parseInt(lebar_transaksi) + 5;
  //               panjang_penawaran = parseInt(panjang_transaksi) + 5;
  //               berat = tebal_penawaran * lebar_penawaran * panjang_penawaran * jumlah * 0.000008;
  //               berat = berat.toFixed(1);
  //               berat = Number(berat);
  //           }
  //           break;
  //       case "CYLINDER":
  //           if (layanan == "CUTTING") {
  //               //    membuat ukuran dan berat pxl 0,0000625
  //               tebal_penawaran = parseInt(tebal_transaksi);
  //               lebar_penawaran = parseInt(lebar_transaksi);
  //               panjang_penawaran = parseInt(panjang_transaksi);
  //               berat = tebal_transaksi * tebal_transaksi * panjang_transaksi * jumlah * 0.00000625;
  //               berat = berat.toFixed(1);
  //               berat = Number(berat);
  //               console.log(berat);

  //           }
  //           if (layanan == "MILLING") {
  //               //    membuat ukuran dan berat pxl 0,00008
  //               tebal_penawaran = parseInt(tebal_transaksi) + 5;
  //               panjang_penawaran = parseInt(panjang_transaksi) + 5;
  //               berat = tebal_penawaran * tebal_penawaran * panjang_penawaran * jumlah * 0.000008;
  //               berat = berat.toFixed(1);
  //               berat = Number(berat);
  //               console.log(berat);

  //           }
  //           break;
  //       default:
  //           return false;
  //   }

  //       // Perhitungan total,pajak,ongkir
  //       var subtotal = harga + ongkir;
  //       var pajak = subtotal * 0.1;
  //       var total = subtotal + pajak;


  //       // membuat element donwload


  //       // buat baris baru
  //       const trbaru = document.createElement('tr');

  //       // buat element cehck box
  //       const check_input = document.createElement('td');
  //       trbaru.appendChild(check_input);
  //       const checkbox = document.createElement('input');
  //       checkbox.setAttribute('type', "checkbox");
  //       checkbox.setAttribute('name', "checkbox");


  //       check_input.appendChild(checkbox);

  //       // // buat element tanggal
  //       const kolom_tanggal = document.createElement('td');
  //       trbaru.appendChild(kolom_tanggal);
  //       const input_tanggal = document.createElement('input');
  //       input_tanggal.setAttribute("class", "form-control text-center");
  //       input_tanggal.style.width = '170px';
  //       input_tanggal.setAttribute("readonly", true);
  //       input_tanggal.setAttribute("name", "tgl_transaksi");
  //       input_tanggal.setAttribute("value", tgl_transaksi);
  //       input_tanggal.innerHTML = tgl_transaksi;
  //       kolom_tanggal.appendChild(input_tanggal);



  //       // // buat element nomor_pekerjaan
  //       const kolom_nomor_pekerjaan = document.createElement('td');
  //       trbaru.appendChild(kolom_nomor_pekerjaan);
  //       const input_kolom_pekerjaan = document.createElement('input');
  //       input_kolom_pekerjaan.setAttribute("class", "form-control text-center");
  //       input_kolom_pekerjaan.style.width = '170px';
  //       input_kolom_pekerjaan.setAttribute("readonly", true);
  //       input_kolom_pekerjaan.setAttribute("name", "nomor_pekerjaan");
  //       input_kolom_pekerjaan.setAttribute("value", nomor_pekerjaan);
  //       input_kolom_pekerjaan.innerHTML = nomor_pekerjaan;
  //       kolom_nomor_pekerjaan.appendChild(input_kolom_pekerjaan);

  //       // buat element id_produk
  //       const kolom_id_produk = document.createElement('td');
  //       trbaru.appendChild(kolom_id_produk);
  //       const input_id_produk = document.createElement('input');
  //       input_id_produk.setAttribute("class", "form-control text-center");
  //       input_id_produk.style.width = '170px';
  //       input_id_produk.setAttribute("readonly", true);
  //       input_id_produk.setAttribute("name", "id_produk");
  //       input_id_produk.setAttribute("value", nama_produk);
  //       input_id_produk.innerHTML = nama_produk;
  //       kolom_id_produk.appendChild(input_id_produk);

  //       // // buat element tebal_transaksi
  //       const kolom_tebal_transaksi = document.createElement('td');
  //       trbaru.appendChild(kolom_tebal_transaksi);
  //       const input_tebal_transaksi = document.createElement('input');
  //       input_tebal_transaksi.setAttribute("class", "form-control text-center");
  //       input_tebal_transaksi.style.width = '170px';
  //       input_tebal_transaksi.setAttribute("readonly", true);
  //       input_tebal_transaksi.setAttribute("name", "tebal_transaksi");
  //       input_tebal_transaksi.setAttribute("value", tebal_transaksi);
  //       input_tebal_transaksi.innerHTML = tebal_transaksi;
  //       kolom_tebal_transaksi.appendChild(input_tebal_transaksi);

  //       // // buat element lebar_transaksi
  //       const kolom_lebar_transaksi = document.createElement('td');
  //       trbaru.appendChild(kolom_lebar_transaksi);
  //       const input_lebar_transaksi = document.createElement('input');
  //       input_lebar_transaksi.setAttribute("class", "form-control text-center");
  //       input_lebar_transaksi.style.width = '170px';
  //       input_lebar_transaksi.setAttribute("readonly", true);
  //       input_lebar_transaksi.setAttribute("name", "lebar_transaksi");
  //       input_lebar_transaksi.setAttribute("value", lebar_transaksi);
  //       input_lebar_transaksi.innerHTML = lebar_transaksi;
  //       kolom_lebar_transaksi.appendChild(input_lebar_transaksi);

  //       // // buat element panjang_transaksi
  //       const kolom_panjang_transaksi = document.createElement('td');
  //       trbaru.appendChild(kolom_panjang_transaksi);
  //       const input_panjang_transaksi = document.createElement('input');
  //       input_panjang_transaksi.setAttribute("class", "form-control text-center");
  //       input_panjang_transaksi.style.width = '170px';
  //       input_panjang_transaksi.setAttribute("readonly", true);
  //       input_panjang_transaksi.setAttribute("name", "panjang_transaksi");
  //       input_panjang_transaksi.setAttribute("value", panjang_transaksi);
  //       input_panjang_transaksi.innerHTML = panjang_transaksi;
  //       kolom_panjang_transaksi.appendChild(input_panjang_transaksi);

  //       // // jumlah
  //       const kolom_jumlah = document.createElement('td');
  //       trbaru.appendChild(kolom_jumlah);
  //       const input_jumlah = document.createElement('input');
  //       input_jumlah.setAttribute("class", "form-control text-center");
  //       input_jumlah.style.width = '170px';
  //       input_jumlah.setAttribute("readonly", true);
  //       input_jumlah.setAttribute("name", "jumlah");
  //       input_jumlah.setAttribute("value", jumlah);
  //       input_jumlah.innerHTML = jumlah;
  //       kolom_jumlah.appendChild(input_jumlah);

  //       //   // buat element id_produk 2
  //       const kolom_id_produk2 = document.createElement('td');
  //       trbaru.appendChild(kolom_id_produk2);
  //       const input_id_produk2 = document.createElement('input');
  //       input_id_produk2.setAttribute("class", "form-control text-center");
  //       input_id_produk2.style.width = '170px';
  //       input_id_produk2.setAttribute("readonly", true);
  //       input_id_produk2.setAttribute("name", "id_produk");
  //       input_id_produk2.setAttribute("value", nama_produk);
  //       input_id_produk2.innerHTML = nama_produk;
  //       kolom_id_produk2.appendChild(input_id_produk2);

  //       // buat element 9
  //       const kolom_tebal_penawaran = document.createElement('td');
  //       trbaru.appendChild(kolom_tebal_penawaran);
  //       const input_tebal_penawaran = document.createElement('input');
  //       input_tebal_penawaran.setAttribute("class", "form-control text-center");
  //       input_tebal_penawaran.style.width = '170px';
  //       input_tebal_penawaran.setAttribute("readonly", true);
  //       input_tebal_penawaran.setAttribute("name", "tebal_penawaran");
  //       input_tebal_penawaran.setAttribute("value", tebal_penawaran);
  //       input_tebal_penawaran.innerHTML = tebal_penawaran;
  //       kolom_tebal_penawaran.appendChild(input_tebal_penawaran);

  //       // buat element 10
  //       const kolom_lebar_penawaran = document.createElement('td');
  //       trbaru.appendChild(kolom_lebar_penawaran);
  //       const input_lebar_penawaran = document.createElement('input');
  //       input_lebar_penawaran.setAttribute("class", "form-control text-center");
  //       input_lebar_penawaran.style.width = '170px';
  //       input_lebar_penawaran.setAttribute("readonly", true);
  //       input_lebar_penawaran.setAttribute("name", "lebar_penawaran");
  //       input_lebar_penawaran.setAttribute("value", lebar_penawaran);
  //       input_lebar_penawaran.innerHTML = lebar_penawaran;
  //       kolom_lebar_penawaran.appendChild(input_lebar_penawaran);

  //       // // buat element 11
  //       const kolom_panjang_penawaran = document.createElement('td');
  //       trbaru.appendChild(kolom_panjang_penawaran);
  //       const input_panjang_penawaran = document.createElement('input');
  //       input_panjang_penawaran.setAttribute("class", "form-control text-center");
  //       input_panjang_penawaran.style.width = '170px';
  //       input_panjang_penawaran.setAttribute("readonly", true);
  //       input_panjang_penawaran.setAttribute("name", "panjang_penawaran");
  //       input_panjang_penawaran.setAttribute("value", panjang_penawaran);
  //       input_panjang_penawaran.innerHTML = panjang_penawaran;
  //       kolom_panjang_penawaran.appendChild(input_panjang_penawaran);

  //       // // buat element 12
  //       const kolom_jumlah2 = document.createElement('td');
  //       trbaru.appendChild(kolom_jumlah2);
  //       const input_jumlah2 = document.createElement('input');
  //       input_jumlah2.setAttribute("class", "form-control text-center");
  //       input_jumlah2.style.width = '170px';
  //       input_jumlah2.setAttribute("readonly", true);
  //       input_jumlah2.setAttribute("name", "jumlah");
  //       input_jumlah2.setAttribute("value", jumlah);
  //       input_jumlah2.innerHTML = jumlah;
  //       kolom_jumlah2.appendChild(input_jumlah2);

  //       // Kolom Berat 
  //       const kolom_berat = document.createElement('td');
  //       trbaru.appendChild(kolom_berat);
  //       const input_berat = document.createElement('input');
  //       input_berat.setAttribute("class", "form-control text-center");
  //       input_berat.style.width = '170px';
  //       input_berat.setAttribute("readonly", true);
  //       input_berat.setAttribute("name", "berat");
  //       input_berat.setAttribute("value", berat);
  //       input_berat.innerHTML = berat;
  //       kolom_berat.appendChild(input_berat);

  //       // kolom harga
  //       const kolom_harga = document.createElement('td');
  //       trbaru.appendChild(kolom_harga);
  //       const input_harga = document.createElement('input');
  //       input_harga.setAttribute("class", "form-control text-center");
  //       input_harga.style.width = '170px';
  //       input_harga.setAttribute("readonly", true);
  //       input_harga.setAttribute("name", "harga");
  //       input_harga.setAttribute("value", harga);
  //       input_harga.innerHTML = harga;
  //       kolom_harga.appendChild(input_harga);

  //       // kolom ongkir
  //       const kolom_ongkir = document.createElement('td');
  //       trbaru.appendChild(kolom_ongkir);
  //       const input_ongkir = document.createElement('input');
  //       input_ongkir.setAttribute("class", "form-control text-center");
  //       input_ongkir.style.width = '170px';
  //       input_ongkir.setAttribute("readonly", true);
  //       input_ongkir.setAttribute("name", "ongkir");
  //       input_ongkir.setAttribute("value", ongkir);
  //       input_ongkir.innerHTML = ongkir;
  //       kolom_ongkir.appendChild(input_ongkir);

  //       // kolom subtotal
  //       const kolom_subtotal = document.createElement('td');
  //       trbaru.appendChild(kolom_subtotal);
  //       const input_subtotal = document.createElement('input');
  //       input_subtotal.setAttribute("class", "form-control text-center");
  //       input_subtotal.style.width = '170px';
  //       input_subtotal.setAttribute("readonly", true);
  //       input_subtotal.setAttribute("name", "subtotal");
  //       input_subtotal.setAttribute("value", subtotal);
  //       input_subtotal.innerHTML = subtotal;
  //       kolom_subtotal.appendChild(input_subtotal);

  //       // kolom pajak
  //       const kolom_pajak = document.createElement('td');
  //       trbaru.appendChild(kolom_pajak);
  //       const input_pajak = document.createElement('input');
  //       input_pajak.setAttribute("class", "form-control text-center");
  //       input_pajak.style.width = '170px';
  //       input_pajak.setAttribute("readonly", true);
  //       input_pajak.setAttribute("name", "pajak");
  //       input_pajak.setAttribute("value", pajak);
  //       input_pajak.innerHTML = pajak;
  //       kolom_pajak.appendChild(input_pajak);

  //       // kolom total
  //       const kolom_total = document.createElement('td');
  //       trbaru.appendChild(kolom_total);
  //       const input_total = document.createElement('input');
  //       input_total.setAttribute("class", "form-control text-center");
  //       input_total.style.width = '170px';
  //       input_total.setAttribute("readonly", true);
  //       input_total.setAttribute("name", "total");
  //       input_total.setAttribute("value", total);
  //       input_total.innerHTML = total;
  //       kolom_total.appendChild(input_total);

  //       // kolom layanan
  //       const kolom_layanan = document.createElement('td');
  //       trbaru.appendChild(kolom_layanan);
  //       const input_layanan = document.createElement('input');
  //       input_layanan.setAttribute("class", "form-control text-center");
  //       input_layanan.style.width = '170px';
  //       input_layanan.setAttribute("readonly", true);
  //       input_layanan.setAttribute("name", "layanan");
  //       input_layanan.setAttribute("value", layanan);
  //       input_layanan.innerHTML = layanan;
  //       kolom_layanan.appendChild(input_layanan);


  //       // buat element pelanggan
  //       const kolom_id_pelanggan = document.createElement('td');
  //       trbaru.appendChild(kolom_id_pelanggan);
  //       const input_id_pelanggan = document.createElement('input');
  //       input_id_pelanggan.setAttribute("class", "form-control text-center");
  //       input_id_pelanggan.style.width = '170px';
  //       input_id_pelanggan.setAttribute("readonly", true);
  //       input_id_pelanggan.setAttribute("name", "id_pelanggan");
  //       input_id_pelanggan.setAttribute("value", id_pelanggan);
  //       input_id_pelanggan.innerHTML = id_pelanggan;
  //       kolom_id_pelanggan.appendChild(input_id_pelanggan);

  //       // buat element id
  //       const kolom_id = document.createElement('td');
  //       trbaru.appendChild(kolom_id);
  //       const input_id = document.createElement('input');
  //       input_id.setAttribute("class", "form-control text-center");
  //       input_id.style.width = '170px';
  //       input_id.setAttribute("readonly", true);
  //       input_id.setAttribute("name", "id");
  //       input_id.setAttribute("value", id);
  //       input_id.innerHTML = id;
  //       kolom_id.appendChild(input_id);

  //       // buat tipe pembayaran
  //       const kolom_tipe_pembayaran = document.createElement('td');
  //       trbaru.appendChild(kolom_tipe_pembayaran);
  //       const input_tipe_pembayaran = document.createElement('input');
  //       input_tipe_pembayaran.setAttribute("class", "form-control text-center");
  //       input_tipe_pembayaran.style.width = '170px';
  //       input_tipe_pembayaran.setAttribute("readonly", true);
  //       input_tipe_pembayaran.setAttribute("name", "tipe_pembayaran");
  //       input_tipe_pembayaran.setAttribute("value", tipe_pembayaran);
  //       input_tipe_pembayaran.innerHTML = tipe_pembayaran;
  //       kolom_tipe_pembayaran.appendChild(input_tipe_pembayaran);



  //       const datatable = document.getElementById('dataTable');
  //       datatable.appendChild(trbaru);

  //   }