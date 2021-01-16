<?php
#####################################################################################################################################################
# Nome do Programa: funcoes.php
# Objetivo........: Conjunto de funções particulares da tabela MEDICOS
# Descrição.......: Monta as funcoes de picklist (escolha de médico) e mostralinha (detalha registro escolhido)
# Autor...........: JMH
# Criação.........: 2020-10-21 - Estruturação e detalhamento das funções
# Atualização.....: 2020-10-21 - Tratamento dos parâmetros passados para o processamento das funções
#                   2020-10-28 - Reposicionei os botões de navegação para dentro da célula da tabela que tem a picklist
#                   2020-10-28 - Emilinamos o uso da variável que guarda o comando SQL executado no BD, nas funções picklist() e mostralina().
#                   2020-10-29 - Reposicionei componentes do picklist pelo uso da função botes(). Coloquei mais parêmetros na função.
#                   2020-10-30 - Reposicionei componentes do mostralinha() pelo uso da função botes(). Coloquei mais parêmetros na função.
#                   2020-10-31 - Montei a função de menu dentro de uma DIV
#                   2020-11-01 - Teste do Programa e detalhamento dos comentários
#####################################################################################################################################################
function picklist($ac,$bl)
{ # esta função projeta da tabela médicos dois campos para montar uma picklist.
  # no final monta uma barra de botões com as alternativas de navegação no sistema.
  $PRG=($ac=='C') ? "consultar.php" : (($ac=='E') ? "excluir.php" : "alterar.php");
  global $nulink;
  # Agora podemos "Ler os dados da tabela medicos"
  # Usamos a função de ambiente _query() para executar comandos SQL na base de dados.;
  $execcmd=mysqli_query($nulink, "SELECT pkfornecedor, txnome FROM fornecedores");
  # O RETORNO da função _query() DEPENDE do comando que foi executado.
  # SE foi um comando de consulta (todos os comando de SELECT) o retorno é um VETOR complexo.
  # Este vetor é dividido em três regiões:
  # Na primeira: Nome(s) das tabelas envolvidas no comando;
  # Na segunda: Os campos que retornaram no comando
  # Na terceira: Os endereços de registros lidos no comando.
  # A partir da execução do _query() outras funções de ambinte podem "ler" os dados vetor complexo.
  # Iniciando o form para montar a picklist de escolha de registro
  printf("  <form action='./$PRG' method='POST'>\n");
  printf("  <input type='hidden' name='bloco' value='2'>\n");
  printf("  <input type='hidden' name='salto' value='2'>\n");
  printf("  <table>\n");
  printf("  <tr><td>Escolha o fornecedor:</td><td><select name='pkfornecedor'>\n");
  # A _fetch_array() 'pede' ao SGBD para acessar a tabela lida e ir até o endereço disponível na lista de endereços
  # e LÊ os campos informados no vetor complexo
  # Montando um vetor com os dados lidos.
  while ( $reg=mysqli_fetch_array($execcmd) )
  {
    printf("<option value='$reg[pkfornecedor]'>$reg[txnome]</option>\n");
  }
  printf("</select>");
  botoes($ac,$bl);
  printf("</td></tr>");
  printf("  </table>\n");
  printf("  </form>\n");
}
function mostralinha($CP,$acao,$bloco)
{ # esta função recebe um valor do código do médico e consulta a tabela exbindo os dados do registro escolhido.
  # no final monta uma barra de botões com as alternativas de navegação no sistema.
  global $nulink;
  $reg=mysqli_fetch_array(mysqli_query($nulink, "SELECT * from fornecedores WHERE pkfornecedor='$CP'"));
  # Exibindo os valores do registro escolhido
  printf("  <table border=1 style='border-collapse: collapse;'>\n");
  printf("  <tr><td>Codigo:</td>     <td>$reg[pkfornecedor]</td></tr>\n");
  printf("  <tr><td>Nome:</td>       <td>$reg[txnome]</td></tr>\n");
  printf("  <tr><td>Razão social:</td>        <td>$reg[txrazaosocial]</td></tr>\n");
  printf("  <tr><td>Logradouro:</td>   <td>$reg[fklogradouro]</td></tr>\n");
  printf("  <tr><td>Complemento:</td> <td>$reg[txcomplemento]</td></tr>\n");
  printf("  <tr><td>Cep:</td><td>$reg[nucep]</td></tr>\n");
  printf("  <tr><td>Limite de venda:</td><td>$reg[vllimitevenda]</td></tr>\n");
  printf("  <tr><td>Cad do fornecedor:</td>        <td>$reg[dtcadfornecedor]</td></tr>\n");
  printf("  <tr><td>&nbsp;</td>   <td>");botoes($acao,$bloco);printf("</td></tr>\n");
  printf("  </table>\n");
}
?>
