<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"/>
  <img src="https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS"/>
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License"/>
</p>

# 🔧 WorkConnect — Local Service Booking Platform

**WorkConnect** is a full-stack web application that connects customers with verified local service professionals. Built with PHP, MySQL, and TailwindCSS, it features role-based dashboards, a real-time booking workflow, admin approval system, and a modern dark-mode UI.

> 🌐 **Live Demo:** [workconnect.great-site.net](https://workconnect.great-site.net)

---

## ✨ Key Features

| Feature | Description |
|---|---|
| **3 Role Dashboards** | Separate interfaces for Customer, Worker, and Admin |
| **Booking System** | Customers search, book, and track worker appointments |
| **Admin Approval** | Workers require admin verification before activation |
| **Worker Search** | Filter professionals by skill, location, and availability |
| **Secure Auth** | `password_hash()` + session-based access control |
| **Dark Mode UI** | Modern glassmorphism design with TailwindCSS |
| **3D Landing Page** | Animated hero with particle effects and perspective tilt |

---

## 🏗️ Architecture

```
┌─────────────┐     ┌──────────────┐     ┌──────────────┐
│  Frontend   │────▶│   PHP 7.4+   │────▶│   MySQL DB   │
│  HTML/CSS/JS│     │  Business    │     │ localworks_db│
│  TailwindCSS│◀────│  Logic Layer │◀────│  4 Tables    │
└─────────────┘     └──────────────┘     └──────────────┘
```

### Database Schema
- **`customers`** — User accounts, contact info
- **`workers`** — Professional profiles, rates, approval status
- **`bookings`** — Service requests with status tracking
- **`admin_users`** — Administrator credentials

---

## 📁 Project Structure

```
WorkConnect/
├── index.html                # 3D animated landing page
├── config.php                # DB connection & session init
├── database.sql              # Full database schema
│
├── customer_login.php        # Customer authentication
├── customer_register.php     # Customer registration
├── customer_dashboard.php    # Customer home
├── customer_bookings.php     # Booking history
│
├── worker_login.php          # Worker authentication
├── worker_register.php       # Worker onboarding
├── worker_dashboard.php      # Worker home
├── worker_profile.php        # Public profile view
├── worker_profile_edit.php   # Profile editor
├── worker_respond_booking.php# Accept/reject bookings
│
├── admin_login.php           # Admin authentication
├── admin_dashboard.php       # Platform statistics
├── admin_workers.php         # Worker approval management
├── admin_customers.php       # Customer oversight
├── admin_bookings.php        # All bookings monitor
│
├── search_workers.php        # Worker search & filter
├── book_worker.php           # Create new booking
└── logout.php                # Session cleanup
```

---

## 🚀 Quick Start

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP)
- Web browser (Chrome/Firefox/Safari)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/Poovaragavan2004/WorkConnect.git

# 2. Move to XAMPP's htdocs
cp -r WorkConnect /Applications/XAMPP/xamppfiles/htdocs/

# 3. Start Apache & MySQL from XAMPP Control Panel

# 4. Create the database
#    Open http://localhost/phpmyadmin
#    Create database: localworks_db
#    Import: database.sql

# 5. Open the app
#    http://localhost/WorkConnect/
```

### Default Admin Login
| Field | Value |
|-------|-------|
| Username | `admin` |
| Password | `admin123` |

---

## 👥 User Flows

### Customer Flow
```
Register → Login → Search Workers → View Profile → Book → Track Status
```

### Worker Flow
```
Register → Wait for Admin Approval → Login → View Bookings → Accept/Reject → Update Profile
```

### Admin Flow
```
Login → Dashboard Stats → Approve/Reject Workers → Monitor Bookings → Manage Users
```

---

## 🔒 Security

- **Password Hashing** — `password_hash()` with `PASSWORD_DEFAULT` (bcrypt)
- **SQL Injection Prevention** — `mysqli_real_escape_string()` on all inputs
- **Session Management** — Server-side sessions with role validation
- **Access Control** — Role-based page protection

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, JavaScript, TailwindCSS 3.x |
| Backend | PHP 7.4+ |
| Database | MySQL 5.7+ / MariaDB |
| Icons | Google Material Symbols |
| Typography | Inter (Google Fonts) |
| Server | Apache (XAMPP) |

---

## 📄 License

This project is developed as part of the **KGASC** academic curriculum.

---

<p align="center">
  Made with ❤️ by <a href="https://github.com/Poovaragavan2004">Poovaragavan</a>
</p>
