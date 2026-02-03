#!/bin/bash

set -e

echo "🚀 Starting Notes Service..."

docker-compose down 2>/dev/null || true
docker-compose up -d --build

echo ""
echo "⏳ Waiting for services to start..."
sleep 20

echo ""
echo "✅ Notes Service is ready!"
echo ""
echo "📍 Access:"
echo "   - App:        http://localhost:8080"
echo "   - API:        http://localhost:8080/api/notes"
echo "   - Swagger:    http://localhost:8080/docs/swagger-ui.html"
echo "   - phpMyAdmin: http://localhost:8081"
echo ""
