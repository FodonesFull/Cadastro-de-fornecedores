<?php
#####################################################################################################################################################
# Nome do Programa: Consultar.php
# Objetivo........: Administrar a funcionalidade consulta dos dados da tabela MEDICOS
# Descrição.......: Monta uma caixa uma picklist com escolha do registro de medico a consultar e seguir exibe os dados detalhados do medico escolhido
# Autor...........: JMH
# Criação.........: 2020-10-14 - Estruturação do switch/case principal do PA
# Atualização.....: 2020-10-21 - Determinação e escrita das funções do programa e desenvolvimento do arquivo externo toolskit.php
#                   2020-10-29/30 - reescrevi a chamada das funçoes picklist e mostralinha por conta do uso da função BOTOES().
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
iniciapagina($cordefundo,"Fornecedores","Consultar");
# um PA recursivo usa uma função de ambinte para verificar se existe valor em uma variável.
# Se o valor existe então assume uma valor senão, assume outro.
# Existe uma função que verifica a existencia de valor de variável. ISSET()
# E pode ser usada para atribuir valor em marcadores de passagem.
# Determinando o VALOR do MArcador de Passagem ($bloco).
$bloco=( !ISSET($_REQUEST['bloco']) ) ? 1 : $_REQUEST['bloco'] ;
switch (TRUE)
{
  case($bloco==1):
  { # executar a função picklist
    picklist("C",1);
    break;
  }
  case($bloco==2):
  { # exibir os dados do registro escolhido no CASE anterior (no formulário)
    mostralinha($_REQUEST['pkfornecedor'],'C',$bloco);
    break;
  }
}
terminapagina("consultar.php","Gabriel Mello Moraes","0210481912017","Tarde");
?>
