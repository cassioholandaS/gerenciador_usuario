<?php
require_once('sessions.php');
$session = new sessions();
$session->autenticar_session();
$session->tempo_session();

require_once(__DIR__ . '/../config/connect.php');

class admin extends connect
{
    private string $table1;
    private string $table2;
    private string $table3;
    private string $table4;
    private string $table5;

    function __construct()
    {
        parent::__construct();
        require(__DIR__ . '/../../../../.env/tables.php');
        $this->table1 = $table['salaberga_users'][1];
        $this->table2 = $table['salaberga_users'][2];
        $this->table3 = $table['salaberga_users'][3];
        $this->table4 = $table['salaberga_users'][4];
        $this->table5 = $table['salaberga_users'][5];
    }
    // ==================== FUNÇÕES PARA SETORES ====================

    /**
     * Criar novo setor
     */
    public function criar_setor($nome)
    {
        try {
            // Verificar se o setor já existe
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table2} WHERE nome = :nome");
            $stmt_check->bindValue(":nome", trim($nome));
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                return 0;
            }

            $stmt = $this->connect->prepare("INSERT INTO {$this->table2} (nome) VALUES (:nome)");
            $stmt->bindValue(":nome", trim($nome));

            if ($stmt->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            error_log("Erro ao criar setor: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Editar setor existente
     */
    public function editar_setor($id, $nome)
    {
        try {
            // Verificar se o novo nome já existe em outro setor
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table2} WHERE nome = :nome AND id != :id");
            $stmt_check->bindValue(":nome", trim($nome));
            $stmt_check->bindValue(":id", $id);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                3;
            }

            $stmt = $this->connect->prepare("UPDATE {$this->table2} SET nome = :nome WHERE id = :id");
            $stmt->bindValue(":nome", trim($nome));
            $stmt->bindValue(":id", $id);

            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            error_log("Erro ao editar setor: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Excluir setor
     */
    public function excluir_setor($id)
    {
        try {
            // Verificar se há usuários usando este setor
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table1} WHERE id_setor = :id");
            $stmt_check->bindValue(":id", $id);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {

                $stmt_check = $this->connect->prepare("UPDATE {$this->table1} SET id_setor = NULL WHERE id_setor = :id");
                $stmt_check->bindValue(":id", $id);
                $stmt_check->execute();
            }

            $stmt = $this->connect->prepare("DELETE FROM {$this->table2} WHERE id = :id");
            $stmt->bindValue(":id", $id);

            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            error_log("Erro ao excluir setor: " . $e->getMessage());
            return 0;
        }
    }

    public function criar_tipo_usuario($nome)
    {
        try {
            // Verificar se o tipo já existe
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table3} WHERE tipo = :tipo");
            $stmt_check->bindValue(":tipo", trim($nome));
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                return 3;
            }

            $stmt = $this->connect->prepare("INSERT INTO {$this->table3} (tipo) VALUES (:tipo)");
            $stmt->bindValue(":tipo", trim($nome));

            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            error_log("Erro ao criar tipo de usuário: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Editar tipo de usuário existente
     */
    public function editarTipoUsuario(int $id, string $tipo): array
    {
        try {
            // Verificar se o tipo existe
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table3} WHERE id = :id");
            $stmt_check->bindValue(":id", $id);
            $stmt_check->execute();

            if ($stmt_check->rowCount() == 0) {
                return ['success' => false, 'message' => 'Tipo de usuário não encontrado'];
            }

            // Verificar se o novo tipo já existe em outro registro
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table3} WHERE tipo = :tipo AND id != :id");
            $stmt_check->bindValue(":tipo", trim($tipo));
            $stmt_check->bindValue(":id", $id);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                return ['success' => false, 'message' => 'Tipo de usuário já existe'];
            }

            $stmt = $this->connect->prepare("UPDATE {$this->table3} SET tipo = :tipo WHERE id = :id");
            $stmt->bindValue(":tipo", trim($tipo));
            $stmt->bindValue(":id", $id);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Tipo de usuário atualizado com sucesso'];
            } else {
                return ['success' => false, 'message' => 'Erro ao atualizar tipo de usuário'];
            }
        } catch (Exception $e) {
            error_log("Erro ao editar tipo de usuário: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro interno do sistema'];
        }
    }

    /**
     * Excluir tipo de usuário
     */
    public function excluirTipoUsuario(int $id): array
    {
        try {
            // Verificar se há permissões usando este tipo
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table4} WHERE id_tipos_usuarios = :id");
            $stmt_check->bindValue(":id", $id);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                return ['success' => false, 'message' => 'Não é possível excluir o tipo. Existem permissões vinculadas a ele.'];
            }

            $stmt = $this->connect->prepare("DELETE FROM {$this->table3} WHERE id = :id");
            $stmt->bindValue(":id", $id);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Tipo de usuário excluído com sucesso'];
            } else {
                return ['success' => false, 'message' => 'Erro ao excluir tipo de usuário'];
            }
        } catch (Exception $e) {
            error_log("Erro ao excluir tipo de usuário: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro interno do sistema'];
        }
    }




    // ==================== FUNÇÕES PARA USUÁRIOS ====================

    /**
     * Criar novo usuário
     */
    public function criar_usuario($nome, $email, $cpf, $id_setor)
    {
        try {
            // Verificar se o email já existe
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table1} WHERE email = :email");
            $stmt_check->bindValue(":email", $email);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                return 3;
            }

            // Verificar se o CPF já existe
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table1} WHERE cpf = :cpf");
            $stmt_check->bindValue(":cpf", $cpf);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                return 3;
            }

            $stmt = $this->connect->prepare("INSERT INTO {$this->table1} (nome, email, cpf, id_setor) VALUES (:nome, :email, :cpf, :id_setor)");
            $stmt->bindValue(":nome", $nome);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":cpf", $cpf);
            $stmt->bindValue(":id_setor", $id_setor);

            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Editar usuário existente
     */
    public function editar_usuario($id, $nome, $email, $cpf, $id_setor)
    {
        try {
            // Verificar se o email já existe em outro usuário
            if (isset($email)) {
                $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table1} WHERE email = :email AND id != :id");
                $stmt_check->bindValue(":email", trim($email));
                $stmt_check->bindValue(":id", $id);
                $stmt_check->execute();

                if ($stmt_check->rowCount() > 0) {
                    return 3;
                }
            }

            // Verificar se o CPF já existe em outro usuário
            if (isset($cpf)) {
                $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table1} WHERE cpf = :cpf AND id != :id");
                $stmt_check->bindValue(":cpf", $cpf);
                $stmt_check->bindValue(":id", $id);
                $stmt_check->execute();

                if ($stmt_check->rowCount() > 0) {
                    return 3;
                }
            }

            $sql = "UPDATE {$this->table1} SET nome = :nome, email = :email, cpf = :cpf, id_setor = :id_setor WHERE id = :id";
            $stmt_check = $this->connect->prepare($sql);
            $stmt_check->bindValue(":id", $id);
            $stmt_check->bindValue(":nome", $nome);
            $stmt_check->bindValue(":cpf", $cpf);
            $stmt_check->bindValue(":email", $email);
            $stmt_check->bindValue(":id_setor", $id_setor);

            if ($stmt_check->execute()) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            error_log("Erro ao editar usuário: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Excluir usuário
     */
    public function excluir_usuario($id)
    {
        try {
            // Verificar se há permissões usando este usuário
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table4} WHERE id_usuarios = :id");
            $stmt_check->bindValue(":id", $id);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                $stmt_check = $this->connect->prepare("DELETE FROM {$this->table4} WHERE id_usuarios = :id");
                $stmt_check->bindValue(":id", $id);
                $stmt_check->execute();
            }

            $stmt = $this->connect->prepare("DELETE FROM {$this->table1} WHERE id = :id");
            $stmt->bindValue(":id", $id);

            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            error_log("Erro ao excluir usuário: " . $e->getMessage());
            return 0;
        }
    }




    // ==================== FUNÇÕES PARA PERMISSÕES ====================

    /**
     * Adicionar permissão para usuário
     */
    public function adicionar_permissao(int $id_usuario, int $id_tipo_usuario, int $id_sistema)
    {
        try {

            // Verificar se a permissão já existe
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table4} WHERE id_usuarios = :id_usuario AND id_tipos_usuarios = :id_tipo_usuario AND id_sistemas = :id_sistema");
            $stmt_check->bindValue(":id_usuario", $id_usuario);
            $stmt_check->bindValue(":id_tipo_usuario", $id_tipo_usuario);
            $stmt_check->bindValue(":id_sistema", $id_sistema);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                return 0;
            }

            $stmt = $this->connect->prepare("INSERT INTO {$this->table4} (id_usuarios, id_tipos_usuarios, id_sistemas) VALUES (:id_usuario, :id_tipo_usuario, :id_sistema)");
            $stmt->bindValue(":id_usuario", $id_usuario);
            $stmt->bindValue(":id_tipo_usuario", $id_tipo_usuario);
            $stmt->bindValue(":id_sistema", $id_sistema);

            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            error_log("Erro ao adicionar permissão: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Editar permissão existente
     */
    public function editar_permissao($id, $id_usuario, $id_tipo_usuario, $id_sistema)
    {
        try {

            // Verificar se a nova combinação já existe em outra permissão
            $stmt_check = $this->connect->prepare("SELECT id FROM {$this->table4} WHERE id_usuarios = :id_usuario AND id_tipos_usuarios = :id_tipo_usuario AND id_sistemas = :id_sistema AND id != :id");
            $stmt_check->bindValue(":id_usuario", $id_usuario);
            $stmt_check->bindValue(":id_tipo_usuario", $id_tipo_usuario);
            $stmt_check->bindValue(":id_sistema", $id_sistema);
            $stmt_check->bindValue(":id", $id);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                return 3;
            }

            $stmt = $this->connect->prepare("UPDATE {$this->table4} SET id_usuarios = :id_usuario, id_tipos_usuarios = :id_tipo_usuario, id_sistemas = :id_sistema WHERE id = :id");
            $stmt->bindValue(":id_usuario", $id_usuario);
            $stmt->bindValue(":id_tipo_usuario", $id_tipo_usuario);
            $stmt->bindValue(":id_sistema", $id_sistema);
            $stmt->bindValue(":id", $id);

            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            error_log("Erro ao editar permissão: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Excluir permissão
     */
    public function excluir_permissao($id)
    {
        try {
            $stmt = $this->connect->prepare("DELETE FROM {$this->table4} WHERE id = :id");
            $stmt->bindValue(":id", $id);

            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            error_log("Erro ao excluir permissão: " . $e->getMessage());
            return 0;
        }
    }
}
