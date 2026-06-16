# Smart Personal Portfolio with Admin Control

A dynamic portfolio management system built using PHP and MySQL that allows users to create and manage their professional portfolio through an easy-to-use dashboard. The platform helps users showcase their skills, projects, blogs, resume, and achievements while giving administrators complete control over portfolio content and user management.

## About the Project

I built this project to gain hands-on experience with real-world web development concepts such as authentication, role-based access control, database management, file uploads, email integration, and dashboard development.

Instead of maintaining a static portfolio website, users can update their profile, projects, skills, blogs, and resume directly from the dashboard without modifying any code.

## Key Features

### User Module

* User Registration and Login
* Secure Password Authentication
* Personal Dashboard
* Profile and About Section Management
* Skills Management
* Project Showcase
* Blog Access
* Resume Upload
* Contact Form
* Change Password

### Admin Module

* Admin Dashboard
* User Management
* Project Management
* Skills Management
* Blog Management
* Contact Message Monitoring
* Portfolio Content Management

### Additional Features

* Password Hashing
* Session Management
* PHPMailer Integration
* Responsive Design
* MySQL Database Connectivity

## Technologies Used

* PHP
* MySQL
* HTML5
* CSS3
* JavaScript
* PHPMailer
* Composer

## Project Structure

```text
smart_portfolio/
├── admin/
├── assets/
├── config/
├── public/
├── user/
├── vendor/
├── PHPMailer/
├── login.php
├── register.php
├── logout.php
├── contact.php
└── composer.json
```

## Default Admin Credentials

```text
Email: admin@portfolio.com
Password: admin123
```

> It is recommended to change the default password before deployment.

## Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/Satyam322/SkillSphere-Portfolio.git
```

### 2. Move the Project Folder

Place the project folder inside:

```text
C:\xampp\htdocs\
```

### 3. Create a Database

Create a MySQL database using phpMyAdmin.

### 4. Configure Database Connection

Update database credentials inside:

```text
config/db.php
```

### 5. Install Dependencies

```bash
composer install
```

### 6. Start the Server

Start Apache and MySQL from the XAMPP Control Panel.

### 7. Run the Application

```text
http://localhost/smart_portfolio/public/
```

## Security Features

* Password Hashing
* Session-Based Authentication
* Role-Based Access Control
* Prepared Statements
* Input Validation

## Problem Solved

Many students and freelancers maintain static portfolio websites that require manual code changes whenever they want to update projects, skills, blogs, resumes, or personal information.

This project solves that problem by providing a dashboard-driven system where content can be managed dynamically without editing source code, making portfolio management easier and more efficient.

## What I Learned

Through this project, I gained practical experience with:

* PHP and MySQL Development
* Authentication and Authorization
* Session Management
* CRUD Operations
* File Upload Handling
* Email Integration using PHPMailer
* Database Design
* Git and GitHub

## Developer

**Satyam Vishwakarma**

Passionate about web development and continuously learning new technologies to build secure, scalable, and user-friendly applications.

## License

This project was developed for educational and portfolio purposes.
