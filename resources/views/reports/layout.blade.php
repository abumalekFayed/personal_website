<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <title>تقرير</title>

    <style>
        th,
        td {
            border-bottom: solid 1px rgb(221, 221, 221);
            padding: 10px;
            text-align: center
        }

        th {
            background-color: rgb(238, 238, 238)
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }


        body {
            font-family: 'examplefont', sans-serif;
        }

        .v-table {
            width: 100%
        }

        .v-table th {
            width: 180px;
            background-color: rgb(235, 231, 252);

        }

        .v-table td {

            text-align: right;
            border-bottom: solid 1px #dddddd;

        }

        @page {
            header: page-header;
            footer: page-footer;
            margin: 150px 50px;
        }

        .center {
            text-align: center
        }

        .ma-2 {
            margin: 20px
        }

        .highlight {
            background-color: rgb(238, 238, 238)
        }

        .registerd {
            background-color: rgb(223, 223, 223)
        }

        .confirmed {
            background-color: rgb(201, 231, 243)
        }

        .payed {
            background-color: rgb(46, 179, 231)
        }

        .accepted {
            background-color: rgb(123, 253, 145)
        }

        .refused {
            background-color: rgb(255, 89, 47)
        }

    </style>

</head>

<body>

    <htmlpageheader name="page-header">
        <table style="width:100%">
            <tr>
                <td> <img src="storage/imgs/logo2.jpg" height="100px"></td>
                <td></td>
                <td>
                    <p> العتيبي </p>
                    <p> الكويت </p>
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <main class="py-4">
        @yield('content')
    </main>

    <htmlpagefooter name="page-footer">
        <hr>

        <div class="border-top center">
            انتخابات 2020 - 2021 </div>
    </htmlpagefooter>


</body>

</html>
