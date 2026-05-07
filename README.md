# TrackIT — IT Asset Management System

> A lightweight IT inventory management system for tracking company devices and assets.

Developed by **Mga Sit-in Sa Web Dev** · Brought to you by **Sinoy Technologies**

---

## Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [How It Works](#how-it-works)
- [Project Structure](#project-structure)
- [Contributing](#contributing)

---

## Overview

**TrackIT** is a web-based IT asset management system designed to help companies keep track of all their IT devices and inventory. It provides a simple, accessible interface for logging, viewing, and managing hardware assets across your organization.

---

## Tech Stack

| Layer    | Technology          |
|----------|---------------------|
| Database | MySQL (via phpMyAdmin) |
| Backend  | PHP                 |
| Frontend | HTML, CSS, JavaScript |
| Server   | Apache (via XAMPP)  |

---

## Prerequisites

Before getting started, make sure you have the following installed:

- [XAMPP](https://www.apachefriends.org/) (includes Apache & MySQL)
- A web browser (Chrome, Firefox, Edge, etc.)
- [Git](https://git-scm.com/)

---

## Installation

### 1. Clone the Repository

Navigate to your XAMPP `htdocs` directory and clone the project:

```bash
cd C:\xampp\htdocs
git clone https://github.com/ryuu-script/TrackIT.git
```

### 2. Start the XAMPP Server

1. Open the **XAMPP Control Panel**
2. Start **Apache**
3. Start **MySQL**

### 3. Set Up the Database

1. Open your browser and go to `http://localhost/phpmyadmin`
2. Create a new database (e.g., `trackit_db`)
3. The included PHP setup file will automatically populate the database with the required tables and initial values

### 4. Launch the Application

Open your browser and navigate to:

```
http://localhost/TrackIT/src/php/index.php
```

---

## How It Works

```
MySQL ──────► PHP ◄────── CSS / JavaScript
  │            │
  │          Logic &
  │         Routing
  ▼
Database
```

1. **Database Setup** — The user creates a MySQL database through phpMyAdmin
2. **Auto-seeding** — A dedicated PHP script automatically inserts the required schema and seed values into the database
3. **Usage** — Users can now freely access and interact with the web interface to manage IT assets

---

## Project Structure

```
TrackIT/
└── src/
    ├── php/          # Backend logic and routing
    ├── css/          # Stylesheets
    └── js/           # Client-side scripts
```

---

## Contributing

This project is maintained by **Mga Sit-in Sa Web Dev**. Contributions are limited to authorized team members only.
 
Each contributor has a dedicated branch. Please **do not push directly to `main`**.
 
1. Switch to your assigned branch (`git checkout <your-branch>`)
2. Make your changes and commit (`git commit -m 'Describe your change'`)
3. Push to your branch (`git push origin <your-branch>`)
4. Open a Pull Request to `main` for review

---

<p align="center">Made with ❤️ by Mga Sit-in Sa Web Dev · Sinoy Technologies</p>
