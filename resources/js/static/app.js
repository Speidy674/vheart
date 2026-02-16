import './bootstrap';

/**
 *  AlpineJS for interactivity
 *  @see https://alpinejs.dev/start-here
 */
import Alpine from 'alpinejs';
import exampleComponent from './components/example-component.js';

Alpine.data('exampleComponent', exampleComponent);

window.Alpine = Alpine;
Alpine.start();
