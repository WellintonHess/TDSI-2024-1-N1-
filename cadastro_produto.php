<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include('conexao.php');

$msg = "";
$tipoMsg = "";

/* ======================================================
   1. INSERIR OU ATUALIZAR PRODUTO
====================================================== */

if (isset($_POST['salvar'])) {

    $id = $_POST['id_produto'];

    // Campos principais
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $cor = trim($_POST['cor']);
    $codigo = trim($_POST['codigo']);
    $fabricante = trim($_POST['fabricante']);
    $preco = trim($_POST['preco']);

    // Campos opcionais
    $processador = trim($_POST['processador']);
    $ram = trim($_POST['ram']);
    $armazenamento = trim($_POST['armazenamento']);
    $tamanho_tela = trim($_POST['tamanho_tela']);
    $resolucao = trim($_POST['resolucao']);
    $sistema_ope = trim($_POST['sistema_ope']);

    // Estoque
    $minimo = (int)$_POST['minimo'];
    $quantidade = (int)$_POST['quantidade'];

    // Se estiver editando
    if (!empty($id)) {

        $sql = "UPDATE produtos SET 
                    nome='$nome',
                    descricao='$descricao',
                    cor='$cor',
                    codigo='$codigo',
                    fabricante='$fabricante',
                    preco='$preco',
                    processador='$processador',
                    ram='$ram',
                    armazenamento='$armazenamento',
                    tamanho_tela='$tamanho_tela',
                    resolucao='$resolucao',
                    sistema_ope='$sistema_ope',
                    quantidade_minima='$minimo'
                WHERE id_produto=$id";

        $acao = "atualizado";

    } else {

        $sql = "INSERT INTO produtos 
                (nome, descricao, cor, codigo, fabricante, preco, 
                 processador, ram, armazenamento, tamanho_tela, resolucao, sistema_ope,
                 quantidade_minima, quantidade_atual)
                VALUES (
                    '$nome', '$descricao', '$cor', '$codigo', '$fabricante', '$preco',
                    '$processador', '$ram', '$armazenamento', '$tamanho_tela', '$resolucao', '$sistema_ope',
                    '$minimo', '$quantidade'
                )";

        $acao = "cadastrado";
    }

    if ($conn->query($sql)) {
        $msg = "Produto $acao com sucesso!";
        $tipoMsg = "sucesso";
    } else {
        $msg = "Erro ao salvar o produto.";
        $tipoMsg = "erro";
    }

    // Limpar formulário
    $produtoEdit = [
        'id_produto' => '',
        'nome' => '',
        'descricao' => '',
        'cor' => '',
        'codigo' => '',
        'fabricante' => '',
        'preco' => '',
        'processador' => '',
        'ram' => '',
        'armazenamento' => '',
        'tamanho_tela' => '',
        'resolucao' => '',
        'sistema_ope' => '',
        'quantidade_minima' => '',
        'quantidade_atual' => ''
    ];
}

/* ======================================================
   2. EXCLUIR PRODUTO
====================================================== */

if (isset($_GET['excluir'])) {

    $id = $_GET['excluir'];

    if ($conn->query("DELETE FROM produtos WHERE id_produto=$id")) {
        $msg = "Produto excluído com sucesso!";
        $tipoMsg = "sucesso";
    } else {
        $msg = "Erro ao excluir produto.";
        $tipoMsg = "erro";
    }
}

/* ======================================================
   3. BUSCA DE PRODUTOS
====================================================== */

$busca = isset($_GET['busca']) ? $_GET['busca'] : '';

$sql = "SELECT * FROM produtos WHERE nome LIKE '%$busca%'";
$result = $conn->query($sql);

/* ======================================================
   4. CARREGAR PRODUTO PARA EDIÇÃO
====================================================== */

$produtoEdit = [
    'id_produto' => '',
    'nome' => '',
    'descricao' => '',
    'cor' => '',
    'codigo' => '',
    'fabricante' => '',
    'preco' => '',
    'processador' => '',
    'ram' => '',
    'armazenamento' => '',
    'tamanho_tela' => '',
    'resolucao' => '',
    'sistema_ope' => '',
    'quantidade_minima' => '',
    'quantidade_atual' => ''
];

if (isset($_GET['editar'])) {

    $idEditar = $_GET['editar'];
    $query = $conn->query("SELECT * FROM produtos WHERE id_produto=$idEditar");

    if ($query->num_rows > 0) {
        $produtoEdit = $query->fetch_assoc();
    }
}
?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastro de Produtos</title>
<link rel="stylesheet" href="style.css">
<style>
.msg {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 5px;
  text-align: center;
  font-weight: bold;
}
.msg.sucesso {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}
.msg.erro {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}
input[readonly] {
  background-color: #e9ecef;
  color: #6c757d;
}
</style>
</head>
<body>
<div class="container">
<h2>Cadastro de Produtos</h2>

<!-- Mensagem de feedback -->
<?php if (!empty($msg)): ?>
  <div class="msg <?= $tipoMsg ?>"><?= $msg ?></div>
<?php endif; ?>

<!-- Campo de busca -->
<form method="get" style="margin-bottom:10px;">
  <input type="text" name="busca" placeholder="Buscar produto..." value="<?= htmlspecialchars($busca) ?>">
  <button type="submit">Buscar</button>
</form>

<!-- Tabela de produtos -->
<table>
<tr>
  <th>ID</th>
  <th>Nome</th>
  <th>Fabricante</th>
  <th>Preço</th>
  <th>Qtd</th>
  <th>Ações</th>
</tr>

<?php if ($result->num_rows > 0): ?>
<?php while($p = $result->fetch_assoc()): ?>
<tr>
  <td><?= $p['id_produto'] ?></td>
  <td><?= htmlspecialchars($p['nome']) ?></td>
  <td><?= htmlspecialchars($p['fabricante']) ?></td>
  <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
  <td><?= $p['quantidade_atual'] ?></td>

  <td>
    <a href="?editar=<?= $p['id_produto'] ?>">✏️</a>
    <a href="?excluir=<?= $p['id_produto'] ?>" onclick="return confirm('Deseja excluir este produto?')">🗑️</a>
  </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="6">Nenhum produto encontrado.</td></tr>
<?php endif; ?>
</table>

<hr>
<h3><?= $produtoEdit['id_produto'] ? "Editar Produto" : "Adicionar Novo Produto" ?></h3>

<!-- Formulário de cadastro/edição -->
<form method="post">
  <input type="hidden" name="id_produto" value="<?= $produtoEdit['id_produto'] ?>">

  <input type="text" name="nome" placeholder="Nome" value="<?= htmlspecialchars($produtoEdit['nome']) ?>" required><br>

  <textarea name="descricao" placeholder="Descrição" required><?= htmlspecialchars($produtoEdit['descricao']) ?></textarea><br>

  <input type="text" name="cor" placeholder="Cor" value="<?= htmlspecialchars($produtoEdit['cor']) ?>"><br>

  <input type="text" name="codigo" placeholder="Código" value="<?= htmlspecialchars($produtoEdit['codigo']) ?>" required><br>

  <input type="text" name="fabricante" placeholder="Fabricante" value="<?= htmlspecialchars($produtoEdit['fabricante']) ?>" required><br>

  <input type="number" step="0.01" name="preco" placeholder="Preço R$" value="<?= htmlspecialchars($produtoEdit['preco']) ?>" required><br>

  <input type="text" name="processador" placeholder="Processador" value="<?= htmlspecialchars($produtoEdit['processador']) ?>"><br>

  <input type="text" name="ram" placeholder="RAM (ex: 8GB)" value="<?= htmlspecialchars($produtoEdit['ram']) ?>"><br>

  <input type="text" name="armazenamento" placeholder="Armazenamento (ex: 256GB SSD)" value="<?= htmlspecialchars($produtoEdit['armazenamento']) ?>"><br>

  <input type="text" name="tamanho_tela" placeholder="Tamanho da tela" value="<?= htmlspecialchars($produtoEdit['tamanho_tela']) ?>"><br>

  <input type="text" name="resolucao" placeholder="Resolução" value="<?= htmlspecialchars($produtoEdit['resolucao']) ?>"><br>

  <input type="text" name="sistema_ope" placeholder="Sistema Operacional" value="<?= htmlspecialchars($produtoEdit['sistema_ope']) ?>"><br>

  <input type="number" name="minimo" placeholder="Qtd Mínima" value="<?= htmlspecialchars($produtoEdit['quantidade_minima']) ?>" required><br>

  <input type="number" 
         name="quantidade" 
         placeholder="Qtd Atual" 
         value="<?= htmlspecialchars($produtoEdit['quantidade_atual']) ?>" 
         <?= $produtoEdit['id_produto'] ? 'readonly' : '' ?> 
         required><br>

  <button type="submit" name="salvar">Salvar</button>
</form>

<br>
<a href="index.php">⬅ Voltar ao menu principal</a>
</div>
</body>
</html>