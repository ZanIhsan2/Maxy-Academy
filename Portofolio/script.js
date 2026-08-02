console.log("[DEBUG] script.js loaded successfully. Welcome to Razan's Portfolio Console!");

document.addEventListener("DOMContentLoaded", () => {
  // 1. Conditional (if/else) berdasarkan waktu untuk Greeting Banner
  const currentHour = new Date().getHours();
  const greetingTextEl = document.getElementById("greeting-text");
  let timeGreeting = "";

  if (currentHour >= 4 && currentHour < 11) {
    timeGreeting = "Good Morning! Ready to code?";
  } else if (currentHour >= 11 && currentHour < 15) {
    timeGreeting = "Good Afternoon! Productive coding hours.";
  } else if (currentHour >= 15 && currentHour < 18) {
    timeGreeting = "Good Evening! Let's wrap up today's tasks.";
  } else {
    timeGreeting = "Late-night coding session with lo-fi beats 🌙";
  }

  if (greetingTextEl) {
    greetingTextEl.textContent = timeGreeting;
    console.log("[DEBUG] Dynamic Greeting applied:", timeGreeting);
  }

  // 2. Event Listener: Mobile menu toggle button interaction (DOM class manipulation)
  const mobileMenuBtn = document.getElementById("mobile-menu-btn");
  if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener("click", () => {
      console.log("[DEBUG] Mobile menu button clicked.");
      alert("Navigasi mobile aktif. Silakan gunakan menu utama di atas.");
    });
  }

  // 3. Form Validation & Submit Event Handling
  const contactForm = document.getElementById("contact-form");
  const formFeedback = document.getElementById("form-feedback");

  if (contactForm) {
    contactForm.addEventListener("submit", function (event) {
      event.preventDefault();
      console.log("[DEBUG] Contact form submission intercepted.");

      const nameInput = this.name.value.trim();
      const emailInput = this.email.value.trim();
      const messageInput = this.message.value.trim();

      // Looping / Conditional validation checks
      if (nameInput === "" || emailInput === "" || messageInput === "") {
        formFeedback.textContent = "Semua field wajib diisi dengan benar!";
        formFeedback.classList.remove("hidden");
        console.warn("[DEBUG] Validation failed: Empty fields detected.");
        return;
      }

      formFeedback.classList.add("hidden");
      console.log("[DEBUG] Validation passed. Redirecting to WhatsApp API...");

      const waMessage = `Halo, nama saya ${nameInput} (${emailInput}). ${messageInput}`;
      const waUrl = `https://wa.me/?text=` + encodeURIComponent(waMessage);
      window.open(waUrl, "_blank");
    });
  }

  // ==========================================
  // Data Visualization dengan Chart.js & Dashboard
  // ==========================================

  // Dummy Data untuk Reporting & Dashboard
  const skillsData = {
    labels: ["React / TS", "Laravel / PHP", "Tailwind CSS", "AI / ML", "Python"],
    values: [88, 82, 90, 75, 70]
  };

  const learningProgressData = {
    labels: ["Sem 1", "Sem 2", "Sem 3", "Sem 4 (Current)"],
    gpaValues: [3.50, 3.62, 3.68, 3.70]
  };

  const projectCategoriesData = {
    labels: ["Full-Stack App", "Company Profile", "Data Warehouse", "AI / ML Lab"],
    counts: [4, 3, 2, 3]
  };

  // Inisialisasi Chart 1: Bar Chart (Skill Level)
  const ctxBar = document.getElementById("skillBarChart").getContext("2d");
  let skillBarChart = new Chart(ctxBar, {
    type: "bar",
    data: {
      labels: skillsData.labels,
      datasets: [{
        label: "Proficiency Level (%)",
        data: skillsData.values,
        backgroundColor: "#4648d4",
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, max: 100 } }
    }
  });

  // Inisialisasi Chart 2: Line Chart (Learning Progress / IPS)
  const ctxLine = document.getElementById("learningLineChart").getContext("2d");
  let learningLineChart = new Chart(ctxLine, {
    type: "line",
    data: {
      labels: learningProgressData.labels,
      datasets: [{
        label: "Semester GPA (IPS)",
        data: learningProgressData.gpaValues,
        borderColor: "#6063ee",
        backgroundColor: "rgba(96, 99, 238, 0.1)",
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      scales: { y: { min: 3.0, max: 4.0 } }
    }
  });

  // Inisialisasi Chart 3: Pie Chart (Project Categories)
  const ctxPie = document.getElementById("projectPieChart").getContext("2d");
  let projectPieChart = new Chart(ctxPie, {
    type: "pie",
    data: {
      labels: projectCategoriesData.labels,
      datasets: [{
        data: projectCategoriesData.counts,
        backgroundColor: ["#4648d4", "#6063ee", "#e1e0ff", "#191c1e"]
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: "bottom", labels: { boxWidth: 12 } } }
    }
  });

  // Inisialisasi Chart 4: Stacked Bar Chart (Dashboard)
  const ctxStacked = document.getElementById("stackedBarChart").getContext("2d");
  let stackedBarChart = new Chart(ctxStacked, {
    type: "bar",
    data: {
      labels: ["Q1 2025", "Q2 2025", "Q3 2025", "Q4 2025", "Q1 2026"],
      datasets: [
        {
          label: "Frontend",
          data: [15, 20, 25, 30, 35],
          backgroundColor: "#4648d4"
        },
        {
          label: "Backend",
          data: [10, 15, 20, 25, 30],
          backgroundColor: "#6063ee"
        },
        {
          label: "Data & ML",
          data: [5, 8, 12, 15, 20],
          backgroundColor: "#e1e0ff"
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        x: { stacked: true },
        y: { stacked: true, beginAtZero: true }
      }
    }
  });

  // Inisialisasi Chart 5: Scatter Chart dengan Scriptable Option (Warna dinamis berdasarkan nilai data)
  const ctxScatter = document.getElementById("scatterChart").getContext("2d");
  let scatterChart = new Chart(ctxScatter, {
    type: "scatter",
    data: {
      datasets: [{
        label: "Complexity vs Hours",
        data: [
          { x: 10, y: 30 },
          { x: 25, y: 60 },
          { x: 40, y: 85 },
          { x: 60, y: 120 },
          { x: 80, y: 160 }
        ],
        // Scriptable Option: Warna titik berubah otomatis berdasarkan nilai sumbu Y
        backgroundColor: (context) => {
          const value = context.raw;
          if (!value) return "#4648d4";
          return value.y > 100 ? "#4648d4" : "#6063ee";
        },
        pointRadius: 8
      }]
    },
    options: {
      responsive: true,
      scales: {
        x: { title: { display: true, text: "Complexity Level" } },
        y: { title: { display: true, text: "Hours Spent" } }
      }
    }
  });

  // 4. Programmatic Event Trigger: Tombol update chart saat diklik
  const updateChartsBtn = document.getElementById("update-charts-btn");
  if (updateChartsBtn) {
    updateChartsBtn.addEventListener("click", () => {
      console.log("[DEBUG] Programmatic Event Trigger activated: Updating chart values.");
      
      // Update data bar chart secara dinamis
      skillBarChart.data.datasets[0].data = [95, 88, 92, 80, 85];
      skillBarChart.update();

      // Update data line chart
      learningLineChart.data.datasets[0].data = [3.55, 3.65, 3.70, 3.75];
      learningLineChart.update();

      alert("Data chart berhasil diperbarui secara dinamis via JavaScript trigger!");
    });
  }
});