<?php 
include "../../../system/koneksi.php";

if (isset($_GET['act'])){
  switch ($_GET['act']) {
    case 'add':
    $kd_mapel=$_POST['mapel'];
    $judul=$_POST['judul'];
    $kd_guru=$_POST['kd_guru'];
    date_default_timezone_set("Asia/Bangkok");
    $upl=date('Y-m-d H:i:s');

//buat kode
    $thn=date("Y");
    $k="04".$thn.$kd_guru;
    $qsil="SELECT MAX(kd_silabus) AS kode FROM silabus WHERE kd_silabus LIKE '$k%'";
    $max=mysqli_fetch_array(mysqli_query($connect,$qsil));
    $kodeurut=substr($max['kode'],strlen($k),3)+1;
    if ($kodeurut<10) {
      $kodeurut="00".$kodeurut;
    } else if ($kodeurut<100){
      $kodeurut="0".$kodeurut;
    } else {

    }
    $kd_silabus=$k.$kodeurut;

    $temp = "../../files/silabus/";
    if (!file_exists($temp)){
      mkdir($temp);
    } 

    $fileupload      = $_FILES['silabusfile']['tmp_name'];
    $filename       = $_FILES['silabusfile']['name'];
    $filetype       = $_FILES['silabusfile']['type'];

    if (!empty($fileupload)){
        // mengacak angka untuk nama file
        //$acak = rand(00000000, 99999999);

      $filext       = substr($filename, strrpos($filename, '.'));
        $filext       = str_replace('.','',$filext); // Extension
        $filename      = preg_replace("/\.[^.\s]{3,4}$/", "", $filename);
        $newfilename   = $kd_silabus.'.'.$filext;


        $q="INSERT INTO silabus (kd_silabus,judul,nama_file,tanggal_upload) VALUES ('$kd_silabus','$judul','$newfilename','$upl')";

        if ($res=mysqli_query($connect,$q)) {
          move_uploaded_file($_FILES["silabusfile"]["tmp_name"], $temp.$newfilename);
          echo "<script>alert('Berhasil Upload Silabus'); location='../../media.php?module=silabus'</script>";
        } else {
          echo "gagal";  
        } 
      } else {
        echo "tidak ada file";
      }
      break;

      case 'upd':
      $qup="UPDATE pengajaran
      SET kd_silabus='$kd_silabus'
      WHERE kd_guru='$kd_guru' AND kd_mapel='$kd_mapel'";
      $up=mysqli_query($connect,$qup);
      break;

      default:
      # code...
      break;
    }
  }


  ?>