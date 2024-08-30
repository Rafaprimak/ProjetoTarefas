<?php
//CRUD
class TarefaService {
    private $conexao;
    private $tarefa;

    public function __construct(Conexao $conexao, Tarefa $tarefa) {
        $this->conexao = $conexao->conectar();
        $this->tarefa = $tarefa;
    }

    public function inserir() {
        $query = 'insert into tb_tarefas(tarefa, prioridade, prazo, id_categoria) values(:tarefa, :prioridade, :prazo, :id_categoria)';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
        $stmt->bindValue(':prioridade', $this->tarefa->__get('prioridade')); 
        $stmt->bindValue(':prazo', $this->tarefa->__get('prazo'));
        $stmt->bindValue(':id_categoria', $this->tarefa->__get('id_categoria'));
        $stmt->execute();
    }

    public function recuperar() { 
        $query = '
            select 
                t.id, s.status, t.tarefa, t.data_cadastrado, t.prioridade, c.nome as categoria
            from 
                tb_tarefas as t
                left join tb_status as s on (t.id_status = s.id)
                left join tb_categorias as c on (t.id_categoria = c.id)
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function atualizar() { 
        $query = "update tb_tarefas set tarefa = ?, prioridade = ?, id_categoria = ? where id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(1, $this->tarefa->__get('tarefa'));
        $stmt->bindValue(2, $this->tarefa->__get('prioridade'));
        $stmt->bindValue(3, $this->tarefa->__get('id_categoria'));
        $stmt->bindValue(3, $this->tarefa->__get('id'));
        return $stmt->execute(); 
    }

    public function remover() { 
        $query = 'delete from tb_tarefas where id = :id';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id', $this->tarefa->__get('id'));
        $stmt->execute();
    }

    public function marcarRealizada() {
        $query = "update tb_tarefas set id_status = ? where id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(1, $this->tarefa->__get('id_status'));
        $stmt->bindValue(2, $this->tarefa->__get('id'));
        return $stmt->execute(); 
    }

    public function ordenarData() {
        $query = '
            select 
                t.id, s.status, t.tarefa, t.data_cadastrado, t.prioridade
            from 
                tb_tarefas as t
                left join tb_status as s on (t.id_status = s.id)
            order by
                t.data_cadastrado
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ordenarPrioridade() {
		$query = '
			select 
				t.id, s.status, t.tarefa, t.data_cadastrado, t.prioridade
			from 
				tb_tarefas as t
				left join tb_status as s on (t.id_status = s.id)
			order by
				t.prioridade DESC
		';
		$stmt = $this->conexao->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

    public function ordenarAlfabetica() {
        $query = '
            select 
                t.id, s.status, t.tarefa, t.data_cadastrado, t.prioridade
            from 
                tb_tarefas as t
                left join tb_status as s on (t.id_status = s.id)
            order by
                t.tarefa
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function recuperarTarefasComPrazo() {
        $query = '
            select 
                t.id, s.status, t.tarefa, t.data_cadastrado, t.prioridade
            from 
                tb_tarefas as t
                left join tb_status as s on (t.id_status = s.id)
            where
                t.prazo is not null
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperarTarefasPorCategoria() {
        $query = 'SELECT * FROM tb_tarefas WHERE id_categoria = :id_categoria';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id_categoria', $this->tarefa->__get('id_categoria'));
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

	public function recuperarTarefasPendentes() {
        $query = '
            select 
                t.id, s.status, t.tarefa, t.data_cadastrado, t.prioridade, c.categoria 
            from 
                tb_tarefas as t
                left join tb_status as s on (t.id_status = s.id)
                left join tb_categorias as c on (t.id_categoria = c.id)
            where 
                t.id_status = 1
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function recuperarTarefasConcluidas() {
        $query = '
            select 
                t.id, s.status, t.tarefa, t.data_cadastrado, t.prioridade, c.categoria 
            from 
                tb_tarefas as t
                left join tb_status as s on (t.id_status = s.id)
                left join tb_categorias as c on (t.id_categoria = c.id)
            where 
                t.id_status = 2
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function recuperarTodasTarefas() {
        $query = '
            select 
                t.id, s.status, t.tarefa, t.data_cadastrado, t.prioridade, c.categoria 
            from 
                tb_tarefas as t
                left join tb_status as s on (t.id_status = s.id)
                left join tb_categorias as c on (t.id_categoria = c.id)
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperarCategorias() {
        $query = 'SELECT * FROM tb_categorias';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>