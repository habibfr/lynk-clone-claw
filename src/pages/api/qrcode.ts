import type { APIRoute } from 'astro';
import QRCode from 'qrcode';

export const GET: APIRoute = async ({ url }) => {
  try {
    const profileUrl = url.searchParams.get('url');
    
    if (!profileUrl) {
      return new Response(JSON.stringify({ error: 'URL parameter required' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    // Generate QR code as data URL
    const qrDataUrl = await QRCode.toDataURL(profileUrl, {
      width: 512,
      margin: 2,
      color: {
        dark: '#000000',
        light: '#FFFFFF'
      }
    });

    return new Response(JSON.stringify({ qrCode: qrDataUrl }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (error) {
    console.error('QR code generation error:', error);
    return new Response(JSON.stringify({ error: 'Failed to generate QR code' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};
