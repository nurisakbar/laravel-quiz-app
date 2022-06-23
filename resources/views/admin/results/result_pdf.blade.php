<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body{font-family: Arial, Helvetica, sans-serif;}
        .page_break { page-break-before: always; }
        #cover{
            text-align: center;
        }
        #activity{
            text-align: left;
        }
        table, td, th {
        border: 1px solid;
        }
        table {
        width: 100%;
        border-collapse: collapse;
        }
        .signature-name{
            font-weight: bold;
            margin-bottom:-10px;
            text-decoration:underline;
            margin-top:70px;
        }
        .signature{
            margin-top:30px;
            width:165px;
            text-align: center;
            margin-right: 10px;
            float: left;
        }
    </style>
</head>
<body>
    <p style="font-size:20px;text-align:center">HASIL PENILAIAN ASESMENT PT Neuronworks Indonesia</p>
    <hr>
    <p>Nama Peserta : <b>{{ $result->user->name }}</b></p>
    <p>Kategori Test &nbsp;: <b>{{ $category->name}}</b></p>

    <?php
    // hitung jumlah soal 
    $hitungJumlahSoal = \App\Models\Question::where('category_id',$_GET['category'])->count();
    $nilaiPersoal = 100/$hitungJumlahSoal;
    ?>

    <hr>
    <h4>Resume Penilaian</h4>
    <hr>
    <table>
        <tr>
            <th width="20">Nomor</th>
            <th>Topik</th>
            <th>Skor</th>
            <th>Jawaban Benar</th>
            <th>Jumlah Soal</th>
            <th>Kesimpulan</th>
        </tr>
        <?php $total=0;?>
        @foreach($categories as $category)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $category->name}}</td>
                <td><?php
                
                $hitung = \DB::select("select count(points) as jumlah_benar, sum(points) as nilai,(select count(id) from questions where topic_categori_id='".$category->id."') as jumlah_soal 
                from question_result where result_id='".Request::segment(3)."'
                and question_id in(select id from questions where topic_categori_id='".$category->id."')");

                $jumlah_benar = \DB::select("select count(id) as jumlah_benar
                                            from question_result
                                            where result_id='".Request::segment(3)."' and points>0 and 
                                            question_id in(select id from questions 
                                            where topic_categori_id='".$category->id."')");
                $point = $hitung[0]->nilai??0;
                $point = $point*$nilaiPersoal;
                $total=$total+$point;
                $benar = $jumlah_benar[0]->jumlah_benar;
                echo round($point);

                $resume_hitung = (100/$hitung[0]->jumlah_soal)*$benar;
                if($resume_hitung>85)
                {
                    $resume = "Sangat Baik";
                }elseif($resume_hitung>70)
                {
                    $resume = "Baik";
                }elseif($resume_hitung>60)
                {
                    $resume = "Cukup";
                }else{
                    $resume = "Kurang";
                }
                ?>
            </td>
            <td>{{ $benar}}</td>
            <td>{{ $hitung[0]->jumlah_soal}}</td>
            <td>{{ round($resume_hitung).' / '.  $resume}}</td>
            </tr>
        @endforeach
    <tr>
        <td colspan="2">Total</td>
        <td colspan="4">{{ round($total) }}</td>
    </tr>
    </table>

    <br>
    <p>Keterangan</p>
    Skor : Jawaban Benar x ( 100 : jumlah soal);<Br>
    Kesimpulan : (100/Jumlah Soal Pada Kategori ) x jumlah soal benar
    <hr>
    <h4>Detail Pertanyaan Dan Soal</h4>
    <table style="font-size:10px">
        <thead>
                <tr>
                    <th>Question Text</th>
                    <th width="100">Topic</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result->questions as $question)
                    <tr>
                        <td>{{ $question->question_text }}</td>
                        <td>{{ $question->subCategory->name }}</td>
                        <td>{{ $question->pivot->points }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</body>
</html>