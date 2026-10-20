CREATE TABLE IF NOT EXISTS `solicitacoes_primeiro_acesso` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_usuario` int(11) NOT NULL,
    `senha_hash` varchar(255) DEFAULT NULL,
    `status` enum('pendente', 'aprovado', 'recusado') NOT NULL DEFAULT 'pendente',
    `data_solicitacao` datetime NOT NULL DEFAULT current_timestamp(),
    `id_aprovador` int(11) DEFAULT NULL,
    `data_decisao` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_solicitacoes_primeiro_acesso_status_data` (`status`, `data_solicitacao`),
    KEY `idx_solicitacoes_primeiro_acesso_usuario_status` (`id_usuario`, `status`),
    CONSTRAINT `fk_solicitacoes_primeiro_acesso_usuario`
        FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_solicitacoes_primeiro_acesso_aprovador`
        FOREIGN KEY (`id_aprovador`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
