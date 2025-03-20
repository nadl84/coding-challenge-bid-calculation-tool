#!/bin/bash

# Create ssl directory if it doesn't exist
mkdir -p infrastructure/ssl

# Generate SSL certificate and key
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout infrastructure/ssl/key.pem \
    -out infrastructure/ssl/cert.pem \
    -subj "/C=US/ST=State/L=City/O=Organization/CN=localhost"

# Set proper permissions
chmod 644 infrastructure/ssl/cert.pem
chmod 644 infrastructure/ssl/key.pem

echo "SSL certificates generated successfully!" 