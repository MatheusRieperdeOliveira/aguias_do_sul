document.addEventListener('alpine:init', () => {
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
})
