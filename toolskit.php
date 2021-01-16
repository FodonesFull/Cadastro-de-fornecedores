<?php
#####################################################################################################################################################
# Nome do Programa: funcoes.php
# Objetivo........: Conjunto de funções GERAIS do sistema de gerenciamento de dados das tabelas
# Descrição.......: Monta as funcoes de conectamy (conexão com BD com recebimento de parâmetros), iniciapagina(recebendo parâmetros de cor de tela e
#                   Título de página) e terminapagina (emitidno as tags finais de HTML)
# Autor...........: JMH
# Criação.........: 2020-10-21 - Estruturação e detalhamento das funções
# Atualização.....: 2020-10-21 - Tratamento dos parâmetros passados para o processamento das funções
#                   2020-10-28 - Criei o acesso ao arquivo .CSS
#                   2020-10-28/29 - escrevi a função botoes().
#                   2020-11-01 - Teste do Programa e detalhamento dos comentários
#####################################################################################################################################################
function conectamy($host, $user, $passwd, $dbase)
{ # função que faz a conexão com o MariaDB (ou MySQL)
  # Conectando com a base de dados.
  # Na PHP para conectar com um Banco de Dados do MariaDB devem ser informados 4 parâmetros:
  # Nome da base, Nome do usuário, nome do servidor e senha de conexão.
  # Estes 4 parâmetros DEVEM SER INFORMADOS em uma FUNÇÃO DE AMBIENTE que retorna um NÚMERO DE CONEXÃO.
  # a função de ambiente é a função mysqli_connect().
  #
  global $nulink;
  $nulink=mysqli_connect($host,$user,$passwd,$dbase);
  # Agora vamos 'ajustar' os caracteres acentuados. Acertando a tabela de caracteres que sera usada no MySQL
  # O MySQL trabalha com vários tipos de caracteres em idiomas diferentes.
  # Os comandos seguintes 'calibram' o MySQL para caracteres do Portugues-Brasil.
  mysqli_query($nulink,"SET NAMES 'utf8'");
  mysqli_query($nulink,'SET character_set_connection=utf8');
  mysqli_query($nulink,'SET character_set_client=utf8');
  mysqli_query($nulink,'SET character_set_results=utf8');
}
function iniciapagina($cordefundo,$tabela,$titulo)
{ # função para Emitir as TAGs que iniciam a página
  # faz a referencia ao arquivo de estilo
  printf("<html>\n");
  printf(" <head>\n");
  printf("  <meta charset='UTF-8'>\n");
  printf("  <link rel='stylesheet' type='text/css' href='../estilo.css'>\n");
  printf(" </head>\n");
  printf(" <body bgcolor='$cordefundo'>\n");# printf("$cordefundo");
  ($tabela!="")?montamenu($tabela):"";
  printf("$tabela <red>$titulo</red><br>\n");
}
function botoes($acao,$bloco)
{ # esta função monta a barra de botões
  # Os dois parâmetros desta função são a letra indicando a operação (ICAEL) e o número do bloco do PA que está executando a função.
  # Os valores dos parâmentros e os botões que devem ser montados e exibidos são os seguintes:
  # I1 | Início | Limpar | Incluir |
  # I2 | Início | 
  # C1 | Início | Limpar | Consultar |
  # C2 | Início | Voltar |
  # A1 | Início | Limpar | Alterar |
  # A2 | Início | Voltar | Limpar | Alterar |
  # A3 | Início | 
  # E1 | Início | Limpar | Excluir |
  # E2 | Início | Confirmar Exclusão |
  # E3 | Início | 
  # L1 | Início | Gerar Relatório |
  # L2 | Gerar para Impressão |
  # L3 | Imprimir |
  $op=$acao.$bloco;
  # printf("$op-");
  $inicio=($op!="L2" and $op!="L3")?"<button onclick='history.go(-".$bloco.")'>Início</button>":"";
  $limpar=($op=="C1" or $op=="I1" or $op=="A1") ? "<button type='reset'>Limpar</button>" : "";
  $voltar=($op=="C2" or $op=="E2" or $op=="A2") ? "<button onclick='history.go(-1)'>Voltar</button>" : "";
  $operar=($op=="C1")?"Consultar":(($op=="E1")?"Excluir":(($op=="E2")?"Confirmar Exclusão":(($op=="A1")?"Alterar":(($op=="A2")?"Confirmar Alteração":(($op=="I1")?"Incluir":(($op=="L1")?"GerarRelatório":(($op=="L2")?"Gerar P/Impressão":(($op=="L3")?"Imprimir":""))))))));
  $operacao=($operar!="")?("<button type='submit'>".$operar."</button>"):"";
  $botao=$inicio.$voltar.$limpar.$operacao;
  printf("$botao\n");
}
function terminapagina($PRG,$nome,$ra,$turno)
{ # função para Emitir as TAGs que terminam a página
  printf("  <hr width=75%%>\n");
  printf("<center><lgrey>$PRG - Sistema desenvolvido por: $nome | $ra |$turno</lgrey></center>\n");
  printf(" </body>\n");
  printf("</html>\n");
}
conectamy("localhost","root","","ilp20202t");
function montamenu($Tabela)
{ # esta função monta uma barra de menu no topo da tela dentro de uma <DIV> configurada no arquivo .CSS vinculado na página.
  printf("<divmenu><table><tr><td valign=top><red>$Tabela</red>:</td><td><form>");
  printf("<button type='submit' formaction='./incluir.php'>Incluir</button>");
  printf("<button type='submit' formaction='./consultar.php'>Consultar</button>");
  printf("<button type='submit' formaction='./alterar.php'>Alterar</button>");
  printf("<button type='submit' formaction='./excluir.php'>Excluir</button>");
  printf("<button type='submit' formaction='./listar.php'>Listar</button>");
  printf("</form></td></tr></table></divmenu><br><br>\n");
}
?>
