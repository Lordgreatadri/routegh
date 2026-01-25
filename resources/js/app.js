import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Import profile form component so it's available globally (Vite will bundle this)
import './profile-form';
