<?php
include __DIR__ . '/koneksi.php';

// Variabel untuk Data Diri & Informasi
$nama         = "Razan Muhammad Ihsan Rismawandi";
$nama_panggil   = "Razan";
$role         = "Full-Stack Web Developer & Data Enthusiast";
$bio          = "Currently in my fourth semester as an Informatics Engineering student, I combine rigorous academic foundations in data structures and systems analysis with hands-on full-stack development experience.";
$bio_detail     = "Whether I'm developing multi-role workshop management systems, company profile platforms, or exploring image processing techniques and AI/ML workflows, I strive for clean, maintainable, and premium digital experiences.";
$github       = "https://github.com/ZanIhsan2";
$linkedin     = "#";
$lokasi       = "Bojong Gede, West Java, Indonesia";
$gpa          = "3.70";
$total_projects = "15+";

// Status Ketersediaan
$open_to_work   = true;

// 2. Array untuk Daftar Skill
$skills = [
    ["name" => "React & TypeScript", "icon" => "terminal"],
    ["name" => "Laravel & PHP", "icon" => "dns"],
    ["name" => "Tailwind CSS", "icon" => "palette"],
    ["name" => "AI / Machine Learning", "icon" => "psychology"]
];

// Ambil Data Projects secara dinamis dari Database MySQL
$projects = [];
$result = mysqli_query($conn, "SELECT * FROM projects ORDER BY id DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Ubah string tech yang dipisah koma menjadi array
        $row['tech'] = explode(',', $row['tech']);
        
        // Prioritaskan file_path (hasil upload), lalu kolom image, jika tidak ada baru gunakan default
        if (!empty($row['file_path']) && file_exists($row['file_path'])) {
            $row['image'] = $row['file_path'];
        } elseif (!empty($row['image']) && file_exists($row['image'])) {
            $row['image'] = $row['image'];
        } else {
            $row['image'] = "assets/sts.png"; 
        }

        $projects[] = $row;
    }
}

// Nested Array untuk Skill Tree Rekursif
$skill_tree = [
    "Web Development" => [
        "Frontend" => [
            "HTML & CSS" => ["Flexbox", "Grid", "Tailwind"],
            "JavaScript" => ["ES6+", "React", "TypeScript"]
        ],
        "Backend" => [
            "PHP" => ["Laravel", "Eloquent ORM"],
            "Database" => ["MySQL", "Database Normalization"]
        ]
    ],
    "Data & AI" => [
        "Machine Learning" => ["Python", "Scikit-Learn"],
        "Image Processing" => ["Fourier Transforms", "OCR Optimization"]
    ]
];

// Fungsi renderProjectCard() dengan Database, Delete, & Download
function renderProjectCard($id, $title, $description, $tech, $image, $file_path, $category) {
    $category_badge_color = "bg-primary-fixed text-on-primary-fixed";
    switch ($category) {
        case 'Frontend':
            $category_badge_color = "bg-blue-100 text-blue-800";
            break;
        case 'Backend':
            $category_badge_color = "bg-purple-100 text-purple-800";
            break;
        case 'Fullstack':
        case 'Full-Stack':
            $category_badge_color = "bg-green-100 text-green-800";
            break;
        default:
            $category_badge_color = "bg-gray-100 text-gray-800";
            break;
    }

    $tech_html = "";
    foreach ($tech as $t) {
        $tech_html .= "<span class='bg-primary-fixed text-on-primary-fixed px-2.5 py-1 rounded text-xs font-medium'>" . trim($t) . "</span>";
    }

    $download_btn = "";
    if (!empty($file_path) && file_exists($file_path)) {
        $download_btn = '<a href="' . $file_path . '" download class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors"><span>Download File</span><span class="material-symbols-outlined text-[18px]">download</span></a>';
    }

    return '
    <div class="project-card group cursor-pointer flex flex-col justify-between bg-white p-6 rounded-2xl border border-outline-variant shadow-sm relative">
        <div>
            <div class="relative overflow-hidden rounded-xl border border-outline-variant bg-surface aspect-video mb-6">
                <img src="' . $image . '" alt="' . $title . ' Preview" class="w-full h-full object-cover project-image transition-transform duration-500" />
            </div>
            <div class="space-y-3">
                <div class="flex flex-wrap gap-2 items-center justify-between">
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="px-2.5 py-1 rounded text-xs font-bold ' . $category_badge_color . '">' . $category . '</span>
                        ' . $tech_html . '
                    </div>
                    <a href="./components/delete.php?id=' . $id . '" onclick="return confirm(\'Yakin ingin menghapus project ini?\')" class="text-red-500 hover:text-red-700 text-xs font-semibold flex items-center gap-1 bg-red-50 px-2.5 py-1 rounded-lg border border-red-100">
                        <span class="material-symbols-outlined text-[16px]">delete</span> Delete
                    </a>
                </div>
                <h3 class="text-xl font-bold">' . $title . '</h3>
                <p class="text-on-surface-variant text-sm leading-relaxed">' . $description . '</p>
            </div>
        </div>
        <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between">
            ' . $download_btn . '
        </div>
    </div>';
}

// Fungsi yang memanggil fungsi lain
function renderPortfolioSection($projects_array) {
    if (empty($projects_array)) {
        return '<p class="text-center text-on-surface-variant py-8">Belum ada project yang ditambahkan ke database.</p>';
    }
    $html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-10">';
    foreach ($projects_array as $p) {
        $html .= renderProjectCard(
            $p['id'],
            $p['title'], 
            $p['description'], 
            $p['tech'], 
            $p['image'], 
            $p['file_path'],
            $p['category']
        );
    }
    $html .= '</div>';
    return $html;
}

// Fungsi Rekursif untuk Skill Tree
function renderSkillTree($array) {
    $html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-8">';
    foreach ($array as $category_name => $subcategories) {
        $html .= '<div class="bg-surface p-6 rounded-2xl border border-outline-variant/40 space-y-4">';
        $html .= '<div class="font-bold text-base text-secondary flex items-center gap-2 border-b border-outline-variant/30 pb-3">';
        $html .= '<span class="material-symbols-outlined text-[20px]">folder_open</span> ' . $category_name;
        $html .= '</div>';
        
        $html .= '<div class="space-y-4 pl-2">';
        foreach ($subcategories as $sub_name => $items) {
            $html .= '<div class="space-y-2">';
            $html .= '<div class="font-semibold text-xs text-on-surface uppercase tracking-wider flex items-center gap-1.5 text-on-surface-variant">';
            $html .= '<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span> ' . $sub_name;
            $html .= '</div>';
            
            $html .= '<div class="flex flex-wrap gap-1.5 pl-3 border-l-2 border-outline-variant/30 ml-1 py-1">';
            foreach ($items as $label => $sub_items) {
                if (is_array($sub_items)) {
                    $html .= '<div class="w-full text-xs font-medium text-on-surface mt-1 mb-1">' . $label . ':</div>';
                    foreach ($sub_items as $skill) {
                        $html .= '<span class="text-xs font-medium text-on-surface bg-white px-3 py-1 rounded-md border border-outline-variant/40 shadow-sm hover:border-secondary transition-colors">✨ ' . $skill . '</span>';
                    }
                } else {
                    $html .= '<span class="text-xs font-medium text-on-surface bg-white px-3 py-1 rounded-md border border-outline-variant/40 shadow-sm hover:border-secondary transition-colors">✨ ' . $sub_items . '</span>';
                }
            }
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';
    }
    $html .= '</div>';
    return $html;
}
?>
<!doctype html>
<html class="scroll-smooth" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo $nama; ?> | Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "on-surface": "#191c1e",
              "surface-tint": "#565e74",
              secondary: "#4648d4",
              "secondary-fixed": "#e1e0ff",
              "on-secondary": "#ffffff",
              "on-secondary-fixed": "#07006c",
              "on-secondary-container": "#fffbff",
              "secondary-container": "#6063ee",
              surface: "#f7f9fb",
              "outline-variant": "#c6c6cd",
              "on-surface-variant": "#45464d",
              "primary-fixed": "#dae2fd",
              "on-primary-fixed": "#131b2e",
            },
            fontFamily: {
              display: ["Inter"],
              body: ["Inter"],
              mono: ["JetBrains Mono"],
            },
          },
        },
      };
    </script>
    <style>
      .material-symbols-outlined { font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24; }
      .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
      .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
      @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
      .project-card:hover .project-image { transform: scale(1.05); }
    </style>
  </head>
  <body class="bg-surface text-on-surface selection:bg-secondary-fixed selection:text-on-secondary-fixed">

    <!-- TopNavBar -->
    <header class="glass-header border-b border-outline-variant/35 sticky top-0 z-50 shadow-sm w-full">
      <nav class="flex justify-between items-center h-[72px] w-full px-6 md:px-10 max-w-[1280px] mx-auto">
        <a class="font-bold text-xl text-on-surface tracking-tight" href="#"><?php echo $nama_panggil; ?></a>

        <!-- Desktop Nav -->
        <div class="hidden md:flex items-center gap-8">
          <a class="text-sm font-medium text-on-surface hover:text-secondary transition-colors" href="#about">About</a>
          <a class="text-sm font-medium text-on-surface hover:text-secondary transition-colors" href="#skills">Skills</a>
          <a class="text-sm font-medium text-on-surface hover:text-secondary transition-colors" href="#projects">Projects</a>
          <a class="text-sm font-medium text-on-surface hover:text-secondary transition-colors" href="#dashboard">Dashboard</a>
          <a class="text-sm font-medium text-on-surface hover:text-secondary transition-colors" href="#contact">Contact</a>
          <a href="<?php echo $github; ?>" target="_blank" class="bg-secondary text-on-secondary px-5 py-2.5 rounded-lg text-sm font-medium transition-all hover:bg-secondary-container shadow-sm">
            GitHub
          </a>
        </div>

        <button id="mobile-menu-btn" class="md:hidden p-2 text-on-surface focus:outline-none">
          <span class="material-symbols-outlined">menu</span>
        </button>
      </nav>
    </header>

    <main>
      <!-- Hero Section -->
      <section class="relative overflow-hidden py-20 md:py-32 px-6 md:px-10 max-w-[1280px] mx-auto">
        <div class="fade-in-up flex flex-col md:flex-row items-center gap-12">
          <div class="flex-1 space-y-6">
            <div class="flex items-center gap-3">
              <div id="dynamic-greeting" class="inline-flex items-center gap-2 bg-secondary-fixed text-on-secondary-fixed px-4 py-1.5 rounded-full text-xs font-medium">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                </span>
                <span id="greeting-text">Welcome to my portfolio!</span>
              </div>

              <?php if ($open_to_work): ?>
                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                  Open to Work 🟢
                </span>
              <?php else: ?>
                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                  Not Available 🔴
                </span>
              <?php endif; ?>
            </div>

            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight">
              Hi, I'm <span class="text-secondary"><?php echo $nama; ?></span>
            </h1>

            <p class="text-lg text-on-surface-variant max-w-xl leading-relaxed">
              <?php echo $bio; ?>
            </p>

            <div class="flex flex-wrap gap-4 pt-2">
              <a class="bg-secondary text-on-secondary px-8 py-3.5 rounded-lg text-sm font-medium transition-all hover:bg-secondary-container flex items-center gap-2 shadow-sm" href="#projects">
                View My Work
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
              </a>
              <a class="border border-outline-variant text-on-surface px-8 py-3.5 rounded-lg text-sm font-medium transition-all hover:bg-surface-container flex items-center gap-2" href="#contact">
                Get In Touch
              </a>
            </div>
          </div>

          <div class="flex-1 w-full max-w-[450px] relative">
            <div class="aspect-square bg-white border border-outline-variant p-4 rounded-2xl shadow-xl rotate-2 relative z-10 overflow-hidden">
              <div class="w-full h-full bg-surface-container rounded-xl flex flex-col items-center justify-center p-6 text-center border border-outline-variant/40">
                <span class="material-symbols-outlined text-[64px] text-secondary mb-4">code</span>
                <p class="font-mono text-xs text-on-surface-variant mb-1">// Full-Stack Developer</p>
                <h3 class="font-bold text-lg text-on-surface"><?php echo $role; ?></h3>
                <p class="text-xs text-on-surface-variant mt-4">"Late-night coding with lo-fi beats."</p>
              </div>
            </div>
            <div class="absolute inset-0 bg-secondary-fixed/40 blur-3xl -z-10 rounded-full translate-x-8 translate-y-8"></div>
          </div>
        </div>
      </section>

      <!-- About Me Section -->
      <section class="bg-white py-24 px-6 md:px-10" id="about">
        <div class="max-w-[1280px] mx-auto">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
            <div class="md:col-span-5">
              <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">
                Bridging Academic Logic with Real-World Code.
              </h2>
              <div class="w-20 h-1.5 bg-secondary rounded-full"></div>
            </div>
            <div class="md:col-span-7 space-y-4">
              <p class="text-lg text-on-surface-variant leading-relaxed">
                <?php echo $bio; ?>
              </p>
              <p class="text-lg text-on-surface-variant leading-relaxed">
                <?php echo $bio_detail; ?>
              </p>

              <div class="grid grid-cols-2 gap-4 pt-6">
                <div class="p-6 bg-surface rounded-xl border border-outline-variant/30">
                  <h4 class="text-2xl font-bold text-secondary mb-1"><?php echo $gpa; ?></h4>
                  <p class="text-sm text-on-surface-variant font-medium">Semester GPA (IPS)</p>
                </div>
                <div class="p-6 bg-surface rounded-xl border border-outline-variant/30">
                  <h4 class="text-2xl font-bold text-secondary mb-1"><?php echo $total_projects; ?></h4>
                  <p class="text-sm text-on-surface-variant font-medium">Projects & Modules Built</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Skills Section -->
      <section class="py-24 px-6 md:px-10 max-w-[1280px] mx-auto" id="skills">
        <div class="text-center mb-16">
          <p class="text-xs font-mono uppercase tracking-widest text-secondary mb-2">Capabilities</p>
          <h2 class="text-3xl md:text-4xl font-bold tracking-tight">Technical Arsenal</h2>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
          <?php foreach ($skills as $skill): ?>
          <div class="group p-8 bg-white border border-outline-variant rounded-xl transition-all hover:shadow-lg hover:border-secondary flex flex-col items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center bg-secondary-fixed rounded-lg group-hover:bg-secondary group-hover:text-white transition-colors">
              <span class="material-symbols-outlined"><?php echo $skill['icon']; ?></span>
            </div>
            <span class="font-medium text-sm text-center"><?php echo $skill['name']; ?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Skill Tree Rekursif Section -->
        <div class="bg-white p-8 rounded-2xl border border-outline-variant shadow-sm w-full">
          <h3 class="font-bold text-base mb-4 text-center">Interactive Skill Tree</h3>
          <?php echo renderSkillTree($skill_tree); ?>
        </div>
      </section>

      <!-- Projects Section (Connected to Database) -->
      <section class="bg-white py-24 px-6 md:px-10" id="projects">
        <div class="max-w-[1280px] mx-auto">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-4">
            <div>
              <p class="text-xs font-mono uppercase tracking-widest text-secondary mb-2">Selected Work</p>
              <h2 class="text-3xl md:text-4xl font-bold tracking-tight">Featured Projects</h2>
            </div>
            <div class="flex items-center gap-4">
              <a href="./components/create.php" class="bg-secondary hover:bg-secondary-container text-on-secondary px-4 py-2.5 rounded-lg text-sm font-medium transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span> Add Project
              </a>
              <a class="hidden md:flex items-center gap-2 text-sm font-medium text-on-surface-variant hover:text-secondary transition-colors" href="<?php echo $github; ?>" target="_blank">
                View GitHub Profile
                <span class="material-symbols-outlined">open_in_new</span>
              </a>
            </div>
          </div>

          <!-- Memanggil daftar project dari database MySQL -->
          <?php echo renderPortfolioSection($projects); ?>
        </div>
      </section>

      <!-- BAGIAN Dashboard & Advanced Chart Reporting Section -->
      <section class="py-24 px-6 md:px-10 max-w-[1280px] mx-auto" id="dashboard">
        <div class="text-center mb-16">
          <p class="text-xs font-mono uppercase tracking-widest text-secondary mb-2">Analytics & Reporting</p>
          <h2 class="text-3xl md:text-4xl font-bold tracking-tight">Interactive Developer Dashboard</h2>
          <p class="text-sm text-on-surface-variant mt-2">Visualized datasets covering skill levels, learning metrics, and advanced configurations.</p>
        </div>

        <div class="flex justify-center mb-10">
          <button id="update-charts-btn" class="bg-secondary hover:bg-secondary-container text-on-secondary px-6 py-2.5 rounded-lg text-sm font-medium transition-all shadow-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">refresh</span>
            Trigger Programmatic Data Update
          </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
          <div class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm flex flex-col justify-between">
            <h3 class="font-bold text-base mb-4">Skill Proficiency (Bar Chart)</h3>
            <div class="relative w-full aspect-square flex items-center justify-center">
              <canvas id="skillBarChart"></canvas>
            </div>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm flex flex-col justify-between">
            <h3 class="font-bold text-base mb-4">Learning Progress (Line Chart)</h3>
            <div class="relative w-full aspect-square flex items-center justify-center">
              <canvas id="learningLineChart"></canvas>
            </div>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm flex flex-col justify-between">
            <h3 class="font-bold text-base mb-4">Project Categories (Pie Chart)</h3>
            <div class="relative w-full aspect-square flex items-center justify-center">
              <canvas id="projectPieChart"></canvas>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm">
            <h3 class="font-bold text-base mb-4">Quarterly Tech Stack Allocation (Stacked Bar)</h3>
            <div class="relative w-full aspect-video flex items-center justify-center">
              <canvas id="stackedBarChart"></canvas>
            </div>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm">
            <h3 class="font-bold text-base mb-4">Complexity vs. Hours Spent (Scatter Chart)</h3>
            <div class="relative w-full aspect-video flex items-center justify-center">
              <canvas id="scatterChart"></canvas>
            </div>
          </div>
        </div>
      </section>

      <!-- Contact Section -->
      <section class="py-24 px-6 md:px-10 max-w-[1280px] mx-auto" id="contact">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
          <div class="space-y-6">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
              Let's build something <span class="text-secondary">extraordinary</span> together.
            </h2>
            <p class="text-lg text-on-surface-variant leading-relaxed">
              Have a project in mind, collaboration proposal, or just want to connect? Send a message or reach out directly via WhatsApp for a fast response.
            </p>
            <div class="space-y-4 pt-2">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary">
                  <span class="material-symbols-outlined">chat</span>
                </div>
                <span class="text-base font-medium">Direct WhatsApp Messaging Available</span>
              </div>
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary">
                  <span class="material-symbols-outlined">location_on</span>
                </div>
                <span class="text-base font-medium"><?php echo $lokasi; ?></span>
              </div>
            </div>
          </div>

          <div class="bg-white p-8 md:p-10 rounded-2xl border border-outline-variant shadow-lg space-y-6">
            <h3 class="text-xl font-bold">Get In Touch</h3>
            <form id="contact-form" class="space-y-4">
              <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-on-surface-variant mb-2">Your Name</label>
                <input type="text" name="name" required class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-secondary transition-colors" placeholder="John Doe" />
              </div>
              <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-on-surface-variant mb-2">Email Address</label>
                <input type="email" name="email" required class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-secondary transition-colors" placeholder="john@example.com" />
              </div>
              <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-on-surface-variant mb-2">Message</label>
                <textarea name="message" rows="4" required class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-secondary transition-colors resize-none" placeholder="Tell me about your project..."></textarea>
              </div>
              <p id="form-feedback" class="text-xs font-medium text-red-500 hidden"></p>
              <button type="submit" class="w-full bg-secondary hover:bg-secondary-container text-on-secondary py-4 rounded-lg font-medium transition-all shadow-md flex justify-center items-center gap-2">
                <span>Send via WhatsApp</span>
                <span class="material-symbols-outlined text-[20px]">send</span>
              </button>
            </form>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="bg-surface border-t border-outline-variant/35 w-full">
      <div class="flex flex-col md:flex-row justify-between items-center py-10 px-6 md:px-10 max-w-[1280px] mx-auto gap-6">
        <div class="flex flex-col items-center md:items-start gap-1">
          <span class="font-bold text-lg text-on-surface"><?php echo $nama; ?></span>
          <p class="text-xs text-on-surface-variant">© <?php echo date('Y'); ?> All rights reserved.</p>
        </div>
        <div class="flex gap-8">
          <a class="text-sm font-medium text-on-surface-variant hover:text-secondary transition-colors" href="<?php echo $github; ?>" target="_blank">GitHub</a>
          <a class="text-sm font-medium text-on-surface-variant hover:text-secondary transition-colors" href="<?php echo $linkedin; ?>" target="_blank">LinkedIn</a>
        </div>
      </div>
    </footer>

    <script src="script.js" defer></script>
  </body>
</html>