<?php
$koneksi=mysqli_connect("localhost","root","","fingerspot");

if($koneksi){
    echo "";
}
else {
    echo "koneksi gagal";
}