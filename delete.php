<?php
    include "connection.php";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
        // 1. Hapus record yang diinginkan
        $sql = "DELETE from `crud2` where id=$id";
        $conn->query($sql);
        
        // 2. Buat tabel temporary dan salin struktur
        $sql = "CREATE TEMPORARY TABLE temp_crud2 LIKE crud2";
        $conn->query($sql);
        
        // 3. Salin data dengan mempertahankan join_date
        $sql = "INSERT INTO temp_crud2 (name, email, phone, join_date)
                SELECT name, email, phone, join_date
                FROM crud2
                ORDER BY id";
        $conn->query($sql);
        
        // 4. Truncate tabel asli
        $sql = "TRUNCATE TABLE crud2";
        $conn->query($sql);
        
        // 5. Salin kembali data dengan ID baru dan join_date yang dipertahankan
        $sql = "INSERT INTO crud2 (name, email, phone, join_date)
                SELECT name, email, phone, join_date
                FROM temp_crud2
                ORDER BY join_date";
        $conn->query($sql);
        
        // 6. Hapus tabel temporary
        $sql = "DROP TEMPORARY TABLE IF EXISTS temp_crud2";
        $conn->query($sql);
        
        // 7. Reset AUTO_INCREMENT
        $sql = "ALTER TABLE crud2 AUTO_INCREMENT = 1";
        $conn->query($sql);
    }
    header('location:/SAW/index.php');
    exit;
?>