<?php
require "../../src/config/config.php";
include '../../components/sidebar.php';
$testimonials = readAllTestimonial();

// Handling requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $result = createTestimonial($connect, $_POST, $_FILES['image']);
        if ($result) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.success('Testimoni baru berhasil ditambahkan', 'Berhasil');
                setTimeout(function() {
                    window.location.href = 'testimonial_control.php';
                }, 2500);
            });
        </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.error('Terjadi kesalahan menambahkan testimoni', 'Gagal');
            });
        </script>";
        }
    }

    if (isset($_POST['update'])) {
        $result = updateTestimonial($connect, $_POST, $_FILES['image']);
        if ($result) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.success('Testimoni berhasil diperbarui', 'Berhasil');
                setTimeout(function() {
                    window.location.href = 'testimonial_control.php';
                }, 2500);
            });
        </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.error('Terjadi kesalahan memperbarui testimoni', 'Gagal');
            });
        </script>";
        }
    }

    if (isset($_POST['delete'])) {
        $result = deleteTestimonial($connect, $_POST['id']);
        if ($result) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.success('Testimoni berhasil dihapus', 'Berhasil');
                setTimeout(function() {
                    window.location.href = 'testimonial_control.php';
                }, 2500);
            });
        </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.error('Terjadi kesalahan menghapus testimoni', 'Gagal');
            });
        </script>";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luarsekolah | Dashboard</title>
    <link rel="stylesheet" href="../../src/css/dashboard.css">
    <link rel="icon" type="image/x-icon" href="../../assets/icon/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <?php include '../../components/header.php'; ?>
        <!-- End Of Header -->

        <main class="main">
            <!-- Sidebar Menu -->
            <?php renderSidebar('testimoni'); ?>
            <!-- End Of Sidebar Menu -->

            <section class="page-content">
                <article class="board">
                    <p>Daftar Testimoni</p>
                    <!-- Form Tambah Testimoni -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="file" name="image" accept="image/*" required>
                        <input type="text" name="name" placeholder="Nama" required>
                        <input type="text" name="course_name" placeholder="Program" required>
                        <input type="text" name="review" placeholder="Kata-kata" required>
                        <input type="text" name="program_name" placeholder="Program Name" required>
                        <input type="text" name="linkedin" placeholder="LinkedIn URL" required>
                        <section class="create-client">
                            <button type="submit" name="create"><i class="fas fa-plus"></i>Tambah Testimoni</button>
                        </section>
                    </form>

                    <div class="table-container">
                        <div class="table-horizontal-container">
                            <table class="unfixed-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Nama</th>
                                        <th>Nama Kelas</th>
                                        <th>Kata - Kata</th>
                                        <th>Nama Program</th>
                                        <th>LinkedIn</th>
                                        <th>Pengaturan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($testimonials)) : ?>
                                        <tr class="empty-row">
                                            <td colspan="8">Belum ada testimonial yang dibuat</td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($testimonials as $testimonial): ?>
                                            <tr>
                                                <td><?= $testimonial['id']; ?></td>
                                                <td><img src="profile/<?= $testimonial['image']; ?>" alt="Image" width="50"></td>
                                                <td><?= $testimonial['name']; ?></td>
                                                <td><?= $testimonial['course_name']; ?></td>
                                                <td><?= $testimonial['review']; ?></td>
                                                <td><?= $testimonial['program_name']; ?></td>
                                                <td><a href="<?= $testimonial['linkedin']; ?>" target="_blank">LinkedIn</a></td>
                                                <td>
                                                    <section class="control-client">
                                                        <button type="button" onclick="openEditModal(<?= htmlspecialchars(json_encode($testimonial)); ?>)">
                                                            <i class="fas fa-edit"></i>Ubah
                                                        </button>
                                                        <form action="" method="POST" style="display: inline;">
                                                            <input type="hidden" name="id" value="<?= $testimonial['id']; ?>">
                                                            <button type="submit" name="delete" onclick="return confirm('Yakin ingin menghapus?');">
                                                                <i class="fas fa-trash"></i>Hapus
                                                            </button>
                                                        </form>
                                                    </section>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>
            </section>
        </main>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Testimoni</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <div class="image-preview-container">
                    <label>Current Image:</label><br>
                    <img id="current_image" src="" alt="Current Image">
                    <input type="file" name="image" accept="image/*">
                </div>
                <input type="text" name="name" id="edit_name" required>
                <input type="text" name="course_name" id="edit_course_name" required>
                <input type="text" name="review" id="edit_review" required>
                <input type="text" name="program_name" id="edit_program_name" required>
                <input type="text" name="linkedin" id="edit_linkedin" required>
                <button type="submit" name="update">Update</button>
            </form>
        </div>
    </div>
    <style>
        /* Modal Background/Overlay */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(3px);
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: scale(0.9);
            /* Awal kecil */
        }

        .modal.show {
            display: block;
            /* Tampilkan modal */
            opacity: 1;
            transform: scale(1);
            /* Perbesar ke ukuran normal */
        }

        /* Modal Content Box */
        .modal-content {
            background-color: #fefefe;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Close Button */
        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            color: #666;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .close:hover {
            color: #000;
        }

        /* Modal Form Styling */
        .modal-content form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .modal-content h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 1.5rem;
        }

        .modal-content input[type="text"],
        .modal-content input[type="file"] {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .modal-content input[type="text"]:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }

        .modal-content button[type="submit"] {
            background-color: #4a90e2;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        .modal-content button[type="submit"]:hover {
            background-color: #357abd;
        }

        /* Image Preview Container */
        .image-preview-container {
            margin-bottom: 15px;
        }

        .image-preview-container img {
            max-width: 150px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                padding: 20px;
            }

            .modal-content h2 {
                font-size: 1.2rem;
            }
        }
    </style>

    <script>
        // Pastikan DOM sudah dimuat sepenuhnya
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById("editModal");
            const closeBtn = document.querySelector('.close');

            // Fungsi untuk menutup modal
            function closeModal() {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = "none";
                }, 300);
                document.body.classList.remove('modal-open');
            }

            // Event listener untuk tombol close
            if (closeBtn) {
                closeBtn.onclick = closeModal;
            }

            // Event listener untuk klik di luar modal
            window.onclick = function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            };

            // Event listener untuk tombol Escape
            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape" && modal.classList.contains('show')) {
                    closeModal();
                }
            });
        });

        // Fungsi untuk membuka modal dengan animasi
        function openEditModal(testimonial) {
            const modal = document.getElementById("editModal");
            modal.style.display = "block";
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
            document.body.classList.add('modal-open');

            document.getElementById("edit_id").value = testimonial.id;
            document.getElementById("current_image").src = "profile/" + testimonial.image;
            document.getElementById("edit_name").value = testimonial.name;
            document.getElementById("edit_course_name").value = testimonial.course_name;
            document.getElementById("edit_review").value = testimonial.review;
            document.getElementById("edit_program_name").value = testimonial.program_name;
            document.getElementById("edit_linkedin").value = testimonial.linkedin;
        }
    </script>
</body>
<script src="../../src/js/toastr.js"></script>

</html>