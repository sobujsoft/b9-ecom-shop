<script setup lang="ts">
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';
import TextAlign from '@tiptap/extension-text-align';
import Underline from '@tiptap/extension-underline';
import StarterKit from '@tiptap/starter-kit';
import { Editor, EditorContent } from '@tiptap/vue-3';
import {
    AlignCenter,
    AlignLeft,
    AlignRight,
    Bold,
    Heading2,
    Heading3,
    Italic,
    Link2,
    List,
    ListOrdered,
    Minus,
    Quote,
    Redo2,
    Strikethrough,
    Underline as UnderlineIcon,
    Undo2,
} from '@lucide/vue';
import { onBeforeUnmount, onMounted, ref, shallowRef, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

type Props = {
    modelValue: string;
    placeholder?: string;
    disabled?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Write your content…',
    disabled: false,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const editor = shallowRef<Editor | null>(null);
const isReady = ref(false);

onMounted(() => {
    editor.value = new Editor({
        content: props.modelValue || '',
        editable: !props.disabled,
        extensions: [
            StarterKit.configure({
                heading: {
                    levels: [2, 3, 4],
                },
            }),
            Underline,
            Link.configure({
                openOnClick: false,
                HTMLAttributes: {
                    rel: 'noopener noreferrer nofollow',
                    target: '_blank',
                },
            }),
            TextAlign.configure({
                types: ['heading', 'paragraph'],
            }),
            Placeholder.configure({
                placeholder: props.placeholder,
            }),
        ],
        onUpdate: ({ editor: currentEditor }) => {
            emit('update:modelValue', currentEditor.getHTML());
        },
    });

    isReady.value = true;
});

watch(
    () => props.modelValue,
    (value) => {
        if (!editor.value) {
            return;
        }

        const currentHtml = editor.value.getHTML();
        const normalizedValue = value || '<p></p>';

        if (currentHtml !== normalizedValue) {
            editor.value.commands.setContent(normalizedValue, { emitUpdate: false });
        }
    },
);

watch(
    () => props.disabled,
    (disabled) => {
        editor.value?.setEditable(!disabled);
    },
);

onBeforeUnmount(() => {
    editor.value?.destroy();
});

function toggleLink(): void {
    if (!editor.value) {
        return;
    }

    const previousUrl = editor.value.getAttributes('link').href as string | undefined;
    const url = window.prompt('Enter URL', previousUrl ?? 'https://');

    if (url === null) {
        return;
    }

    if (url === '') {
        editor.value.chain().focus().extendMarkRange('link').unsetLink().run();

        return;
    }

    editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
}

function toolbarButtonClass(isActive = false): string {
    return cn(
        'size-8',
        isActive && 'bg-muted text-foreground',
    );
}
</script>

<template>
    <div class="overflow-hidden rounded-md border border-input bg-background">
        <div
            v-if="isReady && editor"
            class="flex flex-wrap gap-1 border-b border-input bg-muted/30 p-2"
        >
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive('bold'))"
                @click="editor.chain().focus().toggleBold().run()"
            >
                <Bold class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive('italic'))"
                @click="editor.chain().focus().toggleItalic().run()"
            >
                <Italic class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive('underline'))"
                @click="editor.chain().focus().toggleUnderline().run()"
            >
                <UnderlineIcon class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive('strike'))"
                @click="editor.chain().focus().toggleStrike().run()"
            >
                <Strikethrough class="size-4" />
            </Button>

            <span class="mx-1 w-px self-stretch bg-border" />

            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive('heading', { level: 2 }))"
                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
            >
                <Heading2 class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive('heading', { level: 3 }))"
                @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
            >
                <Heading3 class="size-4" />
            </Button>

            <span class="mx-1 w-px self-stretch bg-border" />

            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive('bulletList'))"
                @click="editor.chain().focus().toggleBulletList().run()"
            >
                <List class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive('orderedList'))"
                @click="editor.chain().focus().toggleOrderedList().run()"
            >
                <ListOrdered class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive('blockquote'))"
                @click="editor.chain().focus().toggleBlockquote().run()"
            >
                <Quote class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive('link'))"
                @click="toggleLink"
            >
                <Link2 class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass()"
                @click="editor.chain().focus().setHorizontalRule().run()"
            >
                <Minus class="size-4" />
            </Button>

            <span class="mx-1 w-px self-stretch bg-border" />

            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive({ textAlign: 'left' }))"
                @click="editor.chain().focus().setTextAlign('left').run()"
            >
                <AlignLeft class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive({ textAlign: 'center' }))"
                @click="editor.chain().focus().setTextAlign('center').run()"
            >
                <AlignCenter class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass(editor.isActive({ textAlign: 'right' }))"
                @click="editor.chain().focus().setTextAlign('right').run()"
            >
                <AlignRight class="size-4" />
            </Button>

            <span class="mx-1 w-px self-stretch bg-border" />

            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass()"
                :disabled="!editor.can().chain().focus().undo().run()"
                @click="editor.chain().focus().undo().run()"
            >
                <Undo2 class="size-4" />
            </Button>
            <Button
                type="button"
                variant="ghost"
                size="icon"
                :class="toolbarButtonClass()"
                :disabled="!editor.can().chain().focus().redo().run()"
                @click="editor.chain().focus().redo().run()"
            >
                <Redo2 class="size-4" />
            </Button>
        </div>

        <div
            v-if="!isReady"
            class="min-h-48 animate-pulse bg-muted/20"
        />

        <EditorContent
            v-else
            :editor="editor"
            class="rich-text-editor min-h-48 px-3 py-3 text-sm"
        />
    </div>
</template>

<style>
.rich-text-editor .tiptap {
    outline: none;
}

.rich-text-editor .tiptap p.is-editor-empty:first-child::before {
    color: hsl(var(--muted-foreground));
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}

.rich-text-editor .tiptap :is(h2, h3, h4) {
    font-weight: 600;
    line-height: 1.3;
    margin: 1rem 0 0.5rem;
}

.rich-text-editor .tiptap h2 {
    font-size: 1.25rem;
}

.rich-text-editor .tiptap h3 {
    font-size: 1.125rem;
}

.rich-text-editor .tiptap h4 {
    font-size: 1rem;
}

.rich-text-editor .tiptap :is(ul, ol) {
    margin: 0.75rem 0;
    padding-left: 1.25rem;
}

.rich-text-editor .tiptap ul {
    list-style: disc;
}

.rich-text-editor .tiptap ol {
    list-style: decimal;
}

.rich-text-editor .tiptap blockquote {
    border-left: 3px solid hsl(var(--border));
    color: hsl(var(--muted-foreground));
    margin: 0.75rem 0;
    padding-left: 0.75rem;
}

.rich-text-editor .tiptap a {
    color: hsl(var(--primary));
    text-decoration: underline;
}

.rich-text-editor .tiptap hr {
    border: 0;
    border-top: 1px solid hsl(var(--border));
    margin: 1rem 0;
}
</style>
