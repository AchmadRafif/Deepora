# ✦ Deepora — Social Pomodoro & Deep Work Hub

> Belajar lebih fokus, lebih seru, dan tidak sendirian.

Deepora adalah platform belajar berbasis web yang menggabungkan teknik **Pomodoro**, **gamifikasi**, dan **elemen sosial** untuk membantu pengguna tetap produktif dan termotivasi.

---

## 🚀 Features

### ⏱ Pomodoro Timer
- Focus session & break session otomatis (25 min focus → 5 min break → 15 min long break)
- Dot indicator untuk tracking sesi ke berapa
- Timer **persistent** — tidak reset saat pindah halaman
- Auto-start sesi berikutnya

### 🎧 Focus Room
- Buat & join room belajar virtual bersama teman
- Playlist Lo-fi bawaan (Lo-fi, Jazz, Nature, Classical)
- Custom YouTube URL untuk musik pilihan sendiri
- Timer Pomodoro tersinkronisasi di tiap room
- 💬 **Live Chat** di dalam room (auto-refresh setiap 3 detik)

### 🎮 Gamification System
- **XP** (Experience Points) setiap menyelesaikan sesi fokus
- **Level** naik otomatis setiap 100 XP
- **Badge** berdasarkan total XP:
  - 🌱 Newbie (0 XP)
  - ⚡ Rising Star (50+ XP)
  - 🔥 Grind Mode (200+ XP)
  - 💎 Focus Master (500+ XP)
  - 🏆 Study Legend (1000+ XP)
- **Avatar Unlock** berdasarkan level:
  - Lv.1 → Default
  - Lv.2 → ⚡ Electric
  - Lv.5 → 🌿 Nature
  - Lv.10 → 🔥 Fire
  - Lv.15 → 💎 Diamond
  - Lv.20 → 🌌 Galaxy
  - Lv.30 → 👑 Legendary

### 🏆 Leaderboard
- Ranking berdasarkan total XP
- Tampil nama, badge, level, dan avatar
- Top 20 pengguna paling produktif

### 👤 User Profile
- Edit nama, sekolah, warna avatar
- Pilih avatar style yang sudah di-unlock
- Statistik belajar:
  - Total sesi & total jam fokus
  - Sesi & menit minggu ini
  - Grafik aktivitas 7 hari terakhir
- XP progress bar menuju level berikutnya

---

## 🛠 Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 11 |
| Frontend | Blade Template + Vanilla JS |
| Database | MySQL |
| Auth | Laravel Breeze |
| Asset Bundler | Vite |
| Music | YouTube Embed API |
| Timer State | localStorage |

---

## 📦 Installation

### Requirements
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL
- Laragon / XAMPP / WAMP

### Steps

```bash
# 1. Clone repository
git clone https://github.com/username/deepora.git
cd deepora

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
DB_DATABASE=deepora
DB_USERNAME=root
DB_PASSWORD=

# 5. Jalankan migration
php artisan migrate

# 6. Build assets
npm run build

# 7. Jalankan server
php artisan serve
```

Buka `http://localhost:8000` di browser.

---

## 📸 Preview

| Halaman | Deskripsi |
|---------|-----------|
| Dashboard | Timer Pomodoro + stats XP |
| Focus Rooms | Daftar room belajar virtual |
| Room | YouTube player + timer + chat |
| Leaderboard | Ranking produktivitas |
| Profile | Stats & avatar customization |

---

## 🤝 Contributing

Pull request sangat terbuka! Silakan fork repo ini dan kembangkan ideamu.

1. Fork repository
2. Buat branch baru (`git checkout -b feature/NamaFitur`)
3. Commit perubahan (`git commit -m 'Add: NamaFitur'`)
4. Push ke branch (`git push origin feature/NamaFitur`)
5. Buat Pull Request

---

## 📌 Notes

Project ini dibuat sebagai bagian dari pembelajaran dan pengembangan portofolio di bidang web development.

---

## ⭐ Support

Kalau kamu suka project ini, jangan lupa kasih ⭐ di repository ya!

---

<div align="center">
  Made with ❤️ using Laravel
</div>
