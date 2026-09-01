# Puppeteer Tasks

Folder ini berisi tiga tugas terpisah:

- `dc.js` dan `discord-runner.js`: login Discord dan membuka channel.
- `index.js`: screenshot otomatis halaman website.
- `farmrpg.js`: memasang session cookie FarmRPG dan menjalankan satu aksi.

## Menjalankan

Salin `.env.example` menjadi `.env`, lalu isi nilai sebenarnya. Jangan membagikan atau commit `.env` karena berisi kredensial dan session cookie.

```powershell
Copy-Item .env.example .env
```

Jalankan dari folder ini:

```powershell
npm run start:screenshot
npm run start:discord
npm run start:farm
```

`npm run start` tetap menjalankan tugas screenshot. Untuk FarmRPG, isi cookie session akun milik sendiri dan pilih `FARM_ACTION_SELECTOR` atau `FARM_ACTION_URL` untuk aksi.

Jika Discord meminta 2FA atau CAPTCHA, selesaikan secara manual pada browser yang terbuka.
