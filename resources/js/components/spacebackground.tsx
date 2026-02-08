import { useLayoutEffect, useRef } from 'react';

const StaticSpaceBackground = () => {
    const containerRef = useRef<HTMLDivElement>(null);
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-expect-error
    const resizeTimeoutRef = useRef<number>();

    useLayoutEffect(() => {
        const drawBackground = () => {
            const container = containerRef.current;
            if (!container) return;

            const isDark = document.documentElement.classList.contains('dark');
            const width = window.innerWidth;
            const height = window.innerHeight;
            const isMobile = width <= 768;

            container.innerHTML = '';

            const starColor = isDark ? 'rgba(255,255,255,' : 'rgba(10,10,40,';
            const planetColor1 = isDark ? 'rgba(100, 65, 165, ' : 'rgba(100, 80, 200, ';
            const planetColor2 = isDark ? 'rgba(145, 70, 255, ' : 'rgba(120, 80, 220, ';
            const background = isDark ? '#0a0a1a' : '#E8F0FE';
            const starAlphaMultiplier = isDark ? 1 : 0.8;

            const backgroundDiv = document.createElement('div');
            backgroundDiv.style.cssText = `
                position: absolute;
                inset: 0;
                background: ${background};
                z-index: 0;
            `;
            container.appendChild(backgroundDiv);

            const staticStars = [
                { x: 10, y: 10, s: 3.0, b: 0.9 },
                { x: 15, y: 25, s: 2.5, b: 0.8 },
                { x: 5, y: 30, s: 4.0, b: 1.0 },
                { x: 20, y: 15, s: 2.0, b: 0.7 },
                { x: 8, y: 40, s: 3.5, b: 0.85 },

                { x: 85, y: 15, s: 2.8, b: 0.8 },
                { x: 90, y: 35, s: 2.2, b: 0.6 },
                { x: 80, y: 25, s: 3.2, b: 0.9 },
                { x: 95, y: 10, s: 2.7, b: 0.75 },
                { x: 75, y: 45, s: 4.5, b: 1.0 },

                { x: 25, y: 65, s: 2.0, b: 0.7 },
                { x: 35, y: 75, s: 3.0, b: 0.8 },
                { x: 45, y: 60, s: 2.5, b: 0.65 },
                { x: 30, y: 85, s: 3.2, b: 0.9 },
                { x: 40, y: 95, s: 4.0, b: 1.0 },

                { x: 65, y: 20, s: 2.3, b: 0.7 },
                { x: 70, y: 50, s: 3.1, b: 0.85 },
                { x: 55, y: 35, s: 2.8, b: 0.75 },
                { x: 60, y: 80, s: 3.5, b: 0.9 },
                { x: 50, y: 70, s: 2.2, b: 0.6 },

                { x: 85, y: 70, s: 2.6, b: 0.8 },
                { x: 90, y: 85, s: 3.8, b: 0.95 },
                { x: 75, y: 80, s: 2.9, b: 0.7 },
                { x: 80, y: 95, s: 3.3, b: 0.85 },
                { x: 95, y: 75, s: 2.4, b: 0.65 },

                { x: 50, y: 50, s: 4.0, b: 1.0 },
                { x: 40, y: 40, s: 2.7, b: 0.75 },
                { x: 60, y: 60, s: 3.2, b: 0.85 },
                { x: 45, y: 55, s: 3.5, b: 0.9 },
                { x: 55, y: 45, s: 2.0, b: 0.6 },

                { x: 12, y: 60, s: 2.8, b: 0.8 },
                { x: 22, y: 35, s: 3.0, b: 0.85 },
                { x: 32, y: 90, s: 2.5, b: 0.7 },
                { x: 42, y: 20, s: 3.2, b: 0.9 },
                { x: 52, y: 85, s: 2.9, b: 0.8 },

                { x: 62, y: 40, s: 3.5, b: 0.95 },
                { x: 72, y: 65, s: 2.6, b: 0.75 },
                { x: 82, y: 30, s: 3.0, b: 0.85 },
                { x: 92, y: 55, s: 2.8, b: 0.8 },
                { x: 98, y: 25, s: 3.7, b: 1.0 },
            ];

            staticStars.forEach((star) => {
                const starDiv = document.createElement('div');
                const baseSize = isMobile ? star.s * 0.9 : star.s;
                const size = baseSize * (isMobile ? 1.1 : 1.0);
                const opacity = star.b * starAlphaMultiplier;

                starDiv.style.cssText = `
                    position: absolute;
                    left: ${star.x}%;
                    top: ${star.y}%;
                    width: ${size}px;
                    height: ${size}px;
                    background: ${starColor}${opacity});
                    border-radius: 50%;
                    transform: translate(-50%, -50%);
                    z-index: 2;
                    ${isMobile ? 'filter: brightness(1.2);' : ''}
                `;
                container.appendChild(starDiv);
            });

            const planetCanvas = document.createElement('canvas');
            planetCanvas.width = width;
            planetCanvas.height = height;
            planetCanvas.style.cssText = `
                position: absolute;
                inset: 0;
                z-index: 3;
                pointer-events: none;
            `;
            container.appendChild(planetCanvas);

            const ctx = planetCanvas.getContext('2d');
            if (ctx) {
                const planet1X = isMobile ? width * 0.85 : width * 0.8;
                const planet1Y = isMobile ? height * 0.15 : height * 0.2;
                const planet1Radius = isMobile ? 32 : 48;

                const planet1Gradient = ctx.createRadialGradient(
                    planet1X,
                    planet1Y,
                    0,
                    planet1X,
                    planet1Y,
                    planet1Radius,
                );
                planet1Gradient.addColorStop(
                    0,
                    `${planetColor1}${isDark ? '0.8)' : '0.9)'}`,
                );
                planet1Gradient.addColorStop(
                    0.6,
                    `${planetColor1}${isDark ? '0.6)' : '0.7)'}`,
                );
                planet1Gradient.addColorStop(
                    1,
                    `${planetColor1}${isDark ? '0.4)' : '0.5)'}`,
                );

                ctx.fillStyle = planet1Gradient;
                ctx.beginPath();
                ctx.arc(planet1X, planet1Y, planet1Radius, 0, Math.PI * 2);
                ctx.fill();

                const ring1Gradient = ctx.createLinearGradient(
                    planet1X - planet1Radius * 1.2,
                    planet1Y,
                    planet1X + planet1Radius * 1.2,
                    planet1Y,
                );
                ring1Gradient.addColorStop(0, `${planetColor2}0)`);
                ring1Gradient.addColorStop(
                    0.3,
                    `${planetColor2}${isDark ? '0.15)' : '0.3)'}`,
                );
                ring1Gradient.addColorStop(
                    0.7,
                    `${planetColor2}${isDark ? '0.15)' : '0.3)'}`,
                );
                ring1Gradient.addColorStop(1, `${planetColor2}0)`);

                ctx.strokeStyle = ring1Gradient;
                ctx.lineWidth = isMobile ? 1.5 : 2;
                ctx.beginPath();
                ctx.ellipse(
                    planet1X,
                    planet1Y,
                    planet1Radius * 1.2,
                    planet1Radius * 0.2,
                    0.5,
                    0,
                    Math.PI * 2,
                );
                ctx.stroke();

                const planet2X = isMobile ? width * 0.15 : width * 0.2;
                const planet2Y = isMobile ? height * 0.85 : height * 0.7;
                const planet2Radius = isMobile ? 24 : 36;

                const planet2Gradient = ctx.createRadialGradient(
                    planet2X,
                    planet2Y,
                    0,
                    planet2X,
                    planet2Y,
                    planet2Radius,
                );
                planet2Gradient.addColorStop(
                    0,
                    `${planetColor1}${isDark ? '0.8)' : '0.9)'}`,
                );
                planet2Gradient.addColorStop(
                    0.6,
                    `${planetColor1}${isDark ? '0.6)' : '0.7)'}`,
                );
                planet2Gradient.addColorStop(
                    1,
                    `${planetColor1}${isDark ? '0.4)' : '0.5)'}`,
                );

                ctx.fillStyle = planet2Gradient;
                ctx.beginPath();
                ctx.arc(planet2X, planet2Y, planet2Radius, 0, Math.PI * 2);
                ctx.fill();

                const ring2Gradient = ctx.createLinearGradient(
                    planet2X - planet2Radius * 1.2,
                    planet2Y,
                    planet2X + planet2Radius * 1.2,
                    planet2Y,
                );
                ring2Gradient.addColorStop(0, `${planetColor2}0)`);
                ring2Gradient.addColorStop(
                    0.3,
                    `${planetColor2}${isDark ? '0.15)' : '0.3)'}`,
                );
                ring2Gradient.addColorStop(
                    0.7,
                    `${planetColor2}${isDark ? '0.15)' : '0.3)'}`,
                );
                ring2Gradient.addColorStop(1, `${planetColor2}0)`);

                ctx.strokeStyle = ring2Gradient;
                ctx.lineWidth = isMobile ? 1.5 : 2;
                ctx.beginPath();
                ctx.ellipse(
                    planet2X,
                    planet2Y,
                    planet2Radius * 1.2,
                    planet2Radius * 0.2,
                    1.2,
                    0,
                    Math.PI * 2,
                );
                ctx.stroke();
            }
        };

        drawBackground();

        const handleResize = () => {
            if (resizeTimeoutRef.current) {
                clearTimeout(resizeTimeoutRef.current);
            }

            resizeTimeoutRef.current = window.setTimeout(() => {
                drawBackground();
            }, 150);
        };

        window.addEventListener('resize', handleResize);
        return () => {
            window.removeEventListener('resize', handleResize);
            if (resizeTimeoutRef.current) {
                clearTimeout(resizeTimeoutRef.current);
            }
        };
    }, []);

    return (
        <div
            ref={containerRef}
            className="pointer-events-none fixed inset-0"
            style={{ zIndex: 0 }}
        />
    );
};

export default StaticSpaceBackground;
