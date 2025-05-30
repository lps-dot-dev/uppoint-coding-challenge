import { App } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

const echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST,
  wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
  wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
  forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
  enabledTransports: ['ws', 'wss'],
  authEndpoint: '/api/broadcasts/auth',
  auth: {
    headers: {
      'X-Echo-Reverb': 'auth'
    }
  }
});

export const EchoSymbol = Symbol('Echo');

export function useEchoReverbPlugin() {
  return {
    install(app: App) {
      app.provide(EchoSymbol, echo);
    },
  };
}
