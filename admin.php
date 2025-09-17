<?php
// admin.php
session_start();
require_once 'config/database.php';

$db = new Database();

// Verificar se está logado
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Processar formulário se enviado
if ($_POST) {
    $success = true;
    $errors = [];
    
    foreach ($_POST as $key => $value) {
        if ($key !== 'action') {
            if (!$db->updateConfig($key, $value, $_SESSION['admin_id'])) {
                $success = false;
                $errors[] = "Erro ao atualizar $key";
            }
        }
    }
    
    if ($success) {
        $message = "Configurações atualizadas com sucesso!";
    }
}

// Buscar todas as configurações
$configs = $db->getAllConfigs();
$configData = [];
foreach ($configs as $config) {
    $configData[$config['config_key']] = $config;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - SONO</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1> Painel Administrativo SONO</h1>
            <div class="header-actions">
                <a href="index.php" class="btn btn-secondary" target="_blank"> Ver Site</a>
                <a href="logout.php" class="btn btn-danger">Sair</a>
            </div>
        </div>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="tabs">
            <button class="tab-button active" onclick="showTab('editor')"> Editor</button>
            <button class="tab-button" onclick="showTab('preview')"> Preview</button>
        </div>
        
        <div id="editor" class="tab-content active">
            <form method="POST" id="contentForm">
                <div class="section-title">Imagens</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="logo_sono"><?= $configData['logo_sono']['label'] ?></label>
                        <input type="url" id="logo_sono" name="logo_sono" value="<?= htmlspecialchars($configData['logo_sono']['config_value']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="nextstation_home"><?= $configData['nextstation_home']['label'] ?></label>
                        <input type="url" id="nextstation_home" name="nextstation_home" value="<?= htmlspecialchars($configData['nextstation_home']['config_value']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="nextstation_produtos"><?= $configData['nextstation_produtos']['label'] ?></label>
                        <input type="url" id="nextstation_produtos" name="nextstation_produtos" value="<?= htmlspecialchars($configData['nextstation_produtos']['config_value']) ?>">
                    </div>
                </div>
                
                <div class="section-title"> Títulos</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="titulo_home"><?= $configData['titulo_home']['label'] ?></label>
                        <input type="text" id="titulo_home" name="titulo_home" value="<?= htmlspecialchars($configData['titulo_home']['config_value']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="titulo_sobre"><?= $configData['titulo_sobre']['label'] ?></label>
                        <input type="text" id="titulo_sobre" name="titulo_sobre" value="<?= htmlspecialchars($configData['titulo_sobre']['config_value']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="titulo_produtos"><?= $configData['titulo_produtos']['label'] ?></label>
                        <input type="text" id="titulo_produtos" name="titulo_produtos" value="<?= htmlspecialchars($configData['titulo_produtos']['config_value']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="titulo_produto"><?= $configData['titulo_produto']['label'] ?></label>
                        <input type="text" id="titulo_produto" name="titulo_produto" value="<?= htmlspecialchars($configData['titulo_produto']['config_value']) ?>">
                    </div>
                </div>
                
                <div class="section-title">Textos</div>
                <div class="form-group">
                    <label for="texto_home"><?= $configData['texto_home']['label'] ?></label>
                    <textarea id="texto_home" name="texto_home"><?= htmlspecialchars($configData['texto_home']['config_value']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="texto_sobre"><?= $configData['texto_sobre']['label'] ?></label>
                    <textarea id="texto_sobre" name="texto_sobre"><?= htmlspecialchars($configData['texto_sobre']['config_value']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="texto_rodape"><?= $configData['texto_rodape']['label'] ?></label>
                    <input type="text" id="texto_rodape" name="texto_rodape" value="<?= htmlspecialchars($configData['texto_rodape']['config_value']) ?>">
                </div>
                
                <button type="submit" class="save-button"> Salvar Alterações</button>
            </form>
        </div>
        
        <div id="preview" class="tab-content">
            <iframe id="previewFrame" class="preview-frame" src="index.php"></iframe>
        </div>
    </div>
    
    <script src="assets/js/admin.js"></script>
</body>
</html>