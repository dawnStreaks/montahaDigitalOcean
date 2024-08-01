<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        /* .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        } */
        
        .invoice-box table {
            width: 100%;
            /*line-height: inherit;*/
            text-align: left;
        }
        
        .invoice-box table td {
            /* padding: 5px; */
            vertical-align: top;
        }
        
        .invoice-box table tr td:nth-child(5) {
            text-align: right;
        }
        
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        
        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }
        
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        
        .invoice-box table tr.total td:nth-child(5) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        @media print {
        html, body {
         max-width: 80mm;
         max-height:50%;
         /* margin-left:-10%; */
         /* position:absolute; */
        }
     }
        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }
            
            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
        
        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }
        
        .rtl table {
            text-align: right;
        }
        
        .rtl table tr td:nth-child(5) {
            text-align: left;
        }
    </style>
</head>
<body>
    <div align='center'>
         <img src="{{ asset('upload/logo/'.$companyInfo->logo) }}" style="width:10%; height:10%;"> 
                                                               
    
        <h3>{{ $companyInfo->name }}</h3>
       
            {{ $companyInfo->address }} <br>
            phone: +965 22253470 <br>
    
            Instagram:  @montahacouture <br>
            <br>
            <div align='left'>
            No: {{$Product_Out[0]->po_no}}  &nbsp;رقم الفاتورة&nbsp;<br> Date: {{date("d/m/Y",time())}} &nbsp; التاريخ &nbsp;<br>
            </div>
            <br>
        <div align='left'>
            Customer: {{$Sales_New[0]->customer_name}}&nbsp; العميل &nbsp;<br>
            Mobile: {{$Sales_New[0]->mob_no}} الموبايل &nbsp;<br>
            Delivery Date: {{date("d/m/Y", strtotime($Product_Out[0]->date))}}
                                 <br>
        </div>
    
        </div>
<div class="invoice-box" style="transform: translateY(-50px);">
<table cellpadding="0" cellspacing="0">
    <tr class="heading">
                <td colspan="2">الصيف</td>
                <td colspan="2"> السعر</td>
                <td colspan="2">المجموع</td>

            </tr> 
            <tr class="heading">
                <td colspan="2">Name</td>
                <td colspan="2">Price</td>
                <!-- <td></td> -->
                <td colspan="2">Subtotal</td>
            </tr>

            @php 
            $total = count($Product_Out); 
            $i=1;
            $allTotal = 0;
            $allQty = 0;

            @endphp

            @foreach($Product_Out as $productData)
            

            @php 
            $i++;
            // dd($productData);
            if($i == $total){
                $tr = '';
            }
            else {
                $tr = 'last';
            }
            @endphp
            <tr class="item {{$tr}}">
                <td colspan="2">{{ $productData->product_name }} &nbsp;&nbsp; </td>
                <td colspan="2">{{ $productData->price }} x {{ $productData->qty }} &nbsp;&nbsp;</td>
                <!-- <td></td> -->
                <td colspan="2">{{ number_format($productData->subtotal * $productData->qty, 3, '.', '') }}</td>
            </tr>
            @php 
            $allTotal += $productData->subtotal * $productData->qty; 
            $allQty += $productData->qty; 

            @endphp
            @endforeach
            <br>
            <br>
            <br>

            <tr class="total">
                <!-- <td></td>
                <td></td> -->
                <td colspan="3">
                <b>Total Qty:</b> {{number_format($allQty)}} <b>الكمية </b>&nbsp;
                </td>
                </tr>
                <tr>
                <td colspan="3">
                   <b>Payment:</b> {{number_format($allTotal, 3, '.', '')}} KWD <b>المدفوع</b>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <div>
        <img src="{{ asset('upload/logo/qr-code.png') }}" style="width:50%; height:50%;">
</div>
        <div>
<br>
<br>
<p style="text-align: center;font-size: 12px">
التبديل أو الإرجاع خلال 14 يومًا من تاريخ الشراء مع الفاتورة الأصلية.</p>


<p style="text-align: center;font-size: 11px">
Exchange or return within 14 days of purchase with the original invoice.
</p>
</div>
    </div>
</body>
</html>
<!-- <!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        /* .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        } */
        
        .invoice-box table {
            width: 100%;
            /*line-height: inherit;*/
            text-align: left;
        }
        
        .invoice-box table td {
            /* padding: 5px; */
            vertical-align: top;
        }
        
        .invoice-box table tr td:nth-child(5) {
            text-align: right;
        }
        
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        
        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }
        
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        
        .invoice-box table tr.total td:nth-child(5) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        @media print {
        html, body {
         max-width: 80mm;
         max-height:50%;
         /* margin-left:-10%; */
         /* position:absolute; */
        }
     }
        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }
            
            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
        
        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }
        
        .rtl table {
            text-align: right;
        }
        
        .rtl table tr td:nth-child(5) {
            text-align: left;
        }
    </style>
</head>
<body>
    <div align='center'>
        {{-- <img src="{{ asset('upload/logo/'.$companyInfo->logo) }}" style="width:10%; height:10%;"> --}}
                                                               
        <h3>{{ $companyInfo->name }}</h3>
       
            {{ $companyInfo->address }} <br>
            phone: +965 22253470 <br>
    
            Instagram:  @montahacouture <br>
            <div align='left'>
            No: {{$Product_Out[0]->po_no}}  &nbsp;رقم الفاتورة&nbsp;<br> Date: {{date("d/m/Y",time())}} &nbsp; التاريخ &nbsp;<br>
            </div>
            <br>
        <div align='left'>
            Customer: {{$Sales_New[0]->customer_name}}&nbsp; العميل &nbsp;<br>
            Mobile: {{$Sales_New[0]->mob_no}} الموبايل &nbsp;<br>
            Delivery Date: {{date("d/m/Y", strtotime($Product_Out[0]->date))}}
                                 <br>
        </div>
         
        </div>
<div class="invoice-box" style="transform: translateY(-50px);">
    <table cellpadding="0" cellspacing="0">
    <tr class="heading">
                <td colspan="2">الصيف</td>
                <td colspan="2"> السعر</td>
                <td colspan="2">المجموع</td>

            </tr> 
            <tr class="heading">
                <td colspan="2">Name</td>
                <td colspan="2">Price</td>
                <td colspan="2">Subtotal</td>
            </tr>

            @php 
            $total = count($Product_Out); 
            $i=1;
            $allTotal = 0;
            $allQty = 0;

            @endphp

            @foreach($Product_Out as $productData)
            

            @php 
            $i++;
            // dd($productData);
            if($i == $total){
                $tr = '';
            }
            else {
                $tr = 'last';
            }
            @endphp
            <tr class="item {{$tr}}">
                <td colspan="2">{{ $productData->product_name }} &nbsp;&nbsp; </td>
                <td colspan="2">{{ $productData->price }} x {{ $productData->qty }} &nbsp;&nbsp;</td>
                <td colspan="2">{{ number_format($productData->subtotal * $productData->qty, 3, '.', '') }}</td>
            </tr>
            @php 
            $allTotal += $productData->subtotal * $productData->qty; 
            $allQty += $productData->qty; 

            @endphp
            @endforeach
            <br>
            <br>
            <br>

            <tr class="total">
                
                <td colspan="3">
                <b>Total Qty:</b> {{number_format($allQty)}} <b>الكمية </b>&nbsp;
                </td>
                </tr>
                <tr>
                <td colspan="3">
                   <b>Payment:</b> {{number_format($allTotal, 3, '.', '')}} KWD <b>المدفوع</b>
                </td>
            </tr>
        </table>
        <div>

        <p style="text-align: center;font-size: 12px">
        التبديل أو الإرجاع خلال 14 يومًا من تاريخ الشراء مع الفاتورة الأصلية.        </p>
      

        <p style="text-align: center;font-size: 11px">
        Exchange or return within 14 days of purchase with the original invoice.
        </p>
        </div>
    </div>
</body>
</html> -->