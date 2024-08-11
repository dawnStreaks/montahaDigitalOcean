<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        .invoice-box {
            max-width: 80mm;
            margin: auto;
            font-size: 12px;
            line-height: 16px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 4px;
            vertical-align: top;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.item td {
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
            .invoice-box {
                max-width: 80mm;
            }

            .invoice-box table tr.top table td,
            .invoice-box table tr.information table td {
                text-align: left;
            }
        }

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
    <div class="invoice-box">
        <div align='center'>
            <img src="{{ asset('upload/logo/'.$companyInfo->logo) }}" style="width:50%; height:50%;">
            <h3>{{ $companyInfo->name }}</h3><br>
            {{ $companyInfo->address }} <br>
            phone: +965 22253470 <br>
            Instagram:  @montahacouture <br>
        </div>

        <div align='left' style="margin-top: 10px;">
            No: {{$Product_Out[0]->po_no}} &nbsp;رقم الفاتورة&nbsp;<br>
            Date: {{date("d/m/Y",time())}} &nbsp; التاريخ &nbsp;<br>
            Customer: {{$Product_Out[0]->customer_name}}&nbsp; العميل &nbsp;<br>
            Mobile: {{$Product_Out[0]->mob_no}} الموبايل &nbsp;<br>
            Delivery Date: {{date("d/m/Y", strtotime($Product_Out[0]->date))}} &nbsp;
        </div>

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
                $credit = 0;
                $paid_amount = 0;
            @endphp

            @foreach($Product_Out as $productData)
                @php 
                    $i++;
                    $tr = $i == $total ? '' : 'last';
                    $credit += $productData->balance;
                    $paid_amount += $productData->paid_amount;
                    $allTotal += $productData->subtotal * $productData->qty; 
                @endphp
                <tr class="item {{$tr}}">
                    <td colspan="2">{{ $productData->product_name }} &nbsp;&nbsp;</td>
                    <td colspan="2">{{ $productData->price }} x {{ $productData->qty }} &nbsp;&nbsp;</td>
                    <td colspan="2">{{ number_format($productData->subtotal * $productData->qty, 3, '.', '') }}</td>
                </tr>
            @endforeach

            <tr class="total">
                <td colspan="1"><b>Qty:</b></td>
                <td colspan="1">{{number_format($total)}} <b>الكمية</b></td>
                <td colspan="1"><b>Cost:</b></td>
                <td colspan="1">{{number_format($allTotal, 3, '.', '')}} KWD</td>
                <td colspan="1"><b>يكلف</b></td>
            </tr>
            <tr>
                <td colspan="1"><b>Credit:</b></td>
                <td colspan="1">{{ number_format($credit, 3, '.', '') }} KWD</td>
                <td colspan="1"><b>الائتمان</b></td>
                <td colspan="1"><b>Paid:</b></td>
                <td colspan="1">{{ number_format($paid_amount, 3, '.', '') }} KWD</td>
                <td colspan="1"><b>مدفوع</b></td>
            </tr>
        </table>

        <div align='center' style="margin-top: 10px;">
            <img src="{{ asset('upload/logo/qr-code.png') }}" style="width:50%; height:50%;">
            <p style="font-size: 12px; margin-top: 10px;">
                <b>التبديل أو الإرجاع خلال 14 يومًا من تاريخ الشراء مع الفاتورة الأصلية.</b>
            </p>
            <p style="font-size: 12px;">
                <b>Exchange or return within 14 days of purchase with the original invoice.</b>
            </p>
        </div>
    </div>
</body>
</html>
