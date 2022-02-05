#rodar os comandos:

- git clone https://github.com/RonaldoSurdi/pokerclubs-admin.git
- composer install
- php artisan key:generate


# PROJETO
Projeto em Laravel 5.5 [Laravel documentation](http://laravel.com/docs/contributions).

- Admim Login 
- Configs para Ptbr, Timezone SaoPaulo
- Html Forms: https://laravelcollective.com/docs/master/html
- Socialite: https://github.com/laravel/socialite
        exemplo de como configurar http://www.laravel.com.br/laravel-5-socialite/
- template limitless - http://demo.interface.club/limitless/layout_2/LTR/default/index.html
- Log view "ARCANEDEV" https://github.com/ARCANEDEV/LogViewer/blob/master/_docs/1.Installation-and-Setup.md

## Comandos
- Criar o model, migrate e controller: <b> php artisan make:model Product -m -cr </b>
    <br>com esse comando ja cria o pacote completo de um objeto, colocar o nome no singular
- só o Controller: php artisan make:controller PessoasController --resource
- rodar um seed especifico: php artisan db:seed --class=UsersTableSeeder


## License
The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
