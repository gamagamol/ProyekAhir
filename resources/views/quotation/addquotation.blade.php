@extends('template.index')
@section('content')
<div class="container  ">
    <div class="card shadow mb-4 ml-4 mr-4">
    <div class="card-header py-3 mb-2 ">
        <h6 class="m-0 font-weight-bold text-primary">Add Quotation</h6>
    </div>
    <div class="container mt-2">
       {{-- serch custumor and make data --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mt-2 rounded">
                        <label for="example1" class="mt-2">Date</label>
                        <input type="date" class="form-control " >
                     </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group mt-2 rounded">
                          <label for="example1" class="mt-2">Job number</label>
                          <input type="number" class="form-control " >
                    </div>
             </div>
                <div class="col-md-3 mt-3">
                     <div class="form-group">
                <label for="id_produk">Custumor</label>
                <select class="form-control" id="id_produk" name="id_produk" onchange="myfunction()">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                </select>
                    </div>
                </div>

                <div class="col-md-3 mt-3">
                    <div class="form-group">
               <label for="id_produk">Product</label>
               <select class="form-control" id="id_produk" name="id_produk" onchange="myfunction()">
                       <option selected>Open this select menu</option>
                       <option value="1">One</option>
                       <option value="2">Two</option>
                       <option value="3">Three</option>
               </select>
                   </div>
               </div>
              
              
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mt-2 rounded">
                        <label for="example1" class="mt-2"> Inquiry Length</label>
                        <input type="number" class="form-control " >
                     </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mt-2 rounded">
                        <label for="example1" class="mt-2">Inquiry Hight</label>
                        <input type="number" class="form-control " >
                     </div>
                </div>
               

                <div class="col-md-3 ">
                       <div class="form-group mt-2 rounded">
                             <label for="example1" class="mt-2">Inquiry Widht</label>
                             <input type="number" class="form-control " >
                       </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group mt-2 rounded">
                          <label for="example1" class="mt-2">Inquiry QTY</label>
                          <input type="number" class="form-control " >
                    </div>
             </div>
              
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mt-2 rounded">
                        <label for="example1" class="mt-2">Quotation Length</label>
                        <input type="number" class="form-control " >
                     </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mt-2 rounded">
                        <label for="example1" class="mt-2">Quotation Hight</label>
                        <input type="number" class="form-control " >
                     </div>
                </div>
               

                <div class="col-md-3 ">
                       <div class="form-group mt-2 rounded">
                             <label for="example1" class="mt-2">Quotation Widht</label>
                             <input type="number" class="form-control " >
                       </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group mt-2 rounded">
                          <label for="example1" class="mt-2"> Weight</label>
                          <input type="number" class="form-control " >
                    </div>
             </div>
              
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mt-2 rounded">
                        <label for="example1" class="mt-2">Proccesing</label>
                        <input type="number" class="form-control " >
                     </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group mt-2 rounded">
                          <label for="example1" class="mt-2">Unit Price</label>
                          <input type="number" class="form-control " >
                    </div>
             </div>
              
                <div class="col-md-3">
                    <div class="form-group mt-2 rounded">
                        <label for="example1" class="mt-2">Shipment</label>
                        <input type="number" class="form-control " >
                     </div>
                </div>
               

                <div class="col-md-3 ">
                       <div class="form-group mt-2 rounded">
                             <label for="example1" class="mt-2">VAT</label>
                             <input type="number" class="form-control " >
                       </div>
                </div>
             
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mt-2 rounded">
                        <label for="example1" class="mt-2">Payment type</label>
                        <input type="number" class="form-control " >
                     </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group mt-2 rounded">
                          <label for="example1" class="mt-2">Amount </label>
                          <input type="number" class="form-control " >
                    </div>
             </div>
              
                
                <div class="col-md-3">
                    <div class="form-group mt-2 rounded">
                        <label for="example1" class="mt-2">Total Amount</label>
                        <input type="number" class="form-control " >
                     </div>
                </div>
                <div class="col-md-3 " style="margin-top:35px">
                    <div class="form-group mt-2 rounded">
                       <button type=submit name=submit class="btn btn-primary">submit</button>
                     </div>
                </div>
               

              
             
            </div>

            <h4 class="text-start mt-2">Data Inputan</h4>
            <div class="table-responsive text-center">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  
                    <tr>
                        <td>No</td>
                        <td>Date</td>
                        <td>Job number</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>Weight(Kg)</td>
                        <td>Unit Price</td>
                        <td>Shipment</td>
                        <td>VAT 10%</td>
                        <td>Amount</td>
                        <td>Total Amount</td>
                        <td>Processing</td>
                        <td>Custumor</td>
                        <td>Prepared</td>
                       
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>2021/09/23</td>
                        <td>JN1</td>
                        <td>SKT4</td>
                        <td>100</td>
                        <td>100</td>
                        <td>100</td>
                        <td>1</td>
                        <td>SKT4</td>
                        <td>100</td>
                        <td>100</td>
                        <td>100</td>
                        <td>1</td>
                        <td>3,1</td>
                        <td>Rp.100.000</td>
                        <td>Rp.10.000</td>
                        <td>Rp.110.000</td>
                        <td>Rp.11.000</td>
                        <td>Rp.121.000</td>
                        <td>milling</td>
                        <td>Gama Ariefsadya</td>
                        <td>Gama Ariefsadya</td>
                      
                        
                       
                    </tr>
                </table>
    
            </div>

            <h4 class="text-start mt-3">History Custumor</h4>
            <div class="table-responsive text-center mb-3">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  
                    <tr>
                        <td>No</td>
                        <td>Date</td>
                        <td>N0 Transaksi</td>
                        <td>Job number</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>Unit Price</td>
                        <td>Shipment</td>
                        <td>VAT 10%</td>
                        <td>Amount</td>
                        <td>Total Amount</td>
                        <td>Processing</td>
                        <td>Custumor</td>
                        
                       
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>2021/09/23</td>
                        <td>JN1</td>
                        <td>SKT4</td>
                        <td>100</td>
                        <td>100</td>
                        <td>100</td>
                       
                        <td>Rp.100.000</td>
                        <td>Rp.10.000</td>
                        <td>Rp.110.000</td>
                        <td>Rp.11.000</td>
                        <td>Rp.121.000</td>
                  
                        <td>Gama Ariefsadya</td>
                        <td>Gama Ariefsadya</td>
                      
                        
                       
                    </tr>
                </table>
    
            </div>
           


           
            <button type=submit name=submit class="btn btn-primary mt-2 mb-4">Submit</button> <a href="{{ url('quotation') }}" class="btn btn-primary mt-2 mb-4">Back</a>
        </form>
    </div>
</div>
</div>
@endsection