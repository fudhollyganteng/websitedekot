<?php
require "session.php";
require "../koneksi.php";

// Fungsi untuk generate string acak
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Proses tambah data anggota
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = isset($_POST['nama']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['nama'])) : "";
    $kode_karyawan = isset($_POST['kode_karyawan']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['kode_karyawan'])) : "";
    $tempat_lahir = isset($_POST['tempat_lahir']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['tempat_lahir'])) : "";
    $tanggal_lahir = isset($_POST['tanggal_lahir']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['tanggal_lahir'])) : "";
    $alamat = isset($_POST['alamat']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['alamat'])) : "";
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['jenis_kelamin'])) : "";
    $pekerjaan = isset($_POST['pekerjaan']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['pekerjaan'])) : "";
    $pendidikan = isset($_POST['pendidikan']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['pendidikan'])) : "";

    // Proses upload foto
    $target_dir = "../img/";
    $random_string = generateRandomString(8); // Ubah panjang sesuai kebutuhan
    $imageFileType = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . $random_string . '.' . $imageFileType;

    // Periksa apakah file sudah ada
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
    } else {
        $uploadOk = 1;

        // Periksa apakah file gambar
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Periksa ukuran file
        if ($_FILES["foto"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Izinkan beberapa tipe file
        $allowed_file_types = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_file_types)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Periksa jika $uploadOk disetel ke 0 oleh suatu kesalahan
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                echo '<div class="alert alert-primary mt-3" role="alert">Anggota kepengurusan berhasil ditambahkan.</div>';
            } else {
                echo '<div class="alert alert-danger mt-3" role="alert">Gagal menyimpan anggota kepengurusan. Error: ' . mysqli_error($con) . '</div>';
            }
        }
    }
    
    // Simpan data anggota jika upload foto berhasil
    if ($uploadOk == 1) {
        $querySimpan = mysqli_query($con, "INSERT INTO anggota_kepengurusan (nama, kode_karyawan, tempat_lahir, tanggal_lahir, alamat, jenis_kelamin, pekerjaan, pendidikan, foto) 
        VALUES ('$nama', '$kode_karyawan', '$tempat_lahir', '$tanggal_lahir', '$alamat', '$jenis_kelamin', '$pekerjaan', '$pendidikan', '$target_file')");

        if (!$querySimpan) {
            echo '<div class="alert alert-danger mt-3" role="alert">Gagal menyimpan anggota kepengurusan. Error: ' . mysqli_error($con) . '</div>';
        } else {
            // Redirect ke halaman yang sama setelah penyimpanan berhasil
            header("Location: kepengurusan_admin.php");
            exit();
        }
    }
}

// Ambil data anggota kepengurusan
$queryAnggota = mysqli_query($con, "SELECT * FROM anggota_kepengurusan");
$jumlahAnggota = mysqli_num_rows($queryAnggota);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Kepengurusan</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.css">
    
    <script>
        // Fungsi untuk mengatur pengalihan halaman setelah 1 detik
        function redirectToSamePage() {
            setTimeout(function () {
                location.reload();
            }, 2000); 
        }
    </script>
</head>
<body>

<?php require "navbar.php"; ?>

<div class="container mt-3">
    <h2 class="mt-3">Form Tambah Anggota Kepengurusan</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div>
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" placeholder="Input nama anggota" class="form-control">
        </div>
        <div>
            <label for="kode_karyawan">Kode Karyawan:</label>
            <input type="text" id="kode_karyawan" name="kode_karyawan" placeholder="Input kode karyawan" class="form-control">
        </div>
        <div>
            <label for="tempat_lahir">Tempat Lahir:</label>
            <input type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Input tempat lahir" class="form-control">
        </div>
        <div>
            <label for="tanggal_lahir">Tanggal Lahir:</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control">
        </div>
        <div>
            <label for="alamat">Alamat:</label>
            <textarea id="alamat" name="alamat" placeholder="Input alamat" class="form-control" rows="4"></textarea>
        </div>
        <div>
            <label for="jenis_kelamin">Jenis Kelamin:</label>
            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div>
            <label for="pekerjaan">Pekerjaan:</label>
            <input type="text" id="pekerjaan" name="pekerjaan" placeholder="Input pekerjaan" class="form-control">
        </div>
        <div>
            <label for="pendidikan">Pendidikan:</label>
            <input type="text" id="pendidikan" name="pendidikan" placeholder="Input pendidikan" class="form-control">
        </div>
        <div>
            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
        </div>
        <button class="btn btn-primary mt-3" type="submit" name="simpan" onclick="redirectToSamePage()">Tambah Anggota</button>
    </form>

    <h2 class="mt-5">Data Anggota Kepengurusan</h2>
    <div class="table-responsive mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kode Karyawan</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>Jenis Kelamin</th>
                    <th>Pekerjaan</th>
                    <th>Pendidikan</th>
                    <th>Foto</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($jumlahAnggota > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($queryAnggota)) {
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['kode_karyawan']; ?></td>
                            <td><?php echo $row['tempat_lahir']; ?></td>
                            <td><?php echo $row['tanggal_lahir']; ?></td>
                            <td><?php echo $row['alamat']; ?></td>
                            <td><?php echo $row['jenis_kelamin']; ?></td>
                            <td><?php echo $row['pekerjaan']; ?></td>
                            <td><?php echo $row['pendidikan']; ?></td>
                            <td>
                                <img src="<?php echo $row['foto'] . '?t=' . time(); ?>" alt="Foto" style="max-width: 100px;">
                            </td>
                            <td>
                                <a href="kepengurusan_admin_detail.php?p=<?php echo $row['id']; ?>" class="btn btn-info">
                                    <i class="fas fa-search"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php
                        $no++;
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="10" class="text-center">Belum ada data anggota kepengurusan.</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
