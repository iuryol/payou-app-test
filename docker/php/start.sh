#!/bin/sh

# Evita que o script continue se algo falhar
set -e

# Comando Laravel
echo "📦 Otimizando rotas Laravel..."
php artisan route:optimize

# Comando npm
echo "🚀 Iniciando Vite (npm run dev)..."
npm run dev
