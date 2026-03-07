import { AlpineComponent } from 'alpinejs';

export interface ClipSliderData {
    isAnimating: boolean;
    next(): void;
    prev(): void;
}

export default (): AlpineComponent<ClipSliderData> => ({
    isAnimating: false,
    next() {
        if (this.isAnimating) return;
        this.isAnimating = true;

        const slider = this.$refs.slider;
        const item = slider.firstElementChild as HTMLElement;
        const itemWidth = item.offsetWidth;

        // technically i dont need to wait for the browser, but this way its consistent with the other way
        // and also way nicer for the browser afaik
        requestAnimationFrame(() => {
            slider.scrollBy({ left: itemWidth, behavior: 'smooth' });

            slider.addEventListener(
                'scrollend',
                () => {
                    slider.appendChild(item);
                    slider.scrollLeft -= itemWidth;
                    this.isAnimating = false;
                },
                { once: true },
            );
        });
    },

    prev() {
        if (this.isAnimating) return;
        this.isAnimating = true;

        const slider = this.$refs.slider;
        const lastItem = slider.lastElementChild as HTMLElement;
        const itemWidth = lastItem.offsetWidth;

        // we kinda have to use a little hack here to prevent jumping around
        slider.style.scrollSnapType = 'none';
        slider.style.scrollBehavior = 'auto';

        slider.prepend(lastItem);
        slider.scrollLeft += itemWidth;

        // in this case we actually have to wait since we modified the DOM
        // and triggering that now could cause jumps otherwise (especially if you spam it lol)
        requestAnimationFrame(() => {
            slider.scrollTo({ left: 0, behavior: 'smooth' });

            slider.addEventListener(
                'scrollend',
                () => {
                    slider.style.scrollSnapType = '';
                    slider.style.scrollBehavior = '';
                    this.isAnimating = false;
                },
                { once: true },
            );
        });
    },
});
