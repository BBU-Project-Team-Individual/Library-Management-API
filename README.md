# Library Management API

A Laravel REST API for managing authors, book types, books, and the relationship between books and authors.

## Requirements

- PHP 8.3 or newer
- Composer
- Docker Desktop
- MySQL 8.0 (provided by Docker)
- DBeaver (optional database viewer)
- Postman

This project uses Laravel 13.

## Installation

```bash
cd "/Users/ingvanly/projects/Practice-Flutter/Flutter APi Lab1"
composer install
cp .env.example .env
php artisan key:generate
```

## MySQL with Docker

Start a MySQL container:

```bash
docker run --name library_api_mysql \
  -e MYSQL_DATABASE=flutter_api_lab1 \
  -e MYSQL_ROOT_PASSWORD=root \
  -e MYSQL_USER=laravel \
  -e MYSQL_PASSWORD=laravel \
  -p 3306:3306 \
  -d mysql:8.0
```

Check that it is running:

```bash
docker ps
```

Update `.env` with the Docker MySQL connection:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flutter_api_lab1
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

The Docker database values are:

| Setting | Value |
|---|---|
| Host | `127.0.0.1` |
| Port | `3306` |
| Database | `flutter_api_lab1` |
| Username | `laravel` |
| Password | `laravel` |

Run the migrations after MySQL is ready:

```bash
php artisan migrate
```

Do not use `migrate:fresh` unless you intentionally want to delete all existing data.

## Connect with DBeaver

Create a new **MySQL** connection in DBeaver with:

- Host: `localhost`
- Port: `3306`
- Database: `flutter_api_lab1`
- Username: `laravel`
- Password: `laravel`

Click **Test Connection**, then **Finish**. The four application tables are created after running `php artisan migrate`.

If DBeaver cannot connect, check that Docker Desktop is open and run:

```bash
docker ps
docker logs library_api_mysql
```

## Start the API

Start the server on port `8001`:

```bash
php artisan serve --port=8001
```

Keep this Terminal window open. The API base URL is:

```text
http://localhost:8001/api
```

Check the routes with:

```bash
php artisan route:list --path=api
```

## Postman setup

Use `http://localhost:8001` in every Postman URL. For `POST` and `PUT` requests, select **Body**, **raw**, and **JSON**.

Do not type `{id}` literally. Replace it with a real ID, such as `1`.

## API endpoints

### Book types

| Operation | Method | URL |
|---|---|---|
| List | GET | `/api/book-types` |
| View one | GET | `/api/book-types/{BookTypeID}` |
| Create | POST | `/api/book-types` |
| Update | PUT/PATCH | `/api/book-types/{BookTypeID}` |
| Delete | DELETE | `/api/book-types/{BookTypeID}` |

Create body:

```json
{
  "BookTypeName": "Novel"
}
```

Update body:

```json
{
  "BookTypeName": "Historical Novel"
}
```

### Authors

| Operation | Method | URL |
|---|---|---|
| List | GET | `/api/authors` |
| View one | GET | `/api/authors/{AuthorID}` |
| Create | POST | `/api/authors` |
| Update | PUT/PATCH | `/api/authors/{AuthorID}` |
| Delete | DELETE | `/api/authors/{AuthorID}` |

Create body:

```json
{
  "AuthorName": "Gabriel Garcia Marquez",
  "Gender": "Male",
  "DOB": "1927-03-06",
  "POB": "Colombia",
  "Address": "Colombia",
  "Phone": "012345678",
  "Email": "gabriel@example.com",
  "Photo": "gabriel.jpg"
}
```

### Books

| Operation | Method | URL |
|---|---|---|
| List | GET | `/api/books` |
| View one | GET | `/api/books/{BookID}` |
| Create | POST | `/api/books` |
| Update | PUT/PATCH | `/api/books/{BookID}` |
| Delete | DELETE | `/api/books/{BookID}` |

`BookTypeID` must exist before creating a book.

Create body:

```json
{
  "BookTitle": "One Hundred Years of Solitude",
  "BookTypeID": 1,
  "PublishDate": "1967-05-30",
  "NumOfPages": 417,
  "NumOfCopies": 5,
  "Edition": "First Edition",
  "Publisher": "HarperCollins",
  "BookSource": "Library",
  "Remark": "Good condition"
}
```

Update body:

```json
{
  "BookTitle": "Updated Book Title",
  "NumOfCopies": 10
}
```

### Book-author relationships

The `tblBookAuthor` table uses both `BookID` and `AuthorID` as its identifier.

| Operation | Method | URL |
|---|---|---|
| List | GET | `/api/book-authors` |
| View one | GET | `/api/book-authors/{BookID}/{AuthorID}` |
| Create | POST | `/api/book-authors` |
| Update | PUT/PATCH | `/api/book-authors/{BookID}/{AuthorID}` |
| Delete | DELETE | `/api/book-authors/{BookID}/{AuthorID}` |

Create body:

```json
{
  "BookID": 1,
  "AuthorID": 1,
  "AuthorDate": "1967-05-30",
  "Remark": "Main author"
}
```

Update body:

```json
{
  "AuthorDate": "1967-06-01",
  "Remark": "Updated author information"
}
```

## Recommended testing order

1. Create a book type.
2. Create an author.
3. Create a book using the returned `BookTypeID`.
4. Create a book-author relationship using the returned `BookID` and `AuthorID`.
5. Test list and view requests.
6. Test update requests.
7. Delete the book-author relationship.
8. Delete the book and author.
9. Delete the book type.

The relationship must be deleted before its book or author. A book type must be deleted after its books.

## Verification

```bash
php artisan test --compact
vendor/bin/pint --dirty --format agent
```

## Common problems

### 404 Not Found

Make sure the server is running and the URL includes the correct port and resource:

```text
http://localhost:8001/api/book-types
```

`http://localhost:8001/api` by itself is not an endpoint.

### 422 Unprocessable Entity

The endpoint is working, but validation failed. Check that the JSON body is not empty and that field names match exactly, including capitalization.

### 404 for a specific ID

The requested record does not exist. First call the list endpoint, copy the real ID, and use it without curly brackets:

```text
Correct: /api/book-types/1
Wrong:   /api/book-types/{1}
```

### Database connection error

Make sure Docker Desktop is open, the MySQL container is running, and the `DB_*` values in `.env` match the Docker values above.

To stop the database container:

```bash
docker stop library_api_mysql
```

To remove the container and its database data:

```bash
docker rm library_api_mysql
```
