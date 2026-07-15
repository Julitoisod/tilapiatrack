<?php

use Illuminate\Support\Facades\File;

// A fully-qualified reference like Forms\Components\select::make() works on
// case-insensitive Windows/macOS but throws "Class not found" on the
// case-sensitive Linux production server. Guard against it repo-wide.
it('has no lower-cased Filament component/column class references', function () {
    $offenders = [];
    $pattern = '/\b(Components|Columns|Actions|Filters)\\\\[a-z][A-Za-z]*::/';

    foreach (File::allFiles(app_path('Filament')) as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }
        foreach (file($file->getPathname()) as $n => $line) {
            if (preg_match($pattern, $line, $m)) {
                $offenders[] = $file->getFilename() . ':' . ($n + 1) . ' → ' . trim($line);
            }
        }
    }

    expect($offenders)->toBe([], 'Lower-cased Filament class refs crash on Linux: ' . implode(' | ', $offenders));
});
