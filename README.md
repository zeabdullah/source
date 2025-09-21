<img src="./readme/title1.svg"/>

<br><br>

<!-- project overview -->
<img src="./readme/title2.svg"/>

> **Source is the hub for your product team's assets.**
>
> It is the _Source_ of truth for all of what your users see in a product. Source allows you to bring your app's flows, marketing assets, releases, and design assets under an AI-powered workspace for seamless collaboration, hand-off, and auditing.

<br><br>

<!-- System Design -->
<img src="./readme/title3.svg"/>

### ER Diagrams

Thanks to [Eraser's](https://eraser.io) simple yet powerful diagrams as code feature, it has helped me quickly map out and revise my database schemas progressively.

You can find [Source's ER Diagram here](https://app.eraser.io/workspace/8FQjCJxkMfRbnclguIwf?origin=share&elements=S5rvZyZoHdEj9nQ0_fGEnQ).

<a href="https://app.eraser.io/workspace/8FQjCJxkMfRbnclguIwf?origin=share&elements=S5rvZyZoHdEj9nQ0_fGEnQ" title="Source ERD"><img src='./readme/db/eraser1.svg' alt="Source ERD" /></a>

<br><br>

<!-- Project Highlights -->
<img src="./readme/title4.svg"/>

### Main Features

#### AI Audits & Reviews

-   Let the AI quickly review your flows and suggest adjustments, rate the flows on specific scales/criteria, measure compliance, and more

#### AI Edits using agents

-   consolidated flows allow for reliable edits and automations. Communicate and act on your assets with the help of AI.

#### Integration with popular tools

-   Source integrates with popular tools that you already use, like **Figma** and **Brevo**, to help you seamlessly work on what's familiar and effective, all while syncing your work.

<br><br>

<!-- Demo -->
<img src="./readme/title5.svg"/>

### Admin Screens (Web)

| Login screen                            | Register screen                       |
| --------------------------------------- | ------------------------------------- |
| ![Landing](./readme/demo/1440x1024.png) | ![fsdaf](./readme/demo/1440x1024.png) |

<br><br>

<!-- Development & Testing -->
<img src="./readme/title6.svg"/>

### Some Snippets

#### AI

| Figma AI Service                                       | Figma AI System Instruction                              |
| ------------------------------------------------------ | -------------------------------------------------------- |
| ![AI Service](./readme/backend/generateFigmaReply.png) | ![fsdaf](./readme/backend/getFigmaSystemInstruction.png) |

#### Tests

| Tests                                             |                                                   |
| ------------------------------------------------- | ------------------------------------------------- |
| ![Test Suite 1](./readme/backend/tests/test1.png) | ![Test Suite 2](./readme/backend/tests/test1.png) |
|                                                   |                                                   |
| ![Test Suite 3](./readme/backend/tests/test1.png) | ![Test Suite 4](./readme/backend/tests/test1.png) |

### Tech Stack

-   **Angular** with **PrimeNG** for frontend web UI
-   **Laravel** backend using Sanctum
-   **PostgreSQL** as the primary database
-   **Figma** integration for design asset syncing
-   **Brevo** integration for email campaigns
-   **Docker** for containerized development and deployment
-   **Gemini API** for AI features
-   **n8n** for workflow automation and integrations

### Local Installation

#### Prerequisites

Before setting up the development environment, ensure you have the following installed:

-   [**Node.js**](https://nodejs.org/) (v22.17.0 or higher)
-   **pnpm** (v10 or higher) - Install with `npm install -g pnpm`
-   [**PHP**](https://www.php.net/downloads) (v8.3 or higher) and **Composer**
-   **[Docker](https://www.docker.com) & Docker Compose**
-   [**PostgreSQL**](<(https://www.postgresql.org/download/)>) (v17.6 or higher)

#### Quick Start (Docker)

1. **Clone the repository**

```bash
git clone <repository-url>
cd source
```

1. **Set up environment variables**

```bash
# 1. Copy environment files
cp .env.example .env # Root env for everything other than Laravel
cp server/.env.example server/.env # env for Laravel

# 2. Fill in .env variables

# 3. Generate Laravel app key
cd server && php artisan key:generate
```

1. **Start all services with Docker**

    ```bash
    docker compose up -d
    ```

This first step will boot up all services: frontend, backend, n8n, and database.

2. **Run database migrations inside backend container**

```bash
# 1. Run the needed services
docker compose up -d

# 2. Use locally provided script
# Basically runs `php artisan migrate` inside the `server` container
cd ./server/run-migrations-docker.sh

```

1. **Access the applications**
    - **Frontend (Angular)**: http://localhost:4200
    - **Backend (Laravel)**: http://localhost:8000
        - Since it's only API routes, use http://localhost:8000/api
    - **n8n**: http://localhost:5678
    - **PostgreSQL**: localhost:5432

#### Figma Plugin Setup (no Docker)

Since the docker setup does not handle running the Figma plugin, it must be launched **locally** to work.

```bash
cd plugin

# Install dependencies
pnpm install

# Build the plugin
pnpm build

# Watch for changes during development
pnpm watch
```

#### Environment Configuration

**REQUIRED Environment Variables:**

-   `GEMINI_API_KEY` - For AI features
-   `N8N_BASIC_AUTH_PASSWORD` - For n8n authentication
-   `N8N_ENCRYPTION_KEY` - For n8n data encryption
-   `POSTGRES_PASSWORD` - Database password
-   `BASIC_AUTH_USERNAME` & `BASIC_AUTH_PASSWORD` - For webhook authentication

#### Development Workflow

1. **Start all services**

```bash
# Terminal 1: Everything but frontend
docker compose up

# Terminal 2: Frontend
cd client && pnpm start
```

2. **Run tests**

```bash
# Run tests on the backend
cd server && php artisan test

# Run tests on the frontend
cd client && pnpm test
```

#### Troubleshooting

-   **Port conflicts**: Ensure ports 4200, 8000, 5432, and 5678 are available
-   **Database connection**: Verify PostgreSQL is running and credentials are correct
-   **CORS issues**: Check `SANCTUM_STATEFUL_DOMAIN` in `server/.env`
-   **Plugin development**: Use `pnpm watch` for hot reloading

<br><br>

<!-- Deployment -->
<img src="./readme/title7.svg"/>

| Postman API 1                           | Postman API 2                         | Postman API 3                         |
| --------------------------------------- | ------------------------------------- | ------------------------------------- |
| ![Landing](./readme/demo/1440x1024.png) | ![fsdaf](./readme/demo/1440x1024.png) | ![fsdaf](./readme/demo/1440x1024.png) |

<br><br>
