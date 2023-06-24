# StarGrid – Teste Desevolvedor(a) Backend N3

## Meu raciocínio inicial

Assim que clonei o projeto e li o readme, analisei no seguinte fluxo: 
- Vi que o projeto está usando Laravel 7 e para usarmos as melhores ferramentas possíveis, vou migrar o projeto para laravel 10 e PHP 8.2. 
1. As rotas que existem e ao que elas levam. Vi que podem ser simplificadas com o metodo apiResource e logo vejo uma melhoria.
2. O código dentro dos Controllers, onde as funções não estão no padrão REST do laravel e com code smell de implementações que podem ser mais manuteníveis e limpas.  
3. Vejo que a responsabilidade da função de listar os Reports acaba criando um registro de um Report no banco, o que não deveria acontecer.

## Como eu agi:

1. Migrar o projeto para Laravel 10 conforme a documentação
- 7 para 8 https://laravel.com/docs/8.x/upgrade#upgrade-8.0
- 8 para 9 https://laravel.com/docs/9.x/upgrade
- 9 para 10 https://laravel.com/docs/10.x/upgrade

2. Ao instalar o laravel sail, percebi que o projeto não tem um .env e nem mesmo um .env.example. Adicionei os dois ao projeto.
