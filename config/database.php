<?php
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $dbh;
    private $error;

    public function __construct()
    {
        try {
            // First, connect without database to create it if needed
            $this->setupDatabase();

            // Then connect to the specific database
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8';
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );

            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }

    private function setupDatabase()
    {
        try {
            // Connect without specifying database
            $pdo = new PDO('mysql:host=' . $this->host . ';charset=utf8', $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create database if not exists
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . $this->dbname . "` CHARACTER SET utf8 COLLATE utf8_general_ci");
            $pdo->exec("USE `" . $this->dbname . "`");

            // Create tables
            $this->createTables($pdo);
            $this->insertDefaultData($pdo);
        } catch (PDOException $e) {
            die("Database setup error: " . $e->getMessage());
        }
    }

    private function createTables($pdo)
    {
        // Create users table with ENUM role
        $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(100) UNIQUE NOT NULL,
            `phone` VARCHAR(20) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `role` ENUM('user', 'admin') DEFAULT 'user',
            `is_deleted` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        // Create packages table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `packages` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `description` TEXT NOT NULL,
            `price` DECIMAL(10,2) NOT NULL,
            `is_deleted` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        // Create orders table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `orders` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `package_id` INT NOT NULL,
            `status` ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
            `total_amount` DECIMAL(10,2) NOT NULL,
            `is_deleted` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_user_id` (`user_id`),
            INDEX `idx_package_id` (`package_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        // Create messages table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `messages` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `subject` VARCHAR(200) NOT NULL,
            `message` TEXT NOT NULL,
            `reply` TEXT NULL,
            `is_replied` TINYINT(1) DEFAULT 0,
            `is_deleted` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `replied_at` TIMESTAMP NULL,
            INDEX `idx_user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        // Create gallery table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `gallery` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(200) NOT NULL,
            `filename` VARCHAR(255) NOT NULL,
            `is_deleted` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    private function insertDefaultData($pdo)
    {
        // Check if users already exist
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `users`");
        $stmt->execute();
        $userCount = $stmt->fetchColumn();

        // Only insert if no users exist
        if ($userCount == 0) {
            // Generate fresh password hashes setiap kali
            $admin1Password = password_hash('admin123', PASSWORD_DEFAULT);
            $admin2Password = password_hash('admin456', PASSWORD_DEFAULT);
            $userPassword = password_hash('user123', PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `phone`, `password`, `role`) VALUES (?, ?, ?, ?, ?)");

            // Admin 1
            $result1 = $stmt->execute(['Administrator', 'admin@lpkfujisanplus.com', '081234567890', $admin1Password, 'admin']);

            // Admin 2  
            $result2 = $stmt->execute(['Super Admin', 'superadmin@lpkfujisanplus.com', '081234567891', $admin2Password, 'admin']);

            // Insert 1 Regular User
            $result3 = $stmt->execute(['John Doe', 'user@lpkfujisanplus.com', '081234567892', $userPassword, 'user']);

            // Log hasil untuk debugging
            error_log("User creation results: Admin1=$result1, Admin2=$result2, User=$result3");
        }

        // Check and insert default package
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `packages` WHERE `is_deleted` = 0");
        $stmt->execute();
        $packageCount = $stmt->fetchColumn();

        if ($packageCount == 0) {
            $stmt = $pdo->prepare("INSERT INTO `packages` (`name`, `description`, `price`) VALUES (?, ?, ?)");
            $stmt->execute([
                'Paket Pelatihan Korea',
                'Pendaftaran pelatihan Bahasa & Budaya Korea, pemeriksaan kesehatan (MCU), dan ujian',
                2500000.00
            ]);
        }
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function resultset()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
}
