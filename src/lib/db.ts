import pg from 'pg';
const { Pool } = pg;

const pool = new Pool({
  host: 'localhost',
  port: 5433,
  database: 'lynk',
  user: 'postgres',
  password: 'habibfr',
});

export const query = async (text: string, params?: any[]) => {
  const start = Date.now();
  const res = await pool.query(text, params);
  const duration = Date.now() - start;
  console.log('Executed query', { text, duration, rows: res.rowCount });
  return res;
};

export default pool;
