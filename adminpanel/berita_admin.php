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

// Proses tambah data berita
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = isset($_POST['judul']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['judul'])) : "";
    $pengarang = isset($_POST['pengarang']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['pengarang'])) : "";
    $tanggal_terbit = isset($_POST['tanggal_terbit']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['tanggal_terbit'])) : "";
    $deskripsi = isset($_POST['deskripsi']) ? mysqli_real_escape_string($con, htmlspecialchars($_POST['deskripsi'])) : "";

    // Array untuk menyimpan nama file foto
    $fotoList = [];

    // Loop untuk memproses foto-foto
    for ($i = 1; $i <= 5; $i++) {
        if ($_FILES["foto$i"]['error'] == UPLOAD_ERR_OK) {
            $foto_name = $_FILES["foto$i"]['name'];
            $foto_tmp = $_FILES["foto$i"]['tmp_name'];
            $foto_path = "../berita/" . $foto_name;

            move_uploaded_file($foto_tmp, $foto_path);
            $fotoList[] = $foto_name;
        }
    }

    // Simpan nama file foto ke database
    $fotoListJSON = json_encode($fotoList);

    $queryTambahBerita = mysqli_query($con, "INSERT INTO berita (judul, pengarang, tanggal_terbit, deskripsi, foto_list) VALUES ('$judul', '$pengarang', '$tanggal_terbit', '$deskripsi', '$fotoListJSON')");

    if (!$queryTambahBerita) {
        echo '<div class="alert alert-danger mt-3" role="alert">Gagal menambahkan berita. Error: ' . mysqli_error($con) . '</div>';
    } else {
        // Redirect ke halaman yang sama setelah penyimpanan berhasil
        header("Location: berita_admin.php");
        exit();
    }
}

// Ambil data berita
$queryBerita = mysqli_query($con, "SELECT * FROM berita");
$jumlahBerita = mysqli_num_rows($queryBerita);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Berita</title>
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
    <h2 class="mt-3">Form Tambah Berita</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="judul">Judul:</label>
            <input type="text" id="judul" name="judul" placeholder="Input judul berita" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="pengarang">Pengarang:</label>
            <input type="text" id="pengarang" name="pengarang" placeholder="Input pengarang berita" class="form-control">
        </div>
        <div class="form-group">
            <label for="tanggal_terbit">Tanggal Terbit:</label>
            <input type="date" id="tanggal_terbit" name="tanggal_terbit" class="form-control">
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" placeholder="Input deskripsi berita" class="form-control" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="foto1">Foto 1:</label>
            <input type="file" id="foto1" name="foto1" class="form-control" accept="image/*">
        </div>
        <div class="form-group">
            <label for="foto2">Foto 2:</label>
            <input type="file" id="foto2" name="foto2" class="form-control" accept="image/*">
        </div>
        <div class="form-group">
            <label for="foto3">Foto 3:</label>
            <input type="file" id="foto3" name="foto3" class="form-control" accept="image/*">
        </div>
        <div class="form-group">
            <label for="foto4">Foto 4:</label>
            <input type="file" id="foto4" name="foto4" class="form-control" accept="image/*">
        </div>
        <div class="form-group">
            <label for="foto5">Foto 5:</label>
            <input type="file" id="foto5" name="foto5" class="form-control" accept="image/*">
        </div>
        <button class="btn btn-primary mt-3" type="submit" name="simpan" onclick="redirectToSamePage()">Tambah Berita</button>
    </form>

    <h2 class="mt-5">Data Berita</h2>
    <div class="table-responsive mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tanggal Terbit</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($jumlahBerita > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($queryBerita)) {
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $row['judul']; ?></td>
                            <td><?php echo $row['pengarang']; ?></td>
                            <td><?php echo $row['tanggal_terbit']; ?></td>
                            <td>
                                <?php
                                $deskripsiWords = explode(' ', $row['deskripsi']);
                                $deskripsiShort = implode(' ', array_slice($deskripsiWords, 0, 5));
                                echo $deskripsiShort;
                                ?>
                            </td>
                            <td>
                                <?php
                                $fotoList = json_decode($row['foto_list']);
                                foreach ($fotoList as $foto) {
                                    echo '<img src="../berita/' . $foto . '?t=' . time() . '" alt="Foto" style="max-width: 50px; margin-right: 5px;">';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="berita_admin_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-info">
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
                        <td colspan="7" class="text-center">Belum ada data berita.</td>
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
