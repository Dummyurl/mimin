<?php

    $server		= "localhost"; //sesuaikan dengan nama server
	$user		= "root"; //sesuaikan username
	$password	= ""; //sesuaikan password
	$database	= "mimin"; //sesuaikan target databese

	$con = mysqli_connect($server, $user, $password, $database);
	if (mysqli_connect_errno()) {
		echo "Gagal terhubung MySQL: " . mysqli_connect_error();
	}


	$query = mysqli_query($con, "SELECT * FROM Socity ");
	$json = '{"Marker": [';

	// bikin looping dech array yang di fetch
	while ($row = mysqli_fetch_array($query)){

	//tanda kutip dua (") tidak diijinkan oleh string json, maka akan kita replace dengan karakter `
	//strip_tag berfungsi untuk menghilangkan tag-tag html pada string
		$char ='"';

		$json .=
		'{
			"id_marker":"'.str_replace($char,'`',strip_tags($row['socity_id'])).'",
			"name":"'.str_replace($char,'`',strip_tags($row['socity_name'])).'",
			"lat":"'.str_replace($char,'`',strip_tags($row['latitude'])).'",
			"lng":"'.str_replace($char,'`',strip_tags($row['longitude'])).'"
		},';
	}

	// buat menghilangkan koma diakhir array
	$json = substr($json,0,strlen($json)-1);

	$json .= ']}';

	// print json
	echo $json;

	mysqli_close($con);

?>