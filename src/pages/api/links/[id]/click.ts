import type { APIRoute } from 'astro';
import { query } from '../../../../lib/db';

export const POST: APIRoute = async ({ request, params }) => {
  try {
    const linkId = params.id;

    // Increment click count
    await query(
      'UPDATE links SET clicks = clicks + 1 WHERE id = $1',
      [linkId]
    );

    // Log click for analytics (optional)
    const clientIp = request.headers.get('x-forwarded-for') || request.headers.get('x-real-ip') || 'unknown';
    const userAgent = request.headers.get('user-agent') || '';
    const referrer = request.headers.get('referer') || '';

    await query(
      'INSERT INTO link_clicks (link_id, ip_address, user_agent, referrer) VALUES ($1, $2, $3, $4)',
      [linkId, clientIp, userAgent, referrer]
    );

    return new Response(JSON.stringify({ success: true }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (error) {
    console.error('Track click error:', error);
    return new Response(JSON.stringify({ error: 'Internal server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};
