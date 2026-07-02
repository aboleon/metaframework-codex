<?php

declare(strict_types=1);

namespace Aboleon\MetaFrameworkCodex;

final class Installer
{
    private const START_MARKER = '<!-- MFW-CODEX-AGENTS:START -->';

    private const END_MARKER = '<!-- MFW-CODEX-AGENTS:END -->';

    public static function main(array $argv): int
    {
        $command = $argv[1] ?? 'help';

        if (in_array($command, ['help', '--help', '-h'], true)) {
            self::printHelp();

            return 0;
        }

        if ($command !== 'install') {
            self::stderr("Unknown command: {$command}");
            self::printHelp();

            return 1;
        }

        $options = self::parseOptions(array_slice($argv, 2));

        return self::install($options);
    }

    /**
     * @param array{target?: string, dry-run?: bool} $options
     */
    private static function install(array $options): int
    {
        $projectRoot = getcwd();

        if ($projectRoot === false) {
            self::stderr('Unable to resolve the current working directory.');

            return 1;
        }

        $target = $options['target'] ?? 'AGENTS.md';
        $targetPath = self::absolutePath($projectRoot, $target);
        $packageEntry = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'mfw-codex-agents.md';

        if (! is_file($packageEntry)) {
            self::stderr("Shared entrypoint not found: {$packageEntry}");

            return 1;
        }

        $includePath = self::relativePath($projectRoot, $packageEntry);
        $block = self::managedBlock($includePath);
        $existing = is_file($targetPath) ? file_get_contents($targetPath) : null;

        if ($existing === false) {
            self::stderr("Unable to read {$targetPath}");

            return 1;
        }

        $next = self::mergeBlock($existing, $block);

        if (($options['dry-run'] ?? false) === true) {
            self::stdout($next);

            return 0;
        }

        $directory = dirname($targetPath);

        if (! is_dir($directory) && ! mkdir($directory, 0777, true) && ! is_dir($directory)) {
            self::stderr("Unable to create directory: {$directory}");

            return 1;
        }

        if (file_put_contents($targetPath, $next) === false) {
            self::stderr("Unable to write {$targetPath}");

            return 1;
        }

        self::stdout("Installed MFW Codex Agents include block in {$targetPath}");

        return 0;
    }

    /**
     * @param list<string> $args
     * @return array{target?: string, dry-run?: bool}
     */
    private static function parseOptions(array $args): array
    {
        $options = [];

        foreach ($args as $arg) {
            if ($arg === '--dry-run') {
                $options['dry-run'] = true;

                continue;
            }

            if (str_starts_with($arg, '--target=')) {
                $options['target'] = substr($arg, strlen('--target='));
            }
        }

        return $options;
    }

    private static function absolutePath(string $baseDir, string $path): string
    {
        if (self::isAbsolutePath($path)) {
            return $path;
        }

        return $baseDir . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    private static function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, DIRECTORY_SEPARATOR)
            || preg_match('/^[A-Za-z]:[\/\\\\]/', $path) === 1;
    }

    private static function relativePath(string $fromDir, string $toFile): string
    {
        $from = realpath($fromDir);
        $to = realpath($toFile);

        if ($from === false || $to === false) {
            return str_replace('\\', '/', $toFile);
        }

        $fromParts = explode(DIRECTORY_SEPARATOR, trim($from, DIRECTORY_SEPARATOR));
        $toParts = explode(DIRECTORY_SEPARATOR, trim($to, DIRECTORY_SEPARATOR));

        if (self::windowsDrive($from) !== self::windowsDrive($to)) {
            return str_replace('\\', '/', $to);
        }

        while ($fromParts !== [] && $toParts !== [] && $fromParts[0] === $toParts[0]) {
            array_shift($fromParts);
            array_shift($toParts);
        }

        $relativeParts = array_merge(array_fill(0, count($fromParts), '..'), $toParts);
        $relative = $relativeParts === [] ? basename($to) : implode('/', $relativeParts);

        return str_replace('\\', '/', $relative);
    }

    private static function windowsDrive(string $path): ?string
    {
        return preg_match('/^[A-Za-z]:/', $path, $matches) === 1 ? strtolower($matches[0]) : null;
    }

    private static function managedBlock(string $includePath): string
    {
        return implode(PHP_EOL, [
            '## Shared MetaFramework Codex Rules',
            self::START_MARKER,
            'Do not delete this managed block unless `aboleon/metaframework-codex` is removed.',
            "Before coding, read `{$includePath}`.",
            'Local project instructions in this `AGENTS.md` override the shared package for project-specific facts.',
            self::END_MARKER,
        ]);
    }

    private static function mergeBlock(?string $existing, string $block): string
    {
        if ($existing === null || trim($existing) === '') {
            return "# AGENTS.md\n\n{$block}\n";
        }

        $pattern = '/' . preg_quote(self::START_MARKER, '/') . '.*?' . preg_quote(self::END_MARKER, '/') . '/s';

        if (preg_match($pattern, $existing) === 1) {
            $replacement = implode(PHP_EOL, array_slice(explode(PHP_EOL, $block), 1));
            $updated = preg_replace($pattern, $replacement, $existing, 1);

            return self::ensureTrailingNewline($updated ?? $existing);
        }

        return self::ensureTrailingNewline($block . PHP_EOL . PHP_EOL . ltrim($existing));
    }

    private static function ensureTrailingNewline(string $content): string
    {
        return rtrim($content) . PHP_EOL;
    }

    private static function printHelp(): void
    {
        self::stdout(implode(PHP_EOL, [
            'MFW Codex Agents installer',
            '',
            'Usage:',
            '  mfw-codex-agents install [--target=AGENTS.md] [--dry-run]',
            '',
            'The install command adds or refreshes a managed include block in the target AGENTS.md.',
            'Existing local project instructions are preserved.',
        ]));
    }

    private static function stdout(string $message): void
    {
        fwrite(STDOUT, $message . PHP_EOL);
    }

    private static function stderr(string $message): void
    {
        fwrite(STDERR, $message . PHP_EOL);
    }
}
