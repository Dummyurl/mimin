<?php

    $server		= "localhost"; //sesuaikan dengan nama server
	$user		= "root"; //sesuaikan username
	$password	= ""; //sesuaikan password
	$database	= "mimin"; //sesuaikan target databese

	$con = mysqli_connect($server, $user, $password, $database);
	if (mysqli_connect_errno()) {
		echo "Gagal terhubung MySQL: " . mysqli_connect_error();
	}

	class emp{}

	$user_id = $_POST['user_id'];
	$sales = urldecode($_POST['sales']);
	$toko = $_POST['toko'];
    $pemilik = $_POST['pemilik'];
	$alamat = $_POST['alamat'];
    $image = $_POST['image'];
	$keterangan = $_POST['keterangan'];

	if (empty($keterangan)) {
		$response = new emp();
		$response->success = 0;
		$response->message = "Harus Diisi Semua !";
		die(json_encode($response));
	} else {
		$random = random_word(20);

		$path = "uploads/kunjungan/".$random.".jpg";
		$image_name = "".$random.".jpg";
		// sesuiakan ip address laptop/pc atau URL server
		$actualpath = "$path";

		$query = mysqli_query($con, "INSERT INTO kunjungan (image,toko,pemilik,alamat,sales,keterangan,uid) VALUES ('$image_name','$toko','$pemilik','$alamat','$sales','$keterangan',$user_id)");

		if ($query){
			file_put_contents($path,base64_decode($image));

			$response = new emp();
			$response->success = 1;
			$response->message = "Data Berhasil di Unggah";
			die(json_encode($response));
		} else{
			$response = new emp();
			$response->success = 0;
			$response->message = "Gagal Unggah";
			die(json_encode($response));
		}
	}

	// fungsi random string pada gambar untuk menghindari nama file yang sama
	function random_word($id_kunjungan = 20){
		$pool = '1234567890abcdefghijkmnpqrstuvwxyz';

		$word = '';
		for ($i = 0; $i < $id_kunjungan; $i++){
			$word .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}
		return $word;
	}

	mysqli_close($con);

?>