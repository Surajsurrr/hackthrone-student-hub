# 🎓 LearnX - Student Hub Portal

A comprehensive student portal system designed to streamline academic and career management for students, colleges, and companies. This platform combines features from LinkedIn, Unstop, Naukri, LeetCode, HackerRank, and other career development tools into a single, unified ecosystem.

## 🌟 Features Overview

### 👨‍🎓 Student Portal
- **📊 Dashboard Overview**: Personalized dashboard with stats, quick actions, and progress tracking
- **👤 Profile Management**: Complete profile setup with skills, education, and portfolio
- **📚 Study Notes Hub**: Upload, organize, and share study materials by subjects/topics
- **🤖 AI Coach**: Interactive AI-powered career guidance and academic support
- **💼 Career Opportunities**: Browse internships, jobs, and hackathons with smart filtering
- **📋 Applications Tracker**: Track job/internship applications with real-time status updates
- **🏆 Events & Hackathons**: Discover and register for coding competitions and events
- **🎯 Achievements System**: Gamified XP system with badges, levels, and progress tracking
- **📅 Calendar & Reminders**: Integrated calendar with smart reminder functionality
- **❓ Help Center**: Comprehensive FAQ, tutorials, and support system

### 🏛️ College Portal
- **📅 Event Management**: Create and manage hackathons, symposiums, workshops, and project expos
- **📈 Student Analytics**: Track student engagement, participation, and success metrics
- **📖 Resource Sharing**: Share educational resources, announcements, and opportunities
- **🎓 Institution Branding**: Showcase college achievements and build reputation

### 🏢 Company Portal
- **💼 Job Postings**: Create internship, full-time, part-time, and contract opportunities
- **👥 Candidate Management**: Review applications, schedule interviews, and manage hiring pipeline
- **🏢 Company Profile**: Showcase company culture, values, and career opportunities
- **📊 Recruitment Analytics**: Track application metrics and hiring success rates

### 👑 Admin Portal
- **👥 User Management**: Comprehensive management of students, colleges, and companies
- **📊 System Analytics**: Monitor platform usage, engagement, and performance metrics
- **🛡️ Content Moderation**: Review and approve user-generated content and posts
- **⚙️ System Configuration**: Manage platform settings, features, and integrations

## 🛠️ Technology Stack

### Backend Technologies
- **PHP 7.4+**: Server-side logic and API endpoints
- **MySQL 5.7+**: Primary relational database for user data and content
- **MongoDB**: Optional NoSQL support for analytics and logging
- **RESTful APIs**: Clean API architecture for frontend-backend communication
- **Session Management**: Secure user authentication and authorization

### Frontend Technologies
- **HTML5/CSS3**: Modern semantic markup and responsive styling
- **JavaScript (ES6+)**: Interactive UI components and dynamic content loading
- **CSS Grid/Flexbox**: Advanced layout systems for responsive design
- **CSS Variables**: Theming system for consistent design patterns
- **AJAX/Fetch API**: Asynchronous data loading and form submissions

### Key Features & Security
- **🔐 Role-Based Access Control**: Different permission levels for users
- **📁 Secure File Upload System**: Safe handling of documents and images
- **🛡️ SQL Injection Protection**: Prepared statements and parameterized queries
- **🔒 XSS Prevention**: Input sanitization and output encoding
- **🎯 CSRF Protection**: Token-based form validation
- **📱 Responsive Design**: Mobile-first approach with cross-device compatibility

## 📁 Project Architecture

```
stfinal/
├── 📂 admin/                    # Admin portal and management
│   ├── dashboard.php           # Admin dashboard
│   ├── manage_events.php       # Event management interface
│   ├── manage_users.php        # User management system
│   └── includes/               # Admin-specific includes
├── 📂 ai/                       # AI integration and endpoints
│   ├── model_endpoint.php      # AI model API endpoint
│   ├── responses/              # AI response templates
│   ├── scripts/                # AI processing scripts
│   └── train_data/             # Training data for AI models
├── 📂 api/                      # REST API endpoints
│   ├── auth/                   # Authentication APIs
│   │   ├── login.php           # User login endpoint
│   │   ├── register.php        # User registration
│   │   └── logout.php          # Session termination
│   ├── college/                # College-specific APIs
│   │   ├── createEvent.php     # Create college events
│   │   ├── getEvents.php       # Fetch college events
│   │   └── deleteEvent.php     # Remove events
│   ├── company/                # Company-specific APIs
│   │   ├── createJob.php       # Create job postings
│   │   ├── getJobs.php         # Fetch job listings
│   │   └── deleteJob.php       # Remove job postings
│   └── student/                # Student-specific APIs
│       ├── getProfile.php      # Fetch student profile
│       ├── uploadNotes.php     # Upload study materials
│       ├── getAchievements.php # Achievement system API
│       └── getOpportunities.php # Career opportunities
├── 📂 assets/                   # Static assets and resources
│   ├── css/                    # Stylesheets
│   │   ├── dashboard.css       # Main dashboard styling
│   │   └── style.css           # Global styles
│   ├── js/                     # JavaScript files
│   │   ├── dashboard.js        # Dashboard functionality
│   │   ├── ai_chat.js          # AI coach interface
│   │   └── enhanced-dashboard.js # Advanced UI features
│   ├── icons/                  # Icon assets
│   └── images/                 # Image assets
│       ├── banners/            # Banner images
│       ├── logos/              # Logo files
│       └── profile_pics/       # User profile pictures
├── 📂 college/                  # College portal interface
│   ├── dashboard.php           # College dashboard
│   ├── manage_posts.php        # Post management
│   ├── post_event.php          # Event creation form
│   └── includes/               # College-specific includes
├── 📂 company/                  # Company portal interface
│   ├── dashboard.php           # Company dashboard
│   ├── manage_postings.php     # Job posting management
│   ├── post_internship.php     # Internship posting form
│   └── includes/               # Company-specific includes
├── 📂 student/                  # Student portal interface
│   ├── dashboard.php           # Main student dashboard
│   ├── profile.php             # Profile management
│   ├── notes.php               # Study notes interface
│   ├── ai_coach.php            # AI coaching system
│   ├── hackathons.php          # Hackathon listings
│   ├── internships.php         # Internship opportunities
│   ├── includes/               # Student-specific includes
│   └── uploads/                # Student file uploads
├── 📂 database/                 # Database schema and migrations
│   ├── schema.sql              # Database structure
│   └── migrations/             # Database migration scripts
├── 📂 includes/                 # Shared PHP components
│   ├── config.php              # Application configuration
│   ├── functions.php           # Utility functions
│   ├── db_connect.php          # Database connection
│   ├── session.php             # Session management
│   └── mongodb_config.php      # MongoDB configuration
├── 📂 uploads/                  # User-uploaded files
└── 📂 vendor/                   # Third-party libraries
```

## 🚀 Installation & Setup Guide

### Prerequisites
- **XAMPP/WAMP/LAMP Stack** with PHP 7.4+ and MySQL 5.7+
- **Web Server**: Apache or Nginx
- **Modern Browser**: Chrome 80+, Firefox 75+, Safari 13+, or Edge 80+
- **Git**: For version control and deployment

### Step-by-Step Installation

#### 1. Clone the Repository
```bash
git clone https://github.com/Surajsurrr/hackthrone-student-hub.git
cd hackthrone-student-hub
```

#### 2. Setup Web Server Environment
```bash
# For XAMPP users (Windows/Mac/Linux)
cp -r stfinal/ /xampp/htdocs/

# For WAMP users (Windows)
cp -r stfinal/ /wamp64/www/

# For LAMP users (Linux)
sudo cp -r stfinal/ /var/www/html/

# Start Apache and MySQL services through your control panel
```

#### 3. Database Configuration
```sql
-- Create database (via phpMyAdmin or MySQL command line)
CREATE DATABASE studenthub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import schema
mysql -u root -p studenthub < database/schema.sql

-- Or use phpMyAdmin to import schema.sql file
```

#### 4. Application Configuration
```bash
# Copy configuration template
cp includes/config.php.example includes/config.php

# Edit database credentials in config.php
nano includes/config.php
```

**Example config.php:**
```php
<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'studenthub');
define('DB_USER', 'root');          // Change for production
define('DB_PASS', '');              // Set password for production

// Application Settings
define('APP_NAME', 'LearnX - Student Portal');
define('BASE_URL', 'http://localhost/stfinal/');
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 10485760);  // 10MB

// Security Settings (Generate new keys for production)
define('CSRF_TOKEN_KEY', 'your-secret-csrf-key');
define('SESSION_COOKIE_NAME', 'learnx_session');

// AI Integration (Optional)
define('AI_API_KEY', 'your-ai-api-key');
define('AI_ENDPOINT', 'your-ai-endpoint-url');
?>
```

#### 5. Set File Permissions
```bash
# Set proper permissions for upload directories
chmod 755 uploads/
chmod 755 student/uploads/
chmod 755 assets/images/profile_pics/

# For Linux/Mac, ensure web server can write
sudo chown -R www-data:www-data uploads/
sudo chown -R www-data:www-data student/uploads/
```

#### 6. Access the Application
```
🌐 Main Portal: http://localhost/stfinal/
👨‍🎓 Student Dashboard: http://localhost/stfinal/student/
🏛️ College Portal: http://localhost/stfinal/college/
🏢 Company Portal: http://localhost/stfinal/company/
👑 Admin Panel: http://localhost/stfinal/admin/
```

### Production Deployment

#### Environment Configuration
```bash
# Create production config
cp includes/config.php.example includes/config.prod.php

# Set environment variables
export APP_ENV=production
export DEBUG_MODE=false
export DB_HOST=your-production-db-host
export DB_NAME=your-production-db-name
export DB_USER=your-production-db-user
export DB_PASS=your-secure-password
```

#### Security Hardening
```apache
# Add to .htaccess for additional security
RewriteEngine On

# Prevent access to sensitive files
<Files "config.php">
    Order allow,deny
    Deny from all
</Files>

<Files "*.sql">
    Order allow,deny
    Deny from all
</Files>

# Enable HTTPS redirect (recommended)
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

## 🧪 Testing & Quality Assurance

### Running Tests
```bash
# Test database connectivity
php test_db_connection.php

# Test configuration settings
php test_config.php

# Test password hashing functions
php test_password.php

# Test MongoDB connection (if using)
php test_mongodb.php
```

### API Testing Examples
```bash
# Test student registration API
curl -X POST http://localhost/stfinal/api/auth/register.php \
  -H "Content-Type: application/json" \
  -d '{"username":"test","email":"test@example.com","password":"password123"}'

# Test student login API
curl -X POST http://localhost/stfinal/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Test get student profile
curl -X GET http://localhost/stfinal/api/student/getProfile.php \
  -H "Authorization: Bearer your-token-here"
```

### Performance Testing
```bash
# Monitor PHP performance
php -m                        # Check loaded modules
php -i | grep memory_limit    # Check memory settings
php -i | grep max_execution_time  # Check execution limits

# Database performance
SHOW PROCESSLIST;             # Check active queries
SHOW STATUS LIKE 'Slow_queries';  # Check slow queries
```

## 🔧 Development Guidelines

### Code Standards
- **PHP**: Follow PSR-4 autoloading and PSR-12 coding standards
- **JavaScript**: Use ES6+ features, async/await for API calls
- **CSS**: BEM methodology for class naming, mobile-first responsive design
- **SQL**: Use prepared statements, proper indexing, normalized database design

### File Organization
```
📁 Project Structure Best Practices:
├── 📄 index.php              # Main landing page
├── 📁 api/                   # RESTful API endpoints
│   ├── 📁 auth/             # Authentication endpoints
│   ├── 📁 student/          # Student-specific APIs
│   ├── 📁 college/          # College-specific APIs
│   └── 📁 company/          # Company-specific APIs
├── 📁 includes/             # Shared PHP functions and configs
├── 📁 assets/               # Static resources (CSS, JS, images)
├── 📁 database/             # Schema and migration files
└── 📁 uploads/              # User-uploaded files (set proper permissions)
```

### Security Best Practices
```php
// Input validation example
function validateInput($data, $type = 'string') {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    
    switch($type) {
        case 'email':
            return filter_var($data, FILTER_VALIDATE_EMAIL);
        case 'int':
            return filter_var($data, FILTER_VALIDATE_INT);
        case 'url':
            return filter_var($data, FILTER_VALIDATE_URL);
        default:
            return $data;
    }
}

// Prepared statement example
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND status = ?");
$stmt->bind_param("ss", $email, $status);
$stmt->execute();
```

## 🤝 Contributing

### Getting Started
1. **Fork the repository** on GitHub
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Make your changes** following the coding standards
4. **Test thoroughly** using the provided test files
5. **Commit your changes**: `git commit -m 'Add amazing feature'`
6. **Push to the branch**: `git push origin feature/amazing-feature`
7. **Open a Pull Request** with detailed description

### Pull Request Guidelines
- Include screenshots for UI changes
- Update documentation if needed
- Add tests for new functionality
- Follow the existing code style
- Write clear commit messages

### Issue Reporting
When reporting bugs, please include:
- Browser and version
- PHP and MySQL versions
- Steps to reproduce
- Expected vs actual behavior
- Screenshots if applicable

## 📊 Monitoring & Analytics

### System Health Monitoring
```sql
-- Check database health
SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    DATA_LENGTH,
    INDEX_LENGTH
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'studenthub';

-- Monitor user activity
SELECT 
    DATE(created_at) as date,
    user_type,
    COUNT(*) as registrations
FROM users 
GROUP BY DATE(created_at), user_type
ORDER BY date DESC;
```

### Performance Metrics
- **Page Load Time**: < 2 seconds
- **API Response Time**: < 500ms
- **Database Query Time**: < 100ms
- **File Upload Speed**: Varies by file size and connection

## 🛠️ Troubleshooting

### Common Issues

#### Database Connection Errors
```php
// Error: "Access denied for user"
// Solution: Check credentials in config.php
define('DB_USER', 'correct_username');
define('DB_PASS', 'correct_password');

// Error: "Unknown database"
// Solution: Create database first
CREATE DATABASE studenthub;
```

#### File Upload Issues
```php
// Error: "File too large"
// Solution: Check PHP settings
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
ini_set('max_execution_time', 300);
```

#### Permission Errors
```bash
# Fix upload directory permissions
chmod 755 uploads/
chown -R www-data:www-data uploads/

# For Windows/XAMPP
# Ensure XAMPP has write permissions to the uploads folder
```

### Debugging Tips
```php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to file
ini_set('log_errors', 1);
ini_set('error_log', 'path/to/error.log');

// Debug database queries
echo "Last executed query: " . $conn->error;
```

## 📞 Support & Contact

### Development Team
- **Lead Developer**: [Your Name]
- **Email**: support@learnx.edu
- **GitHub**: [Repository Link]
- **Documentation**: [Wiki Link]

### Support Channels
- 📧 **Email Support**: help@learnx.edu
- 💬 **Discord Community**: [Discord Invite]
- 📖 **Documentation**: [Docs Link]
- 🐛 **Bug Reports**: [GitHub Issues]

---

## 📝 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

### MIT License Summary
```
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

---

## 🙏 Acknowledgments

- **Inspiration**: Modern educational platforms and student management systems
- **UI/UX Design**: Bootstrap framework and modern web design principles
- **Security**: OWASP security guidelines and best practices
- **Performance**: Web optimization techniques and database design patterns
- **Community**: Open source contributors and the PHP development community

---

## 🚀 Changelog

### Version 2.0.0 (Current)
- ✅ Redesigned responsive dashboard UI
- ✅ Enhanced AI coaching system
- ✅ Improved file upload functionality
- ✅ Advanced achievement system with XP tracking
- ✅ RESTful API architecture
- ✅ Enhanced security measures

### Version 1.0.0
- ✅ Basic student portal functionality
- ✅ User authentication system
- ✅ File upload capabilities
- ✅ Basic dashboard layout

---

## 🔮 Roadmap

### Upcoming Features (v2.1.0)
- [ ] **Mobile App**: React Native mobile application
- [ ] **Advanced Analytics**: Detailed learning analytics dashboard
- [ ] **Integration APIs**: Third-party service integrations
- [ ] **Blockchain**: Certificate verification system
- [ ] **AI Enhancements**: Advanced personalization algorithms

### Future Enhancements (v3.0.0)
- [ ] **Microservices**: Architecture migration to microservices
- [ ] **Real-time Features**: WebSocket-based real-time notifications
- [ ] **Advanced Security**: Two-factor authentication and biometric login
- [ ] **Scalability**: Cloud-native deployment options
- [ ] **Internationalization**: Multi-language support

---

**🎓 Built with ❤️ for students, by developers who understand the importance of education and technology.**

*Last Updated: January 2025*
```apache
# Add to .htaccess for additional security
RewriteEngine On

# Prevent access to sensitive files
<Files "config.php">
    Order allow,deny
    Deny from all
</Files>

<Files "*.sql">
    Order allow,deny
    Deny from all
</Files>

# Enable HTTPS redirect (recommended)
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

2. **Database Setup**
   - Create a MySQL database named `studenthub`
   - Import the database schema from `database/schema.sql`

3. **Configuration**
   - Copy `includes/config.php.example` to `includes/config.php`
   - Update database credentials and other settings

4. **Permissions**
   - Set write permissions for `uploads/` directory
   - Set write permissions for `ai/responses/` and `ai/train_data/` directories

5. **Web Server Configuration**
   - Point your web server document root to the project directory
   - Ensure URL rewriting is enabled for clean URLs

6. **Access the Application**
   - Open your browser and navigate to the installation URL
   - Register as a student, college, or company user

## Database Schema

The application uses the following main tables:

- `users`: User accounts (students, colleges, companies, admins)
- `students`: Student profile information
- `colleges`: College profile information
- `companies`: Company profile information
- `events`: College events (hackathons, symposiums, etc.)
- `jobs`: Company job postings
- `notes`: Shared study materials
- `ai_responses`: AI chat history

## API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout

### Student APIs
- `GET /api/student/getProfile` - Get student profile
- `POST /api/student/createProfile` - Update student profile
- `GET /api/student/getOpportunities` - Get available opportunities
- `POST /api/student/uploadNotes` - Upload study notes
- `GET /api/student/fetchNotes` - Get shared notes
- `POST /api/student/getAIResponse` - Get AI coach response
- `GET /api/student/getDashboard` - Get dashboard statistics

### College APIs
- `POST /api/college/createEvent` - Create new event
- `GET /api/college/getEvents` - Get college events
- `DELETE /api/college/deleteEvent` - Delete event

### Company APIs
- `POST /api/company/createJob` - Create new job posting
- `GET /api/company/getJobs` - Get company job postings
- `DELETE /api/company/deleteJob` - Delete job posting

## File Structure

```
learnx/
├── index.php                 # Main landing page
├── login.php                 # Login page
├── register.php              # Registration page
├── README.md                 # This file
├── admin/                    # Admin section
├── ai/                       # AI functionality
├── api/                      # API endpoints
├── assets/                   # Static assets (CSS, JS, images)
├── college/                  # College user section
├── company/                  # Company user section
├── includes/                 # Shared PHP includes
├── student/                  # Student user section
├── uploads/                  # User uploaded files
└── vendor/                   # Third-party libraries
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please contact the development team or create an issue in the repository.

## Future Enhancements

- Mobile application development
- Advanced AI features with machine learning
- Integration with external job boards
- Video conferencing for virtual events
- Advanced analytics and reporting
- Multi-language support
