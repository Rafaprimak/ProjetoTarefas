<?php
require "../app_lista_tarefas/tarefa.model.php";
require "../app_lista_tarefas/tarefa.service.php";
require "../app_lista_tarefas/conexao.php";

$tarefa = new Tarefa();
$conexao = new Conexao();
$tarefaService = new TarefaService($conexao, $tarefa);
$tarefas = $tarefaService->recuperarTarefasComPrazo();

foreach ($tarefas as $tarefa) {
    $prazo = strtotime($tarefa->prazo);
    $agora = time();
    $diferenca = $prazo - $agora;

    if ($diferenca <= 86400 && $diferenca > 0) { // 24 horas
        $mensagem = "Notificação: A tarefa '{$tarefa->tarefa}' está próxima do prazo.\n";
        mail($tarefa->email, "Tarefa Próxima do Prazo", $mensagem);
    } elseif ($diferenca <= 0) {
        $mensagem = "Notificação: A tarefa '{$tarefa->tarefa}' expirou.\n";
        mail($tarefa->email, "Tarefa Expirada", $mensagem);
    }
}
?>