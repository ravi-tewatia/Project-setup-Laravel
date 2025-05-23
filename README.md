<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<h1>Laravel BaseCode</h1>

<h2>Setup</h2>

<h3>Checkout Process</h3>

- git config --global user.name "Administrator"
- git config --global user.email "admin@example.com"
- git clone http://192.168.1.175/root/laravel_base.git laravel_base
- composer install

<p></p>

<h3>Please follow below steps after checkout:</h3>

- Create database with <b>utf8mb4_unicode_ci</b> encoding. example: <b>laravel</b>
- Go to the config/database.php and change default MySQL engine to 'InnoDB'
- Open Terminal and go to directory in which you have checkout the project and run: php artisan migrate
- copy "pre-commit" file which is in root directory and place it inside your project's .git/hooks folder
    - exa: C:\wamp64\www\laravel_base\\.git\hooks
- Perform <b>composer global require squizlabs/php_codesniffer</b> (open Windows terminal and paste this, Do not do this VS CODE terminal)
- Perform <b>npm install -g eslint</b> (open Windows terminal and paste this, Do not do this VS CODE terminal)
- Perform <b>npm install -g stylint</b> (open Windows terminal and paste this, Do not do this VS CODE terminal)

<p></p>

<h2>Editor Basic Setup<br/>====</h2>

<b>Visual Studio Code</b>

- Click on the <b>CRLF button</b> found at the <b>bottom-right in VS Code</b>.
- Select <b>LF</b>  at the top
- Install below extensions from <b>VS code editor extensions and follow its respective stpes</b>.
    - <b>phpcs v1.0.5 +</b> : For PHP code validation
    - <b>eslint v2.1.14 +</b> : For JS code validation
        - Make sure to "Enable everywhere" setting for eslint
    - <b>stylint v0.1.3 +</b> : For CSS code validation
- Right click on any installed extension or click GEAR icon followed by extension name
    - Select <b>Extension Settings</b>
    - Click on any "Edit in settings.json" link<br/>
    - Add / Edit below lines in opened settings.json file<br/>
        <pre>
        "phpcs.standard": "PSR2",
        "files.eol": "\n",
        "eslint.validate": [
            "vue",
            "html",
            "javascript"
        ],
        </pre>

    - Run <b>eslint --init</b> in VS terminal and generate scenarios like below
    <pre>
        √ How would you like to use ESLint? · style       
        √ What type of modules does your project use? · none
        √ Which framework does your project use? · none
        √ Does your project use TypeScript? · No
        √ Where does your code run? · browser
        √ How would you like to define a style for your project? · guide
        √ Which style guide do you want to follow? · standard
        √ What format do you want your config file to be in? · JSON
        √ Would you like to install them now with npm? · Yes
    </pre>
- Restart the VS code editor.
- If Above steps does not work, </b>
    - Press "<b>Command + ,</b>"
    - Select User Settings and locate <b>PHP CodeSnipper</b>
    - Scroll to <b>Executable Path</b> and put<br>
        <b>/Users/your-username/.composer/vendor/bin/phpcs</b>
- Also check the "PROBLEMS" tab of the VS Code to check / resolve the issues.
- Enable php cs fixer extension and then do "alt + shift + f" to format the file

<p></p>

<h2>Features/Road Map<br/>======</h2>

- [x] Basic Auth
- [x] Query Logging in File
- [x] Exception Logging in File
- [x] Auto Login/Logout from other tabs
- [x] ESLint
- [ ] Stylint (We have done VS code validation but Pre Commit hook on client side is pending)
- [x] PHP CodeSniffer (i.e. phpcs)
- [x] Git Pre Commit Client Hook with phpcs, stylist & eslint support
- [ ] Git Server Side Hook (We have tried pre-receive on GitLab server but git commands didn't worked there so need to check with IT team)
- [x] CRUD using livewire with search, sorting & export (<b>It's done but not going to add in master branch. If you need this checkout feature-7 branh.</b>)
- [x] Activity Log Model
- [ ] Session timeout prompt
- [ ] User's Session Listing & logout from all/any active session
- [ ] Two Factor with SMS
- [ ] Rate Limiter Listing
- [ ] Cron Jobs
- [ ] Custom Permissions
- [ ] Website Daily Summery Report
- [ ] Simple API JWT/Auth Token with all VERB type (i.e. PUT, GET, POST, DELETE etc.)
- [x] API Log (provision to bypass authentication and test)
- [ ] Ip Restriction for certain api's
- [ ] Different Language provision
- [ ] Settings Api
- [ ] Documentation to track user journey
- [ ] Maintnance Page, Setting page to enable and disable flag




<b>Postmen Link : (https://www.getpostman.com/collections/673c619a1ef23817a742)</b>
<h1></h1>
## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

# Laravel Base Project

A Laravel project with Redis integration and authentication features.

## Requirements

- PHP >= 8.1
- Composer
- MySQL
- Redis for Windows

## Advanced Features

### API Features
- [x] JWT Authentication
- [x] API Versioning
- [x] Rate Limiting
- [x] Request Validation
- [x] API Documentation (Swagger/OpenAPI)
- [x] API Response Transformer
- [x] API Logging
- [x] CORS Configuration
- [x] API Throttling
- [x] Request/Response Interceptors

### Security Features
- [x] Sanctum Authentication
- [x] Email Verification
- [x] Password Reset
- [x] User Blocking System
- [x] Two-Factor Authentication (2FA)
- [x] IP Restriction
- [x] API Key Management
- [x] Role-Based Access Control (RBAC)
- [x] Session Management
- [x] Activity Logging

### Performance Features
- [x] Redis Caching
- [x] Query Optimization
- [x] Database Indexing
- [x] Response Compression
- [x] API Response Caching

## Installation Steps

1. **Clone the repository**
```bash
git clone [your-repository-url]
cd [project-name]
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Database**
- Update `.env` file with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Install Redis for Windows**
- Download Redis for Windows: [Redis-x64-3.0.504.msi](https://github.com/microsoftarchive/redis/releases/download/win-3.0.504/Redis-x64-3.0.504.msi)
- Run the installer
- Keep default settings during installation
- Redis will be installed as a Windows service

6. **Configure Redis in .env**
```
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

7. **Configure API Settings in .env**
```
API_DEBUG=true
API_THROTTLE=60,1
API_VERSION=v1
API_PREFIX=api
API_DOMAIN=api.yourdomain.com
```

8. **Run Migrations**
```bash
php artisan migrate
```

9. **Generate API Documentation**
```bash
php artisan l5-swagger:generate
```

10. **Start the Development Server**
```bash
php artisan serve
```

## API Structure

### Versioning
All API endpoints are versioned:
```
/api/v1/resource
/api/v2/resource
```

### Authentication
- JWT Token Authentication
- API Key Authentication
- OAuth2 Support (Optional)

### Response Format
```json
{
    "status": "success",
    "code": 200,
    "message": "Operation successful",
    "data": {},
    "meta": {
        "pagination": {},
        "timestamp": "2024-03-21T12:00:00Z"
    }
}
```

## API Endpoints

### Authentication
- POST `/api/v1/register` - Register new user
- POST `/api/v1/login` - User login
- POST `/api/v1/logout` - User logout (requires authentication)
- POST `/api/v1/forgot-password` - Request password reset
- POST `/api/v1/reset-password` - Reset password
- POST `/api/v1/change-password` - Change password (requires authentication)
- POST `/api/v1/2fa/enable` - Enable 2FA
- POST `/api/v1/2fa/verify` - Verify 2FA

### User Management
- GET `/api/v1/profile` - Get user profile (requires authentication)
- PUT `/api/v1/profile` - Update user profile (requires authentication)
- GET `/api/v1/users` - Get list of users (requires authentication)
- GET `/api/v1/users/blocked` - Get list of blocked users (requires authentication)
- POST `/api/v1/users/{id}/block` - Block a user (requires authentication)
- POST `/api/v1/users/{id}/unblock` - Unblock a user (requires authentication)
- GET `/api/v1/users/sessions` - Get active sessions (requires authentication)
- POST `/api/v1/users/sessions/{id}/logout` - Logout from specific session (requires authentication)

### File Management
- POST `/api/v1/upload` - Upload file
- POST `/api/v1/submit` - Submit form with file
- GET `/api/v1/files` - List uploaded files
- DELETE `/api/v1/files/{id}` - Delete file

### System
- GET `/api/v1/health` - System health check
- GET `/api/v1/status` - API status
- GET `/api/v1/rate-limits` - Check rate limits

## API Documentation
Access the API documentation at:
```
http://your-domain/api/documentation
```

## Testing

### Unit Tests
```bash
php artisan test
```

### API Tests
```bash
php artisan test --testsuite=Feature
```

### Performance Tests
```bash
php artisan test --testsuite=Performance
```

## Security Best Practices
1. All API endpoints are rate-limited
2. Sensitive data is encrypted
3. API keys are rotated regularly
4. All requests are logged
5. CORS is properly configured
6. Input validation is enforced
7. SQL injection prevention
8. XSS protection
9. CSRF protection
10. Regular security audits

## Monitoring
- API request/response logging
- Error tracking
- Performance monitoring
- Rate limit monitoring
- Security event logging

## Contributing
Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## License
This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
