-- Users table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(100),
    bio TEXT,
    avatar_url VARCHAR(500),
    theme VARCHAR(50) DEFAULT 'default',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Links table
CREATE TABLE IF NOT EXISTS links (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    url TEXT NOT NULL,
    icon VARCHAR(100),
    position INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    clicks INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Social links table
CREATE TABLE IF NOT EXISTS social_links (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    platform VARCHAR(50) NOT NULL,
    url TEXT NOT NULL,
    position INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Analytics table
CREATE TABLE IF NOT EXISTS link_clicks (
    id SERIAL PRIMARY KEY,
    link_id INTEGER REFERENCES links(id) ON DELETE CASCADE,
    clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    referrer TEXT
);

-- Indexes
CREATE INDEX IF NOT EXISTS idx_links_user_id ON links(user_id);
CREATE INDEX IF NOT EXISTS idx_links_position ON links(position);
CREATE INDEX IF NOT EXISTS idx_social_links_user_id ON social_links(user_id);
CREATE INDEX IF NOT EXISTS idx_link_clicks_link_id ON link_clicks(link_id);
CREATE INDEX IF NOT EXISTS idx_link_clicks_clicked_at ON link_clicks(clicked_at);
