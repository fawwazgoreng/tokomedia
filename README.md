# 🚀 Project Name

> Fullstack web application built with **react** (Frontend) + **Laravel** (Backend API)

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | vite.js  + react TypeScript |
| Styling | Tailwind CSS |
| Backend | Laravel 11 (REST API) |
| Database | MySQL / PostgreSQL |

---

## 📁 Project Structure

```
├── frontend/        # vite App
└── backend/         # Laravel API
```

---

## ⚙️ Prerequisites

Make sure you have these installed:

- **Node.js** v18+ & **npm** / **bun**
- **PHP** v8.2+
- **Composer**
- **MySQL** or **PostgreSQL**

---

## 🖥️ Frontend — Vite

### Setup

```bash
cd frontend
npm install
# or
bun install
```

### Environment

```bash
cp .env.example .env.local
```

```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

### Run Development Server

```bash
npm run dev
# or
bun dev
```

Open [http://localhost:3000](http://localhost:3000) in your browser.

### Build for Production

```bash
npm run build
npm run start
```

---

## 🔧 Backend — Laravel

### Setup

```bash
cd backend
composer install
```

### Environment

```bash
cp .env.example .env
php artisan key:generate
```

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Database Migration

```bash
php artisan migrate
# with seeders (optional)
php artisan migrate --seed
```

### Run Development Server

```bash
php artisan serve
```

API will be available at [http://localhost:8000](http://localhost:8000)

---

## 🔗 API Endpoints

Base URL: `http://localhost:8000/api`

---

### 🔐 Authentication

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/admin/register` | ❌ | Register admin baru |
| POST | `/admin/login` | ❌ | Login admin, returns token |
| POST | `/admin/logout` | ✅ Sanctum | Logout & revoke token |
| GET | `/user` | ✅ Sanctum | Get authenticated user |

**Contoh login & penggunaan token:**

```bash
# 1. Login
POST /api/admin/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
# Response: { "token": "1|abc123..." }

# 2. Pakai token di header untuk endpoint protected
Authorization: Bearer {token}
```

---

### 👤 Admin

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin` | List semua admin |
| POST | `/admin` | Tambah admin |
| GET | `/admin/{id}` | Detail admin |
| PUT/PATCH | `/admin/{id}` | Update admin |
| DELETE | `/admin/{id}` | Hapus admin |

---

### 🖼️ Slide

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/slide` | List semua slide |
| POST | `/slide` | Tambah slide |
| GET | `/slide/{id}` | Detail slide |
| PUT/PATCH | `/slide/{id}` | Update slide |
| DELETE | `/slide/{id}` | Hapus slide |

---

### 🏫 About

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/about` | List data about |
| POST | `/about` | Tambah about |
| GET | `/about/{id}` | Detail about |
| PUT/PATCH | `/about/{id}` | Update about |
| DELETE | `/about/{id}` | Hapus about |

---

### 📋 Program Kerja

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/programkerja` | List semua program kerja |
| POST | `/programkerja` | Tambah program kerja |
| GET | `/programkerja/{id}` | Detail program kerja |
| PUT/PATCH | `/programkerja/{id}` | Update program kerja |
| DELETE | `/programkerja/{id}` | Hapus program kerja |

---

### 🏫 Program Sekolah

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/programsekolah` | List semua program sekolah |
| POST | `/programsekolah` | Tambah program sekolah |
| GET | `/programsekolah/{id}` | Detail program sekolah |
| PUT/PATCH | `/programsekolah/{id}` | Update program sekolah |
| DELETE | `/programsekolah/{id}` | Hapus program sekolah |

---

### 📰 Berita

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/berita` | List semua berita |
| POST | `/berita` | Tambah berita |
| GET | `/berita/{id}` | Detail berita |
| PUT/PATCH | `/berita/{id}` | Update berita |
| DELETE | `/berita/{id}` | Hapus berita |

---

### 🎯 Visi & Misi

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/visimisi` | List visi misi |
| POST | `/visimisi` | Tambah visi misi |
| GET | `/visimisi/{id}` | Detail visi misi |
| PUT/PATCH | `/visimisi/{id}` | Update visi misi |
| DELETE | `/visimisi/{id}` | Hapus visi misi |

---

### 🎓 Kesiswaan

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/kesiswaan` | List data kesiswaan |
| POST | `/kesiswaan` | Tambah kesiswaan |
| GET | `/kesiswaan/{id}` | Detail kesiswaan |
| PUT/PATCH | `/kesiswaan/{id}` | Update kesiswaan |
| DELETE | `/kesiswaan/{id}` | Hapus kesiswaan |

---

### 📜 Sejarah

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/sejarah` | List data sejarah |
| POST | `/sejarah` | Tambah sejarah |
| GET | `/sejarah/{id}` | Detail sejarah |
| PUT/PATCH | `/sejarah/{id}` | Update sejarah |
| DELETE | `/sejarah/{id}` | Hapus sejarah |

---

### 🏆 Prestasi

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/prestasi` | List semua prestasi |
| POST | `/prestasi` | Tambah prestasi |
| GET | `/prestasi/{id}` | Detail prestasi |
| PUT/PATCH | `/prestasi/{id}` | Update prestasi |
| DELETE | `/prestasi/{id}` | Hapus prestasi |

---

### 🏗️ Fasilitas

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/fasilitas` | List semua fasilitas |
| POST | `/fasilitas` | Tambah fasilitas |
| GET | `/fasilitas/{id}` | Detail fasilitas |
| PUT/PATCH | `/fasilitas/{id}` | Update fasilitas |
| DELETE | `/fasilitas/{id}` | Hapus fasilitas |

---

### 🎭 Ekstrakurikuler

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/ekstra` | List semua ekskul |
| POST | `/ekstra` | Tambah ekskul |
| GET | `/ekstra/{id}` | Detail ekskul |
| PUT/PATCH | `/ekstra/{id}` | Update ekskul |
| DELETE | `/ekstra/{id}` | Hapus ekskul |

---

## 🤝 Contributing

1. Fork this repository
2. Create your feature branch: `git checkout -b feat/your-feature`
3. Commit your changes: `git commit -m 'feat: add your feature'`
4. Push to the branch: `git push origin feat/your-feature`
5. Open a Pull Request

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

<div align="center">
  Made with ❤️ by <a href="https://github.com/fawwazalmumtaz">Muhammad Fawwaz Almumtaz</a>
</div>
