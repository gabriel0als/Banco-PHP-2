<?php

$clientes = [];
$contas   = [];

var_dump($contas);

// Função de menu
function menu(){
    global $clientes, $contas;

    print "Bem-vindo ao sistema bancário! \n";
    
    // Solicita o nome do usuário
    $nome_user = readline("Me informe qual o seu nome: ");
    $nome_user = strtoupper($nome_user);
    
    // Solicita o CPF do usuário
    $cpf_user = readline("Me informe seu CPF: ");
    
    // Verifica se o CPF já existe
    if (!clienteExistente($clientes, $cpf_user)) {
        $telefone = readline("Me informe seu número de telefone: ");
        cadastrarCliente($clientes, $nome_user, $cpf_user, $telefone);
        print "Cliente cadastrado com sucesso! \n";
    } else {
        print "Cliente já existe! \n";
    }

    // Criação de conta bancária
    $numeroConta = cadastrarConta($contas, $cpf_user);
    print "Conta criada com sucesso! Número da conta: {$numeroConta}\n";
    
    // Menu de operações bancárias
    while (true) {
        print "\nSelecione uma opção: \n";
        print "1. Depositar \n";
        print "2. Sacar \n";
        print "3. Consultar Saldo \n";
        print "4. Sair \n";
        
        $opcao = readline("Escolha uma opção: ");
        
        switch ($opcao) {
            case 1:
                $quantia = (float) readline("Informe o valor do depósito: ");
                depositar($contas, $numeroConta, $quantia);
                break;
            case 2:
                $quantia = (float) readline("Informe o valor do saque: ");
                sacar($contas, $numeroConta, $quantia);
                break;
            case 3:
                consultarSaldo($contas, $numeroConta);
                break;
            case 4:
                print "Saindo do sistema. Até logo! \n";
                return;
            default:
                print "Opção inválida! \n";
        }
    }
}

// Função para verificar se o cliente já existe
function clienteExistente($clientes, $cpf) {
    foreach ($clientes as $cliente) {
        if ($cliente['cpf'] == $cpf) {
            return true;
        }
    }
    return false;
}

// Função para cadastrar cliente
function cadastrarCliente(&$clientes, string $nome, string $cpf, string $telefone): void {
    $cliente = [
        "nome" => $nome,
        "cpf"  => $cpf, // 11 digitos
        "telefone" => $telefone // 10 digitos
    ];
    
    $clientes[] = $cliente;
}

// Função para cadastrar conta
function cadastrarConta(&$contas, $cpfCliente): string {
    $conta = [
        "numeroConta" => uniqid(),
        "cpfCliente" => $cpfCliente,
        "saldo" => 0
    ];
    
    $contas[] = $conta;
    return $conta['numeroConta'];
}

// Função para depositar
function depositar(&$contas, $numeroConta, $quantia) {
    foreach($contas as &$conta) {
        if($conta['numeroConta'] == $numeroConta) {
            $conta['saldo'] += $quantia;
            print "Depósito de R$ {$quantia} realizado com sucesso na conta {$numeroConta}\n";
            return;
        }
    }
    print "Conta {$numeroConta} não encontrada!\n";
}

// Função para sacar
function sacar(&$contas, $numeroConta, $quantia) {
    foreach($contas as &$conta) {
        if ($conta['numeroConta'] == $numeroConta) {
            if ($conta['saldo'] >= $quantia) {
                $conta['saldo'] -= $quantia;
                print "Saque de R$ {$quantia} realizado com sucesso na conta {$numeroConta}\n";
            } else {
                print "Saldo insuficiente! \n";
            }
            return;
        }
    }
    print "Conta {$numeroConta} não encontrada!\n";
}

// Função para consultar saldo
function consultarSaldo(&$contas, $numeroConta) {
    foreach($contas as &$conta) {
        if($conta['numeroConta'] == $numeroConta) {
            print "Saldo da conta {$numeroConta}: R$ {$conta['saldo']}\n";
            return;
        }
    }
    print "Conta {$numeroConta} não encontrada!\n";
}

// Chama o menu para iniciar o sistema bancário
menu();

?>
