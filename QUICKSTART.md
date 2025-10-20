# 🚀 Quick Start Guide

Get up and running with Nonprofit Helper in just a few minutes!

## Prerequisites Check

Make sure you have:
- ✅ Docker installed and running
- ✅ Docker Compose installed
- ✅ At least 4GB RAM available

## Installation (3 Easy Steps)

### Step 1: Clone and Navigate
```bash
git clone <your-repo-url>
cd alternative-humane-helper
```

### Step 2: Run Setup
```bash
chmod +x setup.sh
./setup.sh
```

*The setup will take 5-10 minutes. Grab a coffee! ☕*

### Step 3: Access the Application

Once setup completes, open your browser:

**Admin Dashboard:**
```
http://localhost:8000
```

Login with:
- Email: `admin@nonprofit.com`
- Password: `password123`

## Your First Tasks

### 1. Add Your First Animal
1. Click **Animals** in the sidebar
2. Click **Add New Animal**
3. Fill in the details
4. Save!

### 2. Create a Foster Family
1. Click **Fosters** in the sidebar
2. Click **Add New Foster**
3. Enter contact information
4. Assign an animal

### 3. Schedule an Appointment
1. Click **Scheduler** in the sidebar
2. Click **Add Schedule**
3. Select an animal and set date/time
4. Save!

## Troubleshooting

### Containers won't start?
```bash
docker-compose down
docker-compose up -d
```

### Can't access the site?
Check if containers are running:
```bash
docker-compose ps
```

### Database connection error?
Wait 30 seconds for MariaDB to fully initialize, then refresh.

### Need to reset everything?
```bash
docker-compose down -v
./setup.sh
```

## Need Help?

Check the full [README.md](README.md) for:
- Complete API documentation
- Advanced configuration
- Detailed feature guides
- Docker commands

## Sample Data

The setup automatically creates:
- 4 sample animals
- 2 foster families
- 3 scheduled appointments
- 3 donations
- 3 upcoming events
- Admin and volunteer accounts

You can modify or delete this data as needed!

---

**You're all set! Start managing your nonprofit operations efficiently! 🐾**

