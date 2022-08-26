{{-- @dd($students[0]->studentClass->name) --}}
<html>

<head>
    <title>Card Print</title>
    <script src="/assets/vendor/libs/jquery/jquery.js"></script>
    <script type="text/javascript" src="/assets/qr/js/jquery-qrcode-0.18.0.js"></script>
</head>
<style>
    table {
        width: 100%;
        border: 0px;
        padding: 2px;
        font-size: 0.75em;
        color: #000 !important;
        font-family: Verdana, Arial, sans-serif;
    }

    td {
        vertical-align: top;
    }

    hr {
        border: 0.5px solid black;
    }

    .header {
        text-align: center;
        font-weight: bold;
        font-size: 1.1em;
    }

    .kartu {
        width: 310px;
        border: 2px solid black;
        border-radius: 8px;
        padding: 3px;
        margin: 10px;
        display: inline-block;
    }

    .output {
        border: 1px solid #eee;
        min-height: 100px;
        width: 100px;
    }
</style>

<body>

    @foreach ($students as $student)
        <div class="kartu">
            <div class="header">SMA Banjarbaru</div>
            <hr />
            <table>
                <tr>
                    <td>
                        <div id="qrcode-{{ $student->id }}">
                        </div>
                    </td>
                    <td>
                        <p>{{ $student->name }}</p>
                        <p>{{ $student->student_id_number }}</p>
                        <p>{{ $student->studentClass->name }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <script type="text/javascript">
            //menampilkan qr code ke id qrcode dari elemen id teks
            $("#qrcode-{{ $student->id }}").qrcode({
                text: "{{ $student->student_id_number }}",
                size: 100,
            });
        </script>
    @endforeach

    <script lang="javascript">
        $(function() {
            window.print();
        });
    </script>


</body>

</html>
