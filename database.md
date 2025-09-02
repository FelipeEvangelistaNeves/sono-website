-- Criação do banco de dados
CREATE DATABASE sono_website;
USE sono_website;

-- Tabela para configurações do site
CREATE TABLE site_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(50) NOT NULL UNIQUE,
    config_value TEXT,
    config_type ENUM('text', 'textarea', 'image', 'url') DEFAULT 'text',
    section VARCHAR(30) NOT NULL,
    label VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserindo dados padrão
INSERT INTO site_config (config_key, config_value, config_type, section, label) VALUES
-- Imagens
('logo_sono', 'img/logo sony.jpg', 'image', 'images', 'Logo SONO (Header)'),
('nextstation_home', 'img/logo.jpg', 'image', 'images', 'NextStation Vibe (Seção Home)'),
('nextstation_produtos', 'img/logo.jpg', 'image', 'images', 'NextStation Vibe (Seção Produtos)'),

-- Títulos
('titulo_home', 'Bem-vindo à SONO', 'text', 'titles', 'Título da Home'),
('titulo_sobre', 'Sobre a SONO', 'text', 'titles', 'Título da Seção Sobre'),
('titulo_produtos', 'Nosso Produto', 'text', 'titles', 'Título da Seção Produtos'),
('titulo_produto', 'NextStation Vibe', 'text', 'titles', 'Nome do Produto'),

-- Textos
('texto_home', 'Somos a SONO, uma empresa dedicada à inovação em entretenimento portátil. Conheça o nosso lançamento revolucionário: o <strong>NextStation Vibe</strong>.', 'textarea', 'texts', 'Texto da Home'),
('texto_sobre', 'A SONO nasceu com o objetivo de oferecer dispositivos que unem design, desempenho e liberdade. Nossa missão é criar produtos que acompanhem você em qualquer lugar, sem perder qualidade.', 'textarea', 'texts', 'Texto da Seção Sobre'),
('texto_rodape', '© 2025 SONO. Todos os direitos reservados.', 'text', 'texts', 'Texto do Rodapé');

-- Tabela para usuários admin (opcional)
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Inserindo usuário admin padrão (senha: admin123)
INSERT INTO admin_users (username, password, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@sono.com');

-- Tabela para logs de alterações
CREATE TABLE config_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(50) NOT NULL,
    old_value TEXT,
    new_value TEXT,
    admin_id INT,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admin_users(id)
);