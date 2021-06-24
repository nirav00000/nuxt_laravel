# Web

This folder contains all front end related things.

## Requirements
:heavy_check_mark: Make sure `Node v14.x.x` installed on your machine.

:heavy_check_mark: Make sure `NPM v6.x.x` installed on your machine.

## Environment Variables

we used environment variables to change the view of staging, and development.

**NOTE:** 
- In the **Front end,** only pick `.env` *file* specified environments variables, It does not pick `OS` environments variables.  

- If you does not specify `MODE` environment variable it will run in `production`

**Full development .env**

```bash
MODE=development # It can be production, stagging, testing, and development
PORT=3000 # Port number on that web will listen
HOST=localhost # Host to run web will listen 
BASE_URL=localhost:3000 # Full path
API_URL=localhost:8000 # That points to API endpoint (Proxy pass)
```

## Usage

Make sure you have `Node` and `NPM` installed on your machine.

```bash
# Package Installation
$> npm install

# Spin up Web in development
$> npm run dev

# Build the project
$> npm run build

# Test project
$> npm run test
```

## Linting

`ESLint`

- `ESLint` is a tool for identifying and reporting on patterns found in ECMAScript/JavaScript code, with the goal of making code more consistent and avoiding bugs.

    ```bash
    # Lint the project before commit
    # This command will run eslint
    $> npm run lint
    ```

## Useful Links

Useful links for front end development.

- `Javascript Style Guide` - [https://github.com/airbnb/javascript](https://github.com/airbnb/javascript)
- `NuxtJS` - [https://nuxtjs.org/docs/2.x/get-started/installation](https://nuxtjs.org/docs/2.x/get-started/installation)
