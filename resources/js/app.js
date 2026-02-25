import './bootstrap';
import './nav.js';
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Start Alpine
Alpine.start();

// This is the key for Livewire reactivity.
// It tells Alpine to re-scan the DOM after Livewire has finished updating it.
document.addEventListener('livewire:navigated', () => {
    Alpine.initTree(document.body);
});
