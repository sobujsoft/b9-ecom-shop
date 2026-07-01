<script setup lang="ts">
import { computed } from 'vue';
import { sanitizeRichText } from '@/lib/sanitizeRichText';
import { cn } from '@/lib/utils';

type Props = {
    html: string;
    class?: string;
};

const props = defineProps<Props>();

const sanitizedHtml = computed(() => sanitizeRichText(props.html));
</script>

<template>
    <div
        v-if="sanitizedHtml"
        :class="cn('rich-text-content text-sm leading-relaxed', props.class)"
        v-html="sanitizedHtml"
    />
    <p v-else :class="cn('text-sm text-muted-foreground', props.class)">
        <slot>No description provided.</slot>
    </p>
</template>

<style>
.rich-text-content :is(h2, h3, h4) {
    font-weight: 600;
    line-height: 1.3;
    margin: 1rem 0 0.5rem;
}

.rich-text-content h2 {
    font-size: 1.25rem;
}

.rich-text-content h3 {
    font-size: 1.125rem;
}

.rich-text-content h4 {
    font-size: 1rem;
}

.rich-text-content :is(p, ul, ol, blockquote) {
    margin: 0.75rem 0;
}

.rich-text-content :is(ul, ol) {
    padding-left: 1.25rem;
}

.rich-text-content ul {
    list-style: disc;
}

.rich-text-content ol {
    list-style: decimal;
}

.rich-text-content blockquote {
    border-left: 3px solid hsl(var(--border));
    color: hsl(var(--muted-foreground));
    padding-left: 0.75rem;
}

.rich-text-content a {
    color: hsl(var(--primary));
    text-decoration: underline;
}

.rich-text-content hr {
    border: 0;
    border-top: 1px solid hsl(var(--border));
    margin: 1rem 0;
}
</style>
