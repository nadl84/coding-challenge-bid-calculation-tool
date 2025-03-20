# Vehicle Fee Calculator

A web application built with Symfony and Vue.js that calculates various fees for vehicles based on their type and base price.

## Prerequisites

- Docker
- Docker Compose
- Make

## Quick Start

### First time setup
```bash
# Install dependencies and build assets
make install

# Start the application
make start

# Stop the application
make stop
```

### Cleanup
```bash
# Remove containers, volumes, and node_modules
make clean
```

The application will be available at: https://localhost

### Running Tests

Execute PHPUnit tests:
```bash
make test
```

### Accessing the Application

- Main application: https://localhost
- API endpoint: https://localhost/api/vehicles/fees/calculate

### API Usage

The fee calculator endpoint accepts POST requests with the following form data:
- `basePrice`: The base price of the vehicle (float)
- `type`: The type of vehicle ("common" or "luxury")

Example using curl:
```bash
curl -X POST https://localhost/api/vehicles/fees/calculate \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "basePrice=1000&type=common"
```
