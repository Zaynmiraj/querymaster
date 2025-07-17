## QueryMaster PHP Database Model

This is an advanced PHP database model designed to work with MySQL, PostgreSQL, and SQLite databases. It provides a simple and flexible way to perform CRUD operations, handle prepared statements, pagination, and more. This model is easy to use, secure, and can be extended to fit your application needs.

## Features

Multi-Database Support: Works with MySQL, PostgreSQL, and SQLite.

CRUD Operations: Perform Create, Read, Update, and Delete operations.

Prepared Statements: Prevent SQL injection and ensure security.

Pagination: Easily fetch data in pages.

Find Records: Methods to find records by condition and by ID.

Error Handling: Built-in exception handling for better error management.

## Installation

    Prerequisites
    PHP 7.0 or higher

Composer (optional, if you want to manage dependencies)

A MySQL, PostgreSQL, or SQLite database

## Clone the Repository

```
git clone https://github.com/Zaynmiraj/querymaster
```

## Setup

1. Download the code and move it to your project folder.

2. Include the QueryMaster.php file where you want to use the model.

3. Instantiate the Database Object and configure the connection details (host,username, password, database name).

4. Start using the methods as described below.

## Usage

Initialize the Database
To begin using the model, instantiate the QueryMaster class and provide the necessary database connection details.

```
require_once 'QueryMaster.php';

try {
    // MySQL Example
    $db = new QueryMaster('mysql', 'localhost', 'root', '', 'test_db');

    // PostgreSQL Example
    // $db = new QueryMaster('pgsql', 'localhost', 'postgres', 'password', 'test_db');

    // SQLite Example
    // $db = new QueryMaster('sqlite', null, null, null, 'path/to/database.db');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Insert Data

Insert a new record into the database using the insert() method.

```
$insertData = ['name' => 'ZaYn Miraj', 'email' => 'zaynmiraj@example.com'];
$db->insert('users', $insertData);
```

## Update Data

Update existing data using the update() method. Specify the table, data, and condition.

```
$updateData = ['name' => 'Jane Doe'];
$db->update('users', $updateData, "id = 1");
```

## Delete Data

Delete a record using the delete() method by providing a condition.

```
$db->delete('users', "id = 2");
```

## Select Data (Read)

Select Multiple Records
Fetch multiple records with a condition using the findByMany() method.

```
$users = $db->findByMany('users', "status = 'active'");
print_r($users);
```

## Select a Single Record

Fetch a single record by condition with findByOne().

```
$user = $db->findByOne('users', "email = 'john@example.com'");
print_r($user);
```

## Get a Record by ID

Fetch a record by its id using the getById() method.

```
$user = $db->getById('users', 1);
print_r($user);
```

## Pagination

Fetch paginated results for large datasets using the paginate() method. Specify the page number and the number of records per page.

```
$users = $db->paginate('users', '*', "1", 1, 10); // Page 1, 10 records per page
print_r($users);
```

## Error Handling

The model uses exceptions to handle errors. If an error occurs (e.g., a connection failure), it will throw an exception that you can catch and handle.

```
try {
    // Your database operations
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Close Connection

After you’re done with the database operations, close the connection to the database.

```
$db->close();

```

## Database Supported

MySQL: Default for most projects, reliable and fast.

PostgreSQL: Supports advanced SQL features, great for complex queries.

SQLite: Lightweight and self-contained for small projects or local development.

## Contribution Guidelines

Fork the repository: Create your own fork to work on.

Create a branch: Create a new branch for each feature or bug fix.

Submit a pull request: After making your changes, submit a pull request with a description of what you have done.

Follow the code style: Use PSR-12 for code formatting.

## MIT License

Copyright (c) 2025 ZaYn Miraj
