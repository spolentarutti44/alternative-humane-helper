#!/bin/bash

echo "🚀 Starting Nonprofit Helper Application Setup..."

# Create necessary directories
echo "📁 Creating directory structure..."
mkdir -p drupal-data
mkdir -p laravel-data

# Start Docker containers
echo "🐳 Starting Docker containers..."
docker-compose up -d

# Wait for databases to be ready
echo "⏳ Waiting for databases to initialize..."
sleep 15

# Check if Laravel directory is empty
if [ ! -f "laravel-data/artisan" ]; then
    echo "📦 Installing Laravel..."
    docker-compose exec laravel composer create-project laravel/laravel . --prefer-dist
    
    # Copy environment file
    echo "⚙️  Setting up environment..."
    docker-compose exec laravel cp .env.example .env
    
    # Update database configuration in .env
    docker-compose exec laravel sed -i 's/DB_HOST=.*/DB_HOST=laravel-db/' .env
    docker-compose exec laravel sed -i 's/DB_DATABASE=.*/DB_DATABASE=laraveldb/' .env
    docker-compose exec laravel sed -i 's/DB_USERNAME=.*/DB_USERNAME=laraveluser/' .env
    docker-compose exec laravel sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=laravelpass/' .env
    
    # Generate application key
    echo "🔑 Generating application key..."
    docker-compose exec laravel php artisan key:generate
    
    # Install Laravel Sanctum
    echo "🔐 Installing Laravel Sanctum..."
    docker-compose exec laravel composer require laravel/sanctum
    docker-compose exec laravel php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    
    # Run migrations
    echo "🗄️  Running database migrations..."
    docker-compose exec -T laravel php artisan migrate
    
    # Seed database
    echo "🌱 Seeding database..."
    docker-compose exec -T laravel php artisan db:seed
    
    # Install frontend dependencies
    echo "📦 Installing frontend dependencies..."
    docker-compose exec laravel npm install
    
    # Build frontend assets
    echo "🎨 Building frontend assets..."
    docker-compose exec laravel npm run build
    
    echo "✅ Laravel will start automatically with Docker"
else
    echo "✅ Laravel already installed"
    
    # Run migrations
    echo "🗄️  Running database migrations..."
    docker-compose exec -T laravel php artisan migrate
    
    echo "✅ Laravel will start automatically with Docker"
fi

echo ""
echo "✨ Setup complete!"
echo ""
echo "📍 Your application is now running:"
echo "   - Drupal Website: http://localhost"
echo "   - Laravel Admin: http://localhost:8000"
echo "   - Adminer (DB): http://localhost:8082"
echo ""
echo "🔑 Default Admin Credentials:"
echo "   Email: admin@nonprofit.com"
echo "   Password: password123"
echo ""
echo "💡 Useful commands:"
echo "   - Stop containers: docker-compose down"
echo "   - View logs: docker-compose logs -f laravel"
echo "   - Access Laravel shell: docker-compose exec laravel bash"
echo ""

