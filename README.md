# LINKED LIST SORTED App

This is a fresh laravel 12 application for LINKED LIST SORTED (linked lists that keeps values always sorted)

# How it works?
- It strictly uses the next_id column to insert, update, delete or sort the items of the list.
- It never uses sorting from the table by value or any other DB sorting mechanisms because it contradicts with sorted linked list concept.
- When adding, editing or deleting it travers the list using the next_id column to determine the position of the item and its surrounding and update them accordingly.


## Requirements

Before getting started, ensure you have the following installed:

- **Windows:**
    - [Docker Desktop](https://www.docker.com/products/docker-desktop/)
    - [Windows Subsystem for Linux 2 (WSL2)](https://learn.microsoft.com/en-us/windows/wsl/install) (installed and enabled)

- **Mac:**
    - [Docker Desktop](https://www.docker.com/products/docker-desktop/)

- **Linux:**
    - [Docker Engine](https://docs.docker.com/engine/install/)
    - [Docker Compose](https://docs.docker.com/compose/install/)

## Installation

### 1. Clone the Repository

Clone the repository to your local machine:

```
git clone git@github.com:abdel-aouby/sorted-linked-list-laravel.git
cd sorted-linked-list-laravel
```

### 1.1 Installing Composer Dependencies for Existing Applications

Run the following code
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### 2. Run the application

```
./vendor/bin/sail up
```
or detach mode with `-d` flag

```
./vendor/bin/sail up -d
```

### 3. Install FE/BE dependencies

```
./vendor/bin/sail composer install
./vendor/bin/sail npm install
```

### 4. Set up the environment file

```
cp .env.example .env
```

### 5. Generate application key

```
./vendor/bin/sail artisan key:generate
```

### 6. Run database migrations

```
./vendor/bin/sail artisan migrate
```

OR if you would like to have some init lists in the app

```
./vendor/bin/sail artisan migrate --seed
```

### 7. Compile frontend assets

```
./vendor/bin/sail npm run dev
```

### 8. Access the application

Open your browser and navigate to `http://localhost`.

---


### Tests:

#### 1. Run tests locally
    ./vendor/bin/sail artisan test
