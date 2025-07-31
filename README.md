# crud-sistem-ujian-online
Menyediakan platform berbasis web untuk menyelenggarakan ujian secara online dengan fitur manajemen ujian, peserta, soal, dan analisis hasil ujian yang terstruktur dan efisien.
# Panduan Setup Sistem Ujian Online Laravel  
## 1. Requirements 
- Laravel 11
- PHP >= 8.4
- Composer
- MySQL
- Node.js & NPM
  
##Cara Setup:
- Clone/Create project Laravel
- Setup database di file .env
- Jalankan migrations: php artisan migrate
- Jalankan seeders: php artisan db:seed
- Start server: php artisan serve

##Default Login:
- Admin: admin@example.com / password
- Student: student@example.com / password
  
Fitur
✅ Authentication System
- Login/Register untuk siswa
- Role-based access (Admin/Student)
- Session management
- Logout functionality

✅ Exam Management (Admin)
- Create, Read, Update, Delete ujian
- Manage status ujian (aktif/nonaktif)
- Set durasi ujian

✅ Question Management (Admin)
- Add questions to exams
- Multiple choice options (2-5 options)
- Mark correct answers
- Edit/Delete questions

✅ Exam Taking System (Student)
- View available exams
- Start exam with confirmation
- Timer countdown with warnings
- Question navigation
- Auto-save answers via AJAX
- Auto-submit when time expires
-Prevent page refresh during exam

✅ Results System
- Immediate score calculation
- Detailed answer review
- Performance statistics
- Grade categorization

✅ Dashboard
- Student dashboard with statistics
- Admin dashboard with overview
- Recent activities
- Quick actions
