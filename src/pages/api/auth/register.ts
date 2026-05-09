import type { APIRoute } from 'astro';
import { query } from '../../../lib/db';
import { hashPassword, generateToken } from '../../../lib/auth';

export const POST: APIRoute = async ({ request }) => {
  try {
    const { username, email, password } = await request.json();

    // Validation
    if (!username || !email || !password) {
      return new Response(JSON.stringify({ error: 'All fields are required' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    if (username.length < 3 || username.length > 50) {
      return new Response(JSON.stringify({ error: 'Username must be 3-50 characters' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    if (!/^[a-zA-Z0-9_]+$/.test(username)) {
      return new Response(JSON.stringify({ error: 'Username can only contain letters, numbers, and underscores' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    if (password.length < 6) {
      return new Response(JSON.stringify({ error: 'Password must be at least 6 characters' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    // Check if user exists
    const existingUser = await query(
      'SELECT id FROM users WHERE username = $1 OR email = $2',
      [username, email]
    );

    if (existingUser.rows.length > 0) {
      return new Response(JSON.stringify({ error: 'Username or email already exists' }), {
        status: 409,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    // Hash password and create user
    const passwordHash = await hashPassword(password);
    const result = await query(
      'INSERT INTO users (username, email, password_hash, display_name) VALUES ($1, $2, $3, $4) RETURNING id, username, email, display_name',
      [username, email, passwordHash, username]
    );

    const user = result.rows[0];
    const token = generateToken(user.id, user.username);

    return new Response(JSON.stringify({
      success: true,
      token,
      user: {
        id: user.id,
        username: user.username,
        email: user.email,
        displayName: user.display_name
      }
    }), {
      status: 201,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (error) {
    console.error('Register error:', error);
    return new Response(JSON.stringify({ error: 'Internal server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};
