# 🐾 Nonprofit Helper - Furry Friends Shelter Management System

A comprehensive full-stack application designed to help animal shelters and nonprofit organizations manage their operations efficiently. Built with Laravel, Docker, and modern web technologies.

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Project Structure](#project-structure)
- [Contributing](#contributing)

## ✨ Features

### Admin Dashboard
- **Dashboard Overview**: Real-time statistics and quick actions
- **Furry Friends Management**: Track furry friends, their status, medical records, and photos
- **Foster Management**: Manage foster families and animal assignments
- **Scheduler**: Calendar-based scheduling for appointments, medical visits, transport, and grooming
- **Donation Tracking**: Record and manage donations with automated tax receipt generation
- **Event Management**: Create and manage adoption events, fundraisers, and volunteer training

### Public Website
- **Drupal CMS**: Professional website for nonprofit marketing and public engagement
- **Event Listings**: Showcase upcoming events to the community
- **Donation Portal**: Easy-to-use donation interface

### RESTful API
- Complete API with full CRUD operations for all resources
- JSON responses
- Relationship loading
- Filtering and pagination support

## 🛠 Tech Stack

### Backend
- **PHP 8.2+**
- **Laravel 11**: Modern PHP framework
- **MariaDB 10.11**: Relational database
- **Laravel Sanctum**: API authentication

### Frontend
- **Blade Templates**: Server-side rendering
- **Tailwind CSS**: Modern utility-first CSS
- **JavaScript**: Dynamic interactions
- **Font Awesome**: Icon library

### Infrastructure
- **Docker**: Containerization
- **Docker Compose**: Multi-container orchestration
- **Drupal 11**: Content management system
- **Adminer**: Database management interface

## 📦 Prerequisites

Before you begin, ensure you have the following installed:

- **Docker** (version 20.10 or higher)
- **Docker Compose** (version 2.0 or higher)
- **Git**
- At least **4GB RAM** available for Docker

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd alternative-humane-helper
```

### 2. Run Setup Script

Make the setup script executable and run it:

```bash
chmod +x setup.sh
./setup.sh
```

The setup script will:
- Create necessary directories
- Start Docker containers
- Install Laravel and dependencies
- Run database migrations
- Seed the database with sample data
- Build frontend assets

### 3. Access the Application

Once setup is complete, you can access:

- **Laravel Admin Panel**: http://localhost:8000
- **Drupal Website**: http://localhost
- **Adminer (DB Management)**: http://localhost:8080

### Default Login Credentials

**Admin Account:**
- Email: `admin@nonprofit.com`
- Password: `password123`

**Volunteer Account:**
- Email: `volunteer@nonprofit.com`
- Password: `password123`

**Adminer Database:**
- System: MySQL
- Server: laravel-db
- Username: laraveluser
- Password: laravelpass
- Database: laraveldb

## 📖 Usage

### Managing Furry Friends

1. Navigate to **Furry Friends** in the sidebar
2. Click "Add New Furry Friend" to register a new furry friend
3. Fill in details: name, species, breed, age, gender, status
4. Upload a photo (optional)
5. Add medical notes and intake information
6. Click "Save" to add the furry friend

### Managing Fosters

1. Navigate to **Fosters** in the sidebar
2. Click "Add New Foster" to register a foster family
3. Fill in contact information and capacity
4. Assign furry friends to foster families using the "Assign" button
5. Track foster assignments and history

### Scheduling

1. Navigate to **Scheduler** in the sidebar
2. Switch between Calendar and List views
3. Click "Add Schedule" to create a new appointment
4. Select type: appointment, transport, medical, or grooming
5. Link to furry friends, fosters, or volunteers
6. Set date, time, and location

### Recording Donations

1. Navigate to **Donations** in the sidebar
2. Click "Record Donation"
3. Select or create a donor
4. Enter amount and donation type
5. Select payment method
6. Mark as completed and send tax receipt

### Managing Events

1. Navigate to **Events** in the sidebar
2. Click "Create Event"
3. Fill in event details: title, type, date, location
4. Set capacity and registration requirements
5. Publish the event
6. Track registrations and attendance

## 🔌 API Documentation

### Base URL

```
http://localhost:8000/api
```

### Authentication

API routes support Laravel Sanctum authentication. Include the bearer token in headers:

```
Authorization: Bearer {token}
```

### Furry Friends API

```
GET    /api/furry-friends              # List all furry friends
GET    /api/furry-friends/{id}         # Get furry friend details
POST   /api/furry-friends              # Create new furry friend
PUT    /api/furry-friends/{id}         # Update furry friend
DELETE /api/furry-friends/{id}         # Delete furry friend
```

**Query Parameters:**
- `status`: Filter by status (available, fostered, adopted, medical)
- `species`: Filter by species
- `page`: Pagination

### Fosters API

```
GET    /api/fosters                     # List all fosters
GET    /api/fosters/{id}                # Get foster details
POST   /api/fosters                     # Create new foster
PUT    /api/fosters/{id}                # Update foster
DELETE /api/fosters/{id}                # Delete foster
GET    /api/fosters/{id}/assignments    # Get foster assignments
POST   /api/fosters/{id}/assignments    # Assign furry friend to foster
```

### Schedules API

```
GET    /api/schedules            # List all schedules
GET    /api/schedules/{id}       # Get schedule details
POST   /api/schedules            # Create new schedule
PUT    /api/schedules/{id}       # Update schedule
DELETE /api/schedules/{id}       # Delete schedule
```

**Query Parameters:**
- `type`: Filter by type (appointment, transport, medical, grooming)
- `status`: Filter by status (scheduled, completed, cancelled)
- `start_date` & `end_date`: Filter by date range

### Donations API

```
GET    /api/donations              # List all donations
GET    /api/donations/{id}         # Get donation details
POST   /api/donations              # Create new donation
PUT    /api/donations/{id}         # Update donation
POST   /api/donations/{id}/receipt # Send tax receipt
```

### Donors API

```
GET    /api/donors           # List all donors
GET    /api/donors/{id}      # Get donor details
POST   /api/donors           # Create new donor
PUT    /api/donors/{id}      # Update donor
DELETE /api/donors/{id}      # Delete donor
```

### Events API

```
GET    /api/events                      # List all events
GET    /api/events/{id}                 # Get event details
POST   /api/events                      # Create new event
PUT    /api/events/{id}                 # Update event
DELETE /api/events/{id}                 # Delete event
GET    /api/events/{id}/registrations   # Get event registrations
POST   /api/events/{id}/register        # Register for event
PUT    /api/registrations/{id}          # Update registration
```

## 📁 Project Structure

```
alternative-humane-helper/
├── docker-compose.yml          # Docker orchestration
├── Dockerfile                  # Laravel container definition
├── setup.sh                    # Setup automation script
├── README.md                   # This file
├── techSpecs.md               # Technical specifications
├── drupal-data/               # Drupal files (volume mount)
├── laravel-data/              # Laravel application
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   │       ├── API/      # API controllers
│   │   │       └── Admin/    # Admin web controllers
│   │   └── Models/           # Eloquent models
│   ├── database/
│   │   ├── migrations/       # Database migrations
│   │   └── seeders/          # Database seeders
│   ├── resources/
│   │   └── views/
│   │       ├── layouts/      # Blade layouts
│   │       └── admin/        # Admin views
│   ├── routes/
│   │   ├── api.php          # API routes
│   │   └── web.php          # Web routes
│   └── public/              # Public assets
└── .env.example             # Environment template
```

## 🔧 Configuration

### Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="Nonprofit Helper"
APP_URL=http://localhost:8000
DB_HOST=laravel-db
DB_DATABASE=laraveldb
DB_USERNAME=laraveluser
DB_PASSWORD=laravelpass
```

### Database Configuration

The application uses two separate databases:
1. **Drupal Database**: For the public website
2. **Laravel Database**: For the admin system and API

## 🐳 Docker Commands

### Start All Services
```bash
docker-compose up -d
```

### Stop All Services
```bash
docker-compose down
```

### View Logs
```bash
docker-compose logs -f laravel
```

### Access Laravel Container
```bash
docker-compose exec laravel bash
```

### Run Migrations
```bash
docker-compose exec laravel php artisan migrate
```

### Seed Database
```bash
docker-compose exec laravel php artisan db:seed
```

### Clear Cache
```bash
docker-compose exec laravel php artisan cache:clear
docker-compose exec laravel php artisan config:clear
docker-compose exec laravel php artisan view:clear
```

## 🧪 Testing

### Manual Testing

1. Test furry friend CRUD operations
2. Test foster assignments
3. Test schedule creation
4. Test donation recording
5. Test event management
6. Test API endpoints using Postman or curl

### Example API Test

```bash
# Get all furry friends
curl http://localhost:8000/api/furry-friends

# Create a new furry friend
curl -X POST http://localhost:8000/api/furry-friends \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Buddy",
    "species": "dog",
    "age": 2,
    "gender": "male",
    "status": "available",
    "intake_date": "2024-01-15"
  }'
```

## 🔐 Security

- Passwords are hashed using bcrypt
- SQL injection protection via Eloquent ORM
- CSRF protection on forms
- XSS protection via Blade escaping
- API authentication with Sanctum
- Environment variables for sensitive data

## 📱 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-source and available under the MIT License.

## 👥 Support

For issues, questions, or contributions, please open an issue on GitHub.

## 🙏 Acknowledgments

- Laravel Framework
- Drupal CMS
- Tailwind CSS
- Font Awesome
- Docker Community

---

**Made with ❤️ for furry friend shelters and nonprofit organizations**

