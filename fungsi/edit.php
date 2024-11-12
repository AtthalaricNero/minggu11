<?php
session_start();
if (!empty($_SESSION['username'])) {
    require '../config/koneksi.php';
    require '../fungsi/pesan_kilat.php';
    require '../fungsi/anti_injection.php';

    if (!empty($_GET['jabatan'])) {
        $id = antiinjection($koneksi, $_POST['id']);
        $jabatan = antiinjection($koneksi, $_POST['jabatan']);
        $keterangan = antiinjection($koneksi, $_POST['keterangan']);
        $query = "UPDATE jabatan SET jabatan = '$jabatan', keterangan = '$keterangan' WHERE id = '$id'";
        if (mysqli_query($koneksi, $query)) {
            pesan('success', 'Data Jabatan Berhasil Diubah');
        } else {
            pesan('danger', 'Data Jabatan Gagal Diubah' . mysqli_error($koneksi));
        }
        header('location:../index.php?page=jabatan');
        exit();
    } elseif (!empty($_GET['anggota'])) {
        $id_user = antiInjection($koneksi, $_POST['id']);
        $nama = antiInjection($koneksi, $_POST['nama']);
        $jabatan = antiInjection($koneksi, $_POST['jabatan']);
        $jenis_kelamin = antiInjection($koneksi, $_POST['jenis_kelamin']);
        $alamat = antiInjection($koneksi, $_POST['alamat']);
        $no_telp = antiInjection($koneksi, $_POST['no_telp']);
        $username = antiInjection($koneksi, $_POST['username']);
        $query_anggota = "UPDATE anggota SET nama = '$nama',
                          jenis_kelamin = '$jenis_kelamin',
                          alamat = '$alamat',
                          no_telp = '$no_telp',
                          id_jabatan = '$jabatan'
                          WHERE id_user = '$id_user'";
        if (mysqli_query($koneksi, $query_anggota)) {
            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
                $salt = bin2hex(random_bytes(16));
                $combined_password = $salt . $password;
                $hashed_password = password_hash($combined_password, PASSWORD_BCRYPT);

                $query_user = "UPDATE user SET username = '$username', password = '$hashed_password', salt = '$salt' WHERE id = '$id_user'";
                if (mysqli_query($koneksi, $query_user)) {
                    pesan('success', "Anggota Telah Diubah.");
                } else {
                    pesan('warning', "Data Anggota Berhasil Diubah, Tetapi Password Gagal Diubah Karena: " . mysqli_error($koneksi));
                }
            } else {
                $query_user = "UPDATE user SET username = '$username' WHERE id = '$id_user'";
                if (mysqli_query($koneksi, $query_user)) {
                    pesan('success', "Anggota Telah Diubah.");
                } else {
                    pesan('warning', "Data Anggota Berhasil Diubah, Tetapi Username Gagal Diubah Karena: " . mysqli_error($koneksi));
                }
            }
        } else {
            pesan('danger', "Mengubah Anggota Karena: " . mysqli_error($koneksi));
        }
        header("Location: ../index.php?page=anggota");
    }
}
