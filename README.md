# Web Development Environment & CRUD Application

This repository contains two tasks:

---

## 📌 Task-1: Setting Up the Development Environment

*Objective:*  
Set up a local development environment for PHP and MySQL, and configure Git/GitHub for version control.

*Steps Completed:*
1. Installed *XAMPP* (Apache + MySQL). Verified installation at http://localhost.
2. Installed *VS Code* with PHP-related extensions.
3. Installed *Git* and set up a *GitHub repository*.
4. Initialized repository with basic project structure (index1.php, README1.md).

*Deliverables:*
- A working local server environment.
- A GitHub repo with initial commit (Task-1 files: index1.php, README1.md).

---

## 📌 Task-2: Basic CRUD Application with User Authentication

*Objective:*  
Develop a simple blog-style web application to perform CRUD operations with user login/logout.

*Database Setup:*
- Database: blog
- Tables:  
  - users (id, username, password)  
  - posts (id, title, content, created_at, user_id)

*Features Implemented:*
- *User Authentication:*
  - Registration with password_hash
  - Login with password_verify
  - Session management for login/logout
- *CRUD for Posts:*
  - Create: create.php
  - Read: index.php, view.php
  - Update: edit.php
  - Delete: delete.php
- *Extras:*
  - config.php for DB connection
  - header.php / footer.php for layout
  - db.sql for schema setup

 

*How to Run:*
1. Import the db.sql file into MySQL.
2. Update database credentials in config.php.
3. Start Apache & MySQL in XAMPP.
4. Run the app at: [http://localhost/crud_app/](http://localhost/crud_app/)

## 🔗 Localhost Links
Once your XAMPP server is running, open these in your browser:

- [Register](http://localhost/crud_app/register.php)  
- [Login](http://localhost/crud_app/login.php)  
- [Blog Home](http://localhost/crud_app/index.php)  

*Deliverables:*
- Functional CRUD app with authentication.
- Database schema and documentation in db.sql.

---

## 📂 Repo Structure

---

## ✅ Summary
- *Task-1:* Environment setup & GitHub repo.  
- *Task-2:* Blog CRUD application with authentication.  

Now the repo contains both tasks for review.