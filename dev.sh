#!/bin/bash

# Цвета для вывода
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}Запускаем среду разработки...${NC}"

# Остановка и удаление существующего контейнера
echo -e "${GREEN}Останавливаем существующий контейнер...${NC}"
docker stop boostmode-dev 2>/dev/null || true
docker rm boostmode-dev 2>/dev/null || true

# Сборка образа для разработки
echo -e "${GREEN}Собираем Docker образ для разработки...${NC}"
docker build -t boostmode-dev -f Dockerfile.dev .

# Запуск контейнера для разработки
echo -e "${GREEN}Запускаем контейнер для разработки...${NC}"
docker run -d --name boostmode-dev \
  -p 5173:5173 \
  -v "$(pwd):/app" \
  -v /app/node_modules \
  -e NODE_ENV=development \
  -e VITE_API_URL=http://localhost:8000/api \
  --restart unless-stopped \
  boostmode-dev

# Проверка статуса
echo -e "${GREEN}Проверяем статус контейнера...${NC}"
docker ps | grep boostmode-dev

echo -e "${GREEN}Готово! Приложение доступно по адресу:${NC}"
echo -e "${GREEN}http://localhost:5173${NC}"
echo -e "${GREEN}Для просмотра логов используйте: docker logs -f boostmode-dev${NC}" 