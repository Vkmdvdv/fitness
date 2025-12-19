#!/bin/bash

# Цвета для вывода
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}Начинаем сборку и запуск приложения...${NC}"

# Остановка и удаление существующего контейнера
echo -e "${GREEN}Останавливаем существующий контейнер...${NC}"
docker stop boostmode-app 2>/dev/null || true
docker rm boostmode-app 2>/dev/null || true

# Сборка образа
echo -e "${GREEN}Собираем Docker образ...${NC}"
docker build -t boostmode-app -f Dockerfile.prod .

# Запуск контейнера
echo -e "${GREEN}Запускаем контейнер...${NC}"
docker run -d --name boostmode-app -p 80:80 -p 443:443 --restart always boostmode-app

# Проверка статуса
echo -e "${GREEN}Проверяем статус контейнера...${NC}"
docker ps | grep boostmode-app

echo -e "${GREEN}Готово! Приложение доступно по адресу:${NC}"
echo -e "${GREEN}http://localhost${NC}"
echo -e "${GREEN}https://localhost${NC}" 