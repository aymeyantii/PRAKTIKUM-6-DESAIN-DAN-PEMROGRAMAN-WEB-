<?php
//Tipe data dan operator: semua variabel di php diawal dengan $
//Global variable
//$_REQUEST = POST/GET
$action = $_REQUEST['action'];

//DATA YANG DIKIRIM ASYNC => $_REQUEST= {dataku : "firstname=ali&lastname=bos&paymentMethod=creditcard"}
/* ASYNC */
parse_str($_REQUEST['dataku'], $hasil); 


//echo "Nama Depan: ".$hasil['namaDepan']."<br/>";
//echo "Nama Belakang: ".$hasil['namaBelakang']."<br/>";

//ASYNC, data yang diterima format array of record memerlukan looping untuk parsing data - tidak digunakan di sini
//$arrrecordhasil = $_REQUEST['dataku'];

//DATA YANG DIKIRIM SYNC => $_REQUEST = ["firstname" => "ali", "lastname" => "bos"]
/* SYNC */
//$hasil = $_REQUEST;

/* SQL: select, update, delete */
if($action == 'create')
	$syntaxsql = "insert into tbl_pendaftaran values (null,'$hasil[namaDepan]','$hasil[namaBelakang]', '$hasil[namaPanggilan]', '$hasil[radio1]',
	'$hasil[tempatLahir]', '$hasil[tanggalLahir]', '$hasil[email]', '$hasil[alamat]', '$hasil[jurusan]', '$hasil[prodi]', '$hasil[usernameIg]',
	'$hasil[alasan]',now())";
elseif($action == 'update')
	$syntaxsql = "update tbl_pendaftaran set nama_depan = '$hasil[namaDepan]', nama_belakang = '$hasil[namaBelakang]', nama_panggilan = '$hasil[namaPanggilan]',
	jenis_kelamin = '$hasil[radio1]', tempat_lahir = '$hasil[tempatLahir]', tanggal_lahir = '$hasil[tanggalLahir]', email = '$hasil[email]', 
	alamat = '$hasil[alamat]', jurusan = '$hasil[jurusan]', prodi = '$hasil[prodi]', username_ig = '$hasil[usernameIg]',
	alasan_mengikuti_seminar = '$hasil[alasan]' where username_ig= '$hasil[usernameIg]'";
elseif($action == 'delete')
	$syntaxsql = "delete from tbl_pendaftaran where username_ig = '$hasil[usernameIg]'";
elseif($action == 'read')
	$syntaxsql = "select * from tbl_pendaftaran";
	
//eksekusi syntaxsql 
$conn = new mysqli("localhost","root","","db_pendaftaran_formulir"); //dbhost, dbuser, dbpass, dbname
if ($conn->connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}else{
  echo "Database connected. ";
}
//create, update, delete query($syntaxsql) -> true false
if ($conn->query($syntaxsql) === TRUE) {
	echo "Query $action with syntax $syntaxsql suceeded !";
}
elseif ($conn->query($syntaxsql) === FALSE){
	echo "Error: $syntaxsql" .$conn->error;
}
//khusus read query($syntaxsql) -> semua associated array
else{
	$result = $conn->query($syntaxsql); //bukan true false tapi data array asossiasi
	if($result->num_rows > 0){
		echo "<table id='tresult' class='table table-striped table-bordered'>";
		echo "<thead><th>nama_depan</th><th>nama_belakang</th><th>nama_panggilan</th><th>jenis_kelamin</th><th>tempat_lahir</th><th>tanggal_lahir</th><th>email</th> 
	<th>alamat</th><th>jurusan</th><th>prodi</th><th>username_ig</th><th>alasan_mengikuti_seminar</th> where username_ig = '$hasil[usernameIg]'</thead>";
		echo "<tbody>";
		while($row = $result->fetch_assoc()) {
			echo "<tr><td>".$row['nama_depan']."</td><td>". $row['nama_belakang']."</td><td>". $row['nama_panggilan']."</td><td>". $row['jenis_kelamin']."</td><td>". $row['tempat_lahir']."</td>
			<td>". $row['tanggal_lahir']."</td><td>". $row['email']."</td><td>". $row['alamat']."</td><td>". $row['jurusan']."</td><td>". $row['prodi']."</td>
			<td>". $row['username_ig']."</td><td>". $row['alasan_mengikuti_seminar']."</td></tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}
}
$conn->close();

?>