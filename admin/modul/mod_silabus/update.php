<?php 
include "../../../system/koneksi.php";

$kd_mapel=$_POST['mapel'];
$judul=$_POST['judul'];
$kd_guru=$_POST['kd_guru'];
$upl=date("Y-m-d");

$temp = "../../files/silabus/";
if (!file_exists($temp))
	mkdir($temp);

$fileupload      = $_FILES['silabusfile']['tmp_name'];
$filename       = $_FILES['silabusfile']['name'];
$filetype       = $_FILES['silabusfile']['type'];

if (!empty($fileupload)){
        // mengacak angka untuk nama file
	$acak = rand(00000000, 99999999);

	$filext       = substr($filename, strrpos($filename, '.'));
        $filext       = str_replace('.','',$filext); // Extension
        $filename      = preg_replace("/\.[^.\s]{3,4}$/", "", $filename);
        $newfilename   = $filename."_".$acak.'.'.$filext;

        $q="INSERT INTO silabus (judul,nama_file,tanggal_upload) VALUES ('$judul','$newfilename','$upl')";
        if ($res=mysqli_query($connect,$q)) {
        	
        	move_uploaded_file($_FILES["silabusfile"]["tmp_name"], $temp.$newfilename); // Menyimpan file
        	echo "<script>alert('Berhasil diupload'); location='../../media.php?module=silabus'</script>";
        }
        echo "gagal";
    } else 
    ?>