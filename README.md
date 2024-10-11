# Chat Application

A simple chat application built with PHP (using the Slim Framework) for the backend and vanilla JavaScript for the frontend. Users can register, create/join groups, and send messages within those groups.

## Table of Contents
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Setup and Installation](#setup-and-installation)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Frontend](#frontend)
- [Database Schema](#database-schema)
- [Contributing](#contributing)
- [License](#license)

## Features
- User registration with a unique token
- Group creation and joining
- Sending messages within a group
- Viewing chat messages in real-time

## Technologies Used
- **Backend**: PHP, Slim Framework, SQLite
- **Frontend**: HTML, CSS, JavaScript
- **Middleware**: CORS for handling cross-origin requests
- **Environment Variables**: Dotenv for configuration management

## Setup and Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/yourusername/chat-app.git
    cd chat-app
    ```

2. **Install backend dependencies:**

    ```bash
    composer install
    ```

3. **Set up environment variables:** Create a `.env` file in the root directory and define the following variable:

    ```plaintext
    DB_PATH=database/database.sqlite
    ```

4. **Run migrations:** Execute the migrations file to create the necessary database tables:

    ```bash
    php migrations.php
    ```

5. **Start the backend server:** Use PHP’s built-in server to run the application:

    ```bash
    php -S localhost:8000 -t public
    ```

6. **Open the frontend:** Use `http-server` to serve the frontend files. If you haven't installed it yet, you can do so globally using npm:

    ```bash
    npm install -g http-server
    ```

    Then, navigate to your frontend directory and run:

    ```bash
    http-server -p 3000 --cors
    ```

## Usage
- **Register User:** Enter a username and click "Register". A unique token and user ID will be displayed.
- **Create/Join Group:** Enter a group name and click "Create Group" to create a new group, or "Join Group" to join an existing one.
- **Send Messages:** Type a message in the input field and click "Send Message" to send it to the joined group.
- **View Messages:** All messages in the current group will be displayed in the messages container.

## API Endpoints

| Method | Endpoint                          | Description                     |
|--------|-----------------------------------|---------------------------------|
| POST   | `/users`                          | Register a new user            |
| POST   | `/groups`                         | Create a new group             |
| GET    | `/grouplists`                    | Get a list of all groups       |
| GET    | `/groups/{id}/messageslist`      | Get all messages in a group    |
| POST   | `/groups/{group_id}/messages`    | Send a message to a group      |

## Frontend
The frontend consists of a simple HTML page (`index.html`) that interacts with the backend API through AJAX requests made with JavaScript. The interface is user-friendly, providing sections for user registration, group management, and messaging.

### Main Components
- `index.html`: The main HTML structure of the chat application.
- `style.css`: Basic styling for the application.
- `app.js`: JavaScript functionality for handling user interactions and API requests.

## Database Schema
The application uses an SQLite database with the following tables:

### Users
- `id`: INTEGER PRIMARY KEY AUTOINCREMENT
- `username`: TEXT NOT NULL UNIQUE
- `token`: TEXT NOT NULL UNIQUE

### Groups
- `id`: INTEGER PRIMARY KEY AUTOINCREMENT
- `name`: TEXT NOT NULL UNIQUE

### Messages
- `id`: INTEGER PRIMARY KEY AUTOINCREMENT
- `group_id`: INTEGER (FOREIGN KEY)
- `user_id`: INTEGER (FOREIGN KEY)
- `message`: TEXT NOT NULL
- `timestamp`: DATETIME DEFAULT CURRENT_TIMESTAMP

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request with your changes.

## License
This project is licensed under the MIT License. See the LICENSE file for details.
