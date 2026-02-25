import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'

Alpine.plugin(persist)

// Define the store
Alpine.store('navbar', {
    // Initialize with default states for each section
    items: Alpine.$persist({
        principal: true, // Default to open
        gestao: true,    // Default to open
        pontuacoes: true // Default to open
    }).as('navbar_sections'),

    // Toggle a specific section
    toggle(section) {
        this.items[section] = !this.items[section]
    }
})
