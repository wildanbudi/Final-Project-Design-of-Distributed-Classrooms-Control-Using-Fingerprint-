<?php
include "koneksi.php";

$log = mysqli_query($koneksi,"SELECT DAYNAME(scan_date) as mydate, TIME(scan_date) as mytime, pin, sn FROM 
       att_log WHERE sn = 66595018301157 ORDER BY scan_date DESC");

$lastrow=mysqli_fetch_array($log);
$time = $lastrow['mytime'];
$day = $lastrow['mydate'];
$id_scan1 = $lastrow['pin'];
$room = $lastrow['sn'];
echo $time;
echo $id_scan1;
echo $day;
echo $room;


$jadwal = mysqli_query($koneksi,"SELECT jadwal.hari,jadwal.waktu,jadwal.akhir,dosen.id_scan,kelas.sn FROM jadwal JOIN 
          dosen ON jadwal.id_dosen = dosen.id_dosen OR jadwal.id_dosen2 = dosen.id_dosen JOIN kelas ON jadwal.id_kls = kelas.id_kls WHERE 
          jadwal.hari = '$day' AND dosen.id_scan ='$id_scan1' AND kelas.sn = '$room' AND jadwal.waktu <= '$time' AND '$time' <= jadwal.akhir");

if (mysqli_num_rows($jadwal) > 0) {
    $resultSet = array();
    while ($row=mysqli_fetch_assoc($jadwal)){
        $hari = $row['hari'];
        $start_time = $row['waktu'];
        $end_time = $row['akhir'];
        $id_scan2 = $row['id_scan'];
        $ruang = $row['sn'];
        $resultSet[] = $id_scan2;
        $resultSet2[] = $hari;
        $resultSet3[] = $start_time;
        $resultSet4[] = $end_time;
        $resultSet5[] = $ruang;
        $string10 = rtrim(implode(',', $resultSet3), ',');
        $string11 = rtrim(implode(',', $resultSet4), ',');
        echo $start_time;
        echo $end_time;
        echo $ruang;
    }

}

if (in_array($id_scan1, $resultSet)){
    if(in_array($day, $resultSet2)){
        if (in_array($room, $resultSet5)) {
            if(($string10 <= $time) && ($time <= $string11)){
                    echo "% Otorisasi berhasil";       
            } 
            else{
                 echo "% Tidak sesuai jadwal";
            }
        }
        else{
            echo "% Tidak sesuai jadwal";
        }
    }
    else{
        echo "% Tidak sesuai jadwal";
    }
}
else {
    echo "% Tidak sesuai jadwal";
}