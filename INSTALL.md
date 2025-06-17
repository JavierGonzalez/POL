# Installation using Docker

This project includes a Docker configuration for local development.

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/)

## Setup

1. Copy `+passwords.ini.sample` to `+passwords.ini` and adjust the values if necessary.
2. Build and start the containers:

   ```bash
   docker-compose up --build
   ```

3. (Optional) Import the SQL dump located at `database/virtualpol.sql` into the running MySQL container.
4. Once the services are running, open `http://localhost` in your browser.

To stop the containers, run `docker-compose down`.
