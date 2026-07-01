import DOMPurify from 'dompurify';

const ALLOWED_TAGS = [
    'p',
    'br',
    'strong',
    'b',
    'em',
    'i',
    'u',
    's',
    'strike',
    'h2',
    'h3',
    'h4',
    'ul',
    'ol',
    'li',
    'a',
    'blockquote',
    'hr',
];

export function sanitizeRichText(html: string): string {
    if (!html) {
        return '';
    }

    return DOMPurify.sanitize(html, {
        ALLOWED_TAGS,
        ALLOWED_ATTR: ['href', 'target', 'rel'],
    });
}
