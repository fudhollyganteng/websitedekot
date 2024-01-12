<?php include_once "header.php"; ?>
<!-- start blog Area -->
<section class="blog-area section-gap" id="blog">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="menu-content pb-70 col-lg-8">
                <div class="title text-center">
                    <h1 class="mb-10">Biodata</h1>
                    <p>Berikut ini adalah Biodata kepengurusan Dewan Kota</p>
                </div>
            </div>
        </div>

        <?php
        include_once "koneksi.php";

        $query = "SELECT * FROM anggota_kepengurusan";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            $counter = 0;
            echo "<div class='row'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='col-lg-3 col-md-6 row' style='justify-content: center;align-content: center;' " . ($counter >= 4 ? '20px' : '0') . ";'>";
                // Menampilkan foto dengan link ke halaman detail
                echo "<a href='kepengurusan-detail.php?id=" . $row['id'] . "'><img class='img-fluid' src='img/" . $row['foto'] . "' alt='bapak walikota'></a>";
                echo "<strong>" . "<h5 class='date mt-3 mb-3'>" . $row['pekerjaan'] . "</h5>" . "</strong>";
                // Menampilkan nama dengan link ke halaman detail
                echo "<h4><a href='kepengurusan-detail.php?id=" . $row['id'] . "'>" . $row['nama'] . "</a></h4>";
                echo "</div>";

                $counter++;
                if ($counter % 4 == 0 && $counter != mysqli_num_rows($result)) {
                    echo "</div><div class='row'>";
                }
            }
            echo "</div>";
        } else {
            echo "Belum ada data anggota kepengurusan.";
        }

        mysqli_close($con);
        ?>

    </div>
</section>
<!-- end blog Area -->
<?php include_once "footer.php"; ?>

