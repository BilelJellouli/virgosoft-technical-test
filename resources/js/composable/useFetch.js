const BASE_URL = 'http://127.0.0.1/api/';
const HEADERS = {
    'accept': 'application/json',
    'Content-Type': 'application/json'
};

const fetchApi = async (url, options = {}) => {
    const headers = { ...HEADERS, ...options.headers };
    const token = localStorage.getItem('accessToken');
    if (token) headers['Authorization'] = `Bearer ${token}`;

    return await fetch(BASE_URL + url, { ...options, headers });
};

const getJson = (url) => fetchApi(url, { method: 'GET' });
const postJson = (url, body) => fetchApi(url, { method: 'POST', body: JSON.stringify(body) });

export { getJson, postJson };
