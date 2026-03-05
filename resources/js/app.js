import './nav.js';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('livewire:navigated', () => {
    Alpine.initTree(document.body);
});
