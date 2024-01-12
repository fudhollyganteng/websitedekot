<?php
    include("koneksi.php");
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\PHPMailer;

    require_once "libraryEmailer/PHPMailer.php";
    require_once "libraryEmailer/Exception.php";
    require_once "libraryEmailer/OAuth.php";
    require_once "libraryEmailer/POP3.php";
    require_once "libraryEmailer/SMTP.php";
    
    if (isset($_POST['send']) && $_POST['send'] == "form1") {
        $email = new PHPMailer();
        
        //Enable SMTP debugging.
        $email->isSMTP();
        //Set PHPMailer to use SMTP.
        $email->Host = "ssl://smtp.gmail.com"; //host mail server
        //Set this to true if SMTP host requires authentication to send email
        $email->SMTPAuth = true;
        //Provide username and Pass
        $email->Username = "muhammadfudhollidholli@gmail.com";
        $email->Password = "otac swsq duzx uvjq";
    
        $email->SMTPSecure = "ssl";
        $email->Port = 465;
    
        // Pengirim
        $email->From = $email->Username; // email pengirim
    
        //email
        $email->addAddress('fudhollydholli@gmail.com');
        
        $email->isHTML(true);
        
        $email->Subject = $_POST['subject'];
        $email->Body = $_POST['deskripsi_pengaduan'];
        //nama
        $email->FromName = ($_POST['nama_pelapor']);
        
        if (!$email->send()) {
            echo"Mailer Error" . $email->ErrorInfo;
        } else {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Ambil nilai dari form
                $nama_pelapor = mysqli_real_escape_string($con, $_POST["nama_pelapor"]);
                $email_pelapor = mysqli_real_escape_string($con, $_POST["email_pelapor"]);
                $telepon_pelapor = mysqli_real_escape_string($con, $_POST["telepon_pelapor"]);
                $tanggal_pengaduan = mysqli_real_escape_string($con, $_POST["tanggal_pengaduan"]);
                $deskripsi_pengaduan = mysqli_real_escape_string($con, $_POST["deskripsi_pengaduan"]);
            
                // Masukkan data ke dalam tabel pengaduan
                $sql = "INSERT INTO pengaduan (nama_pelapor, email_pelapor, telepon_pelapor, tanggal_pengaduan, deskripsi_pengaduan) 
                        VALUES ('$nama_pelapor', '$email_pelapor', '$telepon_pelapor', '$tanggal_pengaduan', '$deskripsi_pengaduan')";
            
                if (mysqli_query($con, $sql)) {
                    // Notifikasi pengaduan berhasil
                    echo "<script>alert('Pengaduan berhasil dikirim.');</script>";
                    // Redirect ke index.php setelah 2 detik
                    echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 2000);</script>";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($con);
                }
            }
        }
    }

// Tutup koneksi
mysqli_close($con);
?>

<!DOCTYPE html>
<html>
    
<head>
    <title>Form Pengaduan</title>
    <link rel="stylesheet" href="CSSpengaduan/styleCSS.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <div class="container">
      <input type="checkbox" id="flip" />
      <div class="cover">
        <div class="front">
          <img src="img/gedung.png" alt="" />
          <div class="text">
            <span class="text-1"
              >This is a complaint form<br />
              Dewan Kota Jakarta Selatan</span
            >
            <span class="text-2">Come on, send your complaint</span>
          </div>
        </div>
        <div class="back">
          <!--<img class="backImg" src="images/backImg.jpg" alt="">-->
          <div class="text">
            <span class="text-1"
              >This is a complaint form<br />
              Dewan Kota Jakarta Selatan</span
            >
            <span class="text-2">Let's get started</span>
          </div>
        </div>
      </div>
      <div class="forms">
          <a href="index.php"><i class="fa-solid fa-arrow-left"></i></a>
        <div class="form-content">
          <div class="login-form">
            <div class="title">Pengaduan</div>
            <form action="" name="form1" method="post">
              <div class="input-boxes">
				<!-- input nama -->
                <div class="input-box">
                  <i class="fas fa-user"></i>
				  <input type="text" id="nama_pelapor" name="nama_pelapor" placeholder="Nama" required>
                </div>
				<!-- input email -->
                <div class="input-box">
                  <i class="fa-brands fa-google"></i>
				  <input type="email" id="email_pelapor" name="email_pelapor" placeholder="Email" required>
                </div>
				<!-- input telepon pelapor -->
                <div class="input-box">
                  <i class="fas fa-phone"></i>
				  <input type="tel" id="telepon_pelapor" name="telepon_pelapor" placeholder="No.Telp" required>
                </div>
				<!-- input Tanggal Pengaduan -->
                <div class="input-box">
				  <i class="fas fa-calendar"></i>
				  <input type="date" id="tanggal_pengaduan" name="tanggal_pengaduan">
                </div>
				<!-- input subject -->
                <div class="input-box">
                  <i class="fa-solid fa-message"></i>
				  <input type="text" id="subject" name="subject" placeholder="Subject" required>
                </div>
				<!-- input descripsi -->
                <div class="input-box">
                  <i class="fa-solid fa-message"></i>
				  <input type="text" id="deskripsi_pengaduan" name="deskripsi_pengaduan" placeholder="Deskripsi Pengaduan Masyarakat" required>
                </div>
                <div class="button input-box">
					<input id="btn" type="submit" value="Send"><input id="btn" type="hidden" name="send" value="form1"></input>
                </div>
              </div>
            </form>
          </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</body>
</html>
