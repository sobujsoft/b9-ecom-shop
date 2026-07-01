<?php

use App\Support\RichTextSanitizer;

test('rich text sanitizer removes unsafe tags', function () {
    $input = '<p>Hello</p><script>alert("xss")</script><strong>World</strong>';

    expect(RichTextSanitizer::clean($input))
        ->toBe('<p>Hello</p><strong>World</strong>');
});

test('rich text sanitizer converts legacy plain text to html paragraphs', function () {
    $input = "First paragraph.\n\nSecond paragraph.";

    expect(RichTextSanitizer::forDisplay($input))
        ->toBe('<p>First paragraph.</p><p>Second paragraph.</p>');
});

test('rich text sanitizer keeps allowed html for display', function () {
    $input = '<h2>Title</h2><p>Details with <strong>bold</strong> text.</p>';

    expect(RichTextSanitizer::forDisplay($input))
        ->toBe($input);
});
