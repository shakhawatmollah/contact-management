# Contact Management System
A professional contact management system with email functionality built with Laravel 12, Tailwind CSS, and Alpine.js.

## Features

- **Contact Management**
    - Create, view, and manage contacts
    - Track contact sources and metadata
    - Paginated listing with search functionality

- **Email Integration**
    - Email composition interface
    - Reply to contacts directly
    - Email history tracking
    - Mark emails as read/unread

- **API Endpoints**
    - Secure contact submission API
    - Rate limiting and request validation
    - CORS protection
    - JSON responses

- **Security**
    - CSRF protection
    - SQL injection prevention
    - Rate limiting
    - Input validation
    - reCAPTCHA integration - Later

- **User Interface**
    - Responsive design
    - Tailwind CSS styling
    - Alpine.js for interactive elements

## Requirements

- PHP 8.2+
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Node.js 16+ (for frontend assets)
- npm

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/shakhawatmollah/contact-management.git
   cd contact-management
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   npm run dev
   ```

4. Create and configure the `.env` file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database settings in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=contact_management
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

7. Configure mail settings (optional for email functionality):
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=mailpit
   MAIL_PORT=1025
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS="hello@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```

8. Serve the application:
   ```bash
   php artisan serve
   ```
### Example API Request

```bash
curl -X POST \
  http://yourdomain.com/api/v1/contacts \
  -H 'Content-Type: application/json' \
  -H 'X-API-KEY: your-api-key' \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "message": "Test contact message",
    "source_website": "https://client-website.com"
  }'
```

## Configuration

Key configuration options in `.env`:

```env
# reCAPTCHA Settings
RECAPTCHA_SITE_KEY=your-site-key
RECAPTCHA_SECRET_KEY=your-secret-key
RECAPTCHA_THRESHOLD=0.5

# API Security
CONTACT_API_KEY=your-secure-api-key
ALLOWED_ORIGINS=https://yourfrontend.com,https://yourotherdomain.com

# Rate Limiting
API_RATE_LIMIT=60
API_RATE_LIMIT_PER_MINUTE=5
```

## Testing

Run the test suite with:

```bash
php artisan test
```

### Client-Side Implementation Example

Here's how clients can implement the API in their websites:

```javascript
// Example using Fetch API
async function submitContactForm(data) {
    try {
        const response = await fetch('https://your-api-domain.com/api/v1/contacts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Origin': window.location.origin,
                // Include if using API keys:
                // 'X-API-KEY': 'your-client-api-key'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Submission failed');
        }

        return await response.json();
    } catch (error) {
        console.error('Contact form error:', error);
        throw error;
    }
}

// Usage example
submitContactForm({
    name: 'John Doe',
    email: 'john@example.com',
    message: 'Hello from your website!',
    metadata: {
        page_url: window.location.pathname,
        utm_source: 'google'
    }
})
.then(response => {
    console.log('Success:', response);
    // Show success message to user
})
.catch(error => {
    console.error('Error:', error);
    // Show error message to user
});
```

Payload Structure (Request Example for Postman)
```
URL: http://127.0.0.1:8000/api/v1/contacts
Method: POST
Headers:
Content-Type: application/json
X-API-KEY: your-api-key
```
```json
{
    "name": "Shakhawat Mollah",
    "email": "shakhawat@your-domain.com",
    "phone": "+1234567890",
    "message": "I'm interested in your services",
    "source_website": "https://client-website.com",
    "metadata": {
        "page_url": "/contact-us",
        "user_agent": "Mozilla/5.0...",
        "utm_source": "google",
        "utm_campaign": "summer_sale"
    }
}
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## Support

For support, please open an issue on GitHub.
