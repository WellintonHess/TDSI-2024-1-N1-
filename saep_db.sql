-- ================================================
-- SCRIPT DE CRIAÇÃO DO BANCO DE DADOS: saep_db
-- Sistema: Controle de Estoque de eletronicos
-- Tecnologias: PHP, MySQL, HTML, CSS
-- ================================================

-- Remove o banco existente (para recriar do zero)
DROP DATABASE IF EXISTS saep_db;

-- Cria o banco
CREATE DATABASE saep_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE saep_db;

-- ================================================
-- TABELA DE USUÁRIOS
-- ================================================
CREATE TABLE usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL
);

-- Usuários iniciais
INSERT INTO usuarios (nome, email, senha) VALUES
('Administrador', 'admin@serjao.com', MD5('12345')),
('João Silva', 'joao@serjao.com', MD5('12345')),
('Maria Souza', 'maria@serjao.com', MD5('12345')),
('Aluno', 'aluno@serjao.com', MD5('123')),
('Sergio Luiz', 'sergio@serjao.com', MD5('123'));

-- ================================================
-- TABELA DE PRODUTOS
-- ================================================
CREATE TABLE produtos (
  id_produto INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT NOT NULL,
  cor VARCHAR (10) NOT NULL,
  codigo VARCHAR(50) NOT NULL,
  fabricante VARCHAR(20) NOT NULL,
  preco DECIMAL NOT NULL,
  processador VARCHAR(50) NULL,
  ram VARCHAR (10) NULL,
  armazenamento VARCHAR(50) NULL,
  tamanho_tela VARCHAR(50) NULL,
  resolucao VARCHAR(50) NULL,
  sistema_ope VARCHAR(50) NULL,
  quantidade_minima INT DEFAULT 0,
  quantidade_atual INT DEFAULT 0
);

-- Produtos iniciais
INSERT INTO produtos (nome, descricao, cor, codigo, fabricante, preco, processador, ram, armazenamento, tamanho_tela, resolucao, sitema_ope, quantidade_minima, quantidade_atual) VALUES
('Iphone 17', 'iPhone 17 com novo design e câmera avançada', ' cor preto', '323456754', 'Apple', 8000.00,'A18 Bionic','8gb','256gb','6.3','2796 x 1290','iOS 18','7','21'),
('mac book air 13', 'o MacBook Air se encaixa facilmente na correria da sua rotina.', 'cinza', '108122401993','Apple',9000.00,'chip Apple M2','16gb','256gb','2560 x 1664','13,6 polegadas','macOS', '10', '20'),
('TV LG 55', 'Smart TV LG 55 polegadas 4K UHD com inteligência artificial e HDR', 'preto', '2349349765', 'LG', 2899.90,'α7 Gen5 AI Processor 4K','2gb','16GB','55','3840 x 2160','webOS 23','9','30');

-- ================================================
-- TABELA DE MOVIMENTAÇÕES
-- ================================================
CREATE TABLE movimentacoes (
  id_movimentacao INT AUTO_INCREMENT PRIMARY KEY,
  id_produto INT NOT NULL,
  tipo ENUM('entrada', 'saida') NOT NULL,
  quantidade INT NOT NULL,
  data_movimentacao DATE NOT NULL,
  id_usuario INT NOT NULL,
  FOREIGN KEY (id_produto) REFERENCES produtos(id_produto),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Movimentações iniciais
INSERT INTO movimentacoes (id_produto, tipo, quantidade, data_movimentacao, id_usuario) VALUES
(1, 'entrada', 10, '2024-09-01', 1),
(1, 'saida', 5, '2024-09-05', 2),
(2, 'entrada', 3, '2024-09-03', 3),
(2, 'saida', 1, '2024-09-06', 2),
(3, 'entrada', 4, '2024-09-04', 1),
(3, 'saida', 2, '2024-09-07', 3);

-- ================================================
-- CONSULTAS DE TESTE (opcional)
-- ================================================
-- Listar todos os produtos
SELECT * FROM produtos;

-- Listar histórico de movimentações com nomes
SELECT m.id_movimentacao, p.nome AS produto, m.tipo, m.quantidade, 
       m.data_movimentacao, u.nome AS usuario
FROM movimentacoes m
INNER JOIN produtos p ON m.id_produto = p.id_produto
INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
ORDER BY m.data_movimentacao DESC;