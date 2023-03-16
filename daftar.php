<?php session_start(); ?>
<?php
    include('conn/koneksi.php');

    if(isset($_POST["register"])){
        $nik = $_POST["nik"];
        $email = $_POST["email"];
        $nama = $_POST["name"];
        $username = $_POST["username"];
        $telp = $_POST["telp"];
        $password = $_POST["password"];
        $cpassword = $_POST["cpassword"];

        $check_query = mysqli_query($koneksi, "SELECT * FROM masyarakat where email ='$email' OR username ='$username' OR nik ='$nik'");
        $rowCount = mysqli_num_rows($check_query);

        if(!empty($nik) && !empty($email) && !empty($nama) && !empty($username) && !empty($password) && !empty($cpassword) && !empty($telp)){
            if($password !== $cpassword){
                echo "<script>alert('Password Tidak Sama!')</script>";
            }
            elseif(strlen($telp) < 10 || strlen($telp) > 13){
                echo "<script>alert('Nomor telepon tidak boleh lebih dari 13 atau kurang dari 10!')</script>";
            }
            elseif(strlen($nik) !== 16){
                echo "<script>alert('Nomor NIK harus 16 angka!')</script>";
            }
            elseif($rowCount > 0){
                    echo "<script>alert('Akun dengan email, username, atau NIK tersebut sudah ada')</script>";
                }else{
                $password_hash = md5($_POST['password']);

                $result = mysqli_query($koneksi, "INSERT INTO masyarakat (nik, email, nama, username, password, telp, verif) VALUES ('$nik', '$email', '$nama', '$username', '$password_hash', '$telp',  0)");
    
                if($result){
                    $otp = rand(100000,999999);
                    $_SESSION['otp'] = $otp;
                    $_SESSION['mail'] = $email;
                    require "Mail/phpmailer/PHPMailerAutoload.php";
                    $mail = new PHPMailer;
    
                    $mail->isSMTP();
                    $mail->Host='smtp.gmail.com';
                    $mail->Port=587;
                    $mail->SMTPAuth=true;
                    $mail->SMTPSecure='tls';
    
                    $mail->Username='sekolahkarijeneng@gmail.com';
                    $mail->Password='cphnjcbygasymxiy';
    
                    $mail->setFrom('pengaduanmalkot@gmail.com', 'noreply@malangkota.go.id');
                    $mail->addAddress($_POST["email"]);
    
                    $mail->isHTML(true);
                    $mail->Subject="Kode Verifikasi Akun Pelaporan Anda";
                    $mail->Body="<p>Kepada $nama, </p>
                    <h3>Kode verifikasi akun anda adalah $otp <br></h3>
                    <br><br>
                    <p>Hormat kami</p>
                    <b>Pemerintah Kota Malang</b>";
    
                    if(!$mail->send()){
                        echo "<script>alert('Alamat Email Tidak Valid!')</script>";
                    }else{
                        ?>
                        <script>
                            window.location.replace('verifikasi_daftar.php');
                        </script>
                        <?php
                    }
                }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Halaman Daftar</title>
    <link rel="stylesheet" href="css/daftar.css">
    <link rel="stylesheet" href="css/all.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <link rel="icon" href="../image/register.png">
</head>

<body style="background: #0b111e">
    <div class="box" style="height: 800px">
        <div class="form">
            <form action="#" method="POST" onsubmit="return verifyPassword()">
                <h2>Daftar Masyarakat</h2>
                    <div class="inputBox">
                        <input type="number" id="nik"  name="nik" required="required" minlength="16" maxlength="16" title="Masukkan 16 nomor NIK anda" autocomplete="off">
                        <span>NIK</span>
                        <i></i>
                    </div>
                    <div class="inputBox">
                        <input type="name" id="name"  name="name" required="required" maxlength="32" title="Masukkan maksimal 32 karakter" autocomplete="off">
                        <span>Nama Lengkap</span>
                        <i></i>
                    </div>
                    <div class="inputBox">
                        <input type="name" id="username"  name="username" required="required" maxlength="32" title="Masukkan maksimal 32 karakter" autocomplete="off">
                        <span>Nama Pengguna</span>
                        <i></i>
                    </div>
                    <div class="inputBox">
                        <input type="text" id="email_address" name="email" required="required" title="Masukkan alamat email yang valid" autocomplete="off">
                        <span>Alamat Email</span>
                        <i></i>
                    </div>
                    <div class="inputBox">
                        <input type="password" id="password"  name="password" required="required"  title="Masukkan minimal 8 karakter" autocomplete="off">
                        <span>Kata Sandi</span>
                        <i></i>
                    </div>
                    <div class="inputBox">
                        <input type="password" id="cpassword"  name="cpassword" required="required" autocomplete="off">
                        <span>Konfirmasi Kata Sandi</span>
                        <i></i>
                    </div>
                    <div class="inputBox">
                        <input type="number" id="telp"  name="telp" required="required" minlength="10" maxlength="13" title="Masukkan minimal 10 nomor" autocomplete="off">
                        <span>Nomor Telpon</span>
                        <i></i>
                    </div>
                    <br>
                        <input type="submit" value="Daftar" name="register">
                        <br>
                        <br>
                    <div class="links">
                        <p>Sudah mempunyai akun? </p>
                        <br>  
                        <a align="center" href="cek.php">Masuk disini</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

<script>
    function validateEmail($email) {
        $re = '/\S+@\S+\.\S+/';
        return preg_match($re,$email);
    }
    function validateNIK($nik) {
        $re = '/^\d{16}$/';
        return preg_match($re, $nik);
    }
    funcion validatePhoneNumber($telp) {
        $re = '/^\d{10,13}$/';
        return preg_match($re, $telp);
    }
</script>