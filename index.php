<?php
// index.php
require_once 'config/database.php';

$db = new Database();

// Buscar configurações
$logoSono = $db->getConfig('logo_sono');
$nextstationHome = $db->getConfig('nextstation_home');
$nextstationProdutos = $db->getConfig('nextstation_produtos');
$tituloHome = $db->getConfig('titulo_home');
$tituloSobre = $db->getConfig('titulo_sobre');
$tituloProdutos = $db->getConfig('titulo_produtos');
$tituloProduto = $db->getConfig('titulo_produto');
$textoHome = $db->getConfig('texto_home');
$textoSobre = $db->getConfig('texto_sobre');
$textoRodape = $db->getConfig('texto_rodape');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/estilo.css">
    <title>NextStation - SONO</title>
</head>
<body>
    <header>
        <img src="<?= htmlspecialchars($logoSono) ?>" alt="Logo SONO">
    </header>

    <nav style="background-color:#222121; padding: 10px; text-align:center;">
        <a href="#home" style="margin: 0 15px; text-decoration: none; ">Início</a>
        <a href="#sobre" style="margin: 0 15px; text-decoration: none; ">Sobre</a>
        <a href="#produtos" style="margin: 0 15px; text-decoration: none;">Produtos</a>
        <a href="#contato" style="margin: 0 15px; text-decoration: none;">Contato</a>
    </nav>

    <main>
        <section id="home" style="padding: 20px;">
            <h1><?= htmlspecialchars($tituloHome) ?></h1>
            <p><?= $textoHome ?></p>
            <img src="<?= htmlspecialchars($nextstationHome) ?>" alt="NextStation Vibe" style="width:100%; max-width:600px; display:block; margin:auto;">
        </section>

        <section id="sobre" style="padding: 20px; background-color: #232425;">
            <h2><?= htmlspecialchars($tituloSobre) ?></h2>
            <p><?= $textoSobre ?></p>
        </section>

        <section id="produtos" style="padding: 20px;">
            <h2><?= htmlspecialchars($tituloProdutos) ?></h2>
            <article style="display: flex; flex-direction: column; align-items: center;">
                <img src="<?= htmlspecialchars($nextstationProdutos) ?>" alt="NextStation Vibe" style="width: 80%; max-width: 500px;">
                <h3><?= htmlspecialchars($tituloProduto) ?></h3>
                <p>Um console portátil moderno com design ergonômico, desempenho potente e compatibilidade com seus jogos favoritos.</p>
            </article>
        </section>

        <section id="contato" style="padding: 20px; background-color: #0b0f1a;">
            <h2>Contato</h2>
            <br>

            <form style="display: flex; flex-direction: column; width: 100%; margin: auto;" action="contact.php" method="POST">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="mensagem">Mensagem:</label>
                <textarea id="mensagem" name="mensagem" rows="5" required></textarea>

                <button type="submit" style="margin-top: 10px; padding: 10px; background-color: #a0c5f7; border: none;">Enviar</button>
            </form>
        </section>
    </main>

    <footer>
        <p style="text-align: center; color: white; padding: 10px;"><?= htmlspecialchars($textoRodape) ?></p>
    </footer>
</body>
</html>