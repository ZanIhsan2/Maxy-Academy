<?php
include __DIR__ . '/../koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Ambil path file berdasarkan ID sebelum data dihapus
    $query = $conn->prepare("SELECT file_path FROM projects WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $filePath = $row['file_path'];
        
        // Hapus file fisik jika ada di server
        if (!empty($filePath) && file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Hapus record dari database
        $deleteStmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        $deleteStmt->execute();
        $deleteStmt->close();
    }
    $query->close();
}

header("Location: ../index.php#projects");
exit();
?>