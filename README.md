# SORTED LINKED LIST App

This is a fresh laravel 12 application for SORTED LINKED LIST (linked list that always keep values sorted)

# How does it work?
- It strictly uses the next_id column to insert, update, delete, or sort the list items.
- It never uses sorting from the DB by value or any other DB sorting mechanisms because it will contradict the sorted linked list concept.
- When adding, editing, or deleting, it traverses the list using the next_id column to determine the position of the item and its surroundings and updates them accordingly.

- In the Tests folder are different feature tests that makes sure the app works as intended.
- At the end of this file are screenshots of how the app looks visually.


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


### Screenshots:


![Screenshot from 2025-04-08 12-37-01](https://github.com/user-attachments/assets/4dda6d96-1d87-41e3-a9f6-d75e69e3855d)

![Screenshot from 2025-04-08 12-37-29](https://github.com/user-attachments/assets/607eae95-332f-4b48-b520-e48e3d50de9a)

![Screenshot from 2025-04-08 12-37-44](https://github.com/user-attachments/assets/0157f484-95fa-4cb1-b6e6-1ae9e7ac312f)

![Screenshot from 2025-04-08 12-38-05](https://github.com/user-attachments/assets/cf0f8d89-88b4-494d-b528-00caa350110b)

![Screenshot from 2025-04-08 12-38-26](https://github.com/user-attachments/assets/136a2572-2b15-466d-9630-e631d0cf7789)

![Screenshot from 2025-04-08 12-38-44](https://github.com/user-attachments/assets/933e00ec-e0a3-4381-9876-5e6aaf15fbf9)

![Screenshot from 2025-04-08 12-39-18](https://github.com/user-attachments/assets/15696773-8c6d-4540-9acc-f9aa84f586f2)

![Screenshot from 2025-04-08 12-40-37](https://github.com/user-attachments/assets/5249cde7-c0c4-45ee-a050-10d8fa066233)

![Screenshot from 2025-04-08 12-40-54](https://github.com/user-attachments/assets/8a341726-19ff-469e-b52f-bcf22b2ebf6b)

![Screenshot from 2025-04-08 12-41-08](https://github.com/user-attachments/assets/bc90db04-fe65-45a6-8638-62d36db00c91)

![Screenshot from 2025-04-08 12-42-35](https://github.com/user-attachments/assets/d6bbf4a5-d223-4b9e-9b43-88400e348b68)

![Screenshot from 2025-04-08 12-43-29](https://github.com/user-attachments/assets/d142b0ff-ed78-4b41-abcf-fa1ef44bd1f4)

![image](https://github.com/user-attachments/assets/998061ce-75ad-48d7-ac50-f33fafd9d528)

