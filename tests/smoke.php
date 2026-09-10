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
    if (strpos($content, $version) === false) {
        $failures[] = basename($file) . ' is not aligned with VERSION.';
    }
}
$lintRoots = [$root . '/component', $root . '/package'];
foreach ($lintRoots as $lintRoot) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($lintRoot, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') {
            continue;
        }
        $output = [];
        $code = 0;
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file->getPathname()), $output, $code);
        if ($code !== 0) {
            $failures[] = 'PHP lint failed: ' . $file->getPathname();
        }
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
    if (strpos($scan, $needle) !== false) {
        $failures[] = 'Forbidden private cross-product table reference: ' . $needle;
    }
}
$schema = (string) file_get_contents($root . '/component/admin/sql/install.mysql.utf8mb4.sql');
foreach (['source_question_id', 'respondent_key_hash', 'target_extension', 'target_type', 'target_identifier'] as $needle) {
    if (strpos($schema, $needle) === false) {
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
$assets = json_decode((string) file_get_contents($root . '/component/media/joomla.asset.json'), true);
if (!is_array($assets) || ($assets['name'] ?? '') !== 'com_xdecarofeedback') {
    $failures[] = 'Invalid Joomla web asset manifest.';
}
if ($failures) {
    fwrite(STDERR, implode("\n", $failures) . "\n");
    exit(1);
}
echo "Feedback smoke checks passed for {$version}.\n";
