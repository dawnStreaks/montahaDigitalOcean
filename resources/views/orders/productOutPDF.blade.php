<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        /* Base font size for the entire invoice */
        body {
            font-size: 20px; /* Increase the base font size */
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box {
            width: 100%;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 12px; /* Increase font size in invoice-box */
            line-height: 30px; /* Increase line height for better readability */
        }

        .invoice-box table {
            width: 100%;
            text-align: left;
        }
        
        .invoice-box table td {
            padding: 7px; /* Increase padding for better spacing */
            vertical-align: top;
            font-size: 12px; /* Increase font size for table cells */
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            font-size: 15px; /* Larger font for headings */
        }
        
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
            font-size: 15px; /* Larger font for items */
        }
        
        .invoice-box table tr.total td {
            border-top: 2px solid #eee;
            font-weight: bold;
            font-size: 15px; /* Larger font for totals */
        }

        @media print {
            html, body {
                max-width: 80mm;
                font-size: 15px; /* Larger print font size */
            }
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td,
            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
                font-size: 15px; /* Larger font size for small screens */
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
        <img src="{{ asset('upload/logo/'.$companyInfo->logo) }}" style="width:50%; height:50%;">
        <h3>{{ $companyInfo->name }}</h3><br>
        {{ $companyInfo->address }} <br>
        phone: +965 22253470 <br>
        Instagram: @montahacouture <br>
        <br>
        <div align='left'>
            No: {{$Product_Out[0]->po_no}} &nbsp;رقم الفاتورة&nbsp;<br> Date: {{date("d/m/Y",time())}} &nbsp; التاريخ &nbsp;<br>
        </div>
        <br>
        <div align='left'>
            Customer: {{$Product_Out[0]->customer_name}}&nbsp; العميل &nbsp;<br>
            Mobile: {{$Product_Out[0]->mob_no}} الموبايل &nbsp;<br>
            Delivery Date: {{date("d/m/Y", strtotime($Product_Out[0]->date))}} &nbsp;
        </div>
    </div>

    <div class="invoice-box" style="transform: translateY(-80px) translateX(-40px);">
        <table cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td colspan="1">الصيف</td>
                <td colspan="1">السعر</td>
                <td colspan="1">المجموع</td>
            </tr>
            <tr class="heading">
                <td colspan="1">Name</td>
                <td colspan="1">Price</td>
                <td colspan="1">Subtotal</td>
            </tr>

            @php 
            $total = count($Product_Out); 
            $i=1;
            $allTotal = 0;
            $credit = 0;
            $paid_amount = 0;
            @endphp

            @foreach($Product_Out as $productData)
            @php 
            $i++;
            if($i == $total){
                $tr = '';
            } else {
                $tr = 'last';
            }
            @endphp
            <tr class="item {{$tr}}">
                <td colspan="1">{{ $productData->product_name }} &nbsp;&nbsp;</td>
                <td colspan="1">{{ $productData->price }} x {{ $productData->qty }} &nbsp;&nbsp;</td>
                <td colspan="1">{{ number_format($productData->subtotal * $productData->qty, 3, '.', '') }}</td>
            </tr>
            @php 
            $credit += $productData->balance;
            $paid_amount += $productData->paid_amount;
            $allTotal += $productData->subtotal * $productData->qty; 
            @endphp
            @endforeach
            <br>
            <br>
            <br>

            <tr class="total">
                <td colspan="1">Qty:
                {{number_format($total)}} الكمية</td>
                <td colspan="1"> Total:
                {{number_format($allTotal, 3, '.', '')}}KWD </td>
                <td colspan="1"><b>يكلف</b></td>
            </tr>
            <tr>
                <td colspan="1"><b>Credit: </b>
                {{ number_format($credit, 3, '.', '') }}KWD </td>
                <td colspan="1"><b> الائتمان</b></td>
                <!-- <td colspan="1"></td> -->
                <td colspan="1"><b>Paid:</b>
                {{ number_format($paid_amount, 3, '.', '') }}KWD </td>
                <td colspan="1"><b> مدفوع </b></td>
            </tr>
        </table>
        <div align='center'>
                <p style="text-align: center;font-size: 16px"><b>التبديل أو الإرجاع خلال 14 يومًا من تاريخ الشراء مع الفاتورة الأصلية.</b></p>
                <p style="text-align: center;font-size: 16px"><b>Exchange or return within 14 days of purchase with the original invoice.</b></p>
            </div>
        <br>
        <br>

        <div align='center'>
            <img src="{{ asset('upload/logo/qr-code.png') }}" style="width: 100px; height: 100px;">
            <br>
            <br>
           
        </div>
    </div>
</body>
</html>
