import { onMounted, onUnmounted, ref } from 'vue';

const AUTO_MS = 5000;

export function useShopCarousel(slideCount: number) {
    const current = ref(0);
    let autoTimer: ReturnType<typeof setInterval> | undefined;

    function goTo(index: number): void {
        current.value = (index + slideCount) % slideCount;
    }

    function next(): void {
        goTo(current.value + 1);
    }

    function prev(): void {
        goTo(current.value - 1);
    }

    function startAuto(): void {
        stopAuto();
        autoTimer = setInterval(next, AUTO_MS);
    }

    function stopAuto(): void {
        clearInterval(autoTimer);
    }

    function handleTouchStart(event: TouchEvent): number {
        stopAuto();

        return event.changedTouches[0].screenX;
    }

    function handleTouchEnd(
        event: TouchEvent,
        touchStartX: number,
    ): void {
        const delta = event.changedTouches[0].screenX - touchStartX;

        if (Math.abs(delta) > 40) {
            if (delta < 0) {
                next();
            } else {
                prev();
            }
        }

        startAuto();
    }

    onMounted(() => {
        startAuto();
    });

    onUnmounted(() => {
        stopAuto();
    });

    return {
        current,
        goTo,
        next,
        prev,
        startAuto,
        stopAuto,
        handleTouchStart,
        handleTouchEnd,
    };
}
