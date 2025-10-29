# StudentHub - Career Development Platform

A comprehensive web platform that combines features from LinkedIn, Unstop, Naukri, LeetCode, HackerRank, and other career development tools into a single, unified system for students, colleges, and companies.

## Features

### For Students
- **Personal Dashboard**: Overview of opportunities, notes, and AI interactions
- **Opportunities Hub**: Access to hackathons, internships, and placement drives
- **AI Career Coach**: Intelligent career guidance and advice
- **Notes Sharing**: Upload and share study materials with peers
- **Profile Management**: Complete profile with skills, college info, and achievements

### For Colleges
- **Event Management**: Post hackathons, symposiums, project expos, and workshops
- **Student Engagement**: Manage college events and track participation
- **Institution Branding**: Showcase college achievements and opportunities

### For Companies
- **Job Postings**: Post internships, full-time positions, and contract roles
- **Talent Acquisition**: Find skilled students for recruitment
- **Employer Branding**: Build company reputation and attract talent

### For Administrators
- **User Management**: Oversee all users and their activities
- **Content Moderation**: Manage events, jobs, and shared content
- **Analytics**: View platform statistics and usage metrics

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Architecture**: MVC-inspired structure

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for PHP dependencies)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/studenthub.git
   cd studenthub
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
studenthub/
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
