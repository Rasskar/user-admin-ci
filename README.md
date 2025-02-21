## Описание
Веб-приложение на CodeIgniter 4, включающее функции аутентификации пользователей, контроля доступа на основе ролей,
управления пользователями и экспорта данных.

## Как установить
1. Клонируем проект
```bash
git clone git@github.com:Rasskar/users-admin-ci.git
```
2. Создаём env из шаблона
```bash
cp env .env
```
3. Запускаем Docker контейнеры
```bash
docker-compose up -d
```
4. Переходим в ci_app контейнер 
```bash
docker exec -it ci_app bash
```
5. Настраиваем права
```bash
chown -R www-data:www-data /var/www/writable
chmod -R 777 /var/www/writable
```
6. Устанавливаем зависимости
```bash
composer install
```
7. Запускаем миграции
```bash
php spark migrate --all
```
8. Можем тестово наполнить БД
```bash
php spark db:seed UsersSeeder
```
9. Выходим из ci_app контейнера
```bash
exit
```
10. Затем откройте [localhost:8080](http://localhost:8080) вы должны увидеть форму авторизации
11. В другой вкладке откройте [localhost:8025](http://localhost:8025/) должны увидеть локальный почтовый сервер MailHog
## Информация
После запуска миграций создаётся первый пользователь Admin 
Данные для входа под админом находятся в .env