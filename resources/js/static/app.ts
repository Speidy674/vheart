import './bootstrap';

/**
 *  AlpineJS for interactivity
 *  @see https://alpinejs.dev/start-here
 */
import Alpine from 'alpinejs';
import exampleComponent from './components/example-component.js';
import baseEmbed from './components/embeds/base-embed';
import twitchEmbed from './components/embeds/twitch-embed';

Alpine.data('exampleComponent', exampleComponent);
Alpine.data('baseEmbed', baseEmbed);
Alpine.data('twitchEmbed', twitchEmbed);

window.Alpine = Alpine;
Alpine.start();
