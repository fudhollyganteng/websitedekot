<?php include_once "header.php"; ?>

<!-- Start Generic Area -->
<section class="about-generic-area section-gap">
    <div class="container border-top-generic">

        <?php
        include_once "koneksi.php";

        $beritaId = isset($_GET['id']) ? mysqli_real_escape_string($con, $_GET['id']) : '';

        $queryBeritaDetail = mysqli_query($con, "SELECT judul, pengarang, tanggal_terbit, deskripsi, foto_list FROM berita WHERE id = $beritaId");

        if ($row = mysqli_fetch_assoc($queryBeritaDetail)) {
            echo "<div class='row'>";
            echo "<div class='col-md-12'>";
            echo "<div class='img-text'>";
            echo "<h3>{$row['judul']}</h3><br>";
            echo "<p>By " . ($row['pengarang'] ? $row['pengarang'] : 'Penulis Tidak Tersedia') . " | " . date('d M Y', strtotime($row['tanggal_terbit'])) . "</p>";

            if (!empty($row['foto_list'])) {
                echo "<div class='row gallery-item'>";
                foreach (json_decode($row['foto_list']) as $foto) {
                    echo "<div class='col-md-4 mb-3'>";
                    echo "<a href='#' onclick='openModal(\"berita/{$foto}\")' data-toggle='tooltip' data-placement='top' title='Klik untuk memperbesar'>";
                    echo "<img src='berita/{$foto}' alt='Foto' class='img-fluid'>";
                    echo "</a>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>Tidak ada foto untuk berita ini.</p>";
            }

            echo "<p align='justify'>{$row['deskripsi']}</p>";

            // Tambahkan tombol "Cetak Informasi" dengan kelas "print-hidden"
            echo "<button class='print-hidden' onclick='cetakInformasi()'>Cetak Informasi</button>";

            echo "</div>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<p class='text-center'>Berita tidak ditemukan.</p>";
        }

        mysqli_close($con);
        ?>

        <div id="myModal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content" id="modalImg">
        </div>

        <style>
            /* Gaya untuk modal */
            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                padding-top: 125px; /* Sesuaikan dengan tinggi navbar */
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.9);
            }

            .modal-content {
                margin: auto;
                display: block;
                width: 750px; /* Atur lebar gambar modal menjadi 750px */
                height: auto; /* Biarkan tinggi otomatis agar gambar tidak terdistorsi */
                max-width: 100%;
                max-height: 100%;
            }

            .close {
                position: absolute;
                top: 15px;
                right: 35px;
                color: #f1f1f1;
                font-size: 40px;
                font-weight: bold;
                transition: 0.3s;
            }

            .close:hover,
            .close:focus {
                color: #bbb;
                text-decoration: none;
                cursor: pointer;
            }

            /* Gaya untuk cetak */
            @media print {
                body * {
                    visibility: hidden;
                }

                .about-generic-area, .about-generic-area * {
                    visibility: visible;
                }

                .about-generic-area {
                    position: absolute;
                    left: 0;
                    top: 0;
                    padding-top: 20px; /* Sesuaikan nilai padding-top sesuai kebutuhan */
                }

                /* Gaya untuk ukuran foto saat mencetak */
                .gallery-item img {
                    width: 200px;
                    height: auto;
                }

                /* Gaya untuk menyembunyikan tombol cetak saat mencetak */
                .print-hidden {
                    display: none;
                }
            }
        </style>

        <script>
            function openModal(imgPath) {
                var modal = document.getElementById('myModal');
                var modalImg = document.getElementById('modalImg');

                modal.style.display = 'block';
                modalImg.src = imgPath;

                window.onclick = function (event) {
                    if (event.target == modal) {
                        closeModal();
                    }
                };
            }

            function closeModal() {
                var modal = document.getElementById('myModal');
                modal.style.display = 'none';

                window.onclick = null;
            }

            function cetakInformasi() {
                window.print();
            }
        </script>

    </div>
</section>
<!-- End Generic Area -->

<?php include_once "footer.php"; ?>
