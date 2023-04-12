# Leitor QR

## Descrição
Este projeto é um leitor de QR Code para abrir um portão utilizando um Raspberry Pi, um módulo de relay e um código QR válido. Ele verifica se a fatura associada ao código QR é válida e, se for, aciona o portão.

## Requisitos
- Raspberry Pi
- Módulo de relay
- Leitor de QR Code
- PHP instalado no Raspberry Pi
- Apache
- MySQL

## Instalação

1. Clone este repositório no seu Raspberry Pi.
2. Configure as credenciais de conexão com o banco de dados no arquivo db-config.php.
3. Crie uma tabela chamada faturas_corretas no banco de dados MySQL com as seguintes colunas: ID, NIF, Valor, Época.
4. Certifique-se de que o servidor web está configurado para servir o arquivo index.php como o arquivo principal.
5. Conecte o módulo de relé ao Raspberry Pi.
6. Configure o pino de controle do relé no script Python teste.py (a configuração padrão é P17).

## Utilização
- Navegue até a página web do projeto no navegador.
- Passe o código QR no leitor e aguarde a validação da fatura.
- Se a fatura for válida, o portão será acionado. Caso contrário, nada acontecerá.

## Arquivos
- index.php: arquivo principal do projeto que exibe a interface do leitor de QR Code e processa os dados.
- db-config.php: arquivo de configuração do banco de dados MySQL.
- teste.py: script Python para controlar o módulo de relé e acionar o portão.
