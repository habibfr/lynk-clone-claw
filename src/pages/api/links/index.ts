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
      'SELECT id, title, url, description, icon, image_url, position, is_active, clicks FROM links WHERE user_id = $1 ORDER BY position ASC',
      [user.userId]
    );

    return new Response(JSON.stringify({ links: result.rows }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (error) {
    console.error('Get links error:', error);
    return new Response(JSON.stringify({ error: 'Internal server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};

export const POST: APIRoute = async ({ request }) => {
  try {
    const user = getUserFromRequest(request);
    
    if (!user) {
      return new Response(JSON.stringify({ error: 'Unauthorized' }), {
        status: 401,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    const { title, url, description, icon, image_url } = await request.json();

    if (!title || !url) {
      return new Response(JSON.stringify({ error: 'Title and URL are required' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    // Get max position
    const posResult = await query(
      'SELECT COALESCE(MAX(position), -1) + 1 as next_position FROM links WHERE user_id = $1',
      [user.userId]
    );
    const position = posResult.rows[0].next_position;

    const result = await query(
      'INSERT INTO links (user_id, title, url, description, icon, image_url, position) VALUES ($1, $2, $3, $4, $5, $6, $7) RETURNING id, title, url, description, icon, image_url, position, is_active, clicks',
      [user.userId, title, url, description || null, icon || null, image_url || null, position]
    );

    return new Response(JSON.stringify({ link: result.rows[0] }), {
      status: 201,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (error) {
    console.error('Create link error:', error);
    return new Response(JSON.stringify({ error: 'Internal server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};
