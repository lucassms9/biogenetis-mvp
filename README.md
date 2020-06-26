# Biogenetics Applications
	Esse sistema é um MVP, construido exclusivamente para a biogenetics.

## Requisitos

## Configuração
configurar o



### Ajustes


1) A especificação anterior sobre o reconhecimento dos arquivos que te passei continuam valendo. Vamos lá:

FTIR – Swab
FTIR – Saliva
LCMS – Swab
LCMS – Saliva

- O arquivo do equipamento FTIR é aquele que no cabeçalho tem as palavras (Wavenumber, Intensity), é um CSV:
                - Dentro do IF que você testa esse conteúdo, incluir um segundo IF, que é verificar se no nome do arquivo tem SWAB ou SALIVA (testar caixa alta e caixa baixa).
                - Se é Swab vai para um ou alguns EndPoints, se Saliva vai para outro ou outros.

- O arquivo do equipamento LCMS é aquele que pode ser um TXT ou um XLS e no cabeçalho tem as palavras abaixo:
                - TXT: #MS Peaks One: + Scan (rt: 0.063 min) - calibrante1062020_2.d (calibrante1062020_2.d)  #  Center X            Height
                - XLS: Peak List
                Seguir o mesmo critério de cima:
                          - Dentro do IF que você testa esses conteúdos, incluir um segundo IF, que é verificar se no nome do arquivo tem SWAB ou SALIVA (testar caixa alta e caixa baixa).
                          - Se é Swab vai para um EndPoint, se Saliva vai para outro.
Me surgiu só uma dúvida. A opção Dashboard foi ajustada para reconhecer estes tipos diferentes? Seria interessante colocar filho para pesquisa lá.




2) Na Tabela EndPoints incluir os seguintes campos:
        - StatusEndPoint - Ativo ou Inativo; (Para o EndPoint inativo não enviar a amostra que será processada).
        - EndPointPath - Caminho completo do Endpoint;
        - EquipType - Tipo do Equipamento (LCMS ou FTIR);
        - SampleType - Tipo da Amostra (SWAB ou SALIVA);
        - IAModelType - (ML - Machine Learning ou DL Deep Learning);
        - IAModelName - (Nome do Modelo usado: KNN, Random Forest, LCTM, etc).
        - DataScience - (Anderson, Murillo, Wagner, Mário).
        Enviar a amostra para todos os EndPoints que estão Ativos. Considerando o EquipType e o SampleType.

______________________


3) Criar uma tabela intermediária que recebe os retornos de todos os EndPoints. Amarrada com a tabela de EndPoints para sabermos de onde veio cada resultado.
    - Nesta tabela deveremos ter, para cada combinação de amostra e equipamento, o mesmo número de endpoints ativos.
    - Imaginando que temos 6 Endpoints ativos para FTIR/Saliva, o retorno deve ser 6 linhas a mais nesta tabela.
    - Se a maior contagem de resultados for Positivo, o resultado na tabela definitiva de resultados deverá ser Positivo para a referida amostra;
    - Se a maior contagem de resultados for Negativo, o resultado na tabela definitiva de resultados deverá ser Negativo para a referida amostra;
    - Se a contagem de resultados for igual para Positivos e Negativos, o resultado na tabela definitiva de
    resultados deverá ser Indeterminado para a referida amostra.


A identificação da amostra será a chave para amarrar todos esses caras. Assim fica fácil de fazer auditoria depois. Na versão que temos a amostra não pode ser reprocessada, mas acho que poderíamos permitir isso agora. Como teremos o número do lote, a chave deve ser amostra/lote, assim teremos mais facilidade para fazer os testes, pois não vamos precisar ficar limpando o banco de dados toda hora, além de poder comparar resultados.


4) Em relação ao Dashboard vamos precisar criar mais dois campos de filtragem lá:
    - Campo Equipamento: LCMS ou FTIR;
    - Campo Amostra: Saliva ou Swab
    - Quando abrir o Dashboard vem com tudo, mas teremos essas opções de filtragem, além das que já temos.
