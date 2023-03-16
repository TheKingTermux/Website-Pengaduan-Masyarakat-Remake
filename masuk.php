<?php 

    include('conn/koneksi.php');

	if(isset($_POST['login'])){
		$username = mysqli_real_escape_string($koneksi,$_POST['email']);
		$password = mysqli_real_escape_string($koneksi,md5($_POST['password']));
	
		$sql = mysqli_query($koneksi,"SELECT * FROM masyarakat WHERE (username='$username' OR email='$username') AND password='$password'");
		$cek = mysqli_num_rows($sql);
		$data = mysqli_fetch_assoc($sql);
	
		$sql2 = mysqli_query($koneksi,"SELECT * FROM petugas WHERE (username='$username' OR email='$username') AND password='$password' ");
		$cek2 = mysqli_num_rows($sql2);
		$data2 = mysqli_fetch_assoc($sql2);

        if($cek>0){
            if($data['verif'] == 0){
                echo "<script>alert('Silahkan verifikasi akun anda terlebih dahulu!')</script>";
                echo "<script>location='telat.php'</script>";
            }
            elseif($data['verif'] == 1){
                session_start();
                $_SESSION['username']=$username;
                $_SESSION['data']=$data;
                $_SESSION['level']='masyarakat';
                header('location:masyarakat/');
            }
        }
        if($cek2>0){
            if($data2['level']=="admin"){
                session_start();
                $_SESSION['username']=$username;
                $_SESSION['data']=$data2;
                header('location:admin/');
            }
            elseif($data2['level']=="petugas"){
                session_start();
                $_SESSION['username']=$username;
                $_SESSION['data']=$data2;
                header('location:petugas/');
            }
        }
		else{
			echo "<script>alert('Gagal Login Sob')</script>";
		}
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Halaman Login</title>
    <link rel="stylesheet" href="css/masuk.css">
    <link rel="stylesheet" href="css/all.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <link rel="icon" href="../image/login.png">
</head>

<body>
    <div class="box">
        <div class="form">
            <form action="#" method="POST">
                <h2>Silahkan Masuk</h2>
                    <div class="inputBox">
                        <input type="text" id="email_address" name="email" required="required" autocomplete="off">
                        <span>Alamat Email / Nama Pengguna</span>
                        <i></i>
                    </div>
                    <div class="inputBox">
                        <input type="password" id="password"  name="password" required="required" autocomplete="off">
                        <span>Kata Sandi</span>
                        <i></i>
                    </div>
                    <br>
                    <div class="links">
                        <a href="lupa_password.php">Lupa password ya?</a>
                    </div>
                    <input type="submit" value="Masuk" name="login">
                    <br>
                    <br>
                    <div class="cr">
                        <p align="center" color="#28292d">Tidak mempunyai akun? </p>
                        <br>
                        <a align="center" href="daftar.php">Daftar disini</a>
                    </div>
            </form>
        </div>
    </div>
</body>
</html>