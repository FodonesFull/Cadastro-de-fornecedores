<?php
#####################################################################################################################################################
# Nome do Programa: Excluir.php
# Objetivo........: Administrar a funcionalidade excluir dos dados da tabela MEDICOS
# Descrição.......: Monta uma caixa uma picklist com escolha do registro de medico a consultar e seguir exibe os dados detalhados do medico escolhido
#                   para exclusão. Depois executa o treco de controle de transação excluindo o registro da tabela.
# Autor...........: JMH
# Criação.........: 2020-10-14 - Estruturação do switch/case principal do PA
# Atualização.....: 2020-10-21 - Determinação e escrita das funções do programa e desenvolvimento do arquivo externo toolskit.php
#                   2020-10-29/30 - reescrevi a chamada das funçoes picklist e mostralinha por conta do uso da função BOTOES().
#                                   reescrevi trecho do tratamento de transação por conta do uso da função BOTEOS().
#                   2020-11-01 - Teste do Programa e detalhamento dos comentários
#####################################################################################################################################################
require_once("../toolskit.php");
require_once("./funcoes.php");
$cordefundo="#FFDEAD";
iniciapagina($cordefundo,"Fornecedores","Excluir");
$bloco=( !ISSET($_REQUEST['bloco']) ) ? 1 : $_REQUEST['bloco'] ;
switch (TRUE)
{
  case ( $bloco==1 ):
  { # executar a função picklist
    picklist("E",1);
    break;
  }
  case ( $bloco==2 ):
  { # montar o form de confirmação da exclusão e exibir a linha consultada.
    printf("  <form action='./excluir.php' method='POST'>\n");
    printf("  <input type='hidden' name='bloco' value='3'>\n");
    printf("  <input type='hidden' name='pkfornecedor' value='$_REQUEST[pkfornecedor]'>\n");
    # mostrar o registro e montar formulário para excluir
    mostralinha($_REQUEST['pkfornecedor'],"E",$bloco);
    printf("</form>");
    break;
  }
  case ( $bloco==3 ):
  { # Montar o tratamento de transação - segmento de código que usa os logs de transação controlados pelo SGBD
    # Segmento totalmente executado ou abandonado.
    printf("Tratando a transação.<br>");
    # Montar o comando de atualização na tabela do banco de dados
    # enquanto (tentativa==TRUE)
    # |1 iniciar a transação
    #    executar o comando de atualização
    #    Capturar a condição de erro
    #    Se erro==0 então |1.1 transação deve ser concluída
    #                          montar mensagem de "Sucesso" 1.1|
    #               senão |1.2 se erro==1213 então transação deve ser re-tentada
    #                                        senão transação deve ser abandonada
    #                          fim-do-SE
    #                          abandonar transação 1.2|
    #    fim-do-SE
    # fim-do-enquanto 1|
    $cmdsql="DELETE FROM fornecedores WHERE fornecedores.pkfornecedor='$_REQUEST[pkfornecedor]'";
    $tentativa=TRUE;
    while ( $tentativa )
    { # laço de repatição para o controle da tentativa da execução do comando SQL no BD.
      mysqli_query($nulink,"START TRANSACTION");
      $execcmd=mysqli_query($nulink, $cmdsql);
      if ( mysqli_errno($nulink)==0 )
      { # Ok! Comando executado com sucesso... transação deve ser concluída e laço de repetição abandonado.
        # finalizando a transação
        mysqli_query($nulink,"COMMIT");
        $mensagem="Comando de Exclusão do médico $_REQUEST[pkfornecedor], foi executado com sucesso!";
        $tentativa=FALSE;
      }
      else
      {
        if ( mysqli_errno($nulink)==1213 )
        { # DEADLOCK
          $tentativa=TRUE;
        }
        else
        { # Erro irrecuperável - abandona a transação
          $tentativa=FALSE;
          $mensagem=mysqli_errno($nulink)." - ".mysqli_error($nulink);
        }
        mysqli_query($nulink,"ROLLBACK");
      }
    }
    printf("$mensagem<br>\n");
    botoes("E",$bloco);
    break;
  }
}
terminapagina("excluir.php","Gabriel Mello Moraes","0210481912017","Tarde");
?>

















