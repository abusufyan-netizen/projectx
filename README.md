# Leads Academic Resource & Research Hub

A premium, modern Academic Repository and Collaboration Portal designed for university environments. This platform allows students and faculty to share, discover, and collaborate on academic resources with a high-performance, glassmorphism-inspired UI.

![Premium UI Mockup](https://raw.githubusercontent.com/abusufyan-netizen/projectx/main/public/assets/img/mockup_preview.png) *(Note: Add your own screenshot link here)*

## 🚀 Features

### 💎 Premium User Experience
- **Vibrant Light Theme**: A state-of-the-art interface using glassmorphism, fluid animations, and the Outfit typography.
- **Sleek Sidebar Navigation**: Intuitive role-based navigation for seamless access to all portal features.
- **Dynamic Dashboard**: Welcome screen with personalized stats for students and faculty.

### 📚 Resource Management
- **Centralized Repository**: Search and filter academic materials by title, category, department, or semester.
- **Version Control & Forking**: Fork existing resources to create new versions, just like Git.
- **Collaboration Hub**: Submit Pull Requests (PRs) to suggest improvements to resources.
- **Nested Discussions**: Integrated comment system for academic discourse on specific resources.

### 🛡️ Admin Center
- **User Management**: Control roles (Admin, Faculty, Student) and account statuses (Active, Pending, Suspended).
- **Academic Controls**: CRUD operations for university Departments and Semesters.
- **Role Assignments**: Dynamically assign users to specific departments and semesters.

## 🛠️ Technology Stack
- **Backend**: Vanilla PHP (MVC Architecture)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3 (Vanilla + Glassmorphism), Bootstrap 5, Bootstrap Icons
- **Deployment**: Optimized for XAMPP (Local) and InfinityFree (Production)

## 📦 Installation & Setup

### Local Setup (XAMPP)
1. Clone the repository into your `htdocs` folder.
2. Import `database/schema.sql` into your local phpMyAdmin.
3. Configure your local database credentials in `src/Core/Config.php`.
4. Access the portal at `http://localhost/ProjectX/public/`.

### Production Deployment (InfinityFree)
1. Export your local database and import it into your InfinityFree MySQL database.
2. Update `src/Core/Config.php` with your live database credentials and set `BASE_URL` to `''`.
3. Upload all files to the `htdocs` directory on your InfinityFree server via FTP.

## 📄 License
This project is for academic and research purposes.

---
Built with ❤️ for Leads University.


---\n*Last deployed: 2026-04-30*
