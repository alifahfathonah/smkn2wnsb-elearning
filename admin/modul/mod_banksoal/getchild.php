<?php
include "../../../system/koneksi.php";

$kd=$_POST['kds'];
$q="SELECT kd_detail_soal FROM detail_soal WHERE kd_soal='$kd' AND C='Y'";
$qc=mysqli_query($connect,$q);
$output="<label>Pilih Soal Parent</label><select class='form-control' name='parent'>";
while ($rc=mysqli_fetch_array($qc)) {
	$output .= "<option value='$rc[kd_detail_soal]'>'$rc[kd_detail_soal]'</option>";
}
$output .= "</output>";
echo $output;
 ?>