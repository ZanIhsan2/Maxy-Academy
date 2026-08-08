<?php
include __DIR__ . '/../koneksi.php';

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menggunakan ?? '' untuk mencegah warning jika key tidak terdefinisi
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $tech        = trim($_POST['tech'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    
    // Validasi input tidak boleh kosong
    if (empty($title) || empty($description) || empty($tech) || empty($category)) {
        $error_message = "Semua field teks wajib diisi!";
    } elseif (!isset($_FILES['project_file']) || $_FILES['project_file']['error'] == UPLOAD_ERR_NO_FILE) {
        $error_message = "File pendukung wajib diupload!";
    } else {
        $file     = $_FILES['project_file'];
        $fileName = $file['name'];
        $fileTmp  = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Validasi ekstensi dan ukuran (Maksimal 2MB)
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($fileExt, $allowedExtensions)) {
            $error_message = "Format file tidak diizinkan! Hanya diperbolehkan .pdf, .jpg, .jpeg, .png";
        } elseif ($fileSize > $maxSize) {
            $error_message = "Ukuran file terlalu besar! Maksimal 2MB.";
        } else {
            // Cek dan buat folder uploads/ secara otomatis jika belum ada
            $uploadDir = "../uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Buat nama file unik untuk menghindari bentrok
            $newFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9._]/", "_", $fileName);
            $destination = $uploadDir . $newFileName;
            
            if (move_uploaded_file($fileTmp, $destination)) {
                // Simpan ke database menggunakan prepared statement
                $stmt = $conn->prepare("INSERT INTO projects (title, description, tech, category, file_path) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $title, $description, $tech, $category, $destination);
                
                if ($stmt->execute()) {
                    $success_message = "Project berhasil ditambahkan!";
                    header("refresh:2;url=index.php"); // Redirect otomatis setelah 2 detik
                } else {
                    $error_message = "Gagal menyimpan ke database: " . $conn->error;
                }
                $stmt->close();
            } else {
                $error_message = "Gagal mengunggah file ke server.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Project | Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-900 font-['Inter'] antialiased flex items-center justify-center min-h-screen py-12 px-4">
    <div class="max-w-xl w-full bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold tracking-tight">Add New Project</h1>
            <a href="../index.php" class="text-sm text-indigo-600 hover:underline flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Portfolio
            </a>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-600 mb-1">Project Title</label>
                <input type="text" name="title" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600">
            </div>
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-600 mb-1">Category</label>
                <select name="category" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600">
                    <option value="Frontend">Frontend</option>
                    <option value="Backend">Backend</option>
                    <option value="Fullstack">Fullstack</option>
                    <option value="Data & AI">Data & AI</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-600 mb-1">Technologies (pisahkan dengan koma)</label>
                <input type="text" name="tech" placeholder="Laravel, MySQL, Tailwind" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600">
            </div>
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-600 mb-1">Description</label>
                <!-- Atribut ganda name="message" telah dihapus -->
                <textarea name="description" rows="3" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-slate-600 mb-1">Support File (PDF / JPG / PNG - Max 2MB)</label>
                <input type="file" name="project_file" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-medium transition-all shadow-sm">
                Save Project
            </button>
        </form>
    </div>
</body>
</html>