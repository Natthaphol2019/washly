import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Pusher = Pusher;

const rawHost = (import.meta.env.VITE_REVERB_HOST || '').trim();
const localHosts = new Set(['', 'localhost', '127.0.0.1', '::1', '0.0.0.0']);
const reverbHost = localHosts.has(rawHost) ? window.location.hostname : rawHost;

const reverbScheme = (import.meta.env.VITE_REVERB_SCHEME || window.location.protocol.replace(':', '')).trim();
const reverbPort = Number(import.meta.env.VITE_REVERB_PORT || (reverbScheme === 'https' ? 443 : 80));
const reverbPath = (import.meta.env.VITE_REVERB_PATH || '').trim();

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const authHeaders = csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {};

window.Echo = new Echo({
	broadcaster: 'reverb',
	key: import.meta.env.VITE_REVERB_APP_KEY,
	wsHost: reverbHost,
	wsPort: reverbPort,
	wssPort: reverbPort,
	wsPath: reverbPath,
	forceTLS: reverbScheme === 'https',
	enabledTransports: ['ws', 'wss'],
	authEndpoint: '/broadcasting/auth',
	auth: {
		headers: authHeaders,
	},
	disableStats: true,
});
