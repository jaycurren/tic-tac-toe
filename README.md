# First time project setup

Copy env

```bash
cp .env.example .env
```

# Laravel sail setup

Install vendor

```bash
docker run --rm \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

Build the image

```
./vendor/bin/sail up --build -d
```

Enter the container

```bash
docker exec -it tic-tac-toe-curren bash
```

```bash
npm i
```

Generate key

```bash
php artisan key:generate
```

Run migrations and seed

```bash
php artisan migrate --seed
```

```bash
exit
```

```bash
docker exec -it tic-tac-toe-curren npm run dev
```

# Running image normally

```bash
./vendor/bin/sail up -d
```

```bash
docker exec -it tic-tac-toe-curren npm run dev
```

# Usage

Accessible on http://localhost:3000

Running tests

```bash
docker exec -it tic-tac-toe-curren php artisan test
```