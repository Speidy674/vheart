import './bootstrap';

/**
 *  AlpineJS for interactivity
 *  @see https://alpinejs.dev/start-here
 */
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import exampleComponent from './components/example-component.js';
import baseEmbed from './components/embeds/base-embed';
import twitchEmbed from './components/embeds/twitch-embed';
import youtubeEmbed from '@/static/components/embeds/youtube-embed';

Alpine.plugin(intersect);

Alpine.data('exampleComponent', exampleComponent);
Alpine.data('baseEmbed', baseEmbed);
Alpine.data('twitchEmbed', twitchEmbed);
Alpine.data('youtubeEmbed', youtubeEmbed);

window.Alpine = Alpine;
Alpine.start();
