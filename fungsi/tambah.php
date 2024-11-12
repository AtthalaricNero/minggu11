<?php
session_start();
if (!empty($_SESSION['username'])) {
    require '../config/koneksi.php';
    require '../fungsi/pesan_kilat.php';
    require '../fungsi/anti_injection.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
        if (!empty($_GET['jabatan'])) {  
            $jabatan = antiinjection($koneksi, $_POST['jabatan']);
            $keterangan = antiinjection($koneksi, $_POST['keterangan']);

            $query = "INSERT INTO jabatan (jabatan, keterangan) VALUES ('$jabatan', '$keterangan')";
            if (mysqli_query($koneksi, $query)) {
                pesan('success', 'Data Jabatan Berhasil Ditambahkan');
            } else {
                pesan('danger', 'Data Jabatan Gagal Ditambahkan' . mysqli_error($koneksi));
            }
            header('location:../index.php?page=jabatan');
            exit();
        } elseif (!empty($_GET['anggota'])) { 
            $username = antiinjection($koneksi, $_POST['username']);
            $password = antiinjection($koneksi, $_POST['password']);
            $level = antiinjection($koneksi, $_POST['level']);
            $jabatan = antiinjection($koneksi, $_POST['jabatan']);
            $nama = antiinjection($koneksi, $_POST['nama']);
            $jenis_kelamin = antiinjection($koneksi, $_POST['jenis_kelamin']);
            $alamat = antiinjection($koneksi, $_POST['alamat']);
            $no_telp = antiinjection($koneksi, $_POST['no_telp']);

            $salt = bin2hex(random_bytes(16));
            $combined_password = $salt . $password;
            $hashed_password = password_hash($combined_password, PASSWORD_BCRYPT);

            $query = "INSERT INTO user (username, password, salt, level) VALUES ('$username', '$hashed_password', '$salt', '$level')";
            if (mysqli_query($koneksi, $query)) {
                $last_id = mysqli_insert_id($koneksi);
                $query2 = "INSERT INTO anggota (nama, jenis_kelamin, alamat, no_telp, id_user, id_jabatan) VALUES ('$nama', '$jenis_kelamin', '$alamat', '$no_telp', '$last_id', '$jabatan')";
                
                if (mysqli_query($koneksi, $query2)) {
                    pesan('success', 'Anggota Baru Ditambahkan.');
                } else {
                    pesan('warning', 'Gagal Menambahkan Anggota Tetapi Data Login Tersimpan Karena: ' . mysqli_error($koneksi));
                }
            } else {
                pesan('danger', 'Gagal Menambahkan Anggota: ' . mysqli_error($koneksi));
            }
            header('location:../index.php?page=anggota');
            exit();
        }
    }
}
?>