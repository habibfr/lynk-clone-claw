import type { APIRoute } from 'astro';
import pg from 'pg';
import jwt from 'jsonwebtoken';

const { Pool } = pg;

const pool = new Pool({
  host: 'localhost',
  port: 5433,
  database: 'lynk',
  user: 'postgres',
  password: 'habibfr',
});

const query = async (text: string, params?: any[]) => {
  return pool.query(text, params);
};

const JWT_SECRET = process.env.JWT_SECRET || 'your-secret-key-change-in-production';

const verifyToken = (token: string): any => {
  try {
    return jwt.verify(token, JWT_SECRET);
  } catch (error) {
    return null;
  }
};

const getUserFromRequest = (request: Request): any => {
  const authHeader = request.headers.get('Authorization');
  if (!authHeader?.startsWith('Bearer ')) {
    return null;
  }
  
  const token = authHeader.substring(7);
  return verifyToken(token);
};

export const GET: APIRoute = async ({ request }) => {
  try {
    const user = getUserFromRequest(request);
    
    if (!user) {
      return new Response(JSON.stringify({ error: 'Unauthorized' }), {
        status: 401,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    const result = await query(
      'SELECT id, username, email, display_name, bio, avatar_url, theme FROM users WHERE id = $1',
      [user.userId]
    );

    if (result.rows.length === 0) {
      return new Response(JSON.stringify({ error: 'User not found' }), {
        status: 404,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    return new Response(JSON.stringify({ user: result.rows[0] }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (error) {
    console.error('Get profile error:', error);
    return new Response(JSON.stringify({ error: 'Internal server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};

export const PUT: APIRoute = async ({ request }) => {
  try {
    const user = getUserFromRequest(request);
    
    if (!user) {
      return new Response(JSON.stringify({ error: 'Unauthorized' }), {
        status: 401,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    const { display_name, bio, avatar_url } = await request.json();

    const result = await query(
      'UPDATE users SET display_name = $1, bio = $2, avatar_url = $3, updated_at = CURRENT_TIMESTAMP WHERE id = $4 RETURNING id, username, email, display_name, bio, avatar_url, theme',
      [display_name, bio, avatar_url, user.userId]
    );

    return new Response(JSON.stringify({ user: result.rows[0] }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (error) {
    console.error('Update profile error:', error);
    return new Response(JSON.stringify({ error: 'Internal server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};
