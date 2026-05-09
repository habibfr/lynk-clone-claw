module.exports = {
  apps: [{
    name: 'lynk-astro',
    script: './dist/server/entry.mjs',
    cwd: '/home/ubuntu/dev/lynk-clone',
    env: {
      NODE_ENV: 'production',
      HOST: '0.0.0.0',
      PORT: '3001',
      DB_HOST: 'localhost',
      DB_PORT: '5433',
      DB_NAME: 'lynk',
      DB_USER: 'postgres',
      DB_PASSWORD: 'habibfr',
      JWT_SECRET: 'your-super-secret-jwt-key-change-this-in-production'
    }
  }]
};
