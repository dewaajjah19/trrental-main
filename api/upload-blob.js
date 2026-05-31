import { put } from '@vercel/blob';

export const config = {
  api: {
    bodyParser: false,
  },
};

export default async function handler(req, res) {
  try {
    if (req.method !== 'POST') {
      return res.status(405).json({
        success: false,
        message: 'Method not allowed',
      });
    }

    const filename = req.query.filename || `dokumen-${Date.now()}.jpg`;

    const blob = await put(filename, req, {
      access: 'public',
      addRandomSuffix: true,
    });

    return res.status(200).json({
      success: true,
      url: blob.url,
      pathname: blob.pathname,
    });
  } catch (error) {
    return res.status(500).json({
      success: false,
      message: error.message || 'Upload Blob gagal',
    });
  }
}