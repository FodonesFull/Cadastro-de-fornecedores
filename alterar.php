<?php
#####################################################################################################################################################
# Nome do Programa: Alterar.php
# Objetivo........: Administrar a funcionalidade alterar dos dados da tabela MEDICOS
# Descrição.......: Monta uma caixa uma picklist com escolha do registro de medico a consultar e seguir exibe os dados detalhados do medico escolhido
#                   para exclusão. Depois executa o treco de controle de transação excluindo o registro da tabela.
# Autor...........: JMH
# Criação.........: 2020-10-14 - Estruturação do switch/case principal do PA
# Atualização.....: 2020-10-21 - Determinação e escrita das funções do programa e desenvolvimento do arquivo externo toolskit.php
#                   2020-10-29/30 - reescrevi a chamada da função mostralinha por conta do uso da função BOTOES().
#                                   reescrevi trecho do tratamento de transação por conta do uso da função BOTEOS().
#                   2020-11-01 - Teste do Programa e detalhamento dos comentários
#####################################################################################################################################################
require_once("../toolskit.php");
require_once("./funcoes.php");
$cordefundo="#FFDEAD";
iniciapagina($cordefundo,"Fornecedores","Alterar");
$bloco=( !ISSET($_REQUEST['bloco']) ) ? 1 : $_REQUEST['bloco'] ;
switch (TRUE)
{
  case ( $bloco==1 ):
  { # executar a função picklist
    picklist("A",$bloco);
    break;
  }
  case ( $bloco==2 ):
  { # Montar o formulário para alteração de dados do registro escolhido.
    printf("  <form action='./alterar.php' method='POST'>\n");
    printf("  <input type='hidden' name='bloco' value='3'>\n");
    printf("  <input type='hidden' name='pkfornecedor' value='$_REQUEST[pkfornecedor]'>\n");
    $regalt=mysqli_fetch_array(mysqli_query($nulink,"SELECT * FROM fornecedores WHERE fornecedores.pkfornecedor='$_REQUEST[pkfornecedor]'"));
    printf("<table border=1 style='border-collapse: collapse;'>\n");
    printf("<tr><td>Nome</td><td><input type='text' name='txnome' value='$regalt[txnome]' size='40' maxlength='200' placeholder='Nome completo e sem abreviação'></td></tr>");
    printf("<tr><td>Nu CRM</td><td><input type='text' name='txrazaosocial' value='$regalt[txrazaosocial]'      size='20' maxlength='20'  placeholder='S&oacute; números'>Razão social:.</td></tr>");
    # montando a picklist para escolha do logradouro de moradia do médico.
    $cmdsql="SELECT pklogradouro, txnomelogradouro FROM logradouros ORDER BY txnomelogradouro";
    $execsql=mysqli_query($nulink,$cmdsql);
    printf("<tr><td>Logradouro de moradia</td><td><select name='fklogradouro'>\n");
    while ( $reg=mysqli_fetch_array($execsql) )
    {
      $sel=( $reg['pklogradouro']!=$regalt['fklogradouro'] ) ? "" : " selected";
      printf("<option value='$reg[pklogradouro]'$sel>$reg[txnomelogradouro]-($reg[pklogradouro])</option>\n");
    }
    printf("</select></td></tr>\n");
    printf("<tr><td>&nbsp;</td><td>Complemento: <input type='text' name='txcomplemento' value='$regalt[txcomplemento]' placeholder='N&ordm; do imóvel, localização referencial (outros imóveis próximos)' size='55' maxlength='80'></td></tr>");
    printf("<tr><td>&nbsp;</td><td>CEP: <input type='text' name='nucep' value='$regalt[nucep]' placeholder='Só N&ordm;' size='8' maxlength='8'></td></tr>");
    printf("<tr><td>&nbsp;</td><td>");botoes("A",$bloco);
    printf("</td></tr>\n");
    printf("</table>\n");
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
    $cmdsql="UPDATE fornecedores SET txnome          ='$_REQUEST[txnome]',
                                nucep                ='$_REQUEST[nucep]',
                                txnome               ='$_REQUEST[txnome]',
                                txrazaosocial        ='$_REQUEST[txrazaosocial]',
                                fklogradouro         ='$_REQUEST[fklogradouro]',
                                txcomplemento        ='$_REQUEST[txcomplemento]'
                            WHERE fornecedores.pkfornecedor='$_REQUEST[pkfornecedor]'";
    $tentativa=TRUE;
    while ( $tentativa )
    { # laço de repatição para o controle da tentativa da execução do comando SQL no BD.
      mysqli_query($nulink,"START TRANSACTION");
      $execcmd=mysqli_query($nulink, $cmdsql);
      if ( mysqli_errno($nulink)==0 )
      { # Ok! Comando executado com sucesso... transação deve ser concluída e laço de repetição abandonado.
        # finalizando a transação
        mysqli_query($nulink,"COMMIT");
        $mensagem="Comando de Alteração do fornecedor  $_REQUEST[pkfornecedor], foi executado com sucesso!";
        $tentativa=FALSE;
        mostralinha($_REQUEST['pkfornecedor'],"A",$bloco);
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
    break;
  }
}
terminapagina("alterar.php","Gabriel Mello Moraes","0210481912017","Tarde");
?>

















