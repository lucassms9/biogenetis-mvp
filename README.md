# Biogenetics Applications
	Esse sistema é um MVP, construido exclusivamente para a biogenetics.

## Requisitos

## Configuração


### Ajustes

Montagem Croqui:
Devs: colocar select em cada pedido para gerar o croqui com os pedidos selecionados, devemos ter a opção de inclusão manual de paciente/amostra

Devs: incluir fluxo para impressão de etiquetas em massa


yum-config-manager --disable ol7_developer_php74
yum-config-manager --enable ol7_developer_php72


espero q vc nao fique bravo...
atualizei o php aqui...
como eu vi que o yum.repos.d estava configurado, eu so fiz o downgrade mesmo

rodei esses comandos aqui:

yum-config-manager --disable ol7_developer_php74
yum-config-manager --enable ol7_developer_php72

yum downgrade php\*
yum update

