https://github.com/open-telemetry/opentelemetry-php/issues/1436

# Method 1

```sh
cp .env.example .env
php artisan migrate
php artisan db:seed
php artisan serve
```

```sh
tail -f storage/logs/laravel.log
```

```sh
curl -v localhost:8000/api/users
```

# Method 2

```sh
cp .env.example .env
docker build -t otlp/laravel .
docker run --name otlp-laravel --rm -p 8111:80 otlp/laravel
docker exec -it otlp-laravel php artisan migrate
docker exec -it otlp-laravel php artisan db:seed
```

```sh
docker exec -it otlp-laravel tail -f storage/logs/laravel.log
```

```sh
curl -v localhost:8111/api/users
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
