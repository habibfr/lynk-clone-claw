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

export const PUT: APIRoute = async ({ request, params }) => {
  try {
    const user = getUserFromRequest(request);
    
    if (!user) {
      return new Response(JSON.stringify({ error: 'Unauthorized' }), {
        status: 401,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    const linkId = params.id;
    const { title, url, description, icon, image_url, is_active } = await request.json();

    // Verify ownership
    const checkResult = await query(
      'SELECT id FROM links WHERE id = $1 AND user_id = $2',
      [linkId, user.userId]
    );

    if (checkResult.rows.length === 0) {
      return new Response(JSON.stringify({ error: 'Link not found' }), {
        status: 404,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    const result = await query(
      'UPDATE links SET title = $1, url = $2, description = $3, icon = $4, image_url = $5, is_active = $6, updated_at = CURRENT_TIMESTAMP WHERE id = $7 RETURNING id, title, url, description, icon, image_url, position, is_active, clicks',
      [title, url, description, icon, image_url, is_active, linkId]
    );

    return new Response(JSON.stringify({ link: result.rows[0] }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (error) {
    console.error('Update link error:', error);
    return new Response(JSON.stringify({ error: 'Internal server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};

export const DELETE: APIRoute = async ({ request, params }) => {
  try {
    const user = getUserFromRequest(request);
    
    if (!user) {
      return new Response(JSON.stringify({ error: 'Unauthorized' }), {
        status: 401,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    const linkId = params.id;

    // Verify ownership and delete
    const result = await query(
      'DELETE FROM links WHERE id = $1 AND user_id = $2 RETURNING id',
      [linkId, user.userId]
    );

    if (result.rows.length === 0) {
      return new Response(JSON.stringify({ error: 'Link not found' }), {
        status: 404,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    return new Response(JSON.stringify({ success: true }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (error) {
    console.error('Delete link error:', error);
    return new Response(JSON.stringify({ error: 'Internal server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};
