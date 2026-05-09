import type { APIRoute } from 'astro';
import { writeFile } from 'fs/promises';
import { join } from 'path';
import jwt from 'jsonwebtoken';

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

export const POST: APIRoute = async ({ request }) => {
  try {
    const user = getUserFromRequest(request);
    
    if (!user) {
      return new Response(JSON.stringify({ error: 'Unauthorized' }), {
        status: 401,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    const formData = await request.formData();
    const file = formData.get('file') as File;

    if (!file) {
      return new Response(JSON.stringify({ error: 'No file provided' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
      return new Response(JSON.stringify({ error: 'Invalid file type. Only images allowed.' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
      return new Response(JSON.stringify({ error: 'File too large. Max 5MB.' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    // Generate unique filename
    const ext = file.name.split('.').pop();
    const filename = `${user.userId}-${Date.now()}.${ext}`;
    const filepath = join(process.cwd(), 'public', 'uploads', 'links', filename);

    // Save file
    const buffer = Buffer.from(await file.arrayBuffer());
    await writeFile(filepath, buffer);

    const url = `/uploads/links/${filename}`;

    return new Response(JSON.stringify({ url }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (error) {
    console.error('Upload link image error:', error);
    return new Response(JSON.stringify({ error: 'Upload failed' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};
