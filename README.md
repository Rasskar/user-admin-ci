## Как установить

1. Клонируем проект
```bash
git clone git@github.com:Rasskar/user-admin-ci.git
```
2. Создаём env из шаблона
```bash
cp env .env
```
3. Запускаем Docker контейнеры
```bash
docker-compose up -d
```
4. Настраиваем права
```bash
docker exec -it codeigniter_app chown -R www-data:www-data /var/www/writable
docker exec -it codeigniter_app chmod -R 775 /var/www/writable
```
5. Затем откройте [localhost:8080](http://localhost:8080) 