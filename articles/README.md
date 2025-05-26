# Simple REST API in PHP

This project is a pure PHP REST API that manages in-memory "Article" data.

##How to Run

Run with PHP's built-in server:

```bash
php -S localhost

## copy folder articles pass on root path
## 1. Create an Article
curl -X POST http://localhost/articles \
-H "Content-Type: application/json" \
-d '{
  "title": "Nakivo Article",
  "content": "This is a test article.",
  "author": "Kelvin Nguyen"
}'

## 2. Get All Articles
curl http://localhost/articles

## 3. Get an Article by ID
curl http://localhost/articles/1

## 4. Update an Article
curl -X PUT http://localhost/articles/1 \
-H "Content-Type: application/json" \
-d '{
  "title": "Updated Title"
}'

## 5. Delete an Article
curl -X DELETE http://localhost/articles/1