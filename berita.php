<?php include_once "header.php"; ?>

<!-- start blog Area -->
<section class="blog-area section-gap" id="blog">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="menu-content pb-70 col-lg-8">
                <div class="title text-center">
                    <h1 class="mb-10">Seputar Dewan Kota</h1>
                    <p>Kumpulan berita - berita yang terjadi di sekitar wilayah Dewan Kota</p>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            include_once "koneksi.php";

            $queryLatestNews = mysqli_query($con, "SELECT * FROM berita ORDER BY tanggal_terbit DESC LIMIT 4");

            while ($row = mysqli_fetch_assoc($queryLatestNews)) {
                // Periksa apakah fotoList ada dan memiliki elemen pertama
                $foto = isset($row['fotoList'][0]) ? $row['fotoList'][0] : 'default-image.jpg';
                ?>
                <div class="col-lg-3 col-md-6 single-blog">
                    <img class="img-fluid" src="berita/<?php echo $foto; ?>?t=<?php echo time(); ?>" alt="">
                    <p class="date"><?php echo date('j M Y', strtotime($row['tanggal_terbit'])); ?></p>
                    <h4><a href="berita-detail.php?id=<?php echo $row['id']; ?>"><?php echo $row['judul']; ?></a></h4>
                    <p>
                        <?php echo substr($row['deskripsi'], 0, 150) . '...'; ?>
                    </p>
                    <div class="meta-bottom d-flex justify-content-between">
                        <p><span class="lnr lnr-heart"></span> 0 Likes</p>
                        <p><span class="lnr lnr-bubble"></span> 0 Comments</p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>
<!-- end blog Area -->

<?php include_once "footer.php"; ?>
