import './bootstrap';

import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide'; // ✅ betul

window.Alpine = Alpine;
Alpine.start();

// Jalankan lucide setelah DOM siap
document.addEventListener("DOMContentLoaded", () => {
    createIcons({ icons });
});