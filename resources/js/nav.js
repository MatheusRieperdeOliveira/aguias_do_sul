import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'

Alpine.plugin(persist)

Alpine.store('navbar', {
    items: Alpine.$persist({
        principal: true,
        gestao: true,
        pontuacoes: true
    }).as('navbar_sections'),

    toggle(section) {
        this.items[section] = !this.items[section]
    }
})
