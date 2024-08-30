<?php
    $acao = isset($_GET['acao']) ? $_GET['acao'] : 'recuperarTodasTarefas';
    require '../app_lista_tarefas/tarefa_controller.php';
    require_once '../app_lista_tarefas/tarefa.service.php';
    require_once '../app_lista_tarefas/conexao.php';
    require_once '../app_lista_tarefas/tarefa.model.php';

    function isSelected($currentAction, $buttonAction) {
        return $currentAction == $buttonAction ? 'btn-selecionado' : '';
    }

    if ($acao == 'ordenarData') {
        usort($tarefas, function($a, $b) {
            return strtotime($a->data_cadastrado) - strtotime($b->data_cadastrado);
        });
    } else if ($acao == 'ordenarPrioridade') {
        usort($tarefas, function($a, $b) {
            $prioridadeA = isset($a->prioridade) ? $a->prioridade : 0;
            $prioridadeB = isset($b->prioridade) ? $b->prioridade : 0;
            return $prioridadeB - $prioridadeA; 
        });
    } else if ($acao == 'ordenarAlfabetica') {
        usort($tarefas, function($a, $b) {
            return strcmp($a->tarefa, $b->tarefa);
        });
    }

    $conexao = new Conexao();
    $tarefa = new Tarefa();
    $tarefaService = new TarefaService($conexao, $tarefa);
    $categorias = $tarefaService->recuperarCategorias();
?>

<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Lista Tarefas</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        function remover(id) {
            location.href = 'todas_tarefas.php?acao=remover&id=' + id;
        }

        function marcarRealizada(id) {
            location.href = 'todas_tarefas.php?acao=marcarRealizada&id=' + id;
        }

        function arquivar(id) {
            location.href = 'todas_tarefas.php?acao=arquivarTarefa&id=' + id;
        }

        function desarquivar(id) {
            location.href = 'todas_tarefas.php?acao=desarquivarTarefa&id=' + id;
        }
    </script>
</head>
<body>
<nav class="navbar navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            App Lista Tarefas
        </a>
        <a class="navbar-brand">
            <button class="btn btn-primary <?= isSelected($acao, 'ordenarData') ?>" onclick="location.href='todas_tarefas.php?acao=ordenarData'">Ordenar por Data</button>
            <button class="btn btn-primary <?= isSelected($acao, 'ordenarPrioridade') ?>" onclick="location.href='todas_tarefas.php?acao=ordenarPrioridade'">Ordenar por Prioridade</button>
            <button class="btn btn-primary <?= isSelected($acao, 'ordenarAlfabetica') ?>" onclick="location.href='todas_tarefas.php?acao=ordenarAlfabetica'">Ordenar Alfabeticamente</button>
        </a>
        <a class="navbar-brand">
            <button class="btn btn-verde <?= isSelected($acao, 'recuperarTodasTarefas') ?>" onclick="location.href='todas_tarefas.php?acao=recuperarTodasTarefas'">Todas</button>
            <button class="btn btn-verde <?= isSelected($acao, 'recuperarTarefasPendentes') ?>" onclick="location.href='todas_tarefas.php?acao=recuperarTarefasPendentes'">Pendentes</button>
            <button class="btn btn-verde <?= isSelected($acao, 'recuperarTarefasConcluidas') ?>" onclick="location.href='todas_tarefas.php?acao=recuperarTarefasConcluidas'">Concluídas</button>
            <button class="btn btn-verde <?= isSelected($acao, 'recuperarTarefasArquivadas') ?>" onclick="location.href='todas_tarefas.php?acao=recuperarTarefasArquivadas'">Arquivadas</button>
        </a>
        <a class="navbar-brand">
            <form method="post" action="todas_tarefas.php?acao=recuperarTarefasPorCategoria">
                <select name="id_categoria" class="form-control">
                    <option value="">Selecione uma categoria</option>
                    <?php foreach($categorias as $categoria): ?>
                        <option value="<?= $categoria->id ?>"><?= $categoria->categoria ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </a>
    </div>
    
</nav>
<div class="container app">
    <div class="row">
        <div class="col-sm-3 menu">
            <ul class="list-group">
                    <li class="list-group-item"><a href="index.php">Tarefas Pendentes</a></li>
                    <li class="list-group-item"><a href="nova_tarefa.php">Nova Tarefa</a></li>
                    <li class="list-group-item"><a href="todas_tarefas.php">Todas Tarefas</a></li>
                    <li class="list-group-item"><a href="todas_tarefas.php?acao=recuperarTarefasArquivadas">Tarefas Arquivadas</a></li>
                </ul>
        </div>
        <div class="col-sm-9">
            <div class="container">
                <div class="row">
                    <div class="col">
                    <h4>Todas Tarefas</h4>
                    <hr />
                    <?php if ($acao == 'recuperarTarefasArquivadas'): ?>
                        <form method="post" action="todas_tarefas.php?acao=desarquivarSelecionadas">
                            <button type="submit" class="btn btn-warning mb-3">Desarquivar Selecionadas</button>
                            <?php foreach($tarefas as $tarefa): ?>
                                <div class="row mb-3 d-flex align-items-center tarefa">
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="tarefas[]" value="<?= $tarefa->id ?>">
                                    </div>
                                    <div class="col-sm-8" id="tarefa_<?= $tarefa->id ?>">
                                        <?= $tarefa->tarefa ?>
                                    </div>
                                    <div class="col-sm-3 mt-2 d-flex justify-content-between">
                                        <i class="fas fa-folder-open fa-lg text-info" onclick="desarquivar(<?= $tarefa->id ?>)"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </form>
                    <?php else: ?>
                        <?php foreach($tarefas as $tarefa): ?>
                            <div class="row mb-3 d-flex align-items-center tarefa">
                                <div class="col-sm-9" id="tarefa_<?= $tarefa->id ?>">
                                    <?= $tarefa->tarefa ?>
                                    
                                    <?php if ($acao == 'ordenarData'): ?>
                                        (<?= date('d/m/Y H:i', strtotime($tarefa->data_cadastrado)) ?>)
                                    <?php endif; ?>
                                    <!-- Adiciona a prioridade atual ao final de cada tarefa -->
                                    <?php if (isset($tarefa->prioridade)): ?>
                                        (Prioridade: <?= $tarefa->prioridade ?>)
                                    <?php endif; ?>
                                    <!-- Adiciona a categoria atual ao final de cada tarefa -->
                                    <?php if (isset($tarefa->categoria)): ?>
                                        (Categoria: <?= $tarefa->categoria ?>)
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-3 mt-2 d-flex justify-content-between">
                                    <i class="fas fa-trash-alt fa-lg text-danger" onclick="remover(<?= $tarefa->id ?>)"></i>
                                    <i class="fas fa-check-square fa-lg text-success" onclick="marcarRealizada(<?= $tarefa->id ?>)"></i>
                                    <i class="fas fa-archive fa-lg text-warning" onclick="arquivar(<?= $tarefa->id ?>)"></i>
                                    <?php if ($acao == 'recuperarTarefasArquivadas'): ?>
                                        <i class="fas fa-folder-open fa-lg text-info" onclick="desarquivar(<?= $tarefa->id ?>)"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function desarquivar(id) {
        location.href = 'todas_tarefas.php?acao=desarquivar&id=' + id;
    }
</script>

</body>
</html>