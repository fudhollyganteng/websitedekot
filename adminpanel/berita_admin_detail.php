<?php
require "session.php";
require "../koneksi.php";

// Ambil ID berita dari parameter URL
$id = $_GET['id'];

// Ambil data berita berdasarkan ID
$queryBerita = mysqli_query($con, "SELECT * FROM berita WHERE id='$id'");
$dataBerita = mysqli_fetch_assoc($queryBerita);

if (!$dataBerita) {
    echo "Data tidak ditemukan.";
    exit();
}

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

// Proses edit atau delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['editBtn'])) {
        // Proses edit berita
        $judul = htmlspecialchars($_POST['judul']);
        $pengarang = htmlspecialchars($_POST['pengarang']);
        $tanggal_terbit = htmlspecialchars($_POST['tanggal_terbit']);
        $deskripsi = htmlspecialchars($_POST['deskripsi']);

        // Array untuk menyimpan nama file foto lama
        $fotoLama = [];

        // Loop untuk memproses foto-foto lama
        for ($i = 1; $i <= 5; $i++) {
            $namaFotoLama = $_POST["foto_lama_$i"];
            if (!empty($namaFotoLama)) {
                $fotoLama[] = $namaFotoLama;
            }
        }

        // Proses upload foto baru dan simpan nama file ke array
        for ($i = 1; $i <= 5; $i++) {
            if ($_FILES["foto_$i"]['error'] == UPLOAD_ERR_OK) {
                $foto_name = $_FILES["foto_$i"]['name'];
                $foto_tmp = $_FILES["foto_$i"]['tmp_name'];
                $foto_path = "../berita/" . $foto_name;

                move_uploaded_file($foto_tmp, $foto_path);
                $fotoLama[] = $foto_name;
            }
        }

        // Simpan nama file foto ke database
        $fotoListJSON = json_encode($fotoLama);

        $queryUpdateFoto = mysqli_query($con, "UPDATE berita SET foto_list='$fotoListJSON' WHERE id='$id'");
        if (!$queryUpdateFoto) {
            echo '<div class="alert alert-danger mt-3" role="alert">Gagal mengupdate foto. Error: ' . mysqli_error($con) . '</div>';
        }

        // Update data di database
        $queryUpdate = mysqli_query($con, "UPDATE berita SET
            judul='$judul', pengarang='$pengarang', tanggal_terbit='$tanggal_terbit', 
            deskripsi='$deskripsi' WHERE id='$id'");

        if ($queryUpdate) {
            echo '<div class="alert alert-primary mt-3" role="alert">Berita berhasil diubah.</div>';
            echo '<meta http-equiv="refresh" content="1; url=berita_admin.php" />';
        } else {
            echo '<div class="alert alert-danger mt-3" role="alert">Gagal mengubah berita. Error: ' . mysqli_error($con) . '</div>';
        }
    } elseif (isset($_POST['deleteBtn'])) {
        // Proses delete berita
        $queryDelete = mysqli_query($con, "DELETE FROM berita WHERE id='$id'");
    
        if ($queryDelete) {
            echo '<div class="alert alert-success mt-3" role="alert">Berita berhasil dihapus.</div>';
            echo '<meta http-equiv="refresh" content="1; url=berita_admin.php" />';
            exit(); // Jangan lupa untuk keluar dari skrip setelah redirect
        } else {
            echo '<div class="alert alert-danger mt-3" role="alert">Gagal menghapus berita. Error: ' . mysqli_error($con) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Detail Berita</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body>

<?php require "navbar.php"; ?>

<div class="container mt-5">
    <h2>Detail Berita</h2>

    <div class="col-12 col-md-6">
        <form action="berita_admin_detail.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <div>
                <label for="judul">Judul:</label>
                <input type="text" name="judul" id="judul" class="form-control" value="<?php echo $dataBerita['judul']; ?>">
            </div>
            <div>
                <label for="pengarang">Pengarang:</label>
                <input type="text" name="pengarang" id="pengarang" class="form-control" value="<?php echo $dataBerita['pengarang']; ?>">
            </div>
            <div>
                <label for="tanggal_terbit">Tanggal Terbit:</label>
                <input type="date" name="tanggal_terbit" id="tanggal_terbit" class="form-control" value="<?php echo $dataBerita['tanggal_terbit']; ?>">
            </div>
            <div>
                <label for="deskripsi">Deskripsi:</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4"><?php echo $dataBerita['deskripsi']; ?></textarea>
            </div>

            <?php
            // Ambil daftar foto lama dari database dan ubah menjadi array
            $fotoList = json_decode($dataBerita['foto_list']);
            
            // Tampilkan input file untuk setiap foto
            for ($i = 1; $i <= 5; $i++) {
                $fotoLama = isset($fotoList[$i - 1]) ? $fotoList[$i - 1] : '';
                ?>
                <div>
                    <label for="foto_<?php echo $i; ?>">Foto <?php echo $i; ?>:</label>
                    <input type="file" name="foto_<?php echo $i; ?>" id="foto_<?php echo $i; ?>" class="form-control">
                    <input type="hidden" name="foto_lama_<?php echo $i; ?>" value="<?php echo $fotoLama; ?>">
                    <?php
                    if (!empty($fotoLama)) {
                        echo '<img src="../berita/' . $fotoLama . '" alt="Foto Lama" style="max-width: 100px;" class="img-fluid">';
                    }
                    ?>
                </div>
                <?php
            }
            ?>

            <div class="mt-3 d-flex justify-content-between">
                <button type="submit" class="btn btn-primary" name="editBtn">Update</button>
                <button type="submit" class="btn btn-danger" name="deleteBtn">Delete</button>
            </div>
        </form>
    </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
