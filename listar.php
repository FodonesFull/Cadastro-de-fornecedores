<?php
#####################################################################################################################################################
# Nome do Programa: listar.php
# Objetivo........: Administrar a funcionalidade de listar os dados da tabela MEDICOS
# Descrição.......: Monta uma caixa uma picklist com escolha do registro de medico a consultar e seguir exibe os dados detalhados do medico escolhido
# Autor...........: JMH
# Criação.........: 2020-10-14 - Estruturação do switch/case principal do PA
# Atualização.....: 2020-10-21 - Determinação e escrita das funções do programa e desenvolvimento do arquivo externo toolskit.php
#                   2020-10-29/30 - reescrevi a chamada das funçoes picklist e mostralinha por conta do uso da função BOTOES().
#                   2020-11-01 - Teste do Programa e detalhamento dos comentários
#####################################################################################################################################################
require_once("../toolskit.php");
require_once("./funcoes.php");
# Neste PA a estrutura de controle recursivo tem dois CASEs executados sob 3 valores do marcador de passagem $bloco.
# Determinando o VALOR do Marcador de Passagem ($bloco).
$bloco=( !ISSET($_REQUEST['bloco']) ) ? 1 : $_REQUEST['bloco'] ;
######################## A partir daqui é o programa principal
$cordefundo=($bloco<3)?"#FFDEAD":"#FFFFFF";
($bloco<3)?iniciapagina($cordefundo,"Fornecedor","Listar"):iniciapagina($cordefundo,"","Listar");
switch (TRUE)
{
  case($bloco==1):
  { # montar o formulário para escolha da ORDENAÇÃO ou SELEÇÃO de Dados para o relatório.
    printf(" <form action='./listar.php' method='post'>\n");
    printf("  <input type='hidden' name='bloco' value='2'>\n");
    printf("Escolha a ordem como os dados serão exibidos no relatório:<br>\n");
    printf("Código do Fornecedor...:(<input type='radio' name='ordem' value='M.pkfornecedor' checked>)<br>\n");
    printf("Nome ..................:(<input type='radio' name='ordem' value='M.txnome'>)<br>\n");
    printf("Razão social...........:(<input type='radio' name='ordem' value='txrazaosocial'>)<br>\n");
    botoes("L",$bloco);
    printf(" </form>\n");
    break;
  }
  case($bloco==2 or $bloco==3):
      { # Este bloco vai processar a junção de medicos com instituicaoensino, logradouros (moradia e clinica) e especiaidadesmedicas.
        # Depois monta a tabela com os dados e a seguir um form permitindo que a listagem seja exibida para impressão em uma nova aba.
$cmdsql="SELECT M.*, L1.txnomelogradouro FROM fornecedores AS M LEFT JOIN logradouros AS L1 ON M.fklogradouro=L1.pklogradouro   ORDER BY $_REQUEST[ordem]";
        # printf("$cmdsql<br>\n");
        $execsql=mysqli_query($nulink,$cmdsql);
        printf("<table border=1 style='border-collapse: collapse;'>\n");
        printf("<tr><td>Código.</td>\n");
        printf("    <td>Nome:</td>\n");
        printf("    <td>Razao social:</td>\n");
        printf("    <td>logradouro:</td>\n");
        printf("    <td>Complemento:</td>\n");
        printf("    <td>CEP:</td>\n");
        printf("    <td>Limite de venda:</td>\n");
        printf("    <td>Cadastro fornecedor</td>\n");
        $corlinha="white";
        while ( $le=mysqli_fetch_array($execsql) )
        {
          printf("<tr><td>$le[pkfornecedor]</td>\n");
          printf("    <td>$le[txnome]</td>\n");
          printf("    <td>$le[txrazaosocial]</td>\n");
          printf("    <td>$le[txcomplemento]</td>\n");
          printf("    <td>$le[txnomelogradouro]-($le[fklogradouro])</td>\n");
          printf("    <td>$le[nucep]</td>\n");
          printf("    <td>$le[vllimitevenda]</td>\n");
          printf("   <td>$le[dtcadfornecedor]</td></tr>\n");
          $corlinha=( $corlinha=='white')?"lightgreen":"white";
        }
        printf("</table>\n");
        if ( $bloco==2 )
        {
          printf("<table><tr><td valign=top><button onclick='history.go(-2)'>Início</button><button onclick='history.go(-1)'>Voltar</button></td><td>");
          printf("<form action='./listar.php' method='POST' target='_NEW'>\n");
          printf("  <input type='hidden' name='bloco' value='3'>\n");
          printf("  <input type='hidden' name='ordem' value='$_REQUEST[ordem]'>\n");
          botoes("L",$bloco);
          printf("</form></td></tr></table>\n");
        }
        else
        {
          printf("<hr>\n<button type='submit' onclick='window.print();'>Imprimir</button> - Corte a folha abaixo da linha no final da página<br>\n");
        }
        break;
      }
}
terminapagina("listar.php","Gabriel Mello Moraes","0210481912017","Tarde");
?>






