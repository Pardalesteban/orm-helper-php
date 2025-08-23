# Orm Helper PHP

Small library to help you **build and execute SQL queries** easily with **PDO** in your PHP projects.

> *“This is a library for use in all PHP projects which you can create SQL sentences easily.”*

## ✨ Features

- Simple API for CRUD operations.  
- Based on **PDO** (compatible with MySQL/MariaDB, PostgreSQL, etc.).  
- Clear typing and explicit names.  
- Designed to integrate quickly into existing projects.  

## 🚦 Requirements

- PHP 8.0+ (8.1+ recommended).  
- `pdo` extension and the driver of your database (`pdo_mysql`, `pdo_pgsql`, etc.).  

## 📦 Installation

### Option A) When published on Packagist
```bash
composer require pardalesteban/orm-helper-php
```

### Option B) Using the VCS repository
Add to your `composer.json`:
```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/Pardalesteban/orm-helper-php"
    }
  ],
  "require": {
    "pardalesteban/orm-helper-php": "dev-main"
  }
}
```
and then:
```bash
composer update
```

## 🧭 For quick usage check the `tests` directory  

## 🤝 Contributing

PRs and issues are welcome!  

## 📄 License

[MIT](LICENSE)  
