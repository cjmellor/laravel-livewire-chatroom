<?php

namespace App\Concerns;

trait CanTransform
{
    public function transform(string $markdown): string
    {
        // Bold
        $markdown = preg_replace(
            pattern: "/\*\*(.*?)\*\*/",
            replacement: '<strong>$1</strong>',
            subject: $markdown
        );

        // Italic
        $markdown = preg_replace(
            pattern: "/\*(.*?)\*/",
            replacement: '<em>$1</em>',
            subject: $markdown
        );

        // Code
        $markdown = preg_replace(
            pattern: '/`(.*?)`/',
            replacement: '<code>$1</code>',
            subject: $markdown
        );

        // Link
        $markdown = preg_replace(
//            pattern: "/\[(.*?)]\((https?:\/\/\S+|#[a-z]+)\)/",
            pattern: "/\[([a-z]+)]\((https?:\/\/\S+|#[a-z]+)(?::(blank|top|self|parent))?\)(?=$|:)/",
            replacement: '<a href="$2" target="_blank">$1</a>',
            subject: $markdown
        );

        // Blockquote
        $markdown = preg_replace(
            pattern: "/>\s?(.*)/",
            replacement: '<blockquote>$1</blockquote>',
            subject: $markdown
        );

        // New Line
        return preg_replace(
            pattern: '/\n/',
            replacement: '<br>',
            subject: $markdown
        );
    }
}
