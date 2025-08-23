# Muhtaseb - Commercial Property Management System

A modern, full-stack property management system designed specifically for commercial properties, shops, and units. Built with Vue.js 3 frontend and PHP backend with MySQL database.

## 🌟 Features

### Core Management
- **Property Management**: Register and manage commercial properties
- **Unit Management**: Track individual units and shops within properties
- **Tenant Management**: Manage tenant information and contact details
- **Lease Management**: Track lease agreements, terms, and renewals
- **Income Tracking**: Monitor rental income and payments
- **Expense Management**: Track property-related expenses
- **Owner Management**: Manage property owners and distributions
- **Invoice Generation**: Create and manage invoices
- **Category Management**: Organize income and expenses by categories
- **User Management**: Manage system users and permissions
- **Financial Reports**: Comprehensive reporting and analytics

### Enhanced Features
- **Real-time Notifications**: System-wide notification system with different types (success, error, info, warning)
- **Settings Management**: User preferences and system configuration
- **Dark Mode**: Modern dark theme with glass morphism effects
- **Responsive Design**: Mobile-friendly interface
- **Interactive Charts**: Visual data representation using Chart.js
- **Search & Filter**: Advanced search and filtering capabilities
- **Export Functionality**: Export data to various formats

## 🚀 Quick Start

### Prerequisites
- Node.js (v16 or higher)
- PHP (v8.0 or higher)
- MySQL (v8.0 or higher)
- Composer (for PHP dependencies)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Muhtaseb
   ```

2. **Backend Setup**
   ```bash
   cd backend
   composer install
   ```

3. **Database Setup**
   ```bash
   # Create database
   mysql -u root -p
   CREATE DATABASE muhtaseb;
   USE muhtaseb;
   
   # Import schema
   mysql -u root -p muhtaseb < database/schema.sql
   ```

4. **Configure Database**
   ```bash
   # Edit backend/config/database.php
   # Update database credentials
   ```

5. **Frontend Setup**
   ```bash
   cd frontend
   npm install
   ```

6. **Start Development Servers**
   ```bash
   # Terminal 1 - Backend
   cd backend
   php -S localhost:8000
   
   # Terminal 2 - Frontend
   cd frontend
   npm run dev
   ```

7. **Access the Application**
   - Frontend: http://localhost:5173
   - Backend API: http://localhost:8000

## 📁 Project Structure

```
Muhtaseb/
├── backend/
│   ├── api/
│   │   ├── properties.php
│   │   ├── units.php
│   │   ├── tenants.php
│   │   ├── leases.php
│   │   ├── income.php
│   │   ├── expenses.php
│   │   ├── owners.php
│   │   ├── invoices.php
│   │   ├── categories.php
│   │   ├── users.php
│   │   └── dashboard.php
│   ├── config/
│   │   └── database.php
│   └── database/
│       └── schema.sql
├── frontend/
│   ├── src/
│   │   ├── components/
│   │   ├── pages/
│   │   ├── App.vue
│   │   └── main.js
│   ├── package.json
│   └── vite.config.js
└── README.md
```

## 🎨 UI Components

### Navigation
- **Sidebar Navigation**: Collapsible sidebar with navigation items
- **Breadcrumbs**: Context-aware navigation breadcrumbs
- **Search Bar**: Global search functionality

### Dashboard
- **Overview Cards**: Key metrics and statistics
- **Charts**: Interactive income/expenses and owner distribution charts
- **Quick Actions**: Common tasks and shortcuts
- **Recent Activity**: Latest system activities

### Data Management
- **Data Tables**: Sortable and filterable data tables
- **Forms**: Modern form components with validation
- **Modals**: Modal dialogs for data entry and confirmation
- **Notifications**: Toast notifications for user feedback

## 🔧 API Endpoints

### Properties
- `GET /api/properties.php` - List all properties
- `POST /api/properties.php` - Create new property
- `PUT /api/properties.php` - Update property
- `DELETE /api/properties.php` - Delete property

### Units
- `GET /api/units.php` - List all units
- `POST /api/units.php` - Create new unit
- `PUT /api/units.php` - Update unit
- `DELETE /api/units.php` - Delete unit

### Tenants
- `GET /api/tenants.php` - List all tenants
- `POST /api/tenants.php` - Create new tenant
- `PUT /api/tenants.php` - Update tenant
- `DELETE /api/tenants.php` - Delete tenant

### Leases
- `GET /api/leases.php` - List all leases
- `POST /api/leases.php` - Create new lease
- `PUT /api/leases.php` - Update lease
- `DELETE /api/leases.php` - Delete lease

### Income
- `GET /api/income.php` - List all income records
- `POST /api/income.php` - Create new income record
- `PUT /api/income.php` - Update income record
- `DELETE /api/income.php` - Delete income record

### Expenses
- `GET /api/expenses.php` - List all expenses
- `POST /api/expenses.php` - Create new expense
- `PUT /api/expenses.php` - Update expense
- `DELETE /api/expenses.php` - Delete expense

### Owners
- `GET /api/owners.php` - List all owners
- `POST /api/owners.php` - Create new owner
- `PUT /api/owners.php` - Update owner
- `DELETE /api/owners.php` - Delete owner

### Dashboard
- `GET /api/dashboard.php` - Get dashboard statistics and charts data

## 🎯 Key Features Explained

### Notification System
The application includes a comprehensive notification system:
- **Types**: Success, Error, Info, Warning
- **Auto-dismiss**: Notifications automatically disappear after 5 seconds
- **Manual dismiss**: Users can manually close notifications
- **Global access**: Available throughout the application

### Settings Management
User-configurable settings include:
- **Theme preferences**: Dark/light mode toggle
- **Notification preferences**: Email and system notification toggles
- **System information**: Version and update information

### Responsive Design
- **Mobile-first**: Optimized for mobile devices
- **Tablet support**: Responsive layout for tablets
- **Desktop optimization**: Full-featured desktop experience

## 🛠️ Development

### Adding New Features
1. Create API endpoint in `backend/api/`
2. Add frontend page in `frontend/src/pages/`
3. Update navigation in `App.vue`
4. Add routing configuration

### Styling
- Uses Tailwind CSS for styling
- Custom glass morphism effects
- Consistent color scheme and typography
- Responsive design patterns

### State Management
- Vue.js reactive data management
- Component-based architecture
- Event-driven communication

## 📊 Database Schema

The system uses a relational database with the following main tables:
- `properties` - Property information
- `units` - Unit/shop details
- `tenants` - Tenant information
- `leases` - Lease agreements
- `income` - Income records
- `expenses` - Expense records
- `owners` - Property owners
- `categories` - Income/expense categories
- `users` - System users

## 🔒 Security Features

- **Input validation**: Server-side validation for all inputs
- **SQL injection protection**: Prepared statements
- **XSS protection**: Output escaping
- **CSRF protection**: Token-based protection
- **Access control**: User role-based permissions

## 🚀 Deployment

### Production Setup
1. **Backend**: Deploy to web server with PHP support
2. **Frontend**: Build and deploy static files
3. **Database**: Set up production MySQL database
4. **Environment**: Configure production environment variables

### Docker Deployment (Optional)
```bash
# Build and run with Docker
docker-compose up -d
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

## 🔄 Version History

- **v1.0.0**: Initial release with core features
- **v1.1.0**: Added notification system and settings
- **v1.2.0**: Enhanced UI with glass morphism effects
- **v1.3.0**: Added comprehensive reporting features

---

**Muhtaseb** - Modern Property Management for Commercial Real Estate
