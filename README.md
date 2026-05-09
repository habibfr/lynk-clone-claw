# Lynk Clone - Link Hub Platform

Full-featured link-in-bio platform inspired by Lynk.id, built with Astro, PostgreSQL, and Tailwind CSS.

## Features

- 🔐 **Authentication** - Register, Login with JWT
- 📝 **Link Management** - Add, Edit, Delete, Toggle Active/Inactive
- 📸 **File Uploads** - Avatar and link images (max 5MB)
- 🎨 **Profile Customization** - Avatar, bio, display name
- 🌐 **Public Profile** - Beautiful profile pages with pagination
- 📊 **Analytics** - Click tracking per link
- 📱 **QR Code** - Generate and download QR codes
- 🎯 **Pattern Breaking Design** - Dark theme with lime green accents

## Tech Stack

- **Frontend:** Astro 5 (SSR), Tailwind CSS
- **Backend:** Node.js, PostgreSQL
- **Auth:** JWT, bcrypt
- **Server:** PM2, Nginx

## Setup

### 1. Clone Repository

```bash
git clone git@github.com:habibfr/lynk-clone-claw.git
cd lynk-clone-claw
git checkout astro
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Database Setup

Start PostgreSQL (Docker):

```bash
docker run -d \
  --name lynk-db \
  -e POSTGRES_PASSWORD=your_password \
  -p 5433:5432 \
  postgres:15
```

Create database and run schema:

```bash
docker exec -i lynk-db psql -U postgres -c "CREATE DATABASE lynk;"
docker exec -i lynk-db psql -U postgres -d lynk < db/schema.sql
```

### 4. Environment Variables

Copy `.env.example` to `.env` and update values:

```bash
cp .env.example .env
```

Edit `.env`:
- Set `DB_PASSWORD` to your PostgreSQL password
- Change `JWT_SECRET` to a secure random string (min 32 chars)

### 5. Build and Run

Development:
```bash
npm run dev
```

Production:
```bash
npm run build
pm2 start dist/server/entry.mjs --name lynk
```

### 6. Nginx Configuration (Optional)

```nginx
server {
    server_name your-domain.com;
    
    location /uploads/ {
        alias /path/to/lynk-clone/public/uploads/;
        expires 1y;
    }
    
    location / {
        proxy_pass http://localhost:3001;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

## Project Structure

```
lynk-clone/
├── src/
│   ├── pages/
│   │   ├── api/          # API endpoints
│   │   ├── u/            # Public profiles
│   │   ├── index.astro   # Homepage
│   │   ├── login.astro
│   │   ├── register.astro
│   │   ├── dashboard.astro
│   │   └── settings.astro
│   └── lib/
│       ├── db.ts         # Database connection
│       └── auth.ts       # Auth utilities
├── public/
│   └── uploads/          # User uploads (gitignored)
├── db/
│   └── schema.sql        # Database schema
└── .env                  # Environment variables (gitignored)
```

## Security Notes

- ⚠️ Change `JWT_SECRET` in production
- ⚠️ Use strong database passwords
- ⚠️ Enable HTTPS in production
- ⚠️ Set proper file upload limits
- ⚠️ Sanitize user inputs

## License

MIT

## Author

Built by Haclaw 😎
