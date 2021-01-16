<?php
#####################################################################################################################################################
# Nome do Programa: medicos.php
# Objetivo........: Administrar acesso às funcionalidades do sistema através de um menu montado em uma DIV no alto da tela.
# Descrição.......: Referencia os arquivos de funções e explica ao usuário como acessar as funcionalidades do sistema.
# Autor...........: JMH
# Criação.........: 2020-11-01 - Estruturação e desenvolvimento.
#                   2020-11-01 - Teste do Programa.
#####################################################################################################################################################
require_once("../toolskit.php");
require_once("./funcoes.php");
$cordefundo="#FFDEAD";
iniciapagina($cordefundo,"fornecedores","Abertura");
printf("Este sistema apresenta o menu de opções de funcionalidades no topo da tela.<br>\n");
printf("Este menu estará sempre disponível, menos na emissão da listagem para impressão.<br><br>\n");
printf("Este sistema foi desenvolvido como trabalho na disciplina de Linguagem de Programação - Web<br>\n");
printf("<table>\n");
printf("<tr><td valign=top>Dados do Desenvolvedor:</td></tr>\n");
printf("<tr><td>Nome:</td><td>SEU NOME COMPLETO, como está escrito no SIGA.</td></tr>\n");
printf("<tr><td>Matrícula:</td><td>nnnnnnnnn - somente números</td></tr>\n");
printf("<tr><td>Turno:</td><td>Tarde | Noite</td></tr>\n");
printf("</table>\n");
terminapagina("SeuPrograma.php","SEU NOME","RA","Tarde ou Noite");
?>
