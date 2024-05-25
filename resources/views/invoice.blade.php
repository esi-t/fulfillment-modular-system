<!DOCTYPE html>
<html dir="ltr">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>invoice</title>
</head>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .all{
            margin: 5px 5px 0 8px;
            direction: rtl;
        }

        .container{
            /* width: 300px; */
            height: auto;
            /* border: 1px solid black; */
        }

        .header_top{
            /* width: auto; */
            height: auto;
            border: 1px solid black;
            text-align: center;
        }

        .explain{
            border: 1px solid black;
            justify-content: center;
            /* text-align: center; */
            margin: 1px 0px;
            font-size: 11px;
            padding: 3px;
        }

        .title{
            display: flex;
            justify-content: center;
            padding: 15px;
        }

        .title img{
            max-width: 100%;
        }
        .content{
            font-size: 12px;
        }
        .content span{
            display: block;
            text-align: center;
            /* padding-right: 300px; */
        }
        .details{
            font-size: 12px;
        }
        .cash{
            display: flex;
            justify-content: center;
            /* padding-left: 120px; */

        }

        .date {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            flex-direction: column;
            padding-bottom: 0.5em;
        }
        .date span {
            text-align: center;
        }
        .customer_code{
            display: flex;
            justify-content: center;
            /* padding-left: 120px;
            padding-right: 20px; */
            /* margin: 20px 100px; */
            border-top: 1px solid black;
            padding: 8px 0;
            font-size: 0.9em;
        }
        body{
            text-align: right;
            /* font-family: 'B Yekan' , 'Arial', sans-serif; */
            width: 275px;

        }

        table{
            border-collapse: collapse;
            width: 100%;
            line-height: 12px;

        }
        .product_info th , td {
            border: 1px solid black;
            text-align: center;
            padding: 5px;
            font-size: 9px;
        }

        .pro_name{
            text-align: center;
            display: block;
        }
        .p-sale-t-sale{
            display: block;
        }
        /* th {
            background-color: black;
            color: white;
        } */


        .buy_info td{
            display: flex;
            text-align: right;
            /* font-size: 20px; */
        }

        .payment_term{
            justify-content: center;
            text-align: center;
            margin-top: 1px;
            font-size: 12px;
            border: 1px solid #000;
            margin-bottom: 1px;
            height: 45px;
            display: flex;
            flex-direction: column;
        }

        .payment_term p span:first-child{
            /* border-left: 1px solid black; */
            padding-left: 20px;
            margin-left: 10px;
        }
        .address{
            border: 1px solid black;
            justify-content: center;
            /* text-align: center; */
            margin: 1px 0px;
            font-size: 11px;
            padding: 1em;
        }
        .tracking_code span{
            display: block;
            text-align: center;
            background-color: lightgray;
            border: 1px solid black;
        }
        .tracking_code span:last-child {
            border-top: 0px;
        }

        .tracking_code{
            font-size: 12px;
        }

        .onlineـshopping{
            border: 1px solid black;
            margin: 2px 0;
            text-align: center;
            font-size: 14px;
            display: block;

        }

        .vendor_info{
            padding: 0.7rem;
            /* flex-direction: row; */
            display: flex;
            flex-wrap: wrap;
            border: 1px solid black;
            margin-top: 1px;
            font-size: 11px;
        }
        .vendor_info span {
            width: 100%;
            text-align: center;
        }

        .vendor_info > .vn_if {
            width: 100%;
            padding: 0 10px;
        }

        .icons {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 10px;
        }
        /* .icons img {
            margin-right: 5px
        } */
        .dir{
            direction: ltr;

        }

        .thanktobuy{
            display: flex;
            text-align: center;
            flex-direction: column;
            border: 1px solid black;
            margin-top: 1px;
            font-size: 14px;
            padding: 0.5em;
        }
        .vat{
            display: flex;
            text-align: center;
            border: 1px solid black;
            font-size: 9px;
            flex-direction: column;
            margin-top: 1px;
        }


        .returned{
            display: flex;
            margin: 2px 0;
            flex-direction: column;
            text-align: center;
            border: 1px solid black;
            font-size: 12px;
            line-height: 1.5em;
            padding: 0.5em;
        }

        .returned span :first-child{
            margin-top: 10px;

        }
        hr{
            height: 1px;
            border-top: 1px dashed gray;
            margin: 2px 0px;
        }
        .payment_term_child{
            margin-top: 1px;
        }
    </style>

<body>

  <div class="all">
    <div class="container">
        <div class="header_top">
            <div class="title">
                <img src="{{ public_path('images/logo.jpg') }}" alt="logo">
            </div>

            <div class="content">
{{--                <span>فراتر از تجربه</span>--}}
{{--                <br>--}}
                <span>فروشگاه زنجیره ای دیلی مارکت</span>
                <br>
                <span>{{$customerName}}</span>
            </div>
            <div>

            </div>

            <div class="details">
                <div class="cash">
{{--                    <span>صندوقدار : </span>--}}
{{--                    <span style="margin-right: 10px;">{{$cashier}}</span>--}}
{{--                </div>--}}

                <div class="date">
                    <span>شماره : {{$invoiceNumber}}</span>
                    <br>
                    <span>کد سفارش : {{$orderCode}}</span>
                    <br>
                    <span> تاریخ و ساعت: {{verta($orderDate)}}</span>
                    <!-- <span> 1402/11/04</span>
                    <span> 20:30</span> -->
                </div>
            </div>

            <div class="customer_code">
                <span>کد اشتراک مشتری: {{$customerMobile}}</span>
                <!-- <span style="margin-right: 10px;" >989355544558+</span> -->
            </div>

        </div>
        <html-separator/>
<!-- ------------------------------------------------------------------- -->
        <div class="product_info">
            <table>
                <thead>
                    <tr>
                        <th>مبلغ کل</th>
                        <th>قیمت مصرف کننده</th>
                        <th>تعداد</th>
                        <th class="product_name" colspan="2">نام کالا</th>
                    </tr>
                </thead>
<!-- ------------------------------------------------------------------- -->
                @foreach ($products as $product)
                    <tr>
                        <td>{{ number_format(($product['price'] * $product['quantity']), 0, ',') }}</td>
                        <td>{{number_format($product['price'])}}</td>
                        <td colspan="2">{{$product['quantity']}}</td>
                        <td>
                            <span class="pro_name">{{ $product['title'] }}</span>
                            <span>{{ $product['barcode'] }}</span>
                        </td>
                    </tr>
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>--}}
{{--                            <span class="pro_name">{{ $product['title'] }}</span>--}}
{{--                            <span>{{ $product['barcode'] }}</span>--}}
{{--                        </td>--}}
{{--                        <td>{{ number_format($product['price'], 0, ',') }}</td>--}}
{{--                        <td>--}}
{{--                            <span class="p-sale-t-sale">{{$product['quantity']}}</span>--}}
{{--                            <hr>--}}
{{--                            <span>{{ number_format($product['discount'], 0, ',') }}</span>--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            <span class="p-sale-t-sale">{{ number_format(($product['price'] - $product['discount']) * $product['quantity'], 0, ',') }}</span>--}}
{{--                            <hr>--}}
{{--                            <span>{{ number_format(($product['price'] * $product['quantity']), 0, ',') }}</span>--}}
{{--                        </td>--}}

                @endforeach
<!-- ------------------------------------------------------------------- -->
                    <tr class="byu_info">
                        <td colspan="4">
                            تعداد اقلام
                        </td>
                        <td>
                            {{$productsCount}}
                        </td>
                    </tr>

                    <tr class="byu_info">
                        <td colspan="4">
                            مجموع قیمت مصرف کننده
                        </td>
                        <td>
                            {{number_format($totalPrice, 0, ',')}}
                        </td>
                    </tr>

{{--                    <tr class="byu_info">--}}
{{--                        <td colspan="3">--}}
{{--                            مجموع سود شما از خرید--}}
{{--                       </td>--}}
{{--                        <td>--}}
{{--                            {{number_format($saleProfit, 0, ',')}}--}}
{{--                        </td>--}}
{{--                    </tr>--}}

                    <!-- <tr class="byu_info">
                        <td colspan="3">
                            VAT
                        </td>
                        <td>
                            97,520
                        </td>
                    </tr> -->

{{--                    <tr class="byu_info">--}}
{{--                        <td colspan="3">--}}
{{--                            مبلغ قابل پرداخت--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            {{number_format($toPay, 0, ',')}}--}}
{{--                        </td>--}}
{{--                    </tr>--}}
                </tbody>
            </table>
        </div>
        <html-separator/>
<!-- ------------------------------------------------------------------- -->
        <div class="payment_term">
            <p class="payment_term_child">نحوه پرداخت:</p>
            <p>
                <span>{{$channelName}}</span>
                <span>{{number_format($totalPrice, 0, ',')}}</span>
            </p>
        </div>
    </div>

    <div class="address">
        <label>: آدرس </label>
        <p>{{$customerAddress}}</p>
    </div>

        <div class="explain">
            <label>توضیحات: </label>
            <!-- explain -->
            <!-- تغییر در توضیحات : اضافه شده -->
            <p>{{$comment}}</p>
        </div>
{{--    <div class="tracking_code">--}}
{{--        <span>کد رهگیری</span>--}}
{{--         <span>990533143701</span> <!-- ? -->--}}
{{--    </div>--}}

{{--    <div class="onlineـshopping">--}}
{{--        خرید اینترنتی : {{$channelName}}--}}
{{--    </div>--}}

      <div class="vendor_info">
          <div style="margin: auto; text-align: center; margin-bottom: 0.7em">
            <span>
                فروشگاه های زنجیره ای سرزمین خرید
            </span>
          </div>
{{--          <div class="vn_if">--}}
{{--              صدای مشتریان: 90008080--}}
{{--          </div>--}}
          <div class="vn_if">
              کد پستی: 1399613511
          </div>
{{--          <div class="vn_if">--}}
{{--              کد اقتصادی: 411657897533--}}
{{--          </div>--}}
          <div class="vn_if">
              شناسه ملی: 1400958722
          </div>
        <!-- <div class="vn_if icons">
            www.dailymarketstores.com -->
            <!-- <img src="public_FILL0_wght400_GRAD0_opsz24.svg" alt=""> -->
        <!-- </div> -->
        <!-- <div class="vn_if icons">
            @dailymarketstores -->
            <!-- <img src="unnamed.jpg" alt=""> -->
        <!-- </div> -->
        <!-- <div class="thanktobuy"> از خرید شما سپاسگزاریم</div> -->

    </div>

    <div class="thanktobuy"> از خرید شما سپاسگزاریم</div>
      <html-separator/>

    <!-- <div class="barcode">
        <span></span>
        <span></span>
    </div> -->

    <div class="returned">
        <span>بازگشت اقلام تا ۴۸ ساعت و بر اساس قوانین مرجوع کالا امکان پذیر است</span>
        <span>عودت اقلام فاسد شدنی ، آرایشی و بهداشتی مقدور نمیباشد</span>
    </div>
  </div>
</body>
</html>
