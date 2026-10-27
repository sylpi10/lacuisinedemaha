import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    // déclare les cibles : Stimulus crée alors this.menuTarget et this.buttonTarget
    static targets = ["menu", "button"];

    toggle() {
        const isOpen = this.menuTarget.classList.toggle("is-open");
        this.buttonTarget.setAttribute(
            "aria-expanded",
            isOpen ? "true" : "false",
        );
    }
}
