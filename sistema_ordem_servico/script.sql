CREATE DATABASE IF NOT EXISTS jm_informatica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jm_informatica;

-- Tabela de Usuários (user)
CREATE TABLE IF NOT EXISTS user (
    id_user BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Tamanho ajustado para hash de senha seguro
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ativo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- Tabela de Serviços (service)
CREATE TABLE IF NOT EXISTS service (
    id_service BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(45) NOT NULL,
    price DECIMAL(11,3) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    finished_at DATETIME NULL DEFAULT NULL,
    commission_user DECIMAL(11,3) NULL DEFAULT 0.000,
    user_id_user BIGINT(20) NOT NULL,
    FOREIGN KEY (user_id_user) REFERENCES user(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;