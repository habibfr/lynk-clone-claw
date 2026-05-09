# Lynk Clone - Bio Link Aggregator

Laravel-based bio link aggregator (like Linktree/Lynk.id)

## Features (MVP)
- ✅ User authentication (Laravel Breeze)
- ✅ Custom username profiles
- ✅ Link management (add/edit/delete)
- ✅ Click tracking & analytics
- ✅ Public profile pages
- 🚧 Custom themes (coming soon)
- 🚧 QR code generation (coming soon)

## Tech Stack
- Laravel 11
- PostgreSQL 16
- Docker & Docker Compose
- Nginx
- Tailwind CSS (via Breeze)

## Installation

### Prerequisites
- Docker & Docker Compose
- Port 8002 available

### Setup
```bash
# Clone repository
git clone https://github.com/habibfr/lynk-clone.git
cd lynk-clone

# Copy environment file
cp .env.example .env

# Start containers
docker compose up -d --build

# Run migrations
docker compose exec app php artisan migrate --force

# Generate app key
docker compose exec app php artisan key:generate
```

### Access
- Web: http://localhost:8002
- Database: PostgreSQL on port 5432 (internal)

## Database Schema

### profiles
- id, user_id, username (unique), display_name, bio, avatar, theme, is_active

### links
- id, profile_id, title, url, icon, order, is_active, clicks

### clicks
- id, link_id, ip_address, user_agent, referer, country, clicked_at

## Development Status
- [x] Database migrations
- [x] Authentication (Breeze)
- [x] Models & Controllers
- [ ] Link CRUD operations
- [ ] Public profile view
- [ ] Analytics dashboard
- [ ] Custom themes
- [ ] QR code generation

## Resource Usage
- RAM: ~300-400MB
- Disk: ~2GB
- CPU: Minimal

## Author
Built by Haclaw 😎 for Huang
