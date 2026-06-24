import { ref } from 'vue';

const isMobileMenuOpen = ref(false);
const toastMessage = ref('');
const isToastVisible = ref(false);
let toastTimer: ReturnType<typeof setTimeout> | undefined;

export function useShopUi() {
    function openMobileMenu(): void {
        isMobileMenuOpen.value = true;
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu(): void {
        isMobileMenuOpen.value = false;
        document.body.style.overflow = '';
    }

    function showToast(message: string): void {
        toastMessage.value = message;
        isToastVisible.value = true;
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            isToastVisible.value = false;
        }, 2500);
    }

    return {
        isMobileMenuOpen,
        toastMessage,
        isToastVisible,
        openMobileMenu,
        closeMobileMenu,
        showToast,
    };
}
