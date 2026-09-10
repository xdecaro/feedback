<?php
$root = dirname(__DIR__);
$failures = [];
$version = trim((string) file_get_contents($root . '/VERSION'));
if (!preg_match('/^\d+\.\d+\.\d+$/', $version)) {
    $failures[] = 'VERSION must contain a SemVer x.y.z value.';
}
$versionFiles = [
    $root . '/component/xdecarofeedback.xml',
    $root . '/package/pkg_xdecarofeedback.xml',
    $root . '/component/admin/src/Version.php',
    $root . '/component/media/joomla.asset.json',
];
foreach ($versionFiles as $file) {
    $content = (string) file_get_contents($file);
    if (!str_contains($content, $version)) {
        $failures[] = basename($file) . ' is not aligned with VERSION.';
    }
}
foreach (glob($root . '/component/admin/src/**/*.php') ?: [] as $file) {
    $output = [];
    $code = 0;
    exec('php -l ' . escapeshellarg($file), $output, $code);
    if ($code !== 0) {
        $failures[] = 'PHP lint failed: ' . $file;
    }
}
$scan = '';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/component', FilesystemIterator::SKIP_DOTS));
foreach ($iterator as $file) {
    if ($file->isFile()) {
        $scan .= "\n" . file_get_contents($file->getPathname());
    }
}
$forbiddenTables = ['#__xdecarocourses_', '#__xdecaroevents_', '#__xdecarocompetitions_', '#__xdecaromembership_', '#__xdecarobookings_', '#__decaroforms_'];
foreach ($forbiddenTables as $needle) {
    if (str_contains($scan, $needle)) {
        $failures[] = 'Forbidden private cross-product table reference: ' . $needle;
    }
}
$schema = (string) file_get_contents($root . '/component/admin/sql/install.mysql.utf8mb4.sql');
foreach (['source_question_id', 'respondent_key_hash', 'target_extension', 'target_type', 'target_identifier'] as $needle) {
    if (!str_contains($schema, $needle)) {
        $failures[] = 'Schema contract missing: ' . $needle;
    }
}
foreach ([$root . '/component/xdecarofeedback.xml', $root . '/package/pkg_xdecarofeedback.xml', $root . '/component/admin/access.xml', $root . '/component/admin/config.xml'] as $xmlFile) {
    libxml_use_internal_errors(true);
    if (simplexml_load_file($xmlFile) === false) {
        $failures[] = 'Invalid XML: ' . $xmlFile;
    }
    libxml_clear_errors();
}
if ($failures) {
    fwrite(STDERR, implode("\n", $failures) . "\n");
    exit(1);
}
echo "Feedback smoke checks passed for {$version}.\n";
