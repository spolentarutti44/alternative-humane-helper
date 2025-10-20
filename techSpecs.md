

## Business Goals

We are building a multi part business application to help fosters manage schedulers for volenteering and also to create a drupal website for advertising the non profit

I want to be able to support multiple types of non profits as well as multiple types of donation and event scenarios

## Machine breakdown
# Multi part docker file

1st Machine Drupal with local override 
    create folder locally for drupal-data
2nd MariaDB for drupal 
    create folder locally for mariadb-data

3rd Php Machine with laravel
    create folder locally for laravel-data
    
## Website
    Standard drupal docker image website creation that will be used to advertise for the non profit


## Front end for Admin
    -Scheduler Page
    -Fosters Page
    -Animals Page
    -Donation Management Page
    -EveI'll help you create example API endpoints and database schema for these pages. Let me first read the full techSpecs file to understand the context of the project.

## Example API Endpoints & Database Schema

### 1. **Animals Page**

**Database Table: `animals`**
```sql
- id (primary key)
- name (varchar)
- species (varchar) - dog, cat, etc.
- breed (varchar)
- age (integer)
- gender (enum: male, female, unknown)
- status (enum: available, fostered, adopted, medical)
- description (text)
- intake_date (date)
- medical_notes (text)
- photo_url (varchar)
- created_at (timestamp)
- updated_at (timestamp)
```

**API Endpoints:**
- `GET /api/animals` - List all animals (with filters)
- `GET /api/animals/{id}` - Get specific animal details
- `POST /api/animals` - Add new animal
- `PUT /api/animals/{id}` - Update animal info
- `DELETE /api/animals/{id}` - Remove animal record

### 2. **Fosters Page**

**Database Tables:**
```sql
fosters:
- id (primary key)
- first_name (varchar)
- last_name (varchar)
- email (varchar, unique)
- phone (varchar)
- address (text)
- capacity (integer) - max animals they can foster
- status (enum: active, inactive, pending)
- created_at (timestamp)
- updated_at (timestamp)

foster_assignments:
- id (primary key)
- foster_id (foreign key -> fosters)
- animal_id (foreign key -> animals)
- start_date (date)
- end_date (date, nullable)
- status (enum: active, completed, cancelled)
- notes (text)
- created_at (timestamp)
```

**API Endpoints:**
- `GET /api/fosters` - List all foster families
- `GET /api/fosters/{id}` - Get foster details & history
- `POST /api/fosters` - Register new foster
- `PUT /api/fosters/{id}` - Update foster info
- `POST /api/fosters/{id}/assignments` - Assign animal to foster
- `GET /api/fosters/{id}/assignments` - Get foster's animals
- `PUT /api/foster-assignments/{id}` - Update assignment

### 3. **Scheduler Page**

**Database Table: `schedules`**
```sql
- id (primary key)
- title (varchar)
- description (text)
- type (enum: appointment, transport, medical, grooming)
- animal_id (foreign key -> animals, nullable)
- foster_id (foreign key -> fosters, nullable)
- volunteer_id (foreign key -> volunteers, nullable)
- start_time (datetime)
- end_time (datetime)
- location (varchar)
- status (enum: scheduled, completed, cancelled)
- created_by (foreign key -> users)
- created_at (timestamp)
- updated_at (timestamp)
```

**API Endpoints:**
- `GET /api/schedules` - Get all schedules (filterable by date, type)
- `GET /api/schedules/{id}` - Get schedule details
- `POST /api/schedules` - Create new schedule entry
- `PUT /api/schedules/{id}` - Update schedule
- `DELETE /api/schedules/{id}` - Cancel/delete schedule

### 4. **Donation Management Page**

**Database Tables:**
```sql
donors:
- id (primary key)
- first_name (varchar)
- last_name (varchar)
- email (varchar)
- phone (varchar)
- address (text)
- is_recurring (boolean)
- created_at (timestamp)
- updated_at (timestamp)

donations:
- id (primary key)
- donor_id (foreign key -> donors)
- amount (decimal)
- donation_type (enum: monetary, supplies, services)
- payment_method (enum: credit_card, paypal, check, cash)
- transaction_id (varchar)
- status (enum: pending, completed, failed, refunded)
- donation_date (date)
- tax_receipt_sent (boolean)
- notes (text)
- created_at (timestamp)
- updated_at (timestamp)
```

**API Endpoints:**
- `GET /api/donations` - List all donations (with filters)
- `GET /api/donations/{id}` - Get donation details
- `POST /api/donations` - Record new donation
- `PUT /api/donations/{id}` - Update donation
- `GET /api/donors` - List all donors
- `GET /api/donors/{id}` - Get donor details & history
- `POST /api/donors` - Add new donor
- `PUT /api/donors/{id}` - Update donor info
- `POST /api/donations/{id}/receipt` - Generate tax receipt

### 5. **Event Management Page**

**Database Tables:**
```sql
events:
- id (primary key)
- title (varchar)
- description (text)
- event_type (enum: adoption_event, fundraiser, volunteer_training, community)
- start_date (datetime)
- end_date (datetime)
- location (varchar)
- capacity (integer)
- registration_required (boolean)
- status (enum: draft, published, completed, cancelled)
- created_at (timestamp)
- updated_at (timestamp)

event_registrations:
- id (primary key)
- event_id (foreign key -> events)
- attendee_name (varchar)
- attendee_email (varchar)
- attendee_phone (varchar)
- registration_date (timestamp)
- attendance_status (enum: registered, attended, no_show, cancelled)
- notes (text)
```

**API Endpoints:**
- `GET /api/events` - List all events
- `GET /api/events/{id}` - Get event details
- `POST /api/events` - Create new event
- `PUT /api/events/{id}` - Update event
- `DELETE /api/events/{id}` - Delete event
- `GET /api/events/{id}/registrations` - Get event registrations
- `POST /api/events/{id}/register` - Register for event
- `PUT /api/registrations/{id}` - Update registration

### Additional Supporting Tables

```sql
users:
- id (primary key)
- username (varchar)
- email (varchar, unique)
- password_hash (varchar)
- role (enum: admin, volunteer, staff)
- created_at (timestamp)
- updated_at (timestamp)

volunteers:
- id (primary key)
- user_id (foreign key -> users)
- first_name (varchar)
- last_name (varchar)
- phone (varchar)
- availability (text)
- skills (text)
- background_check_date (date)
- status (enum: active, inactive)
- created_at (timestamp)
```

Would you like me to create a detailed implementation plan for these APIs with Laravel, including controllers, models, and routes?