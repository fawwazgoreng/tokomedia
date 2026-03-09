# 🚀 Tokomedia

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

## 🤝 Contributing

1. Fork this repository
2. Create your feature branch: `git checkout -b feat/your-feature`
3. Commit your changes: `git commit -m 'feat: add your feature'`
4. Push to the branch: `git push origin feat/your-feature`
5. Open a Pull Request

---

## 📄 License
---

<div align="center">
  Made with ❤️ by <a href="https://github.com/fawwazalmumtaz">Muhammad Fawwaz Almumtaz</a>
</div>
