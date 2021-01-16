<?php
#####################################################################################################################################################
# Nome do Programa: Incluir.php
# Objetivo........: Administrar a funcionalidade consulta dos dados da tabela MEDICOS
# Descrição.......: Monta uma caixa uma picklist com escolha do registro de medico a consultar e seguir exibe os dados detalhados do medico escolhido
# Autor...........: JMH
# Criação.........: 2020-10-14 - Estruturação do switch/case principal do PA
# Atualização.....: 2020-10-21 - Determinação e escrita das funções do programa e desenvolvimento do arquivo externo toolskit.php
#                   2020-10-29/30 - reescrevi a chamada da função mostralinha por conta do uso da função BOTOES().
#                                   reescrevi trecho do tratamento de transação por conta do uso da função BOTOES().
#                   2020-11-01 - Teste do programa usando BOTOES
#                   2020-11-01 - Teste do Programa e detalhamento dos comentários
#####################################################################################################################################################
# Na PHP existem dois modos de REFERENCIAR um arquivo externo
# include(NomeDoArquivo); ou
# require(NomeDoArquivo);
# tanto include como require tem um complmento que, para programas recursivos, faz com que a leitura seja feita somente uma vez.
# _once
require_once("../toolskit.php");
require_once("./funcoes.php");
######################## A partir daqui é o programa principal
$cordefundo="#FFDEAD";
iniciapagina($cordefundo,"Fornecedores","Incluir");
# um PA recursivo usa uma função de ambinte para verificar se existe valor em uma variável.
# Se o valor existe então assume uma valor senão, assume outro.
# Existe uma função que verifica a existencia de valor de variável. ISSET()
# E pode ser usada para atribuir valor em marcadores de passagem.
# Determinando o VALOR do MArcador de Passagem ($bloco).
$bloco=( !ISSET($_REQUEST['bloco']) ) ? 1 : $_REQUEST['bloco'] ;
switch (TRUE)
{
  case($bloco==1):
  { # Montar o form para entrada de dados que serão gravados na tabela medicos.
    printf("<form action='incluir.php' method='POST'>\n");
    printf("<input type='hidden' name='bloco' value=2>\n");
    printf("<table border=1 style='border-collapse: collapse;'>\n");
    printf("<tr><td>Nome</td><td><input type='text' name='txnome' size='40' maxlength='200' placeholder='Nome completo e sem abreviação'></td></tr>");
    printf("<tr><td>Razão social</td><td><input type='text' name='txrazaosocial'      size='20' maxlength='20'  placeholder='S&oacute; números'></td></tr>");
    # montando a picklist para escolha do logradouro de moradia do médico.
    $cmdsql="SELECT pklogradouro, txnomelogradouro FROM logradouros ORDER BY txnomelogradouro";
    $execsql=mysqli_query($nulink,$cmdsql);
    printf("<tr><td>Logradouro do fornecedores</td><td><select name='fklogradouro'>\n");
    while ( $reg=mysqli_fetch_array($execsql) )
    {
      printf("<option value='$reg[pklogradouro]'>$reg[txnomelogradouro]-($reg[pklogradouro])</option>");
    }
    printf("</select></td></tr>\n");
    printf("<tr><td>&nbsp;</td><td>Complemento: <input type='text' name='txcomplemento' placeholder='N&ordm; do imóvel, localização referencial (outros imóveis próximos)' size='55' maxlength='80'></td></tr>");
    printf("<tr><td>&nbsp;</td><td>CEP: <input type='text' name='nucep' placeholder='Só N&ordm;' size='8' maxlength='8'></td></tr>");
        printf("<tr><td>&nbsp;</td><td>Limite de venda: <input type='text' name='vllimitevenda' placeholder='Limite que sera a venda' size='55' maxlength='80'></td></tr>");


    $currentdate=date("Y-m-d");
    printf("<tr><td>Data de Cadastro do fornecedor</td><td><input type='date' name='dtcadfornecedor' value='$currentdate'></td></tr>");
    printf("<tr><td>&nbsp;</td><td>");botoes("I",$bloco);printf("</td></tr>\n");
    printf("</table>\n");
    printf("</form>\n");
    break;
  }
  case($bloco==2):
  { # Montando o tratamento de transação - segmento de código que usa os logs de transação controlados pelo SGBD
    # Segmento totalmente executado ou abandonado.
    printf("Tratando a transação.<br>");
    # enquanto (tentativa==TRUE)
    # |1 iniciar a transação
    #    Montar o comando de atualização na tabela do banco de dados
    #          Ler o último registro e incrementar de uma unidade o valor de PKMEDICO.
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
    $tentativa=TRUE;
    while ( $tentativa )
    { # laço de repatição para o controle da tentativa da execução do comando SQL no BD.
      mysqli_query($nulink,"START TRANSACTION");
      # Pegar o último gravado em médicos e incrementar o valor de pkmedico em UMA unidade.
      $ultimacp=mysqli_fetch_array(mysqli_query($nulink,"SELECT MAX(pkfornecedor) AS CpMAX FROM fornecedores"));
      $CP=$ultimacp['CpMAX']+1;
$cmdsql="INSERT INTO fornecedores (pkfornecedor,
                                   txnome,
                                   txrazaosocial,
                                   fklogradouro,
                                   txcomplemento,
                                   nucep,
                                   vllimitevenda,
                                   dtcadfornecedor)
                     VALUES ('$CP',
                             '$_REQUEST[txnome]',
                             '$_REQUEST[txrazaosocial]',
                             '$_REQUEST[fklogradouro]',
                             '$_REQUEST[txcomplemento]',
                             '$_REQUEST[nucep]',
                             '$_REQUEST[vllimitevenda]',
                             '$_REQUEST[dtcadfornecedor]')";
      $execcmd=mysqli_query($nulink, $cmdsql);
      if ( mysqli_errno($nulink)==0 )
      { # Ok! Comando executado com sucesso... transação deve ser concluída e laço de repetição abandonado.
        # finalizando a transação
        mysqli_query($nulink,"COMMIT");
        $mensagem="Comando de Inclusão do logradouro $CP, foi executado com sucesso!";
        $tentativa=FALSE;
        mostralinha($CP,"I",$bloco);
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
terminapagina("incluir.php","Gabriel Mello Moraes","0210481912017","Tarde");
?>
