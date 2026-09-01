# Discord Puppeteer Automation

Automation ini membuka Discord, memakai sesi browser yang tersimpan, lalu masuk ke channel dari `DISCORD_CHANNEL_URL`.

## Menjalankan

1. Salin `.env.example` menjadi `.env`, lalu isi nilai sebenarnya. `SERVER_ID` dan `CHANNEL_ID` harus berupa ID dari URL channel, bukan nama server/channel:

```powershell
Copy-Item .env.example .env
```

Contoh isi `.env`:

```env
DISCORD_EMAIL=email-anda@example.com
DISCORD_PASSWORD=password-anda
DISCORD_CHANNEL_URL=https://discord.com/channels/123456789012345678/987654321098765432
```

2. Jalankan dari folder ini:

```powershell
npm run start
```

`SERVER_ID` dan `CHANNEL_ID` diambil dari URL channel Discord. Profil login disimpan di folder `.discord-profile`, sehingga login berikutnya biasanya tidak perlu mengisi kredensial lagi.

Jika akun memakai 2FA atau Discord menampilkan CAPTCHA, selesaikan secara manual pada browser yang terbuka dalam batas waktu 120 detik. Jangan menyimpan password langsung di source code.
