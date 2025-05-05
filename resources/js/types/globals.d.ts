import Pusher from 'pusher-js';
import type { route as routeFn } from 'ziggy-js';

declare global {
    const route: typeof routeFn;
    interface Window {
        Pusher: any; // or a more specific type if you know it, e.g., typeof Pusher
    }
}
