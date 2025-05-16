# Contact Management System

### 9. Client-Side Implementation Example

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

Payload Structure (Request Example)

```json
{
    "name": "John Doe",
    "email": "john@example.com",
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

