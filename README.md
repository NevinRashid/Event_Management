# Event management system

## System Overview
This Laravel-based event management system supports multiple user roles and access control using Spatie Laravel Permission. It is designed to manage events, attendees, and organizers with flexible role-based logic.

## User Roles  
The system includes the following predefined roles:
- **Super Admin**: Has full access to all features and data, Can view and manage all users, events, locations, event types, reservations and system settings.
- **Admin**: Can view and manage users with roles: attendee and organizer, and can create and manage events, manage bookings, locations, and photos.
- **Organizer**: They can only manage their own events (i.e. events for which they are designated as an organizer).
Attendees can view and manage their events.
- **Attendee**: Can view events and make reservations.
They can manage their profile and reservations.
- **Guest**: They can view published events and can create an account.

## User Roles  
- **Event Creation**
Events can be created only by users with the super admin or admin roles.
Each event must be assigned to a single organizer through a one-to-many relationship.
- **Users**:
The user has three relationships with the event 
1) one to many between event creators and the event.
2) one to many relationship between event organizers and the event.
3) many to many relationship between attendees and the event.
- **Reservations**:
A many-to-many relationship exists between users and events via a pivot table reservations, allowing attendees to reserve events.
- **Event Images**:
Events and Locations support a polymorphic one-to-many relationship with images.
Images are uploaded and stored through a flexible morphOne/morphMany relation.
- **Authorization**:
Role-based access control is handled in both middleware and form requests.
FormRequest authorize() methods are customized to allow/deny access depending on the user's role or ownership of a resource.

## Routing & Middleware
API routes are grouped with middleware such as:
auth:sanctum for authentication.
Custom middleware to restrict access based on roles.
Permissions and roles are centrally managed using Spatie’s package.

### Key Features:
- **CRUD Operations**: Create, read, update, and delete event in the system .
- **CRUD Operations**: Create, read, update, and delete event type in the system .
- **CRUD Operations**: Create, read, update, and delete user in the system .
- **CRUD Operations**: Create, read, update, and delete location in the system .
- **CRUD Operations**: Create, read, update, and delete reservation in the system .
- **Form Requests**: Validation is handled by custom form request classes.
- **Seeders**: Populate the database with initial data for testing and development.

### Technologies Used:
- **Laravel 12**
- **PHP**
- **MySQL**
- **Spatie pacakge**
- **XAMPP** (for local development environment)
- **Composer** (PHP dependency manager)


---

## Installation

### Prerequisites

Ensure you have the following installed on your machine:
- **XAMPP**: For running MySQL and Apache servers locally.
- **Composer**: For PHP dependency management.
- **PHP**: Required for running Laravel.
- **MySQL**: Database for the project

### Steps to Run the Project

1. Clone the Repository  
   ```bash
   git clone https://github.com/NevinRashid/Event_Management.git
2. Navigate to the Project Directory
   ```bash
   cd event_task
3. Install Dependencies
   ```bash
   composer install
4. Create Environment File
   ```bash
   cp .env.example .env
   Update the .env file with your database configuration (MySQL credentials, database name, etc.).
5. Generate Application Key
    ```bash
    php artisan key:generate
6. Run Migrations and seeders
    ```bash
    php artisan migraten--seed
8. Run the Application
    ```bash
    php artisan serve

## API Documentation
You can find and test all API endpoints in the provided Postman collection.

### Postman Collection:
- https://www.postman.com/nevinrashid/my-wokspace/collection/fdduur2/event-management
