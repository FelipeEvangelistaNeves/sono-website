<?php
// config/database.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sono_website');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database {
    private $connection;
    
    public function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Buscar todas as configurações
    public function getAllConfigs() {
        $stmt = $this->connection->prepare("SELECT * FROM site_config ORDER BY section, id");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar configuração por chave
    public function getConfig($key) {
        $stmt = $this->connection->prepare("SELECT config_value FROM site_config WHERE config_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['config_value'] : '';
    }
    
    // Atualizar configuração
    public function updateConfig($key, $value, $adminId = null) {
        // Buscar valor antigo para log
        $oldValue = $this->getConfig($key);
        
        // Atualizar configuração
        $stmt = $this->connection->prepare("UPDATE site_config SET config_value = ?, updated_at = NOW() WHERE config_key = ?");
        $success = $stmt->execute([$value, $key]);
        
        // Registrar log se necessário
        if ($success && $adminId) {
            $this->logConfigChange($key, $oldValue, $value, $adminId);
        }
        
        return $success;
    }
    
    // Registrar mudança no log
    private function logConfigChange($key, $oldValue, $newValue, $adminId) {
        $stmt = $this->connection->prepare("INSERT INTO config_logs (config_key, old_value, new_value, admin_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$key, $oldValue, $newValue, $adminId]);
    }
    
    // Verificar usuário admin
    public function verifyAdmin($username, $password) {
        $stmt = $this->connection->prepare("SELECT id, password FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Atualizar último login
            $updateStmt = $this->connection->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            
            return $user['id'];
        }
        
        return false;
    }
}
?>