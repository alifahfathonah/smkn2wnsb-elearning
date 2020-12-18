<?php
include '../system/koneksi.php'; 
if (isset($_POST['act'])) {
	switch ($_POST['act']) {
		case 'kelasmapel':
		$mapel=$_POST['mp'];
		$kd_guru=$_POST['kdg'];
		$q="SELECT kelas.kd_kelas, kelas.nama_kelas 
		FROM pengajaran as p, kelas, mapel
		WHERE p.kd_kelas=kelas.kd_kelas AND p.kd_mapel=mapel.kd_mapel AND mapel.kd_mapel='$mapel' AND p.kd_guru='$kd_guru'";

		$kelas = "<div class=\"form-group\">";
		$qkls=mysqli_query($connect, $q);
		while ($kls=mysqli_fetch_array($qkls)){
			$kelas .= "
			<input type='checkbox' value='$kls[kd_kelas]' name='kd_kls[]' checked='checked'/><label> $kls[nama_kelas] </label>
			";
		}		
		$kelas .= "</div>";

		echo $kelas;
		
		
		break;

		case 'soalmapel':

		$mapel=$_POST['mp'];
		$kd_guru=$_POST['kdg'];

		$output="<select name='kd_soal' class='form-control'>
		<option selected='selected'>Pilih Soal</option>";
		
		function jumSoal($kd,$conn){
			$q=mysqli_query($conn,"SELECT kd_detail_soal FROM detail_soal WHERE kd_soal='$kd'");
			$n=mysqli_num_rows($q);
			return $n;
		}

		$qsoal="SELECT soal.acak, soal.kd_soal,soal.nama_soal,mapel.nama_mapel
		FROM soal,mapel WHERE soal.kd_mapel=mapel.kd_mapel AND soal.kd_guru='$kd_guru' AND soal.kd_mapel='$mapel'";
		$csoal=mysqli_query($connect,$qsoal);
		while ($rsoal=mysqli_fetch_array($csoal)){
			$j=jumSoal($rsoal['kd_soal'],$connect);
			$output .= "<option value='$rsoal[kd_soal]'>$rsoal[nama_soal] - [$j]</option>";
		}

		$output .= "</select>";
		echo $output;
		
		break;

		case 'kelasajar':
		$mapel=$_POST['mp'];
		$jurusan=$_POST['jrs'];

		function cekPengajar($kon,$mapel,$kelas,$nkls){
			$query = mysqli_query($kon,"SELECT * FROM pengajaran as p,kelas,guru,mapel WHERE p.kd_guru=guru.kd_guru AND p.kd_kelas=kelas.kd_kelas AND p.kd_mapel=mapel.kd_mapel AND p.kd_mapel='$mapel' AND p.kd_kelas='$kelas'");
			$jum = mysqli_num_rows($query);
			$pengajar = mysqli_fetch_array($query);
			if ($jum>0){
				$out = " <label>
				<input type='checkbox' name='kd_kls[]' value='$kelas' disabled='disabled'/>$pengajar[nama_kelas] - $pengajar[nama] 
				</label> ";
			} else {
				$out = " <label>
				<input type='checkbox' name='kd_kls[]' value='$kelas' />$nkls
				</label> ";
			}
			return $out;
		}

		$query=mysqli_query($connect,"SELECT kd_kelas,nama_kelas FROM kelas WHERE kd_jurusan='$jurusan' ORDER BY tingkat");
		$output="<div class='checkbox'>";
		while ($result=mysqli_fetch_array($query)) {
			$output .= cekPengajar($connect,$mapel,$result['kd_kelas'],$result['nama_kelas']);
		}
		$output .= "</div>";
		echo $output;

		break;

		case 'mdlslb':
		$kd=$_POST['kd'];

		$query=mysqli_query($connect,"SELECT * FROM pengajaran,mapel,kelas WHERE pengajaran.kd_mapel=mapel.kd_mapel AND pengajaran.kd_kelas=kelas.kd_kelas AND pengajaran.kd_pengajaran='$kd'");
		$rsl=mysqli_fetch_array($query);
		if (mysqli_num_rows($query)){
			echo "<div class='form-group'?>
			<input type='hidden' name='kd_pengajaran' value='$kd'>
			<label>Pilih Silabus</label>
			<select name='kd_silabus' class='form-control'>";
			$qs=mysqli_query($connect,"SELECT * FROM silabus WHRE kd_mapel='$rsl[kd_mapel]' AND kd_jurusan='$rsl[kd_jurusan]' AND tingkat='$rsl[tingkat]'");

			echo "</select>
			</div>
			";
		}
		
		break;

		default:
		echo "alert('Terjadi Kesalahan!')";
		break;
	}
}
?>
