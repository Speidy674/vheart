/**
 *  AlpineJS for interactivity
 *  @see https://alpinejs.dev/start-here
 */

import anchor from '@alpinejs/anchor';
import intersect from '@alpinejs/intersect';
import AsyncAlpine from 'async-alpine';

import youtubeEmbed from '@/static/components/embeds/youtube-embed';
import image from '@/static/components/image';
import reportButton from '@/static/components/ui/report/button';
import baseEmbed from './static/components/embeds/base-embed';
import twitchEmbed from './static/components/embeds/twitch-embed';

document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

    Alpine.plugin(AsyncAlpine);
    Alpine.plugin(intersect);
    Alpine.plugin(anchor);

    // Register alpine based components here, they can be used with `x-data="name({ ...config })"` in html
    Alpine.data('image', image);
    Alpine.data('baseEmbed', baseEmbed);
    Alpine.data('twitchEmbed', twitchEmbed);
    Alpine.data('youtubeEmbed', youtubeEmbed);
    Alpine.data('reportButton', reportButton);

    // These Components (and their dependencies) will be bundled on their own and only
    // get loaded if they get used (or with very low prefetch priority)
    // Make sure they use the x-load attribute to tell alpine that they are lazy/async
    // @see https://async-alpine.dev/docs/
    const asyncComponents: [string, () => Promise<unknown>][] = [
        ['modal', () => import('@/static/components/ui/modal')],
        ['reportModal', () => import('@/static/components/ui/report/modal')],
        [
            'appearanceSlider',
            () => import('@/static/components/appearance-slider'),
        ],
        ['clipsSlider', () => import('@/static/components/index/clips-slider')],
    ];

    asyncComponents.forEach(([componentName, importFn]) => {
        Alpine.asyncData(componentName, importFn);
    });
});
