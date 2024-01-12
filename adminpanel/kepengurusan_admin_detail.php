<?php
require "session.php";
require "../koneksi.php";

// Ambil ID anggota dari parameter URL
$id = $_GET['p'];

// Ambil data anggota berdasarkan ID
$queryAnggota = mysqli_query($con, "SELECT * FROM anggota_kepengurusan WHERE id='$id'");
$dataAnggota = mysqli_fetch_array($queryAnggota);

if (!$dataAnggota) {
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
        // Proses edit anggota
        $nama = htmlspecialchars($_POST['nama']);
        $kode_karyawan = htmlspecialchars($_POST['kode_karyawan']);
        $tempat_lahir = htmlspecialchars($_POST['tempat_lahir']);
        $tanggal_lahir = htmlspecialchars($_POST['tanggal_lahir']);
        $alamat = htmlspecialchars($_POST['alamat']);
        $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
        $pekerjaan = htmlspecialchars($_POST['pekerjaan']);
        $pendidikan = htmlspecialchars($_POST['pendidikan']);

        if ($_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $foto_name = $_FILES['foto']['name'];
            $foto_tmp = $_FILES['foto']['tmp_name'];
            $foto_path = "../img/" . $foto_name;

            move_uploaded_file($foto_tmp, $foto_path);

            // Simpan nama file foto ke database jika diperlukan
            $queryUpdateFoto = mysqli_query($con, "UPDATE anggota_kepengurusan SET foto='$foto_name' WHERE id='$id'");
            if (!$queryUpdateFoto) {
                echo '<div class="alert alert-danger mt-3" role="alert">Gagal mengupdate foto. Error: ' . mysqli_error($con) . '</div>';
            }
        }

        // Lakukan update data di database
        $queryUpdate = mysqli_query($con, "UPDATE anggota_kepengurusan SET
            nama='$nama', kode_karyawan='$kode_karyawan', tempat_lahir='$tempat_lahir', tanggal_lahir='$tanggal_lahir', 
            alamat='$alamat', jenis_kelamin='$jenis_kelamin', pekerjaan='$pekerjaan', 
            pendidikan='$pendidikan' WHERE id='$id'");

        if ($queryUpdate) {
            echo '<div class="alert alert-primary mt-3" role="alert">Anggota kepengurusan berhasil diubah.</div>';
            echo '<meta http-equiv="refresh" content="1; url=kepengurusan_admin.php" />';
        } else {
            echo '<div class="alert alert-danger mt-3" role="alert">Gagal mengubah anggota kepengurusan. Error: ' . mysqli_error($con) . '</div>';
        }
    } elseif (isset($_POST['deleteBtn'])) {
        // Proses delete anggota
        $queryDelete = mysqli_query($con, "DELETE FROM anggota_kepengurusan WHERE id='$id'");

        if ($queryDelete) {
            echo '<div class="alert alert-success mt-3" role="alert">Anggota kepengurusan berhasil dihapus.</div>';
            echo '<meta http-equiv="refresh" content="1; url=kepengurusan_admin.php" />';
            exit(); // Jangan lupa untuk keluar dari skrip setelah redirect
        } else {
            echo '<div class="alert alert-danger mt-3" role="alert">Gagal menghapus anggota kepengurusan. Error: ' . mysqli_error($con) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Detail Anggota Kepengurusan</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body>

<?php require "navbar.php"; ?>

<div class="container mt-5">
    <h2>Detail Anggota Kepengurusan</h2>

    <div class="col-12 col-md-6">
        <form action="kepengurusan_admin_detail.php?p=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <div>
                <label for="nama">Nama:</label>
                <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $dataAnggota['nama']; ?>">
            </div>
            <div>
                <label for="kode_karyawan">Kode Karyawan:</label>
                <input type="text" name="kode_karyawan" id="kode_karyawan" class="form-control" value="<?php echo $dataAnggota['kode_karyawan']; ?>">
            </div>
            <div>
                <label for="tempat_lahir">Tempat Lahir:</label>
                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="<?php echo $dataAnggota['tempat_lahir']; ?>">
            </div>
            <div>
                <label for="tanggal_lahir">Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="<?php echo $dataAnggota['tanggal_lahir']; ?>">
            </div>
            <div>
                <label for="alamat">Alamat:</label>
                <textarea name="alamat" id="alamat" class="form-control" rows="4"><?php echo $dataAnggota['alamat']; ?></textarea>
            </div>
            <div>
                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                    <option value="Laki-laki" <?php echo ($dataAnggota['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo ($dataAnggota['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div>
                <label for="pekerjaan">Pekerjaan:</label>
                <input type="text" name="pekerjaan" id="pekerjaan" class="form-control" value="<?php echo $dataAnggota['pekerjaan']; ?>">
            </div>
            <div>
                <label for="pendidikan">Pendidikan:</label>
                <input type="text" name="pendidikan" id="pendidikan" class="form-control" value="<?php echo $dataAnggota['pendidikan']; ?>">
            </div>
            <div>
                <label for="foto">Foto:</label>
                <img src="../img/<?php echo $dataAnggota['foto']; ?>" alt="Foto Anggota" style="max-width: 100px;" class="img-fluid">
            </div>
            <div>
                <label for="foto">Ganti Foto:</label>
                <input type="file" name="foto" id="foto" class="form-control">
            </div>

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
