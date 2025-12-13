import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["content", "total", "count"];

    connect() {
        console.log("Cart controller connected! 🛒");
    }

    async update(event) {
        const input = event.target;
        const form = input.closest('form');
        const value = parseInt(input.value);

        if (value <= 0) {
            const confirmDelete = confirm("Voulez-vous retirer cet article ?");
            if (!confirmDelete) {
                input.value = 1;
                return;
            }
        }

        try {
            const response = await fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            });

            if (!response.ok) {
                throw new Error('Erreur réseau');
            }

            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('[data-cart-target="content"]');
            if (this.hasContentTarget && newContent) {
                this.contentTarget.innerHTML = newContent.innerHTML;
            } else {
                if(doc.querySelector('.alert-warning')) {
                    window.location.reload();
                    return;
                }
            }

            const newTotal = doc.querySelector('[data-cart-target="total"]');
            if (this.hasTotalTarget && newTotal) {
                this.totalTarget.innerHTML = newTotal.innerHTML;
            }

        } catch (error) {
            console.error("Erreur lors de la mise à jour du panier :", error);
            alert("Une erreur est survenue.");
        }
    }
}