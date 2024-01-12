<?php
include_once "header.php";
include_once "koneksi.php";

// Ambil ID dari parameter URL
$anggota_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Query untuk mengambil data anggota berdasarkan ID
$query = "SELECT * FROM anggota_kepengurusan WHERE id = $anggota_id";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    ?>

    <!-- Tampilkan data anggota di sini sesuai kebutuhan -->
    <section class="service-area pt-100 pb-150" id="service">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1><a class="biodata">B I O D A T A</a></h1>
                        <table border="1" cellspacing="5" align="center" width="700">
                            <tr align="center" bgcolor="1fe5d5">
                                <td width="200">DATA DIRI</td>
                                <td width="300">KETERANGAN</td>
                                <td width="200">FOTO</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td><?php echo $row['nama']; ?></td>
                                <td rowspan="7"><img src="img/<?php echo $row['foto']; ?>" width="200"></td>
                            </tr>
                            <tr>
                                <td>Kode Karyawan</td>
                                <td><?php echo $row['kode_karyawan']; ?></td>
                            </tr>
                            <tr>
                                <td>Tempat/Tanggal Lahir</td>
                                <td><?php echo $row['tempat_lahir'] . ', ' . $row['tanggal_lahir']; ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td><?php echo $row['alamat']; ?></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td><?php echo $row['jenis_kelamin']; ?></td>
                            </tr>
                            <tr>
                                <td>Pekerjaan</td>
                                <td><?php echo $row['pekerjaan']; ?></td>
                            </tr>
                            <tr>
                                <td>Pendidikan</td>
                                <td>
                                    <ul>
                                        <li><?php echo $row['pendidikan']; ?></li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
} else {
    echo "Data anggota tidak ditemukan.";
}

mysqli_close($con);
include_once "footer.php";
?>
