# Password Recovery System

A complete password recovery system with email verification code functionality. Users can request a password reset, receive a verification code via email, and create a new password.

## Features

- ✅ Forgot Password page with email input
- ✅ Email verification code generation and sending
- ✅ Code verification page with 6-digit input
- ✅ Password reset page with strength validation
- ✅ Secure token-based password reset
- ✅ Beautiful, modern UI matching your login design
- ✅ Responsive design for all devices

## Project Structure

```
.
├── forgot-password.html    # Initial forgot password page
├── verify-code.html        # Email code verification page
├── reset-password.html     # New password creation page
├── server.js               # Backend API server
├── package.json            # Node.js dependencies
├── .env.example            # Email configuration template
└── README.md              # This file
```

## Setup Instructions

### 1. Install Dependencies

```bash
npm install
```

### 2. Configure Email Settings

**Option A: Using Gmail (Recommended for testing)**

1. Copy `.env.example` to `.env`:
   ```bash
   copy .env.example .env
   ```

2. Enable 2-factor authentication on your Google account

3. Generate an app-specific password:
   - Go to https://myaccount.google.com/apppasswords
   - Create a new app password for "Mail"
   - Copy the generated password

4. Update `.env` file:
   ```
   EMAIL_USER=your-email@gmail.com
   EMAIL_PASS=your-app-specific-password
   ```

**Option B: Using Other Email Providers**

Update the SMTP settings in `.env`:
- **Outlook**: `SMTP_HOST=smtp-mail.outlook.com`
- **Yahoo**: `SMTP_HOST=smtp.mail.yahoo.com`
- **Custom**: Use your provider's SMTP settings

**Option C: For Testing (Ethereal Email)**

For development/testing without real email, you can use Ethereal Email:
1. Visit https://ethereal.email/
2. Create a test account
3. Update `server.js` with the generated credentials

### 3. Update Server Configuration

Edit `server.js` and update the email transporter configuration if needed (lines 20-30).

### 4. Start the Server

```bash
npm start
```

For development with auto-reload:
```bash
npm run dev
```

The server will start on `http://localhost:3000`

### 5. Open the Forgot Password Page

Open `forgot-password.html` in your browser or integrate it into your existing application.

## Usage Flow

1. **User clicks "Forgot Password"** → Redirects to `forgot-password.html`
2. **User enters email** → System sends 6-digit verification code
3. **User enters code** → Verified on `verify-code.html`
4. **User creates new password** → Password reset on `reset-password.html`
5. **Success** → Redirects to login page

## API Endpoints

### POST `/api/forgot-password`
Request a password reset code.

**Request:**
```json
{
  "email": "user@example.com"
}
```

**Response:**
```json
{
  "message": "Verification code sent to your email address"
}
```

### POST `/api/verify-code`
Verify the email code.

**Request:**
```json
{
  "email": "user@example.com",
  "code": "123456"
}
```

**Response:**
```json
{
  "message": "Code verified successfully",
  "token": "reset-token-here"
}
```

### POST `/api/reset-password`
Reset the password.

**Request:**
```json
{
  "email": "user@example.com",
  "token": "reset-token-here",
  "newPassword": "NewSecurePassword123!"
}
```

**Response:**
```json
{
  "message": "Password reset successfully"
}
```

## Integration with Your Login Page

Add a "Forgot Password?" link to your login page:

```html
<a href="forgot-password.html">Forgot Password?</a>
```

## Security Features

- ✅ Verification codes expire after 10 minutes
- ✅ Reset tokens expire after 30 minutes
- ✅ Secure token generation using crypto
- ✅ Password strength validation
- ✅ Automatic cleanup of expired codes/tokens
- ✅ Email verification before password reset

## Production Considerations

Before deploying to production:

1. **Use Environment Variables**: Never hardcode email credentials
2. **Use a Database**: Replace in-memory storage with a database (MongoDB, PostgreSQL, Redis)
3. **Rate Limiting**: Add rate limiting to prevent abuse
4. **HTTPS**: Always use HTTPS in production
5. **Email Service**: Consider using a dedicated email service (SendGrid, Mailgun, AWS SES)
6. **Remove Test Logging**: Remove console.log statements that show verification codes
7. **Add User Validation**: Verify user exists before sending codes
8. **Add CAPTCHA**: Prevent automated attacks
9. **Logging**: Add proper logging and monitoring
10. **Error Handling**: Improve error messages (don't reveal if email exists)

## Customization

### Styling
All HTML files include inline CSS. Modify the `<style>` sections to match your brand colors and design.

### Email Template
Edit the `sendVerificationCode` function in `server.js` to customize the email template.

### Code Expiration
Modify expiration times:
- Verification codes: Line 50 in `server.js` (currently 10 minutes)
- Reset tokens: Line 95 in `server.js` (currently 30 minutes)

## Troubleshooting

### Email Not Sending
- Check email credentials in `.env`
- Verify SMTP settings match your email provider
- Check firewall/network settings
- For Gmail, ensure app-specific password is used (not regular password)

### Codes Not Working
- Check server console for errors
- Verify server is running on correct port
- Check browser console for API errors
- Ensure CORS is properly configured

### Port Already in Use
Change the PORT in `server.js` (line 7) to a different port.

## License

MIT
