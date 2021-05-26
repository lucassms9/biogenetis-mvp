# Biogenetics Applications
	Esse sistema é um MVP, construido exclusivamente para a biogenetics.

Senha do pfx: Bio@2021
## Requisitos

## Configuração
para funcionar o envio de laudo por email

deve-se:
- finalizar um pedido
- chamar /admin/pedidos/generateFile para gerar os pdfs
- chamar /api/pedidos/dispatchEmails para enviar os pdfs

- seeder

bin/cake bake seed Articles

apply
bin/cake migrations seed
